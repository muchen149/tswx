<?php

namespace App\controllers\wx;
use App\controllers\GetPhoneMsgCodeController;

use App\controllers\BaseController;

use App\controllers\OrderController;
use App\facades\Api;
use App\facades\LogInfoFacade;

use App\Jobs\AutoDismantleOrder;
use App\Jobs\HandOutRechargeCard;

use App\models\member\Member;
use App\models\member\MemberOtherAccount;

use App\models\member\MemberYesbLog;
use App\models\member\MemberWalletLog;
use App\models\member\MemberBalanceLog;

use App\models\order\OrderPrepay;
use App\models\order\Order as PlatOrder;
use App\models\order\OrderExtend;

use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;

class WxPayController extends Controller
{
    // 微信支付
    protected $payment;

    // 微信支付回调地址
    protected $wxPayCallbackUrl;

    // 数据库表前缀
    protected $db_prefix;

    private $OrderController;

    /**
     * 获取支付实例
     * WxPayController constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->payment = $app->payment;
        $this->db_prefix = config('database')['connections']['mysql']['prefix'];
        $this->wxPayCallbackUrl = config('wechat')['payment']['notify_url'];
        $this->OrderController= new OrderController();
    }

    /** ajax (去付款)
     * 根据订单信息调用支付接口 生成支付数据
     * @param $order
     * @return  mixed
     */
    public function pay(Request $request)
    {
        // 平台订单id
        $plat_order_id = (int)$request->input('plat_order_id');

        //标记要支付的单子是不是add生成订单后直接过来的，若是则只有用户级别为团采或者代理才会带该参数，
        //只有团采用户或者代理才会生成订单后直接去支付时才会传送该标记，该标记就是为了进入下面的confirmNotRmbPay，更改订单为已完成，可以进行派单
        $flag = $request->input('flag');

        // =================================== 验证数据合法性 =================================
        $plat_order = PlatOrder::where('plat_order_id', $plat_order_id)->first();
        if (empty($plat_order)) {
            Log::error('平台订单id不存在 无法申请支付  控制器:WxPayController@pay');
            return view('errors.error');        // 数据有误
        }

        // ================================== 验证该订单 现金支付金额 ===========================
        // 如果人民币支付金额为0(即全部为非人民币支付【诸如：虚拟币、零钱、卡余额、优惠劵等】)
        // 就不用调用微信支付页面，直接确认非人民币支付即可
        //当flag为真时（flag标记其使用的支付方式5货到付款，8账期支付），表明是代理用户或团采用户通过使用货到付款或者账期支付 （代理和团采使用微信支付是不会传该参数）
        //flag为真时进入confirmNotRmbPay进行更改订单状态为已付款，进行派单
        if (bccomp($plat_order->pay_rmb_amount, 0.00, 2) == 0 || $flag)
        {
            if (empty($plat_order->pay_rmb_time) || $flag)
            {
                $this->confirmNotRmbPay($plat_order,'',$flag);
            }

            // 显示订单详情信息
            if($plat_order->order_type==1){
                return redirect('ys/order/info/' . $plat_order->plat_order_id);
            }else if($plat_order->order_type==2){
                return redirect('jd/order/info/' . $plat_order->plat_order_id);
            }else{
                return redirect('order/info/' . $plat_order->plat_order_id);
            }
        }


        // 如果用微信支付，必须有微信账号
        // 账户类型(account_type):(1:qq;2:wechat;3:sinaweibo;4:email;5:mobile;6:im;每类型至多一个)
        $member_id = $plat_order->member_id;
        $open_id = MemberOtherAccount::where('member_id', $member_id)
            ->where('account_type',2)
            ->value('account_id');
        if (empty($open_id))
        {
            Log::error('买家微信账户不存在 无法申请微信支付  控制器:WxPayController@pay');
            return view('errors.error');        // 数据有误
        }


        // ============================= 查询是否存在有效的平台预支付订单 ========================
        $now_time = date('YmdHis');
        $order_prepay = OrderPrepay::where('out_trade_no', $plat_order->pay_rmb_sn)
            ->where('time_expire', '>', $now_time)
            ->where('trade_state', 'NOTPAY')
            ->where('is_close', 0)
            ->first();

        // 微信预付单id
        $prepayId = '';
        if ($order_prepay)
        {
            $prepayId = $order_prepay->prepay_id;
        }
        else
        {
            // ================================ 生成预支付订单信息数组 ===============================
            $attributes = [
                'openid' => $open_id,                                                               // 公众和用户之间的open_id
                'trade_type' => 'JSAPI',                                                            // 交易类型
                'body' => '水丁平台 - 商品',                                                          // 商品描述
                'detail' => '平台商品',                                                              // 商品详情
                'out_trade_no' => time() . date('YmdHis') . rand(pow(10, 7), pow(10, 8) - 1),       // 人民币支付单号 32位随机整数                                 // 商户订单号
                'total_fee' => (int)(bcmul($plat_order->pay_rmb_amount, 100)),                      // 订单总金额，单位为分
                'time_start' => date('YmdHis'),                                                     // 交易起始时间
                'time_expire' => date("YmdHis", strtotime("+15 minute")),                           // 交易结束时间 定义15分钟后失效
                'notify_url' => $this->wxPayCallbackUrl,                                            // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            ];


            // 创建微信支付订单、微信预支付记录
            $wx_order = new Order($attributes);
            $result = $this->payment->prepare($wx_order);

            Log::notice('预付单信息数组:' . json_encode($attributes));
            Log::notice('调用统一下单返回信息:' . $result->toJson());
            if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS')
            {
                $prepayId = $result->prepay_id;                  // 返回预付单信息
                $content = '1、生成订单id为:' . $plat_order_id . '的平台订单预付单, 交易起始时间为:'
                    . date('Y-m-d H:i:s', strtotime($attributes['time_start'])) .
                    '; 交易结束时间为:' . date('Y-m-d H:i:s', strtotime($attributes['time_expire'])) .
                    '; 交易有效时间为15分钟；';

                // ================================ 创建平台预支付订单 ==================================
                OrderPrepay::create(array(
                    'pay_mode_code' => 'wxpay',
                    'pay_mode_name' => '微信支付',
                    'prepay_id' => $prepayId,
                    'out_trade_no' => $attributes['out_trade_no'],
                    'time_start' => $attributes['time_start'],
                    'time_expire' => $attributes['time_expire'],
                    'settlement_total_fee' => $attributes['total_fee'],
                    'member_id' => $plat_order->member_id,
                    'openid' => $open_id,
                    'spbill_create_ip' => Api::getIp(),
                    'trade_state' => 'NOTPAY',
                    'trade_state_desc' => $content
                ));

                // ======================= 同步平台订单的预付单编号 (pay_rmb_sn) ==========================
                $plat_order->pay_rmb_sn = $attributes['out_trade_no'];
                $plat_order->save();
            }
            else
            {
                Log::error('预付订单生成失败, 返回信息为:' . $result->toJson() . '控制器:WxPayController@pay');
                return view('errors.error');        // 数据有误
            }
        }

        // ============================= 通过预付单id得到支付数据json ===========================
        // 微信支付数据json(返回前台数据)
        // 返回 json 字符串，如果想返回数组，传第二个参数 false
        $wx_json = $this->payment->configForPayment($prepayId);


        /* 买家支付明细($arr_payment_info，数组)，格式如下：
            [
                'pay_rmb'=>['pay_type'=>'rmb','pay_id'=>326,'pay_amount'=>12.55],
                'pay_vrb'=>['pay_type'=>'vrb','pay_id'=>14,'pay_amount'=>4,'pay_amount_to_rmb'=0.4],
                'pay_wallet'=>['pay_type'=>'wallet','pay_id'=>26,'pay_amount'=>0.45],
                'pay_card_balance'=>['pay_type'=>'card_balance','pay_id'=>456,'pay_amount'=>10,'card_lst'=>[] ],
                'pay_voucher'=>['pay_type'=>'voucher','pay_id'=>51,'pay_amount'=>50,'voucher_lst'=>[] ]
            ]

            注释：rmb——人民币；vrb——虚拟币；wallet——零钱【钱包】；
                  card_balance——卡余额；voucher——代金劵
                  pay_amount_to_rmb——虚拟币抵人民币额
        */
        $arr_payment_info = unserialize(trim($plat_order->payment . ''));

        //获取虚拟币名称【外部简称】
        $base_class = new BaseController();
        $plat_vrb_caption = $base_class->getPlatVrbCaption();

        // 返回h5页面去完成支付
        return view('wx.pay.sd_wxPay', compact('wx_json', 'plat_order', 'arr_payment_info','plat_vrb_caption'));
    }

    /** 确认非人民币支付，即确认虚拟币、零钱、卡余额、优惠劵等非人民币支付
     * 此方法支持两种情况的调用：
     *  1、订单全部由非人民币支付，人民币支付金额为零；
     *  2、微信支付人民币成功后，回调执行该方法，确认非人民币支付；
     * 传入参数
     *  @param $plat_order     object  订单对象
     * @param $flag  只有团采货代理用户使用货到付款，电子汇款，账期支付等非微信支付的才会用到该参数，该参数是为了
     * 日志是用 $flag标记其使用的支付方式5货到付款，7电子付款，8账期支付
     * 传出参数：
     *  @param $return_value        int     (0成功；1出错)
    */
    private function confirmNotRmbPay(& $plat_order, $pay_rmb_sn = '', $flag='')
    {
        $return_value = 0;
        if (empty($plat_order))
        {
            // 逻辑上，订单不应该不存在
            $return_value = 1;
            return $return_value;
        }

        //  plat_order_state tinyint(4) 订单状态
        //  1:（已下单）待付款; 2:（已付款）待发货; 3:（已发货）待收货; 4:（已收货）待评价; 9:已完成;
        //  -1:已取消; -2:已退单; -9:已删除; ）',
        $plat_order_state = 2;
        $member_id = $plat_order->member_id;
        $plat_order_id = $plat_order->plat_order_id;
        $str_payment = trim($plat_order->payment . '');
        $arr_payment_info = unserialize($str_payment);
        /* 订单支付信息（$arr_payment_info）,数组，格式如下：
        [
            'pay_rmb'=>['pay_type'=>'rmb','pay_id'=>326,'pay_amount'=>12.55],
            'pay_vrb'=>['pay_type'=>'vrb','pay_id'=>14,'pay_amount'=>4,'pay_amount_to_rmb'=0.4],
            'pay_wallet'=>['pay_type'=>'wallet','pay_id'=>26,'pay_amount'=>0.45],
            'pay_card_balance'=>['pay_type'=>'card_balance','pay_id'=>456,'pay_amount'=>10,'card_lst'=>[] ],
            'pay_voucher'=>['pay_type'=>'voucher','pay_id'=>51,'pay_amount'=>50,'voucher_lst'=>[] ]
        ]
            注释：rmb——人民币；vrb——虚拟币；wallet——零钱【钱包】；
                  card_balance——卡余额；voucher——代金劵

            卡余额支付列表（card_lst），数组，格式如下：
            [
                ['rechargecard_id'=>321,'card_id'=>123452,
                    'balance_amount'=>300,'balance_available'=>250,'balance_pay_amount'=>200],
                ......
            ]

            rechargecard_id——卡余额ID
            card_id——卡ID
            balance_amount——卡总金额
            balance_available——可用金额【请注意，此时已经减去冻结金额】
            balance_pay_amount——支付金额

            优惠劵支付详情（voucher_lst），数组，格式如下：
        */
        // =================================== 1、非人民币确认支付 =======================
        if (count($arr_payment_info) > 0)
        {
            foreach ($arr_payment_info as & $row){
                $data = array();
                $data['member_id'] = $member_id;
                $data['busine_id'] = $plat_order_id;

                $pay_type = $row['pay_type'];
                switch ($pay_type)
                {
                    case "rmb":
                        $row['pay_id'] = $pay_rmb_sn;
                        break;
                    case "vrb":
                        $data['id'] = $row['pay_id'];
                        $busine_content = '订单确认支付，订单号为：' . $plat_order_id;

                        // 2002	订单确认支付
                        $data['yesb_amount'] = $row['pay_amount'];
                        $obj_log = MemberYesbLog::ChangeBalance(2002, $data, $busine_content);
                        break;
                    case "wallet":
                        //  LQPAY(零钱支付订单)
                        Log::notice('WxPayController@confirmNotRmbPay中LQPAY零钱支付');
                        $busine_type = 'LQPAY';
                        $data['balance_amount'] = $row['pay_amount'];
                        $obj_log = MemberWalletLog::changeBalance($busine_type, $data);
                        if(!$obj_log){
                            Log::notice('WxPayController@confirmNotRmbPay中零钱支付订单失败！');
                            $return_value = 1;
                            return $return_value;
                        }
                        break;
                    case "card_balance":
                        $card_lst = (array)$row['card_lst'];
                        if (count($card_lst) > 0)
                        {
                            /* $card(卡（余额）)数据格式形如：
                                ['rechargecard_id'=>321,'card_id'=>123452,
                                    'balance_amount'=>300,'balance_available'=>250,'balance_pay_amount'=>200]
                            */
                            // 1、循环更新卡（余额）的可用金额
                            $balance_amount = 0.00;
                            foreach ($card_lst as $card) {
                                $balance_amount += $card['balance_pay_amount'];
                            }

                            // 2、卡余额收支明细表增加一条支出记录
                            // KYEPAY   卡余额支付订单
                            $busine_type = 'KYEPAY';
                            $data['balance_amount'] = $balance_amount;
                            $obj_log = MemberBalanceLog::changeBalance($busine_type, $data);
                        }
                        break;
                    case "voucher":
                        break;
                }
            }

            // 更新当前订单支付信息，主要回填支付记录ID
            $str_payment = serialize($arr_payment_info);
            $plat_order->payment = $str_payment;
        }


        // =================================  2.向队列中添加一条充值任务 ========================
        // 如果订单是充值卡，自动化任务队列增加充值业务
        // $sku_source_type  int  SKU来源类型【0:立即购买;1:购物车;7:电子充值卡;8:直接充值;9:礼品（礼券）;】
        Log::notice('WxPayController@confirmNotRmbPay向队列中开始添加一条充值任务！');
        $sku_source_type = 0;
        $this->addRechargeJobToQueue($plat_order, $sku_source_type);


        // =================================  3、向队列中添加一条拆单任务 =====================
        Log::notice('WxPayController@confirmNotRmbPay向队列中开始添加一条拆单任务！');
        // 如果平台订单是自动拆单给供应商，自动化任务队列增加拆单业务
        if ($sku_source_type == 7 || $sku_source_type == 8){
            // 电子充值卡订单（$sku_source_type = 7）或直接充值订单（$sku_source_type = 8）不配送，不拆单
            // 直接充值订单（$sku_source_type = 8）支付成功后，订单状态为“9:已完成”；
            // 电子充值卡订单（$sku_source_type = 7）支付成功后，订单状态为“3:（已发货）待收货”；
            $plat_order_state = ($sku_source_type == 7 ? 3:9);
        }else{
            $this->addDismantleOrderJobToQueue($plat_order_id);
        }

        // =================================== 4、修改订单支付状态 =======================
        // 更新订单状态，关于拆单状态，暂时不涉及
        $plat_order->pay_rmb_time = time();
        $plat_order->pay_rmb_sn = $pay_rmb_sn;

        // 如果订单状态为待收货（3），设置发货时间
        if ($plat_order_state == 3 || $plat_order_state == 9){
            $plat_order->transport_time = time();

            // 如果订单状态完成（9），签收时间
            if ($plat_order_state == 9){
                $plat_order->arrival_time = time();
            }
        }

        $plat_order->plat_order_state = $plat_order_state;
        Log::notice('WxPayController@confirmNotRmbPay修改订单支付状态！');
        $plat_order->save();


        // =================================== 5.记录平台订单操作日志 =====================

        //对于代理商和团采用户的日志
        if($flag){
             switch($flag){

                 case '5':
                     $log = '买家使用货到付款，订单id: '. $plat_order_id . '； ' .
                            '需要支付的人民币：'. $plat_order->pay_rmb_amount . ' 元， ' ;

                     LogInfoFacade::logOrderPlat($plat_order_id, $log, 2, $member_id, '买家');
                     break;

                 case '8':
                     $log = '买家使用账期支付，订单id: '. $plat_order_id . '； ' .
                         '需要支付的人民币：'. $plat_order->pay_rmb_amount . ' 元， ' ;

                     LogInfoFacade::logOrderPlat($plat_order_id, $log, 2, $member_id, '买家');
                     break;

             }
            return $return_value;

        }

        $log_content = '买家已成功付款, 订单id: ' . $plat_order_id . '； ' .
            '支付单号: ' . $pay_rmb_sn . '；' .
            '付款时间： ' . date('Y-m-d H:i:s') . '； ' .
            '支付人民币：' . $plat_order->pay_rmb_amount . ' 元， ' .
            '虚拟币 ' . $plat_order->pay_points_amount . ' 个， ' .
            '其它（零钱、卡余额、优惠劵等） ' . $plat_order->pay_wallet_amount . ' 元。 ' ;
        LogInfoFacade::logOrderPlat($plat_order_id, $log_content, 2, $member_id, '买家');

        return $return_value;
    }

    /** 如果是直接充值订单，自动化任务队列增加充值业务；如果是电子充值卡，发短信告知买家绑定卡密；
     *  传入参数：
     *  @param  $plat_order  object    订单对象
     * 传出参数：
     *  @param  $sku_source_type  int  SKU来源类型【0:立即购买;1:购物车;7:电子充值卡;8:直接充值;9:礼品（礼券）;】
     */
    private function addRechargeJobToQueue($plat_order, &$sku_source_type = 0)
    {

        $db_prefix = $this->db_prefix;
        $member_id = $plat_order->member_id;
        $plat_order_id = $plat_order->plat_order_id;
        $sql = "SELECT ra.activity_id,ra.total_amount,ra.goods_amount,
                    ra.balance_amount,ra.balance_cash_type,ra.month_total,ra.grade,
                    og.number
                FROM " . $db_prefix . "recharge_activity as ra
                    INNER JOIN " . $db_prefix . "order_goods as og ON ra.sku_id = og.sku_id
                WHERE og.plat_order_id = " . $plat_order_id . "
                AND ra.company_id = 0
                AND ra.activity_state = 1
                AND ra.start_time <= " . time() . "
                AND ra.end_time >= " . time();
        $rechargeActivityList = DB::select($sql, []);
        if ($rechargeActivityList)
        {
            /* 订单扩展表
                is_fast_recharge    tinyint(4) （充值卡购买支付后）是否立即充值(0:否;1:是)
                sku_source_type     tinyint(4)  SKU来源类型(0:立即购买;1:购物车;7:电子充值卡;8:直接充值;9:礼品（礼券）;)
            */
            //  在个人中心直接充值（8:直接充值）的订单（is_fast_recharge=1），可立即充值；
            //  在商城立即购买（7:电子充值卡）的订单（is_fast_recharge=0），通过验证码充值；
            $sku_source_type = 7;
            $two_dimension_number_code = '';
            $sql = "select two_dimension_number_code,sku_source_type,is_fast_recharge
                from " . $db_prefix . "order_extend
                where plat_order_id = " . $plat_order_id . "
                limit 0,1 " ;
            $obj_order_extend = DB::select($sql, []);
            if (count($obj_order_extend) > 0)
            {
                $sku_source_type = (int)($obj_order_extend[0]->sku_source_type);
                $two_dimension_number_code = (string)($obj_order_extend[0]->two_dimension_number_code);
            }

            if ($sku_source_type == 8)
            {
                // 立即将充值卡余额充值到个人账户
                $this->dispatch(new HandOutRechargeCard($plat_order, $rechargeActivityList));
                Log::notice('订单id:' . $plat_order_id . ' 加入充值队列');
            }
            else
            {
                // 给买家发短信，告知充值卡激活码信息,$member_id,$two_dimension_number_code
                // 发短信的条件：当前用户已经绑定手机，如果没有绑定，直接退出
                $bind_mobile_num = Member::CheckIsNotBindMobile($member_id);
                if ($bind_mobile_num != -1)
                {
                    // 将充值卡验证码通过买家绑定的手机发送给买家
                    $obj_phoneMsg = new GetPhoneMsgCodeController();
                    $obj_phoneMsg->sendPhoneMessage($bind_mobile_num, $two_dimension_number_code);
                }
            }
        }
    }

    private function addDismantleOrderJobToQueue($plat_order_id)
    {
        // 平台订单是否自动派单（给供货商）【0人工（默认）; 1自动】   is_auto_order_split
        $is_auto_order_split = 0;
        $db_prefix = $this->db_prefix;
        $sql = "select value as parameter_value
                from " . $db_prefix . "plat_setting
                where name = 'is_auto_order_split'
                limit 0,1 " ;
        $obj_plat_setting = DB::select($sql, []);
        if (count($obj_plat_setting) > 0)
        {
            $is_auto_order_split = (int)($obj_plat_setting[0]->parameter_value);
        }

        if ($is_auto_order_split == 1)
        {
            $this->dispatch(new AutoDismantleOrder($plat_order_id));
            Log::notice('订单id:' . $plat_order_id . ' 加入拆单队列(微信支付及非人民币支付)');
        }
    }

    /**
     * 当用户完成支付后(成功与否都会有这个支付通知, 就是当用户输入完密码按确定之后, 微信就会在前台和后台各发一个支付通知)
     * 当因余额不足等各个原因导致支付失败, 微信返回的result_code 都是 FAIL(以此来判断是否支付成功)
     * @return (响应对微信支付的通知)
     */
    public function wxCallback()
    {
        Log::notice('微信支付回调 ================= wxCallback');

        $response = $this->payment->handleNotify(function ($notify, $successful)
        {
            Log::notice('$notify ==================== ' . json_encode($notify));

            // 查询并验证所支付的平台订单, 进行信息的回填
            $pay_rmb_sn = $notify->out_trade_no;
            $plat_order = PlatOrder::where('pay_rmb_sn', $pay_rmb_sn)->first();
            if (!$plat_order)
            {
                Log::alert('用户支付成功, 但是订单不存在 控制器:WxPayController@wxCallback');

                // 告诉微信，我已经处理完了，订单没找到，别再通知我了
                return true;
            }

            // 如果支付时间已经回填, 说明订单中支付成功信息已经保存, 可以给微信服务器返回true, 不再发请求了
            if ($plat_order->pay_rmb_time)
            {
                // 支付时间不为空,说明已经完成支付
                Log::alert('用户已完成对该订单的支付 控制器:WxPayController@wxCallback');
                return true;
            }


            // 用户是否支付成功(订单第一次支付,补全订单表中的支付详细信息)
            if ($successful)
            {

                Log::notice('用户微信支付成功，补全订单表中的支付详细信息');
                // 1、回填订单预支付表信息
                $order_prepay = OrderPrepay::where('out_trade_no', $pay_rmb_sn)->first();

                // 格式化支付完成时间
                $str_pay_rmb_time = date('Y-m-d H:i:s', strtotime($notify->time_end));
                $prepay_content = '2、完成对预付单的支付, 支付完成时间为:' . $str_pay_rmb_time . ', 预付单状态更新为: "SUCCESS"。  ';

                $order_prepay->transaction_id = $notify->transaction_id;    // 微信支付订单号
                $order_prepay->cash_fee = $notify->cash_fee;                 // 现金支付金额
                $order_prepay->bank_type = $notify->bank_type;              // 付款银行
                $order_prepay->time_end = $notify->time_end;                // 支付完成时间
                $order_prepay->trade_state = 'SUCCESS';                     // 支付状态为支付成功
                $order_prepay->trade_state_desc .= $prepay_content;         // 交易状态描述
                $order_prepay->is_update_order = 1;                         // 是否已更新订单(0:否;1:是;)
                $order_prepay->is_close = 1;                                //  是否已关闭(0:否;1:是;)
                $order_prepay->save();

                //对于代理会员和团采会员中的货到付款款，微信支付成功说明货已经收到了，不在进行派单了，更改订单状态为已完成，同时进行
                //确认非人民币支付，下面的派单不在进行，参照confirmNotRmbPay方法进行改写
                if($plat_order->pay_mode_id == 5 || $plat_order->pay_mode_id == 8){ //若订单支付方式为货到付款，账期支付时，用户收到货用微信支付成功后，把订单状态直接改为已完成不在进行派单
                    Log::notice('代理会员和团采会员用户微信支付成功，调用confirmSucess更改订单状态');
                    $this->confirmSucess($plat_order, $pay_rmb_sn);
                }elseif($plat_order->is_share_gifts == 1){ //若是用于wx分享的订单
                    //处理非人民币支付的支付日志
                    Log::notice('用户微信支付成功，调用wxShareGiftConfirmNotRmbPay确认非人民币支付');
                    $this->wxShareGiftConfirmNotRmbPay($plat_order);
                }else{
                    // 2、确认非人民币支付
                    Log::notice('用户微信支付成功，调用confirmNotRmbPay确认非人民币支付');
                    $r = $this->confirmNotRmbPay($plat_order, $pay_rmb_sn);
                    if($r){
                        return true;
                    }
                }

            }
            else
            {
                Log::warning('用户支付失败 控制器:WxPayController@wxCallback ==>平台订单号为:' . $plat_order->plat_order_sn);
                return false;
            }

            // 告诉微信已接收到支付通知, 并处理完成 false再稍后还会重新得到微信的支付通知
            return true;
        });

        return $response;
    }

    /**
     * 对于代理用户，团采用户，当订单为货到付款，账期支付时，他们收到货进行微信支付时，把订单状态状态直接改为已完成
     * 不在对订单进行分单
     */
     public function confirmSucess(& $plat_order, $pay_rmb_sn = ''){
         $return_value = 0;
         if (empty($plat_order))
         {
             // 逻辑上，订单不应该不存在
             $return_value = 1;
             return $return_value;
         }

         //  plat_order_state tinyint(4) 订单状态
         //  1:（已下单）待付款; 2:（已付款）待发货; 3:（已发货）待收货; 4:（已收货）待评价; 9:已完成;
         //  -1:已取消; -2:已退单; -9:已删除; ）',
         $plat_order_state = 9; //把订单改为已完成
         $member_id = $plat_order->member_id;
         $plat_order_id = $plat_order->plat_order_id;

         // =================================== 4、修改订单支付状态 =======================
         // 更新订单状态，关于拆单状态，暂时不涉及
         $plat_order->pay_rmb_time = time();
         $plat_order->pay_rmb_sn = $pay_rmb_sn;

         // 如果订单状态为待收货（3），设置发货时间
         if ($plat_order_state == 3 || $plat_order_state == 9){
             $plat_order->transport_time = time();

             // 如果订单状态完成（9），签收时间
             if ($plat_order_state == 9){
                 $plat_order->arrival_time = time();
             }
         }

         $plat_order->plat_order_state = $plat_order_state;
         $plat_order->save();

         // =================================== 5.记录平台订单操作日志 =====================

         //对于代理商和团采用户的日志
         switch($plat_order->pay_mode_id){

                 case '5':
                     $log = '买家订单使用货到付款，该订单已完成，订单id: '. $plat_order_id . '； ' .
                         '支付的人民币：'. $plat_order->pay_rmb_amount . ' 元， ' .
                         '付款时间： ' . date('Y-m-d H:i:s') . '； ' ;
                     LogInfoFacade::logOrderPlat($plat_order_id, $log, 2, $member_id, '买家');
                     break;


                 case '8':
                     $log = '买家订单使用账期支付，该订单已完成，订单id: '. $plat_order_id . '； ' .
                         '支付的人民币：'. $plat_order->pay_rmb_amount . ' 元， ' .
                         '付款时间： ' . date('Y-m-d H:i:s') . '； ' ;

                     LogInfoFacade::logOrderPlat($plat_order_id, $log, 2, $member_id, '买家');
                     break;

         }

         return $return_value;

     }

    /** 确认非人民币支付，即确认虚拟币、零钱、卡余额、优惠劵等非人民币支付
     * 此方法支持两种情况的调用：
     *  1、订单全部由非人民币支付，人民币支付金额为零；
     *  2、微信支付人民币成功后，回调执行该方法，确认非人民币支付；
     * 传入参数
     *  @param $plat_order     object  订单对象

     * 传出参数：
     *  @param $return_value        int     (0成功；1出错)
     */
    public  function  wxShareGiftConfirmNotRmbPay(& $plat_order)
    {
        $return_value = 0;
        if (empty($plat_order))
        {
            // 逻辑上，订单不应该不存在
            $return_value = 1;
            return $return_value;
        }
        $plat_order_id = $plat_order->plat_order_id;

//        // ---------------------------------- 3.4 保存虚拟币支付日志 -----------------
//        // 订单保存成功后，如果订单支付使用了非人民币支付，
//        // 诸如“虚拟币、零钱、卡余额、优惠劵”等，这些支付信息要保存到相关账户收支明细表中
//        $order_c = new OrderController();
//        $order_c->saveNotRmbPayLog($plat_order_id);


        //  plat_order_state tinyint(4) 订单状态
        //  1:（已下单）待付款; 2:（已付款）待发货; 3:（已发货）待收货; 4:（已收货）待评价; 9:已完成;
        //  -1:已取消; -2:已退单; -9:已删除; ）',
        $plat_order_state = 2;
        $member_id = $plat_order->member_id;

        $str_payment = trim($plat_order->payment . '');
        $arr_payment_info = unserialize($str_payment);
        /* 订单支付信息（$arr_payment_info）,数组，格式如下：
        [
            'pay_rmb'=>['pay_type'=>'rmb','pay_id'=>326,'pay_amount'=>12.55],
            'pay_vrb'=>['pay_type'=>'vrb','pay_id'=>14,'pay_amount'=>4,'pay_amount_to_rmb'=0.4],
            'pay_wallet'=>['pay_type'=>'wallet','pay_id'=>26,'pay_amount'=>0.45],
            'pay_card_balance'=>['pay_type'=>'card_balance','pay_id'=>456,'pay_amount'=>10,'card_lst'=>[] ],
            'pay_voucher'=>['pay_type'=>'voucher','pay_id'=>51,'pay_amount'=>50,'voucher_lst'=>[] ]
        ]
            注释：rmb——人民币；vrb——虚拟币；wallet——零钱【钱包】；
                  card_balance——卡余额；voucher——代金劵

            卡余额支付列表（card_lst），数组，格式如下：
            [
                ['rechargecard_id'=>321,'card_id'=>123452,
                    'balance_amount'=>300,'balance_available'=>250,'balance_pay_amount'=>200],
                ......
            ]

            rechargecard_id——卡余额ID
            card_id——卡ID
            balance_amount——卡总金额
            balance_available——可用金额【请注意，此时已经减去冻结金额】
            balance_pay_amount——支付金额

            优惠劵支付详情（voucher_lst），数组，格式如下：
        */
        // =================================== 1、非人民币确认支付 =======================
        if (count($arr_payment_info) > 0)
        {
            foreach ($arr_payment_info as & $row){
                $data = array();
                $data['member_id'] = $member_id;
                $data['busine_id'] = $plat_order_id;

                $pay_type = $row['pay_type'];
                switch ($pay_type)
                {
                    case "rmb":
                        $row['pay_id'] = $plat_order->pay_rmb_sn;
                        break;
                    case "vrb":
                        $data['id'] = $row['pay_id'];
                        $busine_content = '订单确认支付，订单号为：' . $plat_order_id;

                        // 2002	订单确认支付
                        $data['yesb_amount'] = $row['pay_amount'];
                        $obj_log = MemberYesbLog::ChangeBalance(2002, $data, $busine_content);
                        break;
                    case "wallet":
                        //  LQPAY(零钱支付订单)
                        Log::notice('WxPayController@confirmNotRmbPay中LQPAY零钱支付');
                        $busine_type = 'LQPAY';
                        $data['balance_amount'] = $row['pay_amount'];
                        $obj_log = MemberWalletLog::changeBalance($busine_type, $data);
                        if(!$obj_log){
                            Log::notice('WxPayController@confirmNotRmbPay中零钱支付订单失败！');
                            $return_value = 1;
                            return $return_value;
                        }
                        break;
                    case "card_balance":
                        $card_lst = (array)$row['card_lst'];
                        if (count($card_lst) > 0)
                        {
                            /* $card(卡（余额）)数据格式形如：
                                ['rechargecard_id'=>321,'card_id'=>123452,
                                    'balance_amount'=>300,'balance_available'=>250,'balance_pay_amount'=>200]
                            */
                            // 1、循环更新卡（余额）的可用金额
                            $balance_amount = 0.00;
                            foreach ($card_lst as $card) {
                                $balance_amount += $card['balance_pay_amount'];
                            }

                            // 2、卡余额收支明细表增加一条支出记录
                            // KYEPAY   卡余额支付订单
                            $busine_type = 'KYEPAY';
                            $data['balance_amount'] = $balance_amount;
                            $obj_log = MemberBalanceLog::changeBalance($busine_type, $data);
                        }
                        break;
                    case "voucher":
                        break;
                }
            }

            // 更新当前订单支付信息，主要回填支付记录ID
            $str_payment = serialize($arr_payment_info);
            $plat_order->payment = $str_payment;
        }


        // =================================  2.向队列中添加一条充值任务 ========================
        // 如果订单是充值卡，自动化任务队列增加充值业务
        // $sku_source_type  int  SKU来源类型【0:立即购买;1:购物车;7:电子充值卡;8:直接充值;9:礼品（礼券）;】
//        Log::notice('WxPayController@confirmNotRmbPay向队列中开始添加一条充值任务！');
//        $sku_source_type = 0;
//        $this->addRechargeJobToQueue($plat_order, $sku_source_type);


        // =================================  3、向队列中添加一条拆单任务 =====================
//        Log::notice('WxPayController@confirmNotRmbPay向队列中开始添加一条拆单任务！');
//        // 如果平台订单是自动拆单给供应商，自动化任务队列增加拆单业务
//        if ($sku_source_type == 7 || $sku_source_type == 8){
//            // 电子充值卡订单（$sku_source_type = 7）或直接充值订单（$sku_source_type = 8）不配送，不拆单
//            // 直接充值订单（$sku_source_type = 8）支付成功后，订单状态为“9:已完成”；
//            // 电子充值卡订单（$sku_source_type = 7）支付成功后，订单状态为“3:（已发货）待收货”；
//            $plat_order_state = ($sku_source_type == 7 ? 3:9);
//        }else{
//            $this->addDismantleOrderJobToQueue($plat_order_id);
//        }

        // =================================== 4、修改订单支付状态 =======================
        // 更新订单状态，关于拆单状态，暂时不涉及
        $plat_order->pay_rmb_time = time();
//        $plat_order->pay_rmb_sn = $pay_rmb_sn;

        // 如果订单状态为待收货（3），设置发货时间
//        if ($plat_order_state == 3 || $plat_order_state == 9){
//            $plat_order->transport_time = time();
//
//            // 如果订单状态完成（9），签收时间
//            if ($plat_order_state == 9){
//                $plat_order->arrival_time = time();
//            }
//        }

        $plat_order->plat_order_state = $plat_order_state;
        Log::notice('WxPayController@confirmNotRmbPay修改订单支付状态！');
        $plat_order->save();


        // =================================== 5.记录平台订单操作日志 =====================

        //对于代理商和团采用户的日志
//        if($flag){
//            switch($flag){
//
//                case '5':
//                    $log = '买家使用货到付款，订单id: '. $plat_order_id . '； ' .
//                        '需要支付的人民币：'. $plat_order->pay_rmb_amount . ' 元， ' ;
//
//                    LogInfoFacade::logOrderPlat($plat_order_id, $log, 2, $member_id, '买家');
//                    break;
//
//                case '8':
//                    $log = '买家使用账期支付，订单id: '. $plat_order_id . '； ' .
//                        '需要支付的人民币：'. $plat_order->pay_rmb_amount . ' 元， ' ;
//
//                    LogInfoFacade::logOrderPlat($plat_order_id, $log, 2, $member_id, '买家');
//                    break;
//
//            }
//            return $return_value;
//
//        }

        $log_content = '买家已成功付款, 订单id: ' . $plat_order_id . '； ' .
            '支付单号: ' . $plat_order->pay_rmb_sn . '；' .
            '付款时间： ' . date('Y-m-d H:i:s') . '； ' .
            '支付人民币：' . $plat_order->pay_rmb_amount . ' 元， ' .
            '虚拟币 ' . $plat_order->pay_points_amount . ' 个， ' .
            '其它（零钱、卡余额、优惠劵等） ' . $plat_order->pay_wallet_amount . ' 元。 ' ;
        LogInfoFacade::logOrderPlat($plat_order_id, $log_content, 2, $member_id, '买家');

        return $return_value;
    }

    /**
     *  4000602001201609244811737286  transaction_id  微信内部订单号
     *  2117752501201407033243368299  out_trade_no    商户系统内部的订单号,32个字符内
     *  ◆ 当商户后台、网络、服务器等出现异常，商户系统最终未接收到支付通知；
     *  ◆ 调用支付接口后，返回系统错误或未知交易状态情况；
     *  ◆ 调用被扫支付API，返回USERPAYING的状态；
     *  ◆ 调用关单或撤销接口API之前，需确认支付状态；
     */
    public function queryOrder($out_trade_no)
    {
        $orderNo = $out_trade_no;
        $result = $this->payment->query($orderNo);
        dd($result);
    }

    // 如果平台订单是自动拆单给供应商，自动化任务队列增加拆单业务

    /**
     * 以下情况需要调用关单接口：
     * ◆ 商户订单支付失败需要生成新单号重新发起支付，要对原订单号调用关单，避免重复支付；系统下单后，用户支付超时，系统退出不再受理，避免用户继续，请调用关单接口。
     */
    public function closeOrder()
    {
        $orderNo = "2117752501201407033243368199";
        $res = $this->payment->close($orderNo);
        dd($res);
    }

    //微信分享支付

    /**
     * 微信退款
     */
    public function wxRefund($plat_order_id)
    {
        // =================================== 验证数据合法性 =================================
        $plat_order = PlatOrder::where('plat_order_id', $plat_order_id)->first();
        if (empty($plat_order)) {
            Log::error('平台订单id不存在 无法申请退款  控制器:WxPayController@refund');
            return view('errors.error');        // 数据有误
        }
        //0,普通订单;1,微信送礼单,当微信送礼单被全部领取后，不予以退单
        //$get_share_gifts = DB::table('share_gifts_info')->where('member_id',$plat_order->member_id)->
        if($plat_order->is_share_gifts == 1){
            $share_gifts_info = DB::table('share_gifts_info')->where('member_id',$plat_order->member_id)->where('plat_order_id',$plat_order_id)->first();
            $rest_share_num = $share_gifts_info->gifts_num - $share_gifts_info->current_num;
            if($rest_share_num == 0){
                return Api::responseMessage(2, '', '礼品已被领取');
            }
        }
        //通过供应商退款
        /*$refound = DB::table('service_order')->where('plat_order_id',$plat_order_id)->sum(['price','transport_cost_totals']);
        foreach($refound as $val){

        }*/
        $db_prefix = $this->db_prefix;
        $sql = "select sum(price) as price,sum(transport_cost_totals) as transport from ".$db_prefix."service_order where supplier_state = 1 and plat_order_id=".$plat_order_id;
        $data = DB::select($sql);
        $sum_price = bcsub($data[0]->price + $data[0]->transport-$plat_order->pay_org_amount,0.00,2);
        // 如果人民币支付金额为0(即全部为虚拟币支付时)
        if (bccomp($plat_order->pay_rmb_amount, 0.00, 2) == 0) {

            // 1、订单（确认）支付退单,原预支付的虚拟资产，要解冻
            $str_payment = trim($plat_order->payment . '');
            $arr_payment_info = unserialize($str_payment);
            if (count($arr_payment_info) > 0) {
                foreach ($arr_payment_info as & $row) {
                    $data = array();
                    $data['member_id'] = $plat_order->member_id;
                    $data['busine_id'] = $plat_order_id;

                    $pay_type = $row['pay_type'];
                    switch ($pay_type) {
                        case "rmb":
                            break;
                        case "vrb":
                            $data['id'] = $row['pay_id'];
                            //微信分享退单，普通订单
                            if($plat_order->is_share_gifts == 1){
                                $data['yesb_amount'] = (int)($row['pay_amount']/$share_gifts_info->gifts_num)*(int)$rest_share_num;
                            }else{

                                if(bccomp($row['pay_amount_to_rmb'],$sum_price,2) >= 0){
                                    $row['pay_amount'] = (int)($sum_price*10);
                                    $sum_price = 0;
                                }else{
                                    $sum_price = bcsub($sum_price-$row['pay_amount_to_rmb'],0.00,2);
                                }
                                $data['yesb_amount'] = $row['pay_amount'];
                            }
                            $busine_content = '订单未（确认）支付撤单，订单号为：' . $plat_order_id;

                            // 2004	订单（支付后）退单退款
                            $obj_log = MemberYesbLog::ChangeBalance(2004, $data, $busine_content);
                            break;
                        case "wallet":
                            //  DDTDTK  2004【订单（确认）支付退单】；可用余额增加，冻结额度减少，总额度不变。
                            $busine_type = 'DDTDTK';
                            if($plat_order->is_share_gifts == 1){
                                $data['balance_amount'] = bcsub($row['pay_amount']/(int)$share_gifts_info->gifts_num*(int)$rest_share_num,0.00,2);
                            }else{
                                if(bccomp($row['pay_amount'],$sum_price,2) >= 0){
                                    $row['pay_amount'] = bcsub($sum_price,0.00,2);
                                    $sum_price = 0;
                                }
                                $data['balance_amount'] = $row['pay_amount'];
                            }

                            $obj_log = MemberWalletLog::changeBalance($busine_type, $data);
                            break;
                        case "card_balance":
                            $card_lst = (array)$row['card_lst'];
                            if (count($card_lst) > 0) {
                                /* $card(卡（余额）)数据格式形如：
                                    ['rechargecard_id'=>321,'card_id'=>123452,
                                        'balance_amount'=>300,'balance_available'=>250,'balance_pay_amount'=>200]
                                */
                                // 1、循环更新卡（余额）的可用金额
                                $balance_amount = 0.00;
                                foreach ($card_lst as $card) {
                                    if($plat_order->is_share_gifts == 1){
                                        $balance_pay_amount = $card['balance_pay_amount']/(int)$share_gifts_info->gifts_num*(int)$rest_share_num;
                                        $balance_amount += bcsub($balance_pay_amount,0.00,2);
                                    }else{
                                        if(bccomp($card['balance_pay_amount'],$sum_price,2) >= 0){
                                            $card['balance_pay_amount'] = bcsub($sum_price,0.00,2);
                                        }
                                        $balance_amount += $card['balance_pay_amount'];
                                    }

                                    $obj_card = MemberRechargeCard::where('rechargecard_id', $card['rechargecard_id'])->first();
                                    if ($obj_card) {
                                        // 原卡（余额）可用余额减少此次支付部分，注意此操作没有冻结操作
                                        if($plat_order->is_share_gifts == 1){
                                            $obj_card->balance_available += bccomp($balance_pay_amount,0.00,2);
                                        }else{
                                            if(bccomp($card['balance_pay_amount'],$sum_price,2) >= 0){
                                                $card['balance_pay_amount'] = bcsub($sum_price,0.00,2);
                                                $sum_price = 0;
                                            }
                                            $obj_card->balance_available += $card['balance_pay_amount'];
                                        }
                                        $obj_card->updated_at = time();
                                        $obj_card->save();
                                    }
                                    if($sum_price == 0){
                                        break;
                                    }
                                }

                                // 2、卡余额收支明细表增加一条支出记录
                                // DDTDTK   2004【订单（支付后）退单退款】；可用余额增加，冻结额度不变，总额度不变。
                                $busine_type = 'DDTDTK';
                                $data['balance_amount'] = $balance_amount;
                                $obj_log = MemberBalanceLog::changeBalance($busine_type, $data);
                            }
                            break;
                        case "voucher":
                            break;
                    }
                    if($sum_price == 0){
                        break;
                    }
                }
            }


        }

        // 2、根据订单SKU 来源类型，更新关联的商品信息
        /*
            sku_source_type tinyint(4) SKU来源类型(0:立即购买;1:购物车;7:购买充值卡;8:立即充值;9:礼品（礼券）;10:（实物）奖品);)
            sku_source_info text sku来源信息【数组序列化】
        */
        $obj_order_extend = OrderExtend::select('sku_source_type', 'sku_source_info')
            ->where('plat_order_id', $plat_order_id)
            ->first();
        if ($obj_order_extend) {
            $sku_source_type = (int)($obj_order_extend->sku_source_type);
            $sku_lst = unserialize(trim($obj_order_extend->sku_source_info . ''));
            switch ($sku_source_type) {
                case 7;
                    // 7:电子充值卡订单【支付成功后不兑换、不充值，发验证码充值，不配送无物流】
                    // 电子充值卡订单，变更相关充值卡信息，操作类型【0:下订单增加销售量；1:撤单或退单减少销售量】
                    // 这里特别要注意的是，$sku_source_type=8 的是直接充值订单，不限购、不配送无物流
                    $this->OrderController->updateRechargeCard($sku_lst, 1);
                    break;
                case 9;
                    // 9:礼品（礼券）订单，变更相关礼品信息，操作类型【0:下订单增加兑换量；1:撤单或退单减少兑换量】
                    $this->OrderController->updateGiftSku($sku_lst, 1);
                    break;
                case 10;
                    // 10:奖品订单，变更相关奖品领取信息，操作类型【0:下订单领取；1:撤单或退单撤销领取】
                    $this->OrderController->updateAwardsRecord($sku_lst, 1);
                    break;
                default:
                    break;
            }
        }

        //人民币退还金额（普通订单和卫星送礼订单）
        if($plat_order->is_share_gifts == 1){
            $pay_rmb_amount = bcsub($plat_order->pay_rmb_amount/(int)$share_gifts_info->gifts_num*(int)$rest_share_num,0.00,2);
        }else{
            if(bccomp($plat_order->pay_rmb_amount,$sum_price,2) >= 0){
                $plat_order->pay_rmb_amount = bcsub($sum_price,0.00,2);
            }
            $pay_rmb_amount = $plat_order->pay_rmb_amount;
        }
        $result = $this->payment->refund($plat_order->pay_rmb_sn, $plat_order->pay_rmb_sn, (int)(bcmul($pay_rmb_amount, 100)));
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            //退款成功，记录日志

            $reason_content = '用户member_id: '.$plat_order->member_id.', 用户名member_name: '.$plat_order->member_id.
                              ' 在'.date('Y-m-d H:i:s').' 申请退款，退款金额为：'.$plat_order->pay_rmb_amount;

            $r_data = [
                'refund_sn' => $plat_order->plat_order_sn,
                'plat_order_id' => $plat_order_id,

                'buy_id' =>  $plat_order->member_id,
                'buy_name' => $plat_order->member_name,
                'buy_mobile' => $plat_order->member_mobile,
                'refund_amount' => $pay_rmb_amount,
                'refund_type' => 3,
                'create_time' => time(),
                'reason_content' => $reason_content
            ];

            //记录退款日志
            $refund_id = DB::table('order_refund')->insertGetId($r_data);
            if (!$refund_id) {
                Log::error($plat_order->member_name.'退款的基本信息生成失败  控制器:WxPayController@wxRefund');
                return Api::responseMessage(1, '', '退单失败！');
            }

            LogInfoFacade::logOrderPlat($plat_order_id, $reason_content, 2, $plat_order->member_id, '买家');

            //return true;
        }

        // 设置订单状态
        $plat_order->plat_order_state = -2;
        $return_value = $plat_order->save();
        if (!$return_value) {
            //return Api::responseMessage(1, '', '失败！');
            return response()->json([
                'code' => 1,
            ]);
        }
        //填写日志
        Log::alert('9999999');
        LogInfoFacade::logOrderPlat($plat_order_id, '退单', -2);
        return response()->json(
            [
                "code" => 0,
                "msg" => 'ok'
            ]
        );



    }

    public function wx_share_gift_pay($plat_order_id)
    {


        // =================================== 验证数据合法性 =================================
        $plat_order = PlatOrder::where('plat_order_id', $plat_order_id)->first();
        if (empty($plat_order)) {
            Log::error('平台订单id不存在 无法申请支付  控制器:WxPayController@wx_share_gift');
            return false;        // 数据有误
        }

        // ================================== 验证该订单 现金支付金额 ===========================
        // 如果人民币支付金额为0(即全部为非人民币支付【诸如：虚拟币、零钱、卡余额、优惠劵等】)
        // 就不用调用微信支付页面，直接确认非人民币支付即可
//        if (bccomp($plat_order->pay_rmb_amount, 0.00, 2) == 0 )
//        {
//            if (empty($plat_order->pay_rmb_time) )
//            {
//                $this->wxShareGiftConfirmNotRmbPay($plat_order);
//            }
//
//            // 显示订单详情信息
//            return redirect('order/info/' . $plat_order->plat_order_id);
//        }


        // 如果用微信支付，必须有微信账号
        // 账户类型(account_type):(1:qq;2:wechat;3:sinaweibo;4:email;5:mobile;6:im;每类型至多一个)
        $member_id = $plat_order->member_id;
        $open_id = MemberOtherAccount::where('member_id', $member_id)
            ->where('account_type',2)
            ->value('account_id');
        if (empty($open_id))
        {
            Log::error('买家微信账户不存在 无法申请微信支付  控制器:WxPayController@wx_share_gift_pay');
            return false;        // 数据有误
        }


        // ============================= 查询是否存在有效的平台预支付订单 ========================
//        $now_time = date('YmdHis');
//        $order_prepay = OrderPrepay::where('out_trade_no', $plat_order->pay_rmb_sn)
//            ->where('time_expire', '>', $now_time)
//            ->where('trade_state', 'NOTPAY')
//            ->where('is_close', 0)
//            ->first();
//
//        // 微信预付单id
//        $prepayId = '';
//        if ($order_prepay)
//        {
//            $prepayId = $order_prepay->prepay_id;
//        }
//        else
//        {
            // ================================ 生成预支付订单信息数组 ===============================
            $attributes = [
                'openid' => $open_id,                                                               // 公众和用户之间的open_id
                'trade_type' => 'JSAPI',                                                            // 交易类型
                'body' => '水丁平台 - 商品',                                                          // 商品描述
                'detail' => '平台商品',                                                              // 商品详情
                'out_trade_no' => time() . date('YmdHis') . rand(pow(10, 7), pow(10, 8) - 1),       // 人民币支付单号 32位随机整数                                 // 商户订单号
                'total_fee' => (int)(bcmul($plat_order->pay_rmb_amount, 100)),                      // 订单总金额，单位为分
                'time_start' => date('YmdHis'),                                                     // 交易起始时间
                'time_expire' => date("YmdHis", strtotime("+15 minute")),                           // 交易结束时间 定义15分钟后失效
                'notify_url' => $this->wxPayCallbackUrl,                                            // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            ];


            // 创建微信支付订单、微信预支付记录
            $wx_order = new Order($attributes);
            $result = $this->payment->prepare($wx_order);

            Log::notice('预付单信息数组:' . json_encode($attributes));
            Log::notice('调用统一下单返回信息:' . $result->toJson());
            if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS')
            {
                $prepayId = $result->prepay_id;                  // 返回预付单信息
                $content = '1、生成订单id为:' . $plat_order_id . '的平台订单预付单, 交易起始时间为:'
                    . date('Y-m-d H:i:s', strtotime($attributes['time_start'])) .
                    '; 交易结束时间为:' . date('Y-m-d H:i:s', strtotime($attributes['time_expire'])) .
                    '; 交易有效时间为15分钟；';

                // ================================ 创建平台预支付订单 ==================================
                OrderPrepay::create(array(
                    'pay_mode_code' => 'wxpay',
                    'pay_mode_name' => '微信支付',
                    'prepay_id' => $prepayId,
                    'out_trade_no' => $attributes['out_trade_no'],
                    'time_start' => $attributes['time_start'],
                    'time_expire' => $attributes['time_expire'],
                    'settlement_total_fee' => $attributes['total_fee'],
                    'member_id' => $plat_order->member_id,
                    'openid' => $open_id,
                    'spbill_create_ip' => Api::getIp(),
                    'trade_state' => 'NOTPAY',
                    'trade_state_desc' => $content
                ));

                // ======================= 同步平台订单的预付单编号 (pay_rmb_sn) ==========================
                $plat_order->pay_rmb_sn = $attributes['out_trade_no'];
                $plat_order->save();
            }
            else
            {
                Log::error('预付订单生成失败, 返回信息为:' . $result->toJson() . '控制器:WxPayController@wx_share_gift_pay');
                return false;        // 数据有误
            }
//        }

        // ============================= 通过预付单id得到支付数据json ===========================
        // 微信支付数据json(返回前台数据)
        // 返回 json 字符串，如果想返回数组，传第二个参数 false
        $wx_json = $this->payment->configForPayment($prepayId,false);


        /* 买家支付明细($arr_payment_info，数组)，格式如下：
            [
                'pay_rmb'=>['pay_type'=>'rmb','pay_id'=>326,'pay_amount'=>12.55],
                'pay_vrb'=>['pay_type'=>'vrb','pay_id'=>14,'pay_amount'=>4,'pay_amount_to_rmb'=0.4],
                'pay_wallet'=>['pay_type'=>'wallet','pay_id'=>26,'pay_amount'=>0.45],
                'pay_card_balance'=>['pay_type'=>'card_balance','pay_id'=>456,'pay_amount'=>10,'card_lst'=>[] ],
                'pay_voucher'=>['pay_type'=>'voucher','pay_id'=>51,'pay_amount'=>50,'voucher_lst'=>[] ]
            ]

            注释：rmb——人民币；vrb——虚拟币；wallet——零钱【钱包】；
                  card_balance——卡余额；voucher——代金劵
                  pay_amount_to_rmb——虚拟币抵人民币额
        */
//        $arr_payment_info = unserialize(trim($plat_order->payment . ''));

        //获取虚拟币名称【外部简称】
//        $base_class = new BaseController();
//        $plat_vrb_caption = $base_class->getPlatVrbCaption();

         return $wx_json;
        // 返回h5页面去完成支付
//        return view('wx.pay.wxPay', compact('wx_json', 'plat_order', 'arr_payment_info','plat_vrb_caption'));
    }





}