<?php

/**
 * 平台订单管理
 * @auth 杨瑞
 * Class OrderController
 * @package App\controllers
 */
namespace App\controllers\ys;

use App\controllers\wx\WxPayController;
use App\facades\Logistics;
use App\facades\Api;
use App\facades\LogInfoFacade;

use App\lib\WyYxThirdApi;
use App\models\order\OrderRefund;
use App\models\order\ServiceOrder;
use App\models\store\StoreWayBill;
use App\models\supplier\OrderSupplier;
use App\models\supplier\StoreDeliverGoods;

use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\models\dct\DctArea;
use App\models\order\Order;
use App\models\order\OrderExtend;
use App\models\order\OrderGoods;
use App\models\order\Order as PlatOrder;
use App\models\goods\GoodsSpu;
use App\models\goods\GoodsSku;
use App\models\goods\GoodsSkuImages;

use App\models\member\MemberCart;
use App\models\member\MemberAddress;
use App\models\member\MemberOtherAccount;

use App\models\member\MemberYesbLog;
use App\models\member\MemberWalletLog;
use App\models\member\MemberBalanceLog;
use App\models\member\MemberRechargeCard;
use App\models\member\MemberGiftCouponGoods;
//use App\models\member\MemberYsxx;

// 充值卡活动
use App\models\marketing\RechargeActivity;

class
OrderController extends BaseController
{
    /**
     * 订单列表页
     * @auth yang
     * @param null $state
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($state = null)
    {
        //判断当前用户是否登陆过
        $member = Auth::user();
        //用户没登录，去登陆
        if (!$member) {
            return redirect('/oauth');
        }


        if (empty($state)) {
            $orders = Order::listsOrder()->where('order_type', 1)->get();
        } elseif ($state == 9) { //4:（已收货）待评价; 9:已完成;二者都是已完成，所以二者都查出来
            $orders = Order::listsOrder()
                ->where(function ($query) {
                    $query->where('plat_order_state', 9)
                        ->orwhere(function ($query) {
                            $query->where('plat_order_state', 4);
                        });
                })->where('order_type', 1)->get();

        } else {
            $orders = Order::listsOrder()->where('plat_order_state', $state)->where('order_type', 1)->get();
        }

//        $orders = empty($state) ? Order::listsOrder()->get() :
//        Order::listsOrder()->where('plat_order_state', $state)->get();

        $orders_info = array();

        foreach ($orders as $order) {
            $order_info = $this->buildInfoByOrder($order);

            array_push($orders_info, $order_info);
        }

        return view('ysview.ys_order.order_list', compact('orders_info', 'state'));
    }

    /**
     * 组装处理一个订单的详细信息数组
     * @auth yang
     * @param $order (Eloquent对象)
     * @return array 数组信息
     */
    private function buildInfoByOrder($order)
    {
        // 存放一个订单的商品信息数组
        $skus_info = array();
        $skus = OrderGoods::selectInfoZd()
            ->where('plat_order_id', $order->plat_order_id)
            ->orderBy('order_detail_index', 'asc')
            ->get();

        foreach ($skus as $sku) {
            // SKU名称显示标题
            $sku_title = trim($sku->sku_title);
            if (!$sku_title) {
                $sku_title = $sku->sku_name;
            }

            // 图片的相对目录增加上域名
            $sku_img = $this->getFullPictureUrl($sku->sku_image);

            // 一个订单中单个商品的信息
            $sku_info = array(
                "order_detail_id" => $sku->order_detail_id,
                "goods_state" => $sku->goods_state,
                'sku_id' => $sku->sku_id,
                'spu_id' => $sku->spu_id,
                'sku_name' => $sku_title,
                'sku_image' => $sku_img,
                'sku_spec' => empty($sku->sku_spec) ? array() : unserialize($sku->sku_spec),
                'goods_price' => $sku->goods_price,
                'transport_cost' => $sku->transport_cost,
                'settlement_price' => $sku->settlement_price,
                'number' => $sku->number
            );

            array_push($skus_info, $sku_info);                                  // 放入商品信息数据
        }

        /*  所有付款（payment text）记录，序列化数组字符，每笔支付的明细，格式为：
            [
                ['pay_type'=>'rmb','pay_id'=>326,'pay_amount'=>12.55],
                ['pay_type'=>'vrb','pay_id'=>14,'pay_amount'=>4],
                ['pay_type'=>'wallet','pay_id'=>26,'pay_amount'=>0.45],
                ['pay_type'=>'card_balance','pay_id'=>456,'pay_amount'=>10],
                ['pay_type'=>'voucher','pay_id'=>51,'pay_amount'=>50]
            ]
            注释：rmb——人民币；vrb——虚拟币；wallet——零钱【钱包】；
                  card_balance——卡余额；voucher——代金劵
        */
        $arr_payment_info = unserialize($order->payment);

        //统计已支付的总金额（虚拟金额+其他支付金额pay_wallet_amount）
        if (isset($arr_payment_info['pay_vrb'])) {
            $yifu_total = bcadd($arr_payment_info['pay_vrb']['pay_amount_to_rmb'], $order->pay_wallet_amount, 2);
        } else {
            $yifu_total = $order->pay_wallet_amount;
        }

        // 一个订单的详细信息
        $order_info = array(
            'plat_order_id' => $order->plat_order_id,                           // 订单id
            'plat_order_sn' => $order->plat_order_sn,                           // 订单编号
            'create_time' => $order->create_time,                               // 生成订单时间
            'transport_time' => $order->transport_time,                         // 发货时间
            'arrival_time' => $order->arrival_time,                             // 到货时间

            'goods_amount_totals' => $order->goods_amount_totals,               // 商品结算金额
            'goods_preferential' => $order->goods_preferential,                 // 商品优惠金额

            'transport_cost_totals' => $order->transport_cost_totals,           // 运费结算金额
            'transport_preferential' => $order->transport_preferential,         // 运费优惠金额
            'fare_amount' => bcsub($order->transport_cost_totals, $order->transport_preferential, 2),

            'order_amount_totals' => $order->order_amount_totals,               // 订单结算总金额
            'payable_amount' => $order->payable_amount,                         // 订单应付金额

            'pay_points_amount' => $order->pay_points_amount,                   // 虚拟币支付金额
            'pay_rmb_amount' => $order->pay_rmb_amount,                         // 人民币支付金额
            'pay_rmb_sn' => $order->pay_rmb_sn,                                 // 人民币支付单号
            'pay_rmb_time' => $order->pay_rmb_time,                             // 人民币支付时间

            'pay_wallet_amount' => $order->pay_wallet_amount,                   // 其它（卡余额、零钱、代金劵等）支付金额
            'payment' => $arr_payment_info,                                         // 所有支付明细

            'plat_order_state' => $order->plat_order_state,                     // 订单状态
            'evaluation_state' => $order->evaluation_state,                     // 评价状态

            'yifu_total' => $yifu_total,

            'skus' => $skus_info,                                               // 订单中商品信息

            'pay_mode_id' => $order->pay_mode_id,
            'pay_mode_code' => $order->pay_mode_code,
            'pay_mode_name' => $order->pay_mode_name,
            'pay_cert' => $order->pay_cert,
            'end_time_day' => 0,

            'is_share_gifts' => $order->is_share_gifts,
            'group_is_send' => $order->group_is_send,
            'is_get_gift' => $order->is_get_gift,

        );
        //若不空，说明有上传的图片，则加上路径
        if ($order->pay_cert) {
            $order_info['pay_cert'] = $this->img_domain . $order->pay_cert;
        }
        //若订单为账期支付,且订单为未完成，则计算其剩余支付时间
        if ($order->pay_mode_id == 8 && $order->plat_order_state >= 1 && $order->plat_order_state < 9) {
            //计算剩余支付时间
            $end_t = $order->create_time + ($order->expire_time * 24 * 3600); //截止时间
            $d_t = time(); //当前时间
            //截止时间大于当前时间，说明还有延迟支付时间，计算延迟支付时间
            if (($end_t - $d_t) > 0) {
                $order_info['end_time_day'] = floor(($end_t - $d_t) / (24 * 3600));  //截止剩余日期天数，精确到天数
            }

        }
        return $order_info;
    }

    /**
     * 订单详情
     * @param $order_sn
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info($order_id)
    {
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前买家未登录，导航到登录界面
            return redirect('/oauth');
        }

        //平台配置内容
        $plat_vrb_name = $this->getPlatSetting('plat_vrb_name');

        // 获取订单对象
        $order = Order::selectInfoZd()
            ->where('plat_order_id', $order_id)
            ->first();

        if (empty($order)) {
            // 60101 => '订单id不存在'
            return Api::responseMessage(60101);
        }

        // 组装订单的详细信息，需要注意的是支付明细（payment）已经反序列化为数组，在外部可直接用
        $order_info = $this->buildInfoByOrder($order);

        // 配送地址信息
        //若地址id为空，说明是虚拟商品，因为虚拟商品不需要发货地址
        $order_address = '';
        if (!empty($order->sendee_address_id)) {

            $address = MemberAddress::where('address_id', $order->sendee_address_id)->first();

            $order_address = array(
                'recipient_name' => $address->recipient_name,
                'mobile' => $address->mobile,
                'address' => $address->area_info . $address->address,
            );
        }

        // 买家留言
        $message = OrderExtend::where('plat_order_id', $order->plat_order_id)->value('order_message');

        //月嫂预约信息
        //$member_ysxx = MemberYsxx::where('plat_order_id', $order->plat_order_id)->first();

        $OrderExtend = OrderExtend::where('plat_order_id', $order->plat_order_id)->first();
        //判断是否关注公众号
        $subscribe = $this->getSubscribe($member_id);

        //,'member_ysxx' wx_order.order_detail
        return view('ysview.ys_order.order_detail', compact('order_info', 'message', 'order_address','subscribe','OrderExtend','plat_vrb_name'));
        //return view('ysview.ys_order.order_detail', compact('order_info', 'message', 'order_address'));



    }

    /** 验证拟下单商品：
     *      1、是否在该商品的销售区域内；
     *      2、是否存在发货站可发货【该仓点有商品可发，并且该仓点物流网络能到达】
     * @param  $skus                array 下单商品SKU及数量，格式为：["12-1","21-3","34-1"]
     * @param  $address_id          int    收货人地址ID
     * @param  $sku_source_type     int    SKU来源类型【0:立即购买;1:购物车;7:电子充值卡;8:直接充值;9:礼品（礼券）;10:（实物）奖品);】
     * @return $return_value int   0(不能下单购买)/1(能下单购买)
     */
    public function canBuy(Request $request)
    {
        // 默认能下单购买【0不能下单；1可以下单】
        $is_can_buy = 1;
        $goods_sku_lst = array();
        $skus = $request->input('skus');
        foreach ($skus as $sku_info) {
            // $sku_info 格式为：12-1，每个数据为：购买商品SkuID、购买数量
            $array_sku = explode('-', $sku_info);
            $sku_id = (int)$array_sku[0];
            $sku_number = (int)$array_sku[1];
            $sku_name = '';

            // use_state  tinyint(4) 有效状态【0:无效;1:有效;-1:已删除】
            $goods_sku = GoodsSku::select('sku_id', 'sku_name', 'sku_title', 'use_state', 'from_plat_code', 'from_plat_skuid')
                ->where('sku_id', $sku_id)->first();

            // 如果为空跳过本次循环
            if (!$goods_sku) {
                continue;
            } else {
                $sku_name = $goods_sku->sku_title;
                if (!$sku_name) {
                    $sku_name = $goods_sku->sku_name;
                }

                if (($goods_sku->use_state) <= 0) {
                    return Api::responseMessage(1, 0, $sku_name . '已下架！');
                }

                /*if($goods_sku->from_plat_code != 0){
                    $stockInfo = WyYxThirdApi::stockInfo(json_encode([$goods_sku->from_plat_skuid]));
                    if($stockInfo['code'] == 200){
                        if($stockInfo['result'][0]['inventory'] < $sku_number){
                            return Api::responseMessage(1, 0,  $sku_name . '库存不足！');
                        }
                    }else{
                        return Api::responseMessage(1, 0,  $stockInfo['msg']);
                    }
                }*/
            }

            $goods_sku_lst[] = array('sku_id' => $sku_id,
                'sku_name' => $sku_name,
                'number' => $sku_number);
        }

        if (count($goods_sku_lst) == 0) {
            // 如果没有传入商品SKU，直接通过
            return Api::responseMessage(1, 0, '没有传入要下单的商品SKU！');
        }

        // $sku_source_type SKU来源类型【0:立即购买;1:购物车;7:电子充值卡;8:直接充值;9:礼品（礼券）;10:（实物）奖品);】
        // 电子充值卡(7)要验证充值活动发卡量，直接充值（8）不限购；但二者都不配送，我物流
        $sku_source_type = 0;
        if ($request->input('sku_source_type')) {
            $sku_source_type = (int)($request->input('sku_source_type'));
        }

        if ($sku_source_type == 7) {
            $member_id = 0;
            $member = Auth::user();
            if ($member) {
                // 验证该用户是否绑定手机号
                $member_id = $member->member_id;
                // 如果立即购买的是电子充值卡【虚拟商品】，要通过卡密充值，需要手机发送卡密，也就是卖家需要绑定手机
                if (($this->getMemberBindMobile($member_id)) == '') {
                    // 104 => '当前用户没有绑定手机'
                    return Api::responseMessage(104, 0, '当前用户没有绑定手机，请到个人中心绑定！');
                };
            } else {
                // 没有登录，提示用户登录
                return Api::responseMessage(102, 0, '当前用户没有登录');
            }

            // 电子充值卡，要验证充值卡活动有效期、发卡量、是否售完等信息
            // $checkData 格式为：array('code' => 0, 'message' => '', 'data' => $recharge_card_lst);
            $checkData = [];
            $checkData = $this->checkRechargeCard($goods_sku_lst);
            if ($checkData['code'] > 0) {
                return Api::responseMessage(2, 0, $checkData['message']);
            }

            $recharge_card_lst = array();
            if (array_key_exists('data', $checkData)) {
                $recharge_card_lst = (array)($checkData['data']);
            }

            // 如果没有充值卡信息，说明已经售完
            if (count($recharge_card_lst) == 0) {
                return Api::responseMessage(2, 0, '充值卡已售完');
            }
        }


        if ($sku_source_type == 7 || $sku_source_type == 8) {
            // 电子充值卡和直接充值，无物流配送
            return Api::responseMessage(0, $is_can_buy, '可以下单购买');
        }

        // 收货人地址，如果为空，启用默认收货人地址
        $address_id = (int)$request->input('address_id');
        $sendee_address = null;
        if ($address_id > 0) {
            $sendee_address = MemberAddress::where('address_id', $address_id)->first();
        }

        // 没有指定收货地址，启用买家的默认地址
        if (!$sendee_address) {
            // 买家信息
            $member = Auth::user();
            $sendee_address = MemberAddress::where('member_id', $member->member_id)
                ->where('is_default', 1)->first();
        }

        if (!$sendee_address) {
            // 提示没有收货人地址
            return Api::responseMessage(1, 0, '没有设置收货人地址！');
        }

        // 所在地区ID
        $message = '';
        $sendee_city_id = $sendee_address->area_id;
        $is_can_buy = $this->isCanBuy($goods_sku_lst, $sendee_city_id, $message);
        return Api::responseMessage(0, $is_can_buy, $message);
    }

    /** 如果SKU是电子充值卡，要检查充值卡合法性及库存情况，特别限制：一次只能购买一张电子充值卡
     * 传入参数：
     * @param  $skus               array   拟下单的SKU 数组，多个
     * 传出参数
     * @param  $recharge_card_lst   array   充值卡信息
     *      如果是电子电子充值卡，只有立即购买业务中才能出现，并且一次只能购买一张电子充值卡【SKU仅一个】
     */
    private function checkRechargeCard($skus)
    {
        /* $skus 结构如下
            $sku = [array(
                        'sku_id'            => 141,     // sku_id
                        'number'            => 2,       // 数量
                        'price'             => 98.03,   // 价格
                        'promotions_type'  => 1,       // 营销类型
                        'promotions_id'    => 0      //  营销记录ID
                ),...];
        */
        $recharge_card_lst = [];
        $db_prefix = $this->db_prefix;

        foreach ($skus as $sku) {
            $sku_id = $sku['sku_id'];

            /* 业务逻辑
            1、以SKU 表为主表，内链接SPU表，验证当前SKU 是否为虚拟商品，如果不是，暂时不做库存验证；
            2、以SKU 表为主表，左链接充值卡活动表，
                验证当前虚拟SKU是否关联有属于平台（company_id=0）的有效充值卡，如果没有，提示错误；
            3、以充值卡活动为主表，左链接充值卡表，验证是否存在未销售未兑换的充值卡，
                如果有未销售未兑换，任选一张拟销售；如果没有，给出提示，该充值卡已销售完；
            */
            $sql = 'select k.sku_id,k.sku_storage_num,
                        p.is_virtual,
                        a.activity_id,a.card_amount,a.sale_num,a.exchange_num,a.start_time,a.end_time,
                        r.card_id,r.two_dimension_id,r.two_dimension_code,r.two_dimension_number_code
                    from ' . $db_prefix . 'goods_sku as k
                        inner join ' . $db_prefix . 'goods_spu as p on k.spu_id = p.spu_id
                        left join ' . $db_prefix . 'recharge_activity as a on k.sku_id = a.sku_id
                            and a.activity_state = 1
                            and a.company_id = 0
                        left join ' . $db_prefix . 'recharge_card as r on a.activity_id = r.activity_id
                            and r.card_state = 0
                            and r.sale_state = 0
                    where k.use_state = 1
                        and k.sku_id = ' . $sku_id . '
                        and p.state = 1
                    limit 0,1 ';
            $obj_recharge_card = DB::select($sql, []);
            if ($obj_recharge_card) {
                // 如果是虚拟商品，关联的充值卡活动不能为空，至少存在一个充值卡
                // is_virtual tinyint(1) 是否虚拟商品【0:否；1:是】',
                $row = (array)$obj_recharge_card[0];
                if ($row['is_virtual']) {
                    $activity_id = (int)$row['activity_id'];
                    if ($activity_id <= 0) {
                        return array('code' => 50002, 'message' => '该充值卡已下架');
                    }

                    if ($row['start_time'] > time() ||
                        $row['end_time'] < time()
                    ) {
                        return array('code' => 50002, 'message' => '该充值卡不在销售期内');
                    }

                    if ($row['card_amount'] - $row['sale_num'] < 1 ||
                        $row['card_amount'] - $row['exchange_num'] < 1
                    ) {
                        return array('code' => 50002, 'message' => '该充值卡已售完');
                    }

                    $card_id = (int)$row['card_id'];
                    if ($card_id <= 0) {
                        return array('code' => 50002, 'message' => '该充值卡已售完');
                    }

                    $recharge_card = array(
                        'sku_id' => $sku['sku_id'],
                        'number' => $sku['number'],
                        'activity_id' => $activity_id,
                        'card_id' => $card_id,
                        'two_dimension_id' => trim($row['two_dimension_id'] . ''),
                        'two_dimension_code' => trim($row['two_dimension_code'] . ''),
                        'two_dimension_number_code' => trim($row['two_dimension_number_code'] . '')
                    );

                    // 增加一个充值卡元素
                    array_push($recharge_card_lst, $recharge_card);
                } else {
                    // 非电子充值卡，暂时不验证库存是否足以销售；直接充值，不限制购买次数
                }
            } else {
                // 没有发现SKU，应该出意外了，购买了没有上架的商品
                return array('code' => 50000, 'message' => '商品不存在或已下架');
            }
        }

        return array('code' => 0, 'message' => '', 'data' => $recharge_card_lst);
    }

    /**
     * 买家能下单，要满足两个条件：
     *  1、下单商品的销售区域覆盖收货地址所在地区【商品可在该地区销售】
     *  2、收货地址所在地区有仓点且有商品配货【有仓点有商品可发货到收货地址所在地区】
     * @param $goods_sku_lst        array   商品SKU，格式为：
     *      array('sku_id'=>$sku_id,'sku_name'=>$sku_name,'sku_number'=>$sku_number)
     * @param $sendee_city_id       int     收货人所在市ID
     * @return $return_value        int     0(不允许下单)/1(允许下单)
     */
    public function isCanBuy($goods_sku_lst, $sendee_city_id = 0, & $message = '')
    {
        // 默认允许下单
        $message = '';
        $is_can_buy = 1;
        $sendee_city_id = (int)$sendee_city_id;
        if (count($goods_sku_lst) == 0 || $sendee_city_id == 0) {
            // 没有传入商品或没有指定收货地区，默认允许下单
            return $is_can_buy;
        }

        foreach ($goods_sku_lst as $goods_sku) {
            $sku_id = $goods_sku['sku_id'];
            $sku_name = $goods_sku['sku_name'];
            $sku_number = $goods_sku['number'];

            // 1、验证商品是否可在该地区销售
            $is_in_sell_area = $this->isInSellArea($sku_id, $sendee_city_id);
            if ($is_in_sell_area <= 0) {
                $is_can_buy = 0;
                $message_0 = '收货地址所在地区不在“' . $sku_name . '”的销售区域内。';
                $message_1 = '“' . $sku_name . '”没有上架或参数有误。';
                $message = ($is_in_sell_area == 0 ? $message_0 : $message_1);
                return $is_can_buy;
            }

            $is_can_deliver = $this->isCanDeliver($sku_id, $sku_number, $sendee_city_id);
            if ($is_can_deliver <= 0) {
                $is_can_buy = 0;
                $message_0 = '“' . $sku_name . '”配送不到收货地址所在地区。';
                $message_1 = '“' . $sku_name . '”没有上架或没有配置发货站。';
                $message = ($is_can_deliver == 0 ? $message_0 : $message_1);
                return $is_can_buy;
            }
        }

        return $is_can_buy;
    }

    /** 判定某商品（sku）的销售区域是否覆盖指定地区
     * @param   $sku_id             int     商品SKU
     * @param   $sendee_city_id     int     收货人所在地区ID
     * @return $return_value        int     0(不在销售区域)/1(在销售区域)
     */
    public function isInSellArea($sku_id, $sendee_city_id = 0)
    {
        // 默认在商品的销售区域内，如果SKU没有设置销售区域，默认在该商品的销售区域内
        $is_in_sell_area = 1;
        $sku_id = (int)$sku_id;

        if ($sku_id <= 0) {
            $is_in_sell_area = -1;
            return $is_in_sell_area;
        }

        $db_prefix = $this->db_prefix;
        $sql = 'select sku_id,area_id_lst
                from ' . $db_prefix . 'goods_sku_sell_area
                where sku_id = ?
                limit 0,1';
        $param = array($sku_id);
        $sku_sell_area = DB::select($sql, $param);
        if (count($sku_sell_area) > 0) {
            $area_id_lst = $sku_sell_area[0]->area_id_lst;
            $area_id_lst = trim($area_id_lst . '');
            if ($area_id_lst != '') {
                // 如果当前仓点设置了发货区域，要验证收货地址所在地区是否在仓点发货地区范围内
                $str_area_id = ',' . (string)$sendee_city_id . ',';
                $int_pos = strpos($area_id_lst, $str_area_id);
                if ($int_pos === 0) {
                    // 首字符匹配（0）处理为 1
                    $int_pos = 1;
                }

                if (!$int_pos) {
                    // 收货地址所在地区不在该商品的销售区域内
                    $is_in_sell_area = 0;
                }
            }
        }

        return $is_in_sell_area;
    }

    /** 判定某商品（sku）是否有仓点有库存，并且可配货到指定地区
     * @param   $sku_id             int     商品SKU
     * @param   $sendee_city_id     int     收货人所在地区ID
     * @return  $return_value        int     0(不能配货)/1(能配货)
     */
    public function isCanDeliver($sku_id, $sku_number = 0, $sendee_city_id = 0)
    {
        // 默认不能配货
        $is_can_deliver = 0;
        $sku_id = (int)$sku_id;
        $sku_number = (int)$sku_number;
        if ($sku_number == 0) {
            $sku_number = 1;
        }

        if ($sku_id <= 0) {
            $is_can_deliver = -1;
            return $is_can_deliver;
        }

        // 暂时不考虑库存数量是否够发货
        // supplier_store_r.state	tinyint(4) 有效状态【0:无效;1:有效;】
        // store_goods_sku.use_state tinyint(4) 有效状态【0:无效;1:有效;-1:已删除】
        $db_prefix = $this->db_prefix;
        $sql = 'select a.store_id,a.storage_num,e.area_id_lst
                from ' . $db_prefix . 'store_goods_sku as a
                  inner join ' . $db_prefix . 'supplier_store_r as d on a.supplier_id = d.supplier_id
                    and a.store_id = d.store_id
                    and d.state = 1
                  left join ' . $db_prefix . 'store_send_area as e on a.store_id = e.store_id
                where a.use_state = 1
                    and a.sku_id = ?
                    and a.supplier_id <> 1
                order by d.weight,a.storage_num desc ';
        $param = array($sku_id);
        $store_goods_info = DB::select($sql, $param);
        if (count($store_goods_info) > 0) {
            foreach ($store_goods_info as $row) {
                // 如果当前仓点没设置发货区域，默认全球任何地方都可配货
                $area_id_lst = trim($row->area_id_lst . '');
                if ($area_id_lst == '') {
                    $is_can_deliver = 1;
                    break;
                }

                // 如果当前仓点设置了发货区域，要验证收货地址所在地区是否在仓点发货地区范围内
                $str_area_id = ',' . (string)$sendee_city_id . ',';
                $int_pos = strpos($area_id_lst, $str_area_id);
                if ($int_pos === 0) {
                    // 首字符匹配（0）处理为 1
                    $int_pos = 1;
                }

                if ($int_pos) {
                    $is_can_deliver = 1;
                    break;
                }
            }
        } else {
            $is_can_deliver = -1;
        }

        return $is_can_deliver;
    }

    /**
     * 平台下单前的购买信息确认页
     * @auth yang
     * @param Request $request
     * @return (view)
     */
    public function showPay(Request $request)
    {
        $member = Auth::user();
        $member_id = $member->member_id;

        /* 参数 SKU 来源类型，目前支持下列业务场景
            1、$sku_source_type = 0   SKU来源于商品详情页，立即购买【默认，没有加入购物车】
            2、$sku_source_type = 1   SKU来源于购物车；
            3、$sku_source_type = 7   购买电子充值卡，来自于商品详情页
            4、$sku_source_type = 8   SKU来源于个人中心，直接充值，即：支付成功后立即充值到我的【零钱、卡余额】，
                                      不限购、不配送、不拆单，商品数量为 1；
            5、$sku_source_type = 9   选择我的礼品（礼券）资产中若干礼品（赠品），这些礼品（赠品）免运费、免支付，
                                      需要配送、需要拆单、数量和价格受限于我的礼品（礼券）资产；
            6、$sku_source_type = 10  选择我的获奖记录中若干实物奖品，这些奖品免运费、免支付，
                                      需要配送、需要拆单、数量和价格受限于我的奖品记录；
        */
        $sku_source_type = 0;
        if ($request->input('sku_source_type')) {
            $sku_source_type = (int)($request->input('sku_source_type'));
        }

        // 购物车记录ID,形如：141,234,921
        $cartIds = '';

        $months = $request->input('months');
        $spuId = $request->input('spuId');


        if ($request->input('cartIds')) {
            $sku_source_type = 1;
            $cartIds = trim($request->input('cartIds') . '');
        }


        // ------------------------------------------------- 验证信息 ----------------------------------------------------
        // 是否立即购买【$sku_source_type = 0；$sku_source_type = 8 为立即购买】
        $is_fastBuy = 0;

        /* 预结算商品信息,格式：
            array(['sku_id'=>180,'number'=>2,'price'=>10.8,'promotions_type'=>1,'promotions_id'=>0],...)
        */
        $define_skus = array();
        switch ($sku_source_type) {
            case 1:
                // SKU来源于购物车；
                $define_skus = $this->getSkusFromMyCart($cartIds, $member_id);
                break;
            case 8:
                // SKU来源于个人中心，直接充值，支付成功后立即充值到我的【零钱、卡余额】
                $sku_id = 0;
                if ($request->input('sku_id')) {
                    $sku_id = (int)($request->input('sku_id'));
                }

                $number = 0;
                if ($request->input('number')) {
                    $number = (int)($request->input('number'));
                }

                if ($sku_id > 0 && $number > 0) {
                    $obj_sku = GoodsSku::select('sku_id')
                        ->where('sku_id', $sku_id)
                        ->first();
                    if ($obj_sku) {
                        $define_sku = array(
                            'sku_id' => $sku_id,
                            'number' => $number,
                            'price' => 0,
                            'promotions_type' => 1,
                            'promotions_id' => 0
                        );
                    }
                }

                $is_fastBuy = 1;
                array_push($define_skus, $define_sku);
                break;
            case 9:
                /*  选择我的礼品（礼券）资产中若干礼品（赠品），这些礼品（赠品）免运费、免支付
                    数据格式为：SKUid、数量、价格、营销活动类型（99）、营销活动ID（礼品记录ID）
                    gift_sku_lst = [array('sku_id'=>180,'number'=>2,'price'=>10.8,
                                            'promotions_type'=>99,'promotions_id'=>103),...]
                */
                $gift_sku_lst = array();
                if ($request->input('gift_sku_lst')) {
                    // 传入的信息项【sku_id,number,price,promotions_type,promotions_id】及合法性，由调用者验证
                    $gift_sku_lst = (array)($request->input('gift_sku_lst'));
                    foreach ($gift_sku_lst as & $gift_sku) {
                        // 1、验证合法性【记录是否存在，数量是否满足】

                        // 2、商品促销类型(promotions_type)【1:无促销(默认); 2:团购; 3:限时折扣; 4:组合套装; 5:赠品;
                        //                               6:满折; 7:满减; 99:礼品（礼券）;100:奖品;】
                        // 商品促销活动ID（promotions_id）【团购ID/限时折扣ID/优惠套装ID/瞒折/满减）与promotions_type搭配使用】
                        $gift_sku['promotions_type'] = 99;
                        // $gift_sku['promotions_id'] = $gift_sku['promotions_id'];
                        $define_skus[] = $gift_sku;
                    }
                }

                break;
            case 10:
                if ($request->input('awards_sku_lst')) {
                    // 传入的信息项【sku_id,number,price,promotions_type,promotions_id】及合法性，由调用者验证
                    // price = 0,奖品没有价格信息，由后期回填
                    // promotions_type = 100;
                    // promotions_id 即 member_awardsrecord.awardsrecord_id
                    $awards_sku_lst = (array)($request->input('awards_sku_lst'));
                    foreach ($awards_sku_lst as $awards_sku) {
                        // 1、验证合法性【记录是否存在，数量是否满足】

                        // 2、促销类型，promotions_type =
                        // 商品促销类型(promotions_type)【1:无促销(默认); 2:团购; 3:限时折扣; 4:组合套装; 5:赠品;
                        //                               6:满折; 7:满减; 99:礼品（礼券）;100:奖品;】
                        // 商品促销活动ID（promotions_id）【团购ID/限时折扣ID/优惠套装ID/瞒折/满减）与promotions_type搭配使用】
                        $define_sku = array(
                            'sku_id' => $awards_sku['sku_id'],
                            'number' => $awards_sku['number'],
                            'price' => 0,
                            'promotions_type' => 100,
                            'promotions_id' => $awards_sku['promotions_id']
                        );

                        $define_skus[] = $define_sku;
                    }
                }

                break;
            default:
                // 会员中心列出的商品直接返回具体的sku_id
                $sku_id = $request->input('goods');
                if ($sku_id) {
                    $define_skus[] = [
                        'sku_id' => $sku_id,
                        'number' => 1,
                        'price' => 0,
                        'promotions_type' => 1,
                        'promotions_id' => 0
                    ];

                    $is_fastBuy = 1;
                    break;
                }

                // 立即购买【普通商品、电子充值卡虚拟商品】

                $define_skus = $this->getSkuByFastBuy($request);
                /*$define_sku = $this->getSkuByFastBuy($request);
                if (count($define_sku) > 0) {
                    array_push($define_skus, $define_sku);
                }*/

                $is_fastBuy = 1;
                break;
        }

        // 如果没有指定要购买的商品，直接退出
        if (count($define_skus) == 0) {
            return view('errors.error');
        }

        //若用户级别团采用户或代理用户，若有享受团采或代理价商品的，校验其购买商品的数量是否达到购买下限
//        if($member->grade == 20 || $member->grade == 30){
//            foreach($define_skus as $d_sku){
//                //查找该该商品是否在该用户享受团采或代理的商品列表中，若存在校验购买下限
//                $sku_info = DB::table('member_goods_sku')->select('sku_id','sku_name','minimum_limit')
//                            ->where('member_id',$member_id)
//                            ->where('sku_id',$d_sku['sku_id'])
//                            ->where('use_state',1)
//                            ->first();
//                //若不存在记录，说明该用户购买该商品不享受代理或团采价，也就没有购买下限的限制，
//                //存在记录则判断最低购买下限
//                if($sku_info){
//                    //若购买数量小于最低购买下限显示错误信息
//                    if($d_sku['number'] < $sku_info->minimum_limit){
//                        $errorData['message'] = "您购买商品 '".$sku_info->sku_name."' 的数量小于其最低购买数量 ".$sku_info->minimum_limit;
//                        return view('errors.error',compact('errorData'));
//                    }
//                }
//
//            }
//        }


        // $sku_source_type【0:立即购买;1:购物车;7:电子充值卡;8:直接充值;9:礼品（礼券）;10:（实物）奖品);】
        // 如果SKU是电子充值卡，要检查充值卡合法性及库存情况
        $recharge_card_lst = array();
        if ($sku_source_type == 0 && $sku_source_type == 7) {//判断是否是电子卡充值，原用||，应该用&& ，0 立即购买且是 7 电子卡，而不应该用或-170714
            // $checkData 格式为：array('code' => 0, 'message' => '', 'data' => $recharge_card_lst);
            $checkData = $this->checkRechargeCard($define_skus);
            if ($checkData['code'] > 0) {
                $errorData = array('message' => $checkData['message']);
                return view('errors.error', compact('errorData'));
                // return Api::responseMessage(2, null, $checkData['message']);
            }

            if (array_key_exists('data', $checkData)) {
                $recharge_card_lst = (array)($checkData['data']);
            }

            // 如果立即购买的时电子充值卡，$sku_source_type 设为 7
            if (count($recharge_card_lst) > 0) {
                // 7:购买充值卡;
                if ($sku_source_type == 0) {
                    $sku_source_type = 7;
                }
            }
        }

        // 如果是礼品（礼券），验证我的礼品库存量是否能满足此次兑换
        if ($sku_source_type == 9) {
            // $checkData 格式为：array('code' => 0, 'message' => '', 'data' => $gift_record_lst);
            $checkData = $this->checkGiftRecord($define_skus);
            if ($checkData['code'] > 0) {
                $errorData = array('message' => $checkData['message']);
                return view('errors.error', compact('errorData'));
                // return Api::responseMessage(2, null, $checkData['message']);
            }
        }

        // 如果是奖品，验证奖品是否已领用
        if ($sku_source_type == 10) {
            // $checkData 格式为：array('code' => 0, 'message' => '', 'data' => $recharge_card_lst);
            $checkData = $this->checkAwardsRecord($define_skus);
            if ($checkData['code'] > 0) {
                $errorData = array('message' => $checkData['message']);
                return view('errors.error', compact('errorData'));
                // return Api::responseMessage(2, null, $checkData['message']);
            }
        }


        // ---------------------------------------1、买家收货人地址----------------------------------------
        // 当前买家收货地址列表
        $address_info = MemberAddress::selectZd()
            ->where('member_id', $member->member_id)
            ->where('use_state', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        // 如果当前买家没有收货地址，提示买家增加
        $is_hasAddress = $address_info->isEmpty() ? 0 : 1;

        // 当前买家默认收货地址
        $default_address = MemberAddress::selectZd()
            ->where('member_id', Auth::user()->member_id)
            ->where('use_state', 0)
            ->where('is_default', 1)
            ->first();

        // 省地址数组（新建地址信息需要）
        $province_dct = DctArea::select('id', 'name', 'pid')
            ->where('pid', 0)
            ->where('is_use', 1)
            ->get()
            ->toArray();

        // -------------------------------------2、根据初定义订单商品数组信息补充运费、优惠及其它信息 -----
        /*  $total_info 的元素有：
                goods_amount_totals         商品结算金额
                transport_cost_totals       运费结算金额
                order_amount_totals         订单结算金额合计(商品金额+运费)
                goods_preferential          商品优惠金额
                transport_preferential      运费优惠金额
                goods_points_totals         虚拟币支付限额

                // 订单应付金额
                $payable_amount = (order_amount_totals - goods_preferential - transport_preferential)
        */
        $total_info = array();      // 预结算统计数据
        $sendee_city_id = 0;        // 收货地址所在区县ID,根据此区县地址，计算运费
        if ($default_address) {
            $sendee_city_id = $default_address->city_id;
        }

        // 如果是电子充值卡，不配送无物流
        $is_virtual = 0;
        if ($sku_source_type == 7 || $sku_source_type == 8) {
            $is_virtual = 1;
        }
        $total_info = $this->fillOrderGoodsInfo($define_skus, $sendee_city_id, $is_virtual);

        // -------------------------------------3、预结算信息、酒币支付、人民币支付等信息-----
        // 商品展示信息 (传入前台数据)
        $skus_info = $this->getSkuInfo($define_skus);

        // 运费应付金额 =  (运费结算金额 - 运费优惠结算金额)
        $fare_total = bcsub($total_info['transport_cost_totals'], $total_info['transport_preferential'], 2);

        // 订单应付金额
        // $order_amount_totals - $goods_preferential_totals - $transport_preferential_totals
        $payable_amount = $total_info['payable_amount'];

        // 订单虚拟币支付限额、我的虚拟币余额
        $goods_points_totals = $total_info['goods_points_totals'];

        // 我的财富列表，用于订单支付
        $my_money_info = $this->iniMyMoneyInfo($payable_amount, $goods_points_totals, $define_skus);

        // 应付人民币额
        $pay_rmb = $payable_amount;

        //若用户级别为团采用户或代理用户，这支付方式显示其能用的支付方式
        $grade = $member->grade;
        $expire_arr = '';
        $payment_type = '';

//        if($grade == 20 || $grade == 30){
//            $payment_type = unserialize($member->payment_type);
//
//            //判断该用户有没有账期支付，若有，提出账期支付延迟时间
//            if(!empty($payment_type)){
//                foreach($payment_type as $k => $v){
//                    if($v['id'] == 8){
//                        $expire_arr = $v;
//                    }
//                }
//            }
//
//
//        }

        // 是否立即购买（is_fastBuy）、SKU来源类型（sku_source_type）
        return view('wx_order.ysorder_confirm', compact(
            'is_hasAddress', 'address_info', 'default_address', 'province_dct',
            'skus_info', 'fare_total', 'payable_amount', 'my_money_info', 'pay_rmb',
            'is_fastBuy', 'sku_source_type', 'payment_type', 'grade', 'expire_arr','months','spuId'
        ));
    }


    // ---------------------------以下为本类私有函数-------------------------------------

    /** 根据选择我的购物车中的记录ID，获得商品SKU信息
     * 传入参数：
     * @param      $cartIds     string   购物车记录ID列，数据格式“141,234,921”；
     * @param      $member_id   int      买家ID
     * 传出参数：
     * @param      $define_skus  array  SKU信息数组，数据格式如下：
     *   array(['sku_id'=>180,'number'=>2,'price'=>10.8,'promotions_type'=>1,'promotions_id'=>0],...)
     */
    private function getSkusFromMyCart($cartIds, $member_id)
    {
        // SKU来源于购物车,购物车信息项：sku_id、number
        // 验证购物车中是否存在勾选商品(验证skuIds的正确性)
        $define_skus = array();
        $cart_skus = MemberCart::selectZd()
            ->where('member_id', $member_id)
            ->whereIn('cart_id', explode(',', $cartIds))
            ->orderBy('created_at', 'desc')
            ->get();
        if ($cart_skus->isEmpty()) {
            return $define_skus;
        }

        // 商品促销类型(promotions_type)【1:无促销(默认); 2:团购; 3:限时折扣; 4:组合套装; 5:赠品; 6:满折; 7:满减;99:礼品（礼券）;100:奖品;】
        // 商品促销活动ID（promotions_id）【团购ID/限时折扣ID/优惠套装ID/瞒折/满减）与promotions_type搭配使用】
        // 请注意，这两项信息将来要从SKU关联的营销活动中实时获取
        $define_skus = $cart_skus->toarray();
        foreach ($define_skus as & $row) {
            // sku_id、number
            // 如果价格为零，将来和SKU关联时实时提取，只有我的礼品是从礼品包中提取
            $row['price'] = 0;
            $row['promotions_type'] = 1;
            $row['promotions_id'] = 0;
        }

        return $define_skus;
    }

    /** 根据立即购买选择的商品及数量，获得商品SKU信息
     * 传入参数：
     * @param      $number     int     购买数量
     * @param      $spuId      int     商品SPUID
     * @param      $guiges     string  SKU规格
     * 传出参数：
     * @param      $define_sku  array  SKU信息数组，数据格式如下：
     *   array('sku_id'=>180,'number'=>2,'price'=>10.8,'promotions_type'=>1,'promotions_id'=>0)
     */
    private function getSkuByFastBuy(Request $request)
    {
        $define_sku = array();
        $number = $request->input('number');
        if ($number <= 0) {
            Log::error('购买商品数量不能为负数 控制器:OrderController@showByFastBuy');
            return $define_sku;
        }

        // 获取spu_id
        $spu_id = $request->input('spuId');
        if (empty($spu_id)) {
            return $define_sku;
        }

        // 处理得到spu_id和spec来确定立即购买选择的商品sku_id
        $spec = $request->input('guiges');
        if (empty($spec)) {
            $sku_id = GoodsSku::where('spu_id', $spu_id)->value('sku_id');
            if (empty($sku_id)) {
                return $define_sku;
            }
        } else {
            // 存在规格 则找出指定的sku商品
            // 处理前台数据 得到规格数组信息 $spec_arr
            $spec_arr = array();
            $arr = explode('SEPARATOR', $spec);
            /*foreach ($arr as $spec) {
                $temp_arr = explode('CONNECTOR', $spec);
                $spec_arr[$temp_arr[0]] = $temp_arr[1];
            }*/
            $skuids = array();
            foreach ($arr as $spec) {
                $temp_arr = explode('CONNECTOR', $spec);
                $spec_arr[$temp_arr[0]] = $temp_arr[1];
                $skuids[]=$temp_arr[0];
            }

            /*// 比对规格数组序列化值 找出sku商品
            $sku_spec = serialize($spec_arr);
            $sku_id = GoodsSku::where('spu_id', $spu_id)->where('sku_spec', $sku_spec)->value('sku_id');
            if (!$sku_id) {
                Log::error('参数数据有误,平台不存在该sku,请检查传入的数据 控制器:OrderController@showByFastBuy');
                return $define_sku;
            }*/
        }

        // 商品促销类型(promotions_type)【1:无促销(默认); 2:团购; 3:限时折扣; 4:组合套装; 5:赠品; 6:满折; 7:满减;99:礼品（礼券）;100:奖品;】
        // 商品促销活动ID（promotions_id）【团购ID/限时折扣ID/优惠套装ID/瞒折/满减）与promotions_type搭配使用】
        // 请注意，这两项信息将来要从SKU关联的营销活动中实时获取或由用户选择、确认是否参与
        /*$define_sku = array(
            'sku_id' => $sku_id,
            'number' => $number,
            'price' => 0,
            'promotions_type' => 1,
            'promotions_id' => 0
        );*/
        $define_sku= array();
        foreach($skuids as $skuid){
            $define_sku[] = array(
                'sku_id' => $skuid,
                'number' => $number,
                'price' => 0,
                'promotions_type' => 1,
                'promotions_id' => 0,
                'spu_id'=>$spu_id,
            );
        }

        return $define_sku;
    }

    /** 如果SKU是礼品记录，要检查库存数量，即原有兑换情况
     * 传入参数：
     * @param  $skus               array   拟下单的SKU 数组，多个
     * 传出参数
     * @param  $gift_record_lst   array   兑换礼品信息
     */
    private function checkGiftRecord($skus)
    {
        /* $skus 结构如下
            $sku = [array(
                        'sku_id'            => 141,     // sku_id
                        'number'            => 2,       // 数量
                        'price'             => 98.03,   // 价格
                        'promotions_type'  => 99,       // 99:礼品（礼券）
                        'promotions_id'    => 632      //  我的礼品记录ID（member_giftcoupon_goods.id）
                ),...];

                礼品信息表（member_giftcoupon_goods）
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
                `sku_id` int(10) NOT NULL COMMENT 'sku_id',
                `total_num` int(11) NOT NULL COMMENT '商品总数量',
                `exchanged_num` int(11) NOT NULL COMMENT '已兑换商品数量',
        */
        $gift_record_lst = [];
        foreach ($skus as $sku) {
            $sku_id = (int)$sku['sku_id'];
            $sku_number = $sku['number'];
            $record_id = (int)$sku['promotions_id'];

            $obj_gift = MemberGiftCouponGoods::where('id', $record_id)->first();
            if ($obj_gift) {
                if (($obj_gift->sku_id) <> $sku_id) {
                    // 数据异常，礼品关联的SKU 与传入的参数不一致
                    return array('code' => 50002, 'message' => '礼品关联的SKU与传入的SKU参数不一致！');
                }

                // 礼品原总数、已经兑换数、剩余数
                $total_num = $obj_gift->total_num;
                $old_exchanged_num = $obj_gift->exchanged_num;
                $temp = ($total_num - $old_exchanged_num);
                if ($sku_number > $temp || $temp <= 0) {
                    return array('code' => 50002, 'message' => '礼品剩余数小于拟兑换数！');
                }

                array_push($gift_record_lst, (array)$obj_gift);
            } else {
                // 没有发现礼品记录，应该出意外了，传入的参数有问题
                return array('code' => 50000, 'message' => '礼品记录不存在！');
            }
        }

        return array('code' => 0, 'message' => '', 'data' => $gift_record_lst);
    }

    /** 如果SKU是抽奖记录，要检查奖品领用情况
     * 传入参数：
     * @param  $skus               array   拟下单的SKU 数组，多个
     * 传出参数
     * @param  $awards_record_lst   array   获奖信息
     */
    private function checkAwardsRecord($skus)
    {
        /* $skus 结构如下
            $sku = [array(
                        'sku_id'            => 141,     // sku_id
                        'number'            => 1,       // 数量
                        'price'             => 0,       // 价格
                        'promotions_type'  => 100,      // 营销类型【100 奖品】
                        'promotions_id'    => 2861      // 营销记录ID【member_awardsrecord.awardsrecord_id】
                ),...];
        */
        $awards_record_lst = [];
        $db_prefix = $this->db_prefix;
        foreach ($skus as $sku) {
            $sku_id = (int)$sku['sku_id'];
            $record_id = (int)$sku['promotions_id'];

            /* 获奖纪录（member_awardsrecord）
                awardsrecord_id		int(10) 	记录ID
                member_id		int(10)		会员ID
                order_id 		int(11) 	订单ID
                supplier_id		int(10) 	所属供应商ID(“0”为平台)

                prize			bigint(20) 	sku_id、虚拟币数、红包人民币额
                exchange_type		tinyint(4) 	奖品来源【0:没中奖; 1:礼品; 2:虚拟币;】
                prize_type		tinyint(1) 	奖品类型【0:实物; 1:微信红包;】
                exchange_state		tinyint(4) 	领取状态【0:未领取; 1:已领取】
                下订单的条件【exchange_type = 1 and prize_type = 0 and exchange_state = 0】
            */
            $sql = 'select awardsrecord_id as id,prize as sku_id,exchange_state
                    from ' . $db_prefix . 'member_awardsrecord
                    where exchange_type = 1
                        and prize_type = 0
                        and awardsrecord_id = ' . $record_id . '
                    limit 0,1 ';
            $obj_awards_record = DB::select($sql, []);
            if ($obj_awards_record) {
                // 查看是否已经领用
                // exchange_state   tinyint(4) 	领取状态【0:未领取; 1:已领取】
                $row = (array)$obj_awards_record[0];
                if ($row['exchange_state'] == 1) {
                    return array('code' => 50002, 'message' => '该奖品已领取！');
                } else {
                    if ($row['sku_id'] <> $sku_id) {
                        // 数据异常，奖品关联的SKU 与传入的参数不一致
                        return array('code' => 50002, 'message' => '奖品关联的SKU与传入的SKU参数不一致！');
                    }

                    array_push($awards_record_lst, $row);
                }
            } else {
                // 没有发现获奖记录，应该出意外了，传入的参数有问题
                return array('code' => 50000, 'message' => '获奖记录不存在或不是实物奖品！');
            }
        }

        return array('code' => 0, 'message' => '', 'data' => $awards_record_lst);
    }

    /**
     * 统一定义处理订单和订单商品数据表中的信息
     * @param $define_skus      array   拟购买的商品SKU列
     * @param $sendee_city_id   int     收货城市ID，此地址计算运费用，默认0（不限制）)
     * @param $is_virtual  tinyint(1)   是否虚拟商品【电子充值卡】，虚拟商品不配送不拆单
     * @return $order_info array        下单信息
     */
    private function fillOrderGoodsInfo(& $define_skus, $sendee_city_id = 0, $is_virtual = 0)
    {
        $spu_id = 0;
        $sku_id = 0;
        $grade = 10;
        $member = Auth::user();
        if ($member) {
            $grade = $member->grade;
        }

        // 补充每个商品的具体信息(会用于订单商品详细表中)
        $goods_amount_totals = 0.00;                    // 商品结算金额
        $transport_cost_totals = 0.00;                  // 运费结算金额
        $goods_preferential_totals = 0.00;              // 商品优惠结算金额
        $transport_preferential_totals = 0.00;          // 运费优惠结算金额
        $goods_points_totals = 0;                       // 虚拟币限额合计

        // 传入的区县，不存在，默认未知
        if (!DctArea::find($sendee_city_id)) {
            $sendee_city_id = 0;
        }

        // 获取虚拟币与人民币间的汇率【1元（人民币）等于多少依你币】，计算商品价格兑换成虚拟币数值用
        $plat_vrb_rate = $this->getPlatVrbRate();
        $obj_templateController = new TransportTemplateController();
        $arry_transCost=[];
        $arry_transPrice=[];
        foreach ($define_skus as $key => $define_sku) {
            // 单个商品sku和spu
            $sku_id = $define_sku['sku_id'];
//            $obj_sku = GoodsSku::where('sku_id', $sku_id)->first();

            $obj_sku = GoodsSku::select('goods_sku.sku_id', 'goods_sku.spu_id',
                'goods_sku.sku_name', 'goods_sku.sku_title',
                'goods_sku.market_price',
                'goods_sku.price', 'goods_sku.groupbuy_price',
                'goods_sku.trade_price', 'goods_sku.partner_price',
                'goods_sku.points_limit', 'goods_sku.sku_spec',
                'member_goods_sku.minimum_limit', 'member_goods_sku.storage_num', 'member_goods_sku.base_discount_rate', 'member_goods_sku.use_state')
                ->leftJoin('member_goods_sku', 'member_goods_sku.sku_id', '=', 'goods_sku.sku_id')//左连接时没有判断member_goods_sku的use_state，若加这个判断会把左边的表记录失去，所以后面判断sku价格时候要加上use_state是否为有效1
                ->where('goods_sku.sku_id', $sku_id)
                ->first();


            $spu_id = $obj_sku->spu_id;
            $obj_spu = GoodsSpu::where('spu_id', $spu_id)->first();
            $sku_main_img = GoodsSkuImages::mainImg($sku_id);
            if (is_object($sku_main_img)) {
                $sku_main_img = '';
            }

            // 图片格式化为带域名的字符串
            $sku_main_img = $this->getFullPictureUrl($sku_main_img);

            // ----------------------------------------- 1、商品数量及价格【定价、结算价】 -----
            $goods_number = $define_sku['number'];



            // ---------------------------------------- 3、商品价格【定价、结算价】 -----------------------------
            $goods_price = $define_sku['price'];    // 外部传入的价格【只有礼品传入非零】
            $goods_points = 0;                      // 该商品的虚拟币支付限额
            if ($define_sku['promotions_type'] == 99) {
                // 如果是礼品（礼券）(promotions_type=99)信息，直接使用传入的SKU价格，免运费、免支付
                // array_key_exists('price', $define_sku)
            } else {
                // 根据当前登录用户级别，获取其应享受的价格，非登录情况下，显示平台价【官网零售价】
                $goods_price = $this->getSkuPrice($grade, $obj_sku);
                $goods_points = $this->getPointsLimit($obj_sku->points_limit, $goods_price, $plat_vrb_rate);
            }

            $goods_amount = 0.00;                    // 商品结算金额
            $goods_preferential = 0.00;              // 商品优惠结算金额
            $transport_preferential = 0.00;          // 运费优惠结算金额

            // promotions_type【1:无促销(默认); 2:团购; 3:限时折扣; 4:组合套装; 5:赠品; 6:满折; 7:满减;99:礼品（礼券）;100:奖品;】
            // promotions_id【团购ID/限时折扣ID/优惠套装ID/瞒折ID/满减ID】
            $settlement_price = $this->getSettlementPrice(
                $define_sku['promotions_type'], $define_sku['promotions_id'],
                $spu_id, $goods_price, $goods_number,
                $goods_amount, $goods_preferential, $transport_preferential);

            // 合计商品结算金额、商品优惠金额、运费优惠金额
            $goods_amount_totals = bcadd($goods_amount_totals, $goods_amount, 2);
            $goods_preferential_totals = bcadd($goods_preferential_totals, $goods_preferential, 2);
            $transport_preferential_totals = bcadd($transport_preferential_totals, $transport_preferential, 2);

            // 合计虚拟币限额【整数】
            if($goods_points>=0){//增加值为-1时不计算，-1为不支持虚拟币支付。——20170816
                $goods_points_totals += ($goods_points * $goods_number);
            }

            // ------------------------- 2、运费结算和优惠(可能和商品结算金额有关，例如：满X元免运费) --
            $transport_cost = 0.00;

            if ($define_sku['promotions_type'] == 99 ||
                $define_sku['promotions_type'] == 100 || $is_virtual
            ) {
                // 如果是礼品（礼券）(promotions_type=99)信息，免运费、免支付
                // 如果是奖品(promotions_type=100)信息，免运费、免支付
                // 如果是虚拟商品【电子充值卡】，不配送，无运费
            } else {
                // 固定运费优先，模板运费次之
                $transport_cost = $obj_spu->freight;
                if ($transport_cost == 0) {
                    $tpl_transport_id = $obj_spu->tpl_transport_id;
                    if ($tpl_transport_id) {
                        /*
                        // 根据商品SpuID、数量(number)、到达区县ID(sendee_city_id)计算运费
                        $transport_cost = $obj_templateController->getTransportCost($tpl_transport_id, $goods_number, $sendee_city_id);
                        */

                        if(array_key_exists($tpl_transport_id,$arry_transCost)){
                            $arry_transCost[$tpl_transport_id]+=$goods_number;
                        }else{
                            $arry_transCost[$tpl_transport_id]=$goods_number;
                        }

                        if(array_key_exists($tpl_transport_id,$arry_transPrice)){
                            $arry_transPrice[$tpl_transport_id]+=$goods_amount;
                        }else{
                            $arry_transPrice[$tpl_transport_id]=$goods_amount;
                        }


                    }
                }
            }

            /*// 运费合计
            $transport_cost_totals = bcadd($transport_cost_totals, $transport_cost, 2);*/


            // ------------------------------------ 4、组装商品详情(订单商品详情表中的数据) ----------
            // sku_name、sku_title、sku_image、sku_spec(从sku表中获取的信息)
            $define_skus[$key]['sku_name'] = $obj_sku->sku_name;

            // SKU名称显示标题
            $sku_title = trim($obj_sku->sku_title);
            if (!$sku_title) {
                $sku_title = $obj_sku->sku_name;
            }
            $define_skus[$key]['sku_title'] = $sku_title;

            $define_skus[$key]['sku_image'] = $sku_main_img;
            $define_skus[$key]['sku_spec'] = $obj_sku->sku_spec;

            // 商品spu(spu_id、gc_id、gc_name)(从spu表中获取的信息)
            $define_skus[$key]['spu_id'] = $obj_spu->spu_id;
            $define_skus[$key]['gc_id'] = $obj_spu->gc_id;
            $define_skus[$key]['gc_name'] = $obj_spu->gc_name;

            // 商品佣金比例(供应商支付给平台的服务费率)，将来通过函数获取其值，用于平台与供应商的结算
            $define_skus[$key]['commis_rate'] = 0.00;

            // 运费(商品运费金额)
            $define_skus[$key]['transport_cost'] = $transport_cost;

            // 商品定价和结算价格
            $define_skus[$key]['goods_price'] = $goods_price;
            $define_skus[$key]['settlement_price'] = $settlement_price;

            // 虚拟币限额
            $define_skus[$key]['goods_points'] = $goods_points;
        }

        //20170810-新增根据运费模板同一模板下商品统一计算运费--start
        // 根据商品SpuID、数量(number)、到达区县ID(sendee_city_id)计算运费
        if(!empty($arry_transCost)){
            foreach($arry_transCost as $transKey=>$transGnum){
                $transport_cost = $obj_templateController->getTransportCost($transKey, $transGnum, $sendee_city_id,$arry_transPrice[$transKey]);
                // 运费合计
                $transport_cost_totals = bcadd($transport_cost_totals, $transport_cost, 2);
            }
        }
        //20170810-新增根据运费模板同一模板下商品统一计算运费--end




        //  -------------------------------------- 5、订单总体优惠【商品优惠、运费优惠】 -------
        // $transport_preferential = 0.00;                                                 // 运费优惠
        // $transport_preferential_totals += $transport_preferential;

        // -------------------------------------- 6、订单结算金额和应付金额 ----------------
        // 订单结算金额 = 商品结算金额 + 运费结算金额
        $order_amount_totals = bcadd($goods_amount_totals, $transport_cost_totals, 2);

        // 订单应付金额 = 订单结算金额 - 商品优惠金额 - 运费优惠金额
        // $payable_amount_totals = $order_amount_totals - $goods_preferential_totals - $transport_preferential_totals;
        $payable_amount_totals = bcsub($order_amount_totals, $goods_preferential_totals, 2);
        $payable_amount_totals = bcsub($payable_amount_totals, $transport_preferential_totals, 2);


        //购买商品所需的虚拟币总和+运费结算金额 = 虚拟币一共可以支付的总额
        // (把这两行去掉，就是虚拟币只计算商品所需要的虚拟币，不包括运费所需的)
        // 3/7添加  jiang
        //20170816注释，去掉商品运费可以用虚拟币支付--start
        /*$transport_to_v = floor($transport_cost_totals) * $plat_vrb_rate;
        $goods_points_totals = $goods_points_totals + $transport_to_v;*/
        //20170816注释，去掉商品运费可以用虚拟币支付--end


        // 商品总价格信息数组(决定平台订单价格字段信息)
        $order_info = array(
            'goods_amount_totals' => $goods_amount_totals,                  // 商品结算金额
            'transport_cost_totals' => $transport_cost_totals,              // 运费结算金额
            'order_amount_totals' => $order_amount_totals,                  // 订单结算金额合计(商品金额+运费)
            'goods_preferential' => $goods_preferential_totals,             // 商品优惠金额
            'transport_preferential' => $transport_preferential_totals,     // 运费优惠金额
            'payable_amount' => $payable_amount_totals,                     // 订单应付金额
            'goods_points_totals' => $goods_points_totals                     // 虚拟币限额合计
        );

        return $order_info;
    }

    /**
     * 根据营销类型、营销活动ID，获得某SKU的结算价格
     * @param $promotions_type (营销类型：1:无促销(默认); 2:团购; 3:限时折扣; 4:组合套装; 5:赠品; 6:满折; 7:满减;99:礼品（礼券）;100:奖品;)
     * @param $promotions_id (营销活动ID：团购ID/限时折扣ID/优惠套装ID/瞒折ID/满减ID)
     * @param $spu_id (商品SPU)
     * @param $goods_price （商品定价）
     * @param $goods_number （商品数量）
     * @param $goods_amount （商品结算金额）
     * @param $goods_preferential （商品优惠金额）
     * @param $transport_preferential （运费优惠金额）
     * @return $settlement_price(结算价格)
     */
    public function getSettlementPrice($promotions_type = 1, $promotions_id = 0,
                                       $spu_id, $goods_price, $goods_number = 1,
                                       & $goods_amount = 0.00, & $goods_preferential = 0.00,
                                       & $transport_preferential = 0.00)
    {
        $settlement_price = $goods_price;
        $goods_preferential = 0.00;
        $transport_preferential = 0.00;

        // (营销类型：1:无促销(默认); 2:团购; 3:限时折扣; 4:组合套装; 5:赠品; 6:满折; 7:满减;99:礼品（礼券）;100:奖品;
        // 99:礼品（礼券）;100:奖品)
        $promotions_type = (int)$promotions_type;
        $promotions_id = (int)$promotions_id;
        switch ($promotions_type) {
            case 99:
                // 礼品（礼券），免运费、免支付
                $goods_amount = bcmul($goods_price, $goods_number, 2);
                $goods_preferential = $goods_amount;
                break;
            case 100:
                // 奖品，免运费、免支付
                $goods_amount = bcmul($goods_price, $goods_number, 2);
                $goods_preferential = $goods_amount;
                break;
            default:
                // 默认无促销，单价和数量相乘，保留 2 位小数
                $goods_amount = bcmul($goods_price, $goods_number, 2);
                break;
        }

        // 根据不同的营销类型和活动，得到商品结算价格
        return $settlement_price;
    }

    /**
     * 组装订单确认页需要展示的商品信息
     * @auth yang
     * @param $define_skus
     * @return array
     */
    private function getSkuInfo($define_skus)
    {
        $skus_info = array();
        foreach ($define_skus as $define_sku) {
            // 订单明细SKU的名称，显示SKU标题信息
            // $define_sku['sku_image'] 已经为带域名的绝对地址
            $arr = [
                'sku_id' => $define_sku['sku_id'],
                'sku_name' => $define_sku['sku_title'],
                'main_img' => $define_sku['sku_image'],
                'sku_spec' => empty($define_sku['sku_spec']) ? array() : unserialize($define_sku['sku_spec']),

                // 商品价【平台定价、零售价】、结算价【优惠（后）价】
                'goods_price' => $define_sku['goods_price'],
                'price' => $define_sku['settlement_price'],
                'number' => $define_sku['number'],

                // 营销类型、营销活动ID
                'promotions_type' => $define_sku['promotions_type'],
                'promotions_id' => $define_sku['promotions_id']
            ];

            array_push($skus_info, $arr);
        }

        return $skus_info;
    }

    /** 初始化当前用户(买家)可支付的非人民币财富列表，以数组形式返回
     * 传入参数：
     * @param $payable_amount decimal(10,2)    订单应付金额
     * @param $goods_points_totals int         订单虚拟币限制支付额
     * @param $goods_sku_lst array             订单商品SKU列表【验证是否为虚拟商品用】
     * 传出参数：
     * @param $arr_money_info array     我的非人民币财富列表【用于支付】
     */
    public function iniMyMoneyInfo($payable_amount = 0.00, $goods_points_totals = 0, $goods_sku_lst = [])
    {
        // 买家信息、买家财富数组
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
            $grade = $member->grade;
        } else {
            // 当前用户没有登录，跳到登录页面进行登陆
            return redirect('/oauth');
        }

        $arr_money_info = array();

        if ($payable_amount <= 0) {
            // 如果应付金额为零，直接退出
            return $arr_money_info;
        }

        /* 系统支持4种非人民币财富（虚拟币、零钱、卡余额、优惠劵）支付，格式如下：
            [
                'money_vrb'=>['pay_type'=>'vrb','available'=>200,
                                'pay_max_amount'=>105,'pay_max_amount_to_rmb' => 10.5,
                                'plat_vrb_caption'=>'酒币','plat_vrb_rate'=>10],
                'money_wallet'=>['pay_type'=>'wallet','available'=>15.09],
                'money_card_balance'=>['pay_type'=>'card_balance','available'=>456.01,'count'=>3,'card_lst'=>[] ],
                'money_voucher'=>['pay_type'=>'voucher','available'=>300,'count'=>2,'voucher_lst'=>[] ]
            ]
            注释：vrb——虚拟币；wallet——零钱【钱包】；
                  card_balance——卡余额；voucher——代金劵

            1、卡余额列表（card_lst），数组，格式如下：
            [
                ['rechargecard_id'=>321,'card_id'=>123452,
                    'balance_amount'=>300,'balance_available'=>250,'balance_pay_amount'=>0],
                ......
            ]

            卡余额信息项说明：
                rechargecard_id——记录ID
                card_id——卡ID
                balance_amount——总金额
                balance_available——可用金额
                balance_pay_amount——拟支付金额，默认零

            2、优惠劵列表（voucher_lst），数组，格式如下：

        */

        // 1、虚拟币资产
        $my_vrb_available = $member->yesb_available;
        if ($goods_points_totals > 0 && $my_vrb_available > 0) {
            // 请注意，这里有个逻辑判定，由调用者验证，即：订单虚拟币限额，换算成人民币额不能大于应付金额
            // 如果该订单允许支付虚拟币并且买家有可用的虚拟币，增加此财富
            // 获取虚拟币名称、汇率【1元（人民币）等于多少虚拟币】
            $plat_vrb_caption = $this->getPlatVrbCaption();
            $plat_vrb_rate = $this->getPlatVrbRate();

            // 本订单最多可支付虚拟币额【整数】
            $pay_vrb_max_amount = floor((bccomp($my_vrb_available, $goods_points_totals, 2) > 0 ?
                $goods_points_totals : $my_vrb_available));

            // 本订单最多可支付虚拟币额【整数】相当于多少人民币，保留2位小数
            // 并且该值不能大于订单应付金额【最多等于应付金额】
            $pay_vrb_max_amount_to_rmb = bcdiv($pay_vrb_max_amount, $plat_vrb_rate, 2);
            if ($pay_vrb_max_amount_to_rmb > $payable_amount) {
                $pay_vrb_max_amount_to_rmb = $payable_amount;
                $pay_vrb_max_amount = ceil($payable_amount * $plat_vrb_rate);

                // 再次修正虚拟币最大支付额
                if ($pay_vrb_max_amount > $my_vrb_available) {
                    $pay_vrb_max_amount = $my_vrb_available;
                }
            }


            $arr_money_info['money_vrb'] = array(
                'pay_type' => 'vrb',
                'available' => (int)$my_vrb_available,
                'pay_max_amount' => $pay_vrb_max_amount,
                'pay_max_amount_to_rmb' => $pay_vrb_max_amount_to_rmb,
                'plat_vrb_caption' => $plat_vrb_caption,
                'plat_vrb_rate' => $plat_vrb_rate
            );
        }

        // 2、零钱【钱包】
        $my_wallet_available = $member->wallet_available;
        if ($my_wallet_available > 0) {
            $arr_money_info['money_wallet'] = array(
                'pay_type' => 'wallet',
                'available' => $my_wallet_available
            );
        }


        // 3、卡余额，如果订单（$goods_sku）存在有虚拟商品，不支持卡余额支付
        // 4/13 修改为：对于充值卡之外的所有虚拟商品均可使用卡余额,（虚拟商品只能立即购买）
        $exist_virtual_goods = $this->isExistVirtualGoods($goods_sku_lst);

        $is_card = 0;
        if ($exist_virtual_goods) { //若是虚拟商品，判断其是不是充值卡
            $sku_id = $goods_sku_lst[0]['sku_id']; //虚拟商品只能立即购买，所以若是虚拟商品，只有一个sku_id,直接取出

            $db_prefix = $this->db_prefix;
            //若sku_id在表recharge_activity中存在记录，则表示为充值卡
            $sql = 'select activity_id, sku_id
                from ' . $db_prefix . 'recharge_activity
                where sku_id = ' . $sku_id;
            $card_lst = DB::select($sql, []);
            if (count($card_lst) > 0) {
                $is_card = 1;
            }
        }


        //（不是虚拟商品）或者（是虚拟商品且不是充值卡）则可使用卡余额
        if (!$exist_virtual_goods || ($exist_virtual_goods && !$is_card)) {
            $db_prefix = $this->db_prefix;
            $member_id = $member->member_id;
            $sql = 'select rechargecard_id,card_id,balance_amount,balance_available
                from ' . $db_prefix . 'member_rechargecard
                where use_state = 0
                and balance_available > 0
                and member_id = ' . $member_id . '
                order by balance_available ';
            $card_balance_lst = DB::select($sql, []);
            if (count($card_balance_lst) > 0) {
                $available = 0.00;
                $count = count($card_balance_lst);
                $card_lst = array();

                foreach ($card_balance_lst as $row) {
                    $available += $row->balance_available;
                    $card_lst[] = (array)$row;
                }

                $arr_money_info['money_card_balance'] = array(
                    'pay_type' => 'card_balance',
                    'available' => $available,
                    'count' => $count,
                    'card_lst' => $card_lst
                );
            }
        }


        // 4、代金劵，代金劵支付和商品相关

        return $arr_money_info;
    }

    private function isExistVirtualGoods($goods_sku_lst = [])
    {
        $sku_ids = '';
        $exist_virtual_goods = 0;
        if (count($goods_sku_lst) > 0) {
            // 获取商品SKU ID
            // $goods_sku_lst 数据格式为：[['sku_id'=>121,'number'=>2],[], ...]
            foreach ($goods_sku_lst as $sku) {
                if ($sku_ids == '') {
                    $sku_ids = $sku['sku_id'];
                } else {
                    $sku_ids .= "," . $sku['sku_id'];
                }
            }
        }

        if ($sku_ids != '') {
            $db_prefix = $this->db_prefix;
            $sql = 'select a.spu_id
                from ' . $db_prefix . 'goods_spu as a
                inner join ' . $db_prefix . 'goods_sku as b on a.spu_id = b.spu_id
                where a.is_virtual = 1
                and b.sku_id in (' . $sku_ids . ') ';
            $virtual_goods = DB::select($sql, []);
            if ($virtual_goods) {
                // 存在有虚拟商品
                $exist_virtual_goods = 1;
            }
        }

        return $exist_virtual_goods;
    }

    /** 订单保存，预结算页面，点击“去支付”时，调用该接口。
     *  前言：
     *  1、虚拟商品（例如电子充值卡），不能加入购物车；
     *  2、虚拟商品，可在商品列表中立即购买电子充值卡，也可在个人中心中直接充值；
     *  3、电子充值卡商品，和当前充值卡活动唯一关联，也就是说：一个电子充值卡，不可能关联两个充值卡活动【仅且仅关联一个充值卡活动】；
     *  4、电子充值卡虚拟商品SKU的价格，就是电子充值卡的价格【recharge_activity.price】；
     *  5、直接充值，是在个人中心发起的充值业务，支付成功后直接充值到个人账户，不限量、无物流、不配送；
     *  6、电子充值卡，无物流、不配送；
     *  接口主要任务：
     *  1、保存订单及明细；
     *  2、保存非人民币支付信息；
     *  3、删除购物车内已下单SKU信息；
     *  4、同步SKU信息【库存、销售量、销售状态等】
     * 传入参数：
     * @param $sku_source_type  int     SKU来源类型 0:立即购买; 1:购物车; 7:电子充值卡; 8:直接充值; 9:礼品(礼券); 10:（实物）奖品)
     * @param $skus             string  SKU信息格式为：13-1-19.89-1-0,891-2-300.99-7-1234,......
     *  SKU信息英文逗号分隔，每个SKU信息项为：
     *      商品SkuID、下单数量、下单价格【仅礼品非零】、营销活动类型、营销活动ID，
     *  其中营销活动类型及活动ID，说明如下：
     *      商品促销类型【1:无促销(默认); 2:团购; 3:限时折扣; 4:组合套装; 5:赠品;6:满折;7:满减;99:礼品（礼券）;100:奖品;】
     *      商品促销活动ID【团购ID/限时折扣ID/优惠套装ID/瞒折/满减/我的礼品）与商品促销类型搭配使用】
     * @param $address_id      int     配送地址ID【8:立即充值，无需配送】;
     * 传出参数：
     * @param   $code   int     执行结果代码【0保存成功；1保存出错；2传入参数格式有误】
     * @param   $data   array() 订单ID，形如：array('plat_order_id' => $plat_order_id)
     */
    public function add(Request $request)
    {
        // 买家信息
        $member = Auth::user();
        $member_id=$member->member_id;

//        $member_ysxx= array();//月嫂信息
//        $member_ysxx['member_id']=$member->member_id;

        $month = $request->input('months');
        $month=str_replace('-','',$month);
        $spuId = (int)$request->input('spuId');
       /* $dates=array();
        $dates[]=$enterdate;
        for($i=1;$i<=$nights-1;$i++){
            $tdate=date('Ymd',strtotime($enterdate." +".$i." day"));
            $dates[]=$tdate;
        }*/


        $sku_source_type = 0;
        $sku_source_info = '';
        if ($request->input('sku_source_type')) {
            $sku_source_type = (int)($request->input('sku_source_type'));
        }

        // ---------------------------------- 1 验证传入信息的合法性 ------------------
        $skus = array();
        if ($request->input('skus')) {
            $skus = explode(',', $request->input('skus'));
        } else {
            Log::error('传入的 skus 为空!  控制器:OrderController@add');
            return Api::responseMessage(2, null, '没有传入下单商品信息！');
        }
        $type=0;

        $sold_rcds=array();
        foreach ($skus as $sku) {
            // 验证平台是否存在该商品 SKU
            $array_sku = explode('-', $sku);
            if(strpos($sku, '预付')){
                $type=1;
            }else if(strpos($sku, '尾款')){
                $type=2;
            }
            $sold_rcds[]=array(
                'sku_id'=>$array_sku[0],
                'spu_id'=>$spuId,
                'sold_date'=>$month,
                'member_id'=>$member_id,
                'type'=>$type,
            );
            $num=DB::table("ys_sku_soldrecord")->insert($sold_rcds);

        }

        // 结算的商品信息
        $define_skus = array();
        foreach ($skus as $sku) {
            // 验证平台是否存在该商品 SKU
            // $sku 格式为：13-1-9.05-1-0，每个数据为：商品SkuID、数量、价格、营销活动类型、营销活动ID
            $array_sku = explode('-', $sku);
            $sku_id = GoodsSku::where('sku_id', $array_sku[0])->value('sku_id');

            // 如果为空跳过本次循环
            if (empty($sku_id)) {
                continue;
            }

            $define_sku = array();
            switch ($sku_source_type) {
                case 9;
                    if (count($array_sku) <> 5) {
                        // 礼品订单，传入的参数不够
                        Log::error('礼品兑换，传入的参数不够! 控制器:OrderController@add');
                        return Api::responseMessage(2, null, '礼品兑换，传入的参数不够! ');
                    }

                    // $sku_source_type = 9   我的礼品（礼券）
                    $define_sku = array(
                        'sku_id' => $sku_id,         // sku_id
                        'number' => $array_sku[1],   // 数量
                        'price' => $array_sku[2],   // 价格
                        'promotions_type' => 99,              // $array_sku[3] 营销类型【99:礼品（礼券）;100:奖品】
                        'promotions_id' => $array_sku[4]    // 营销ID【member_giftcoupon_goods.id】
                    );
                    break;

                case 10;
                    if (count($array_sku) <> 5) {
                        // 奖品订单，$array_sku 数据格式为：
                        // ['sku_id'=>321,'number'=>1,'price'=>0,'promotions_type'=>100,'promotions_id'=>8951]
                        Log::error('奖品领取，传入的参数不够! 控制器:OrderController@add');
                        return Api::responseMessage(2, null, '奖品领取，传入的参数不够! ');
                    }

                    // $sku_source_type = 10   我的奖品
                    $define_sku = array(
                        'sku_id' => $sku_id,         // 关联 member_awardsrecord.prize【exchange_type = 1 and prize_type = 0】
                        'number' => $array_sku[1],   // 数量，默认 1
                        'price' => 0,               // 价格,我的奖品记录里面不存在，默认零；
                        'promotions_type' => 100,             // 营销类型【100:奖品】
                        'promotions_id' => $array_sku[4]    // 营销ID【member_awardsrecord.awardsrecord_id】
                    );
                    break;

                default:
                    $define_sku = array(
                        'sku_id' => $sku_id,
                        'number' => $array_sku[1],
                        'price' => 0,
                        'promotions_type' => 1,
                        'promotions_id' => 0
                    );
                    break;
            }

            // 一个或者多个商品(商品的二维数组信息)
            array_push($define_skus, $define_sku);
        }

        if (empty($define_skus)) {
            // 如果数组为空 说明传入的数组sku_id中,平台没有或者未上架
            Log::error('传入的商品信息无效! 控制器:OrderController@add');
            return Api::responseMessage(2, null, '传入的商品信息无效! ');
        } else {
            // 将SKU 信息序列化为字符，保存到 $sku_source_info 中
            $sku_source_info = serialize($define_skus);
        }

        // $sku_source_type【0:立即购买;1:购物车;7:电子充值卡;8:直接充值;9:礼品（礼券）;10:（实物）奖品);】
        // 如果SKU是虚拟充值卡，要检查充值卡合法性及库存情况
        $recharge_card_lst = array();
        if ($sku_source_type == 0 || $sku_source_type == 7) {
            // $checkData 格式为：array('code' => 0, 'message' => '', 'data' => $recharge_card_lst);
            $checkData = $this->checkRechargeCard($define_skus);
            if ($checkData['code'] > 0) {
                return Api::responseMessage(2, null, $checkData['message']);
            }

            if (array_key_exists('data', $checkData)) {
                $recharge_card_lst = (array)($checkData['data']);
            }

            // 如果有充值卡信息，保存到 $sku_source_info 中
            if (count($recharge_card_lst) > 0) {
                $sku_source_info = serialize($recharge_card_lst);

                // 7:购买充值卡;
                if ($sku_source_type == 0) {
                    $sku_source_type = 7;
                }
            }
        }


        // 如果是礼品（礼券），验证我的礼品库存量是否能满足此次兑换
        if ($sku_source_type == 9) {
            // $checkData 格式为：array('code' => 0, 'message' => '', 'data' => $gift_record_lst);
            $checkData = $this->checkGiftRecord($define_skus);
            if ($checkData['code'] > 0) {
                return Api::responseMessage(2, null, $checkData['message']);
            }
        }

        // 如果是奖品，验证奖品是否已领用
        if ($sku_source_type == 10) {
            // $checkData 格式为：array('code' => 0, 'message' => '', 'data' => $recharge_card_lst);
            $checkData = $this->checkAwardsRecord($define_skus);
            if ($checkData['code'] > 0) {
                return Api::responseMessage(2, null, $checkData['message']);
            }
        }


        $sendee_city_id = 0;
        $sendee_address = null; // new MemberAddress();
        if ($sku_source_type == 7 ||
            $sku_source_type == 8
        ) {
            // 电子充值卡及直接充值业务，不配送(不需要配送地址)、不拆单
        } else {
            // 当前登录用户的默认地址(用户新增或者修改后,前台展示的都是默认地址,用于订单主表的配送信息字段)
            $address_id = (int)$request->input('address_id');

            if ($address_id > 0) {
                $sendee_address = MemberAddress::where('address_id', $address_id)->first();
            }

            // 没有指定收货地址，启用买家的默认地址
            if (empty($sendee_address)) {
                $sendee_address = MemberAddress::where('member_id', $member->member_id)
                    ->where('is_default', 1)->first();
            }

            if (empty($sendee_address)) {
                Log::error('没有指定收货人地址  控制器:OrderController@add');
                return Api::responseMessage(2, null, '没有指定收货人地址! ');
            }

            // 收货人所在城市
            $sendee_city_id = $sendee_address->city_id;
        };

        // ------------------------------- 2.1 补充添加统一的商品数组信息 返回计算后的商品相关总价格 ----
        /*  $order_info 的元素有：
                goods_amount_totals         商品结算金额
                transport_cost_totals       运费结算金额
                order_amount_totals         订单结算金额合计(商品金额+运费)
                goods_preferential          商品优惠金额
                transport_preferential      运费优惠金额

                // 订单应付金额
                $payable_amount = (order_amount_totals - goods_preferential - transport_preferential)
        */
        // 如果是电子充值卡，不配送无物流
        $is_virtual = 0;
        if ($sku_source_type == 7 || $sku_source_type == 8) {
            $is_virtual = 1;
        }
        $order_info = $this->fillOrderGoodsInfo($define_skus, $sendee_city_id, $is_virtual);

        // 将客户端传入的拟支付信息形成数组
        $plat_order_id = 0;
        $arr_payment_info = $this->readPaymentInfo($request, $order_info['payable_amount']);


        $pay_mode_id = 1;
        $expire_time = 0;
//        if($member->grade == 20 ||  $member->grade == 30){
//
//           $pay_mode_id =  $request->input('pay_id');
//           //若支付方式为账期支付，则保存延迟支付时间
//           $expire_time =  $request->input('expire_time');
//
//        }

        // 保存订单主表、明细表、预支付信息等
        try {
            DB::beginTransaction();
            /* 目前的订单都是平台订单，暂时不考虑分销商因素，也就是暂时不考虑分销商和平台间结算问题
                如果是分销商订单（买家从分销商微商城下的单，销售方为分销商），要考虑下列信息项
                -- 订单主表（yyd_order）
                seller_id				销售方ID			int(10)
                seller_name				销售方名称			varchar(200)
                seller_goods_amount_totals		销售方结算金额	decimal(10,2)

                -- 订单SKU表（yyd_order_goods）
                seller_sku_id				销售方SKUid			bigint(20)
                seller_price		销售方价格【分销商与平台间结算价】		decimal(10,2)
            */
            // -------------------------------- 3.1 生成订单主表 ---------------------------------------------
            $order = $this->createOrderBySkus($member, $order_info,
                $sendee_address, $arr_payment_info, $sku_source_type, $pay_mode_id, $expire_time,1);
            if ($order) {
                $plat_order_id = $order->plat_order_id;
            } else {
                Log::error('用户平台订单生成失败  控制器:OrderController@add');
                DB::rollBack();
                return Api::responseMessage(1, null, '保存订单出错! ');
            }

            // ---------------------------------- 3.2 生成订单扩展表 ---------------------------
            // 初始化订单扩展表数据
            $extend_data = array(
                'plat_order_id' => $plat_order_id,
                'sku_source_type' => $sku_source_type,
                'sku_source_info' => $sku_source_info);

            // 买家留言(未作任何验证,可以为空)
            $message = '';
            if ($request->input('message')) {
                $message = trim($request->input('message') . '');
            }

            /*//去掉月嫂信息单独存表
            $hospital_info='';
            $member_name='';
            $mobile='';
            if ($request->input('hospital_info')) {
                $hospital_info = trim($request->input('hospital_info') . '');
            }
            if ($request->input('member_name')) {
                $member_name = trim($request->input('member_name') . '');
            }
            if ($request->input('mobile')) {
                $mobile = trim($request->input('mobile') . '');
            }
            $member_ysxx['hospital_info']=$hospital_info;
            $member_ysxx['member_name']=$member_name;
            $member_ysxx['mobile']=$mobile;
            $member_ysxx['plat_order_id']=$plat_order_id;*/

            $extend_data['order_message'] = $message;
            if($order->order_type==1){
                $extend_data['month'] = $month;
            }

            // is_fast_recharge  tinyint(4) （充值卡购买支付后）是否立即充值(0:否;1:是)；
            // $sku_source_type = 8   SKU来源于个人中心，立即购买充值卡，不配送、不拆单
            $is_fast_recharge = 0;
            if ($sku_source_type == 8) {
                // 直接充值
                $is_fast_recharge = 1;
            }
            $extend_data['is_fast_recharge'] = $is_fast_recharge;

            // 如果存在电子充值卡信息，要保存关联的二维码到扩展表中
            if ($sku_source_type == 7 && count($recharge_card_lst) > 0) {
                // $recharge_card 的信息项有：sku_id、number、activity_id、card_id、
                // two_dimension_id、two_dimension_code、two_dimension_number_code
                $recharge_card = $recharge_card_lst[0];
                if (array_key_exists('activity_id', $recharge_card) &&
                    array_key_exists('card_id', $recharge_card)
                ) {
                    $extend_data['code_id'] = $recharge_card['two_dimension_id'];
                    $extend_data['two_dimension_code'] = $recharge_card['two_dimension_code'];
                    $extend_data['two_dimension_number_code'] = $recharge_card['two_dimension_number_code'];
                }
            }
            OrderExtend::create($extend_data);
            //MemberYsxx::create($member_ysxx);


            // --------------------------------- 3.3 生成订单明细表(订单商品信息) -----------------------------------------
            foreach ($define_skus as $key => $define_sku) {
                $data = [
                    'order_detail_index' => $key + 1,
                    'plat_order_id' => $plat_order_id,

                    'sku_id' => $define_sku['sku_id'],
                    'sku_name' => $define_sku['sku_name'],
                    'sku_title' => $define_sku['sku_title'],

                    'sku_image' => $define_sku['sku_image'],
                    'sku_spec' => $define_sku['sku_spec'],
                    'spu_id' => $define_sku['spu_id'],
                    'gc_id' => $define_sku['gc_id'],
                    'gc_name' => $define_sku['gc_name'],

                    // 佣金比例，用于平台与供应商间的结算
                    'commis_rate' => $define_sku['commis_rate'],
                    'transport_cost' => $define_sku['transport_cost'],

                    // 商品定价、商品结算价【优惠后价】
                    'goods_price' => $define_sku['goods_price'],
                    'settlement_price' => $define_sku['settlement_price'],

                    'number' => $define_sku['number'],
                    'promotions_type' => $define_sku['promotions_type'],
                    'promotions_id' => $define_sku['promotions_id'],
                ];

                $order_goods = OrderGoods::create($data);
                if (!$order_goods->exists) {
                    DB::rollBack();
                    Log::error('用户平台订单明细表生成失败  控制器:OrderController@add');
                    return Api::responseMessage(1, null, '保存订单明细出错! ');
                }
            }

            // ---------------------------------- 3.4 保存虚拟币支付日志 -----------------
            // 订单保存成功后，如果订单支付使用了非人民币支付，
            // 诸如“虚拟币、零钱、卡余额、优惠劵”等，这些支付信息要保存到相关账户收支明细表中
            $this->saveNotRmbPayLog($plat_order_id);


            // ---------------------------------- 3.5 保存订单操作日志【下单待付款】 -----------------
            $content = '用户' . $member->member_name . ', 生成了一个订单！ 订单编号为:' . $order->plat_order_sn;
            LogInfoFacade::logOrderPlat($plat_order_id, $content, 1, $member->member_id, $member->nick_name);


            // ---------------------------------- 3.6 如果是购物车结算 需要删除已经结算的商品 --------
            // $sku_source_type = 1   SKU来源于购物车；
            if ($sku_source_type == 1) {
                foreach ($define_skus as $define_sku) {
                    MemberCart::where('member_id', $member->member_id)
                        ->where('sku_id', $define_sku['sku_id'])
                        ->delete();
                }
            }


            // ---------------------------------- 3.7 根据订单SKU 来源类型，更新关联的商品信息--------
            // $sku_source_type tinyint(4) SKU来源类型(0:立即购买;1:购物车;7:电子充值卡;8:直接充值;9:礼品（礼券）;10:（实物）奖品);)
            $sku_lst = unserialize($sku_source_info);
            switch ($sku_source_type) {
                case 7;
                    // 7:购买充值卡订单【支付成功后不兑换、不充值，发验证码充值，可能有配送】
                    // 充值卡订单，变更相关充值卡信息，操作类型【0:下订单增加销售量；1:撤单或退单减少销售量】
                    $this->updateRechargeCard($sku_lst, 0);
                    break;
                case 9;
                    // 9:礼品（礼券）订单，变更相关礼品信息，操作类型【0:下订单增加兑换量；1:撤单或退单减少兑换量】
                    $this->updateGiftSku($sku_lst, 0);

                    // 将礼品订单加入拆单工作队列
                    $this->addDismantleOrderJobToQueue($plat_order_id);
                    break;
                case 10;
                    // 10:奖品订单，变更相关奖品领取信息，操作类型【0:下订单领取；1:撤单或退单撤销领取】
                    $this->updateAwardsRecord($sku_lst, 0, $plat_order_id);

                    // 将奖品订单加入拆单工作队列
                    $this->addDismantleOrderJobToQueue($plat_order_id);
                    break;
                default:
                    break;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('订单提交失败  控制器:OrderController@add');
            return Api::responseMessage(1, null, '保存订单出错! ');
        }


        // ---------------------------------------- 4 现金支付(支付宝或微信支付) ---------------------------------------
        // 去支付 wxPayController
        // return redirect()->route('wxPay', array('plat_order_id' => $order->plat_order_id));
        $return_data = array('plat_order_id' => $plat_order_id,
            'sku_source_type' => $sku_source_type);
        return Api::responseMessage(0, $return_data, '订单保存成功！');
    }

    /** 将客户端传入的拟支付信息形成数组，并传出
     * 传入参数：
     * @param $request             array             传入的拟支付信息【虚拟币、零钱、卡余额、优惠劵等】
     * @param $payable_amount      decimal(10,2)     订单应付金额
     * @param $pay_wallet_amount   int              拟支付其它金额合计【卡余额、零钱、代金劵等】【传出参数】
     * @param $arr_payment_info    array           非人民币支付信息(数组)
     * @param $plat_vrb_rate       decimal(10,2)    虚拟币汇率【1元（人民币）等于多少虚拟币】
     * 传出参数：
     * @param $pay_rmb_amount      decimal(10,2)     拟支付人民币金额
     */
    private function readPaymentInfo(Request $request, $payable_amount = 0)
    {
        /* 系统支持5种支付币种，详情如下：
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
            balance_pay_amount——拟支付金额

            优惠劵支付详情（voucher_lst），数组，格式如下：
        */
        // 买家及支付非人民币信息
        $member = Auth::user();
        $arr_payment_info = array();

        // 订单应付金额为零，直接返回空数组
        if ($payable_amount <= 0) {
            return $arr_payment_info;
        }

        // 如果拟支付非人民币金额合计大于应付金额，并且差额大于0.1，系统认为客户端计算有误
        $sum_pay_amount = 0.00;

        // 1、虚拟币付款，保留整数
        if ($request->input('pay_vrb_amount')) {
            // 虚拟币名称
            $plat_vrb_caption = $this->getPlatVrbCaption();
            $pay_vrb_amount = (int)$request->input('pay_vrb_amount');
            if ($pay_vrb_amount > $member->yesb_available) {
                // 关于冻结额度的使用，以后再考虑
                Log::error('买家输入的' . $plat_vrb_caption . '数超出当前账户可用额度  控制器:OrderController@readPaymentInfo');
                return view('errors.error');                // 数据有误
            }

            // 重新计算的拟支付虚拟币数，即不能大于可用数
            // $pay_vrb = floor((bccomp($my_vrb_available, $pay_vrb, 2) > 0 ? $pay_vrb : $my_vrb_available));
            // 计算拟支付的虚拟币相当于多少人民币，保留2位小数
            $plat_vrb_rate = $this->getPlatVrbRate();
            $pay_vrb_to_rmb = bcdiv($pay_vrb_amount, $plat_vrb_rate, 2);
            $arr_payment_info['pay_vrb'] = array(
                'pay_type' => 'vrb',
                'pay_id' => 0,
                'pay_amount' => $pay_vrb_amount,
                'pay_amount_to_rmb' => $pay_vrb_to_rmb
            );

            $sum_pay_amount += $pay_vrb_to_rmb;

        }

        // 2、零钱【钱包】，保留两位小数
        if ($request->input('pay_wallet_amount')) {
            $pay_wallet_amount = bcadd($request->input('pay_wallet_amount'), 0.00, 2);
            if ($pay_wallet_amount > $member->wallet_available) {
                Log::error('买家输入的零钱数超出当前账户可用额度  控制器:OrderController@readPaymentInfo');
                return view('errors.error');                // 数据有误
            }

            $arr_payment_info['pay_wallet'] = array(
                'pay_type' => 'wallet',
                'pay_id' => 0,
                'pay_amount' => $pay_wallet_amount
            );

            $sum_pay_amount += $pay_wallet_amount;
        };

        // 3、卡余额，保留两位小数
        if ($request->input('pay_card_balance_amount')) {
            // 卡余额支付金额及详情
            $pay_card_balance_amount = bcadd($request->input('pay_card_balance_amount'), 0.00, 2);
            $pay_card_balance_lst = (array)$request->input('card_lst');
            if (count($pay_card_balance_lst) == 0 && $pay_card_balance_amount > 0) {
                Log::error('传入的卡余额列表为空数组 控制器:OrderController@readPaymentInfo');
                return view('errors.error');                // 数据有误
            }

            // 卡余额列每一个元素的格式为：
            //  ['rechargecard_id'=>321,'card_id'=>123452,
            //      'balance_amount'=>300,'balance_available'=>250,'balance_pay_amount'=>200]
            $sum_balance_pay_amount = 0;
            foreach ($pay_card_balance_lst as $item) {
                if ($item['balance_pay_amount'] > $item['balance_available']) {
                    Log::error('单张卡余额的支付额不能大于可用额  控制器:OrderController@readPaymentInfo');
                    return view('errors.error');
                }

                $sum_balance_pay_amount += $item['balance_pay_amount'];
            }

            // 以卡余额支付额合计为准确值
            $pay_card_balance_amount = $sum_balance_pay_amount;
            if ($pay_card_balance_amount > $member->card_balance_available) {
                Log::error('买家使用的卡余额超出当前账户可用额度  控制器:OrderController@readPaymentInfo');
                return view('errors.error');
            }

            $arr_payment_info['pay_card_balance'] = array(
                'pay_type' => 'card_balance',
                'pay_id' => 0,
                'pay_amount' => $pay_card_balance_amount,
                'card_lst' => $pay_card_balance_lst
            );

            $sum_pay_amount += $pay_card_balance_amount;
        };


        // 4、代金劵
        if ($request->input('pay_voucher_amount')) {
            // 暂时不考虑
        };

        $sub_amount = bcsub($sum_pay_amount, $payable_amount, 2);
        if ($sub_amount > 0.1) {
            Log::error('拟支付非人民币金额与应付金额相比，大于0.1,  控制器:OrderController@readPaymentInfo');
            return view('errors.error');
        }

        return $arr_payment_info;
    }

    /**
     * 根据订单信息生成订单数据表
     * 传入参数：
     * @param $member           int     买家信息
     * @param $order_info       array   订单结算信息
     * @param $sendee_address   object  收件人地址信息(订单的配送地址，买家收货地址)
     * @param $arr_payment_info array  非人民币支付信息
     * @param $sku_source_type  int     SKU来源类型【0:立即购买;1:购物车;7:电子充值卡;8:直接充值;9:礼品（礼券）;10:（实物）奖品);】
     * @param $pay_mode_id            用户试用的支付方式id(团采用户，代理用户会传送该值表达其使用的支付方式，普通用户不会使用该值，因为默认是1（微信支付）,)
     * 传出参数：
     * @return bool|Object(成功返回对象 失败返回false)
     */
    private function createOrderBySkus($member, $order_info, $sendee_address,
                                       $arr_payment_info = [], $sku_source_type = 0, $pay_mode_id = 1, $expire_time = 0,$order_type=0)
    {
        /* $arr_payment_info 支付明细（payment text）,数据格式为：
            [
                ['pay_type'=>'rmb','pay_id'=>326,'pay_amount'=>12.55],
                ['pay_type'=>'vrb','pay_id'=>14,'pay_amount'=>4],
                ['pay_type'=>'wallet','pay_id'=>26,'pay_amount'=>0.45],
                ['pay_type'=>'card_balance','pay_id'=>456,'pay_amount'=>10],
                ['pay_type'=>'voucher','pay_id'=>51,'pay_amount'=>50]
            ]
            注释：rmb——人民币；vrb——虚拟币；wallet——零钱【钱包】；
                  card_balance——卡余额；voucher——代金劵
        */
        // 平台订单编号(调用数据库存储过程获取)
        $plat_order_sn = $this->getPlatOrderSn();
        if (!$plat_order_sn) {
            Log::error('获取平台订单编号失败  控制器:OrderController@add');
            return view('errors.error');                // 数据有误
        }

        // 虚拟币支付额度、人民币支付额度、其它非人民币支付额度
        $pay_vrb_amount = 0;
        $pay_rmb_amount = 0.00;
        $pay_wallet_amount = 0.00;

        // 根据订单应付金额及非人民币支付额度，计算人民币支付额度
        $payable_amount = bcadd($order_info['payable_amount'], 0.00, 2);

        if ($payable_amount > 0) {
            $pay_rmb_amount = $this->getPayRmbAmount($payable_amount, $pay_vrb_amount,
                $pay_wallet_amount, $arr_payment_info);
        }

        // 支付信息数组序列化
        $payment = serialize($arr_payment_info);

        // 订单配送地址信息
        $sendee_province_id = 0;
        $sendee_area_id = 0;
        $sendee_city_id = 0;
        $sendee_address_id = 0;
        $sendee_address_info = '';

        if ($sendee_address) {
            $province_name = DctArea::find($sendee_address->province_id)->name;            // 省级名称
            $area_name = DctArea::find($sendee_address->area_id)->name;                    // 地区名称
            $city_name = DctArea::find($sendee_address->city_id)->name;                    // 城市名称

            $sendee_province_id = $sendee_address->province_id;                      // 收货人所在省份ID
            $sendee_area_id = $sendee_address->area_id;                              // 收货人所在地区ID
            $sendee_city_id = $sendee_address->city_id;                              // 收货人所在区县ID
            $sendee_address_id = $sendee_address->address_id;                        // 收货人地址ID

            // 收货人地址信息
            $sendee_address_info = '收货人姓名:' . $sendee_address->recipient_name .
                '  收货人手机号:' . $sendee_address->mobile .
                '  收货地址:' . $province_name . $area_name . $city_name .
                '  ' . $sendee_address->address;
        }


        // 组装生成订单数据
        $data = [
            'plat_order_sn' => $plat_order_sn,

            //  买家会员信息 即登录者信息
            'member_id' => $member->member_id,
            'member_name' => $member->nick_name,
            'member_email' => $member->email,
            'member_mobile' => $member->mobile,

            // 结算金额
            'goods_amount_totals' => $order_info['goods_amount_totals'],               // 商品结算金额
            'transport_cost_totals' => $order_info['transport_cost_totals'],           // 运费金额
            'order_amount_totals' => $order_info['order_amount_totals'],               // 订单总结算价钱

            // 优惠金额
            'goods_preferential' => $order_info['goods_preferential'],                 // 商品优惠金额
            'transport_preferential' => $order_info['transport_preferential'],         // 运费优惠金额

            // 支付金额
            'payable_amount' => $payable_amount,                                        // 应付金额
            'pay_points_amount' => $pay_vrb_amount,                                     // 虚拟币支付金额
            'pay_rmb_amount' => $pay_rmb_amount,                                         // 人民币支付额
            'pay_wallet_amount' => $pay_wallet_amount,                                  // 其它非人民币支付额度
            'payment' => $payment,                                                       // 支付信息数组序列化

            // 配送
            'sendee_time_type' => 0,                                          // 配送时间类型 默认:不限
            'sendee_province_id' => $sendee_province_id,                      // 收货人所在省份ID
            'sendee_area_id' => $sendee_area_id,                              // 收货人所在地区ID
            'sendee_city_id' => $sendee_city_id,                              // 收货人所在区县ID
            'sendee_address_id' => $sendee_address_id,                        // 收货人地址ID
            'sendee_address_info' => $sendee_address_info,                    // 收货人地址信息

            // 下单信息
            'plat_order_state' => 1,                                           // 订单状态 1:（已下单）待付款
            'from_media' => 1,                                                 // 下单媒介【0:WEB; 1:mobile】
            'create_time' => time(),                                           // 下单时间【成交时间】(整型时间戳)
        ];

        // plat_order_state tinyint(4)  订单状态
        //      1:（已下单）待付款; 2:（已付款）待发货; 3:（已发货）待收货; 4:（已收货）待评价; 9:已完成;
        //      -1:已取消; -2:已退单; -9:已删除; ）'
        // $sku_source_type  int     SKU来源类型【0:立即购买;1:购物车;7:电子充值卡;8:直接充值;9:礼品（礼券）;10:（实物）奖品);】
        if ($sku_source_type == 9 || $sku_source_type == 10) {
            // 人民币支付单号 32位随机整数
            // $data['pay_rmb_sn'] = time() . date('YmdHis') . rand(pow(10, 7), pow(10, 8) - 1);
            // $data['pay_rmb_time'] = time();

            // 9:礼品（礼券） 免支付免运费，创建订单后状态为“2:（已付款）待发货;”
            // 10:奖品  免支付免运费，创建订单后状态为“2:（已付款）待发货;”
            $data['plat_order_state'] = 2;
        }

        //若用户级别是20团采用户 30代理用户时，保存传过来的支付方式
        //通过id查代码表
//        if($member->grade == 20 ||  $member->grade == 30){
//            $dec_info = DB::table('dct_payment_mode')
//                ->select('id', 'code', 'name')
//                ->where('id', $pay_mode_id)
//                ->first();
//
//            $data['pay_mode_id']   =   $dec_info->id;
//            $data['pay_mode_code'] =   $dec_info->code;
//            $data['pay_mode_name'] =   $dec_info->name;
//
//            $data['expire_time'] =   $expire_time;
//        }


        $data['order_type']=$order_type;//月嫂类型订单--设置order_type
        // 生成订单
        $order = Order::create($data);
        if (!$order->exists) {
            return false;
        }

        return $order;
    }

    /**
     * 生成平台订单编号
     * @return mixed|null
     */
    private function getPlatOrderSn()
    {
        $sql = 'call usp_get_plat_order_sn(@out_orderSn,@returninfo);';
        $result_set = DB::statement($sql);
        if ($result_set) {
            $row = DB::select('select @out_orderSn');
            $temp = (array)$row[0];
            return $temp['@out_orderSn'];
        }

        return null;
    }

    /** 根据应付金额及非人民币支付金额，计算人民币拟支付金额
     * 传入参数：
     * @param $payable_amount      decimal(10,2)    应付金额
     * @param $pay_vrb_amount      int              拟支付虚拟币数【传出参数】
     * @param $pay_wallet_amount   int              拟支付其它金额合计【卡余额、零钱、代金劵等】【传出参数】
     * @param $arr_payment_info    array           非人民币支付信息(数组)
     * @param $plat_vrb_rate       decimal(10,2)    虚拟币汇率【1元（人民币）等于多少虚拟币】
     * 传出参数：
     * @param $pay_rmb_amount      decimal(10,2)     拟支付人民币金额
     */
    private function getPayRmbAmount($payable_amount = 0.00, & $pay_vrb_amount = 0, & $pay_wallet_amount = 0,
                                     & $arr_payment_info = [], $plat_vrb_rate = 0)
    {
        /* $arr_payment_info 支付明细（payment text）,数据格式为：
            [
                ['pay_type'=>'rmb','pay_id'=>326,'pay_amount'=>12.55],
                ['pay_type'=>'vrb','pay_id'=>14,'pay_amount'=>4],
                ['pay_type'=>'wallet','pay_id'=>26,'pay_amount'=>0.45],
                ['pay_type'=>'card_balance','pay_id'=>456,'pay_amount'=>10],
                ['pay_type'=>'voucher','pay_id'=>51,'pay_amount'=>50]
            ]
            注释：rmb——人民币；vrb——虚拟币；wallet——零钱【钱包】；
                  card_balance——卡余额；voucher——代金劵
        */
        $pay_vrb_amount = 0;
        $pay_wallet_amount = 0;
        $pay_rmb_amount = $payable_amount;
        if (count($arr_payment_info) > 0) {
            // 存在非人民币支付信息
            // if (array_key_exists('pay_rmb', $arr_payment_info)) {}
            foreach ($arr_payment_info as $key => $row) {
                if ($key == 'pay_vrb') {
                    $pay_vrb_amount = $row['pay_amount'];
                } else {
                    if ($key != 'pay_rmb') {
                        $pay_wallet_amount += $row['pay_amount'];
                    };
                }
            }

            // 如果使用了虚拟币支付，要兑换成人民币额度
            $pay_vrb_to_rmb = 0.00;
            if ($pay_vrb_amount > 0) {
                if ($plat_vrb_rate <= 0) {
                    $plat_vrb_rate = $this->getPlatVrbRate();
                }

                // 计算拟支付的虚拟币相当于多少人民币，保留2位小数
                $pay_vrb_to_rmb = bcdiv($pay_vrb_amount, $plat_vrb_rate, 2);
            }

            // 非人民币支付总额
            $pay_not_rmb_amount = bcadd($pay_wallet_amount, $pay_vrb_to_rmb, 2);

            // 人民币拟支付金额,不能为负数
            if ($payable_amount > $pay_not_rmb_amount) {
                $pay_rmb_amount = bcsub($payable_amount, $pay_not_rmb_amount, 2);
            } else {
                $pay_rmb_amount = 0;
            }
        }

        $arr_payment_info['pay_rmb'] = array(
            'pay_type' => 'rmb',
            'pay_id' => 0,
            'pay_amount' => $pay_rmb_amount
        );

        return $pay_rmb_amount;
    }

    /** 保存订单非人民币支付日志，诸如“虚拟币、零钱、卡余额、优惠劵”等，这些支付信息要保存到相关账户收支明细表中
     * 传入参数：
     * @param $plat_order_id       int     订单ID
     * 传出参数：
     * @param $return_value        int     (0成功；1出错)
     */
    public function saveNotRmbPayLog($plat_order_id = 0)
    {
        /* 订单非人民币支付信息（payment）为序列化数组字符串，数组格式如下：
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
        // select('plat_order_id','payment','pay_rmb_sn','member_id')
        $return_value = 0;
        $plat_order_id = (int)$plat_order_id;
        $order = Order::where('plat_order_id', $plat_order_id)
            ->first();
        if (empty($order)) {
            // 逻辑上，订单不应该不存在
            $return_value = 1;
            return $return_value;
        }

        $member_id = $order->member_id;
        $pay_rmb_sn = trim($order->pay_rmb_sn . '');
        $str_payment = trim($order->payment . '');
        $arr_payment_info = unserialize($str_payment);
        if (count($arr_payment_info) > 0) {
            foreach ($arr_payment_info as & $row) {
                $data = array();
                $data['member_id'] = $member_id;
                $data['busine_id'] = $plat_order_id;

                $pay_type = $row['pay_type'];
                switch ($pay_type) {
                    case "rmb":
                        $row['pay_id'] = $pay_rmb_sn;
                        break;
                    case "vrb":
                        $data['yesb_amount'] = $row['pay_amount'];
                        $busine_content = '订单预（冻结）支付，订单号为：' . $plat_order_id;

                        // 2001	订单预（冻结）支付
                        $obj_log = MemberYesbLog::ChangeBalance(2001, $data, $busine_content);
                        if ($obj_log) {
                            $row['pay_id'] = $obj_log->id;
                        }
                        break;
                    case "wallet":
                        // 冻结支付(订单)零钱
                        $busine_type = 'DJPAYLQ';
                        $data['balance_amount'] = $row['pay_amount'];
                        $obj_log = MemberWalletLog::changeBalance($busine_type, $data);
                        if ($obj_log) {
                            $row['pay_id'] = $obj_log->id;
                        }

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
                                $balance_amount += $card['balance_pay_amount'];
                                $obj_card = MemberRechargeCard::where('rechargecard_id', $card['rechargecard_id'])->first();
                                if ($obj_card) {
                                    // 原卡（余额）可用余额减少此次支付部分，注意此操作没有冻结操作
                                    $obj_card->balance_available -= $card['balance_pay_amount'];
                                    $obj_card->updated_at = time();
                                    $obj_card->save();
                                }
                            }

                            // 2、卡余额收支明细表增加一条支出记录
                            // 冻结支付(订单)卡余额
                            $busine_type = 'DJPAYKYE';
                            $data['balance_amount'] = $balance_amount;
                            $obj_log = MemberBalanceLog::changeBalance($busine_type, $data);
                            if ($obj_log) {
                                $row['pay_id'] = $obj_log->id;
                            }
                        }
                        break;
                    case "voucher":
                        break;
                }
            }

            // 更新当前订单支付信息，主要回填支付记录ID
            $str_payment = serialize($arr_payment_info);
            $order->payment = $str_payment;
            $order->save();
        }

        return $return_value;
    }

    /** 根据充值卡订单信息，更新充值卡销售量及销售状态
     * 传入参数：
     * @param  $sku_lst           array        充值卡信息
     * @param  $operate_type      tinyint(4)   操作类型【0:下订单增加销售量；1:撤单或退单减少销售量】
     * 传出参数：
     * @param $return_value        int     (0成功；1出错)
     */
    private function updateRechargeCard($sku_lst, $operate_type = 0)
    {
        $return_value = 0;
        $sku_lst = (array)$sku_lst;
        if (count($sku_lst) > 0) {
            $db_prefix = $this->db_prefix;
            foreach ($sku_lst as $sku) {
                /* $sku 结构如下
                $sku = array(
                    'sku_id' => $sku['sku_id'],
                    'number' => $sku['number'],
                    'activity_id' => $activity_id,
                    'card_id' => $card_id,
                    'two_dimension_id' => trim($row['two_dimension_id'] . ''),
                    'two_dimension_code' => trim($row['two_dimension_code'] . ''),
                    'two_dimension_number_code' => trim($row['two_dimension_number_code'] . '')
                );
                */
                if (array_key_exists('activity_id', $sku) &&
                    array_key_exists('card_id', $sku)
                ) {
                    // 1、修改充值卡销售状态
                    // $operate_type 操作类型【0:下订单增加销售量；1:撤单或退单减少销售量】
                    // sale_state tinyint(4) 销售状态(0:未销售;1:已销售;)
                    $sale_state = (($operate_type == 0) ? 1 : 0);
                    $sql = 'update ' . $db_prefix . 'recharge_card
                            set sale_state = ' . $sale_state . '
                        where card_id = ' . (int)$sku['card_id'];
                    $exe_result = DB::update($sql, []);

                    // 2、修改充值卡活动销售量
                    $obj_active = RechargeActivity::where('activity_id', (int)$sku['activity_id'])
                        ->first();
                    if ($obj_active) {
                        /* 充值卡发卡数量、销售量、兑换量，在更新销售量时，防止出现负数或超过发卡量的情况
                        card_amount int(10) 充值卡数量
                        sale_num  int(10) 销售数量
                        exchange_num int(10) 兑换数量
                        */
                        $card_amount = $obj_active->card_amount;
                        $old_sale_num = $obj_active->sale_num;
                        $new_sale_num = $old_sale_num;
                        if ($operate_type == 0) {
                            $new_sale_num = ($old_sale_num + 1);
                            if ($new_sale_num > $card_amount) {
                                $new_sale_num = $card_amount;
                            }
                        } else {
                            $new_sale_num = $old_sale_num - 1;
                            if ($new_sale_num < 0) {
                                $new_sale_num = 0;
                            }
                        }

                        // 如果已兑换数有变更，要更新该记录
                        if ($new_sale_num <> $old_sale_num) {
                            $data = array(
                                'sale_num' => $new_sale_num,
                                'updated_time' => time());
                            $obj_active->update($data);
                        }
                    }
                } else {
                    // 数据格式不对，直接退出
                    $return_value = 1;
                    return $return_value;
                }
            }
        }

        return $return_value;
    }


    // 判定指定的SKU数组是否存在虚拟商品

    /** 根据礼品订单信息，更新我的礼品SKU数量
     * 传入参数：
     * @param  $sku_lst           array        礼品卡信息
     * @param  $operate_type      tinyint(4)   操作类型【0:下订单增加兑换量；1:撤单或退单减少兑换量】
     * 传出参数：
     * @param $return_value        int     (0成功；1出错)
     */
    private function updateGiftSku($sku_lst, $operate_type = 0)
    {
        $return_value = 0;
        $sku_lst = (array)$sku_lst;
        if (count($sku_lst) > 0) {
            foreach ($sku_lst as $sku) {
                // $sku 结构如下
                /* $sku = array(
                    'sku_id'            => 141,     // sku_id
                    'number'            => 2,       // 数量
                    'price'             => 98.03,   // 价格
                    'promotions_type'  => 99,       // 99:礼品（礼券）
                    'promotions_id'    => 632      //  我的礼品记录ID（member_giftcoupon_goods.id）
                );

                礼品信息表（MemberGiftCouponGoods）
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
                    `sku_id` int(10) NOT NULL COMMENT 'sku_id',
                    `total_num` int(11) NOT NULL COMMENT '商品总数量',
                    `exchanged_num` int(11) NOT NULL COMMENT '已兑换商品数量',
                */
                $obj_gift = MemberGiftCouponGoods::where('id', $sku['promotions_id'])->first();
                if ($obj_gift) {
                    // 礼品原总数、已经兑换数
                    $total_num = $obj_gift->total_num;
                    $old_exchanged_num = $obj_gift->exchanged_num;

                    // 当前变化量
                    $new_exchanged_num = $old_exchanged_num;

                    // 操作类型【0:下订单增加兑换量；1:撤单或退单减少兑换量】
                    // 在更新兑换量时，防止出现负数或超过原总量的情况
                    $sku_number = $sku['number'];
                    if ($operate_type == 0) {
                        $new_exchanged_num = $old_exchanged_num + $sku_number;
                        if ($new_exchanged_num > $total_num) {
                            $new_exchanged_num = $total_num;
                        }
                    } else {
                        $new_exchanged_num = $old_exchanged_num - $sku_number;
                        if ($new_exchanged_num < 0) {
                            $new_exchanged_num = 0;
                        }
                    }

                    // 如果已兑换数有变更，要更新该记录
                    if ($new_exchanged_num <> $old_exchanged_num) {
                        $data = array(
                            'exchanged_num' => $new_exchanged_num,
                            'updated_at' => time());
                        $obj_gift->update($data);
                    }
                }
            }
        }

        return $return_value;
    }


    /*1
    // 订单保存成功后，如果订单支付使用了非人民币支付，
        //
        $this->saveNotRmbPayLog($plat_order_id);
    3*/

    /** 根据奖品订单信息，更新我的获奖记录领取状态
     * 传入参数：
     * @param  $sku_lst           array        奖品信息
     * @param  $operate_type      tinyint(4)   操作类型【0:下订单领取；1:撤单或退单撤销领取状态】
     * 传出参数：
     * @param $return_value        int     (0成功；1出错)
     */
    private function updateAwardsRecord($sku_lst, $operate_type = 0, $order_id = 0)
    {
        $return_value = 0;
        $sku_lst = (array)$sku_lst;
        $order_id = (int)$order_id;
        $db_prefix = $this->db_prefix;

        if (count($sku_lst) > 0) {
            foreach ($sku_lst as $sku) {
                // $sku 结构如下
                /* $sku = array(
                    'sku_id'            => 141,     // sku_id
                    'number'            => 2,       // 数量
                    'price'             => 98.03,   // 价格
                    'promotions_type'  => 100,       // 100:奖品
                    'promotions_id'    => 632      //  我的获奖记录ID（member_awardsrecord.awardsrecord_id）
                );

                获奖纪录（yyd_member_awardsrecord）
                awardsrecord_id		int(10) 	记录ID
                member_id		int(10)		    会员ID
                order_id 		int(11) 	    订单ID
                company_id		int(10) 	    所属供应商ID(“0”为平台)

                prize			bigint(20) 	sku_id、虚拟币数、红包人民币额
                exchange_type	tinyint(4) 	奖品来源【0:没中奖; 1:礼品; 2:虚拟币;】
                prize_type		tinyint(1) 	奖品类型【0:实物; 1:微信红包;】
                exchange_state	tinyint(4) 	领取状态【0:未领取; 1:已领取】

                下订单的条件【exchange_type = 1 and prize_type = 0 and exchange_state = 0】
                */
                // 操作类型【0:下订单领取；1:撤单或退单撤销领取状态】
                $exchange_state = ($operate_type == 0 ? 1 : 0);
                $sql = 'update ' . $db_prefix . 'member_awardsrecord
                        set order_id = ' . $order_id . ',
                        exchange_state = ' . $exchange_state . '
                      where awardsrecord_id = ' . $sku['promotions_id'];
                $update_result = DB::update($sql, []);
                $return_value = ($update_result == 1 ? 0 : 1);
            }
        }

        return $return_value;
    }

    /**
     * 订单预支付【人民币预支付】
     * @auth yang
     * @param Request $request
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    public function prePay(Request $request)
    {
        $order_sn = $request->input('order_sn');
        $order = Order::select('plat_order_id', 'pay_rmb_sn', 'pay_rmb_amount', 'plat_order_state')
            ->where('plat_order_sn', $order_sn)
            ->first();

        if (!$order) {
            // 60102 => '订单编号不存在',
            return Api::responseMessage(60102);
        } else {
            // 订单状态（1:（已下单）待付款; 2:（已付款）待发货; 3:（已发货）待收货; 4:（已收货）待评价; 9:已完成;
            // -1:已取消; -2:已退单; -5:已退货; -9:已删除;）
            $plat_order_state = $order->plat_order_state;
            if ($plat_order_state != 1) {
                // 60201 => '不是待支付订单',
                return Api::responseMessage(60201);
            }

            // 如果人民币支付金额为 0 ，直接修改支付状态，退出程序，支付成功
            $pay_rmb_amount = $order->pay_rmb_amount;
            if ($pay_rmb_amount == 0.00) {
                $order->plat_order_state = 2;
                $order->save();
                return Api::responseMessage(0, null, '支付成功！');
            }
        }


        // 验证支付方法
        $able_payMethod = array('wxpay', 'alipay');
        $buyMethod = $request->input('buyMethod');
        if (!in_array($buyMethod, $able_payMethod)) {
            Log::error('不是可用的支付方式,请检查参数是否有误 控制器:OrderController@add');
            return view('errors.error');                // 数据有误
        }

        switch ($buyMethod) {
            case 'wxpay' :
                // 微信支付
                // 买家信息
                $member = Auth::user();
                $open_id = MemberOtherAccount::where('member_id', $member->member_id)
                    ->value('account_id');
                return redirect()->route('wxPay', ['open_id' => $open_id,
                    'rmb_sn' => $order->pay_rmb_sn,
                    'rmb' => $order->pay_rmb_amount,
                    'order_sn' => $order_sn]);
                break;
            case 'alipay' :
                // 支付宝支付接口
                dd('支付宝支付');
                break;
        }

        return true;
    }

    /** 删除某一个状态为完成的订单（逻辑删除）
     * @param  $order_id         int     订单ID
     * @return $return_value     0/1     0(成功删除)/1（其它问题，删除不成功）
     */
    public function delete($order_id)
    {
        /* 只有订单状态为“9:已完成;”的订单，可以删除，并且为逻辑删除
            1、plat_order_state    订单状态
                1:（已下单）待付款; 2:（已付款）待发货; 3:（已发货）待收货;
                4:（已收货）待评价; 9:已完成;
                -1:已取消; -2:已退单; -9:已删除;
            2、delete_state tinyint(4) 删除状态(0:未删除;1:放入回收站; 2:逻辑删除);
        */
        $return_value = 0;
        $order_id = (int)$order_id;
        $order = Order::where('plat_order_id', $order_id)->first();
        if (empty($order)) {
            // 60102 => '订单id不存在',
            return Api::responseMessage(60101);
        } else {
            // 暂时不实现评价功能
            if ($order['plat_order_state'] <> 4 && $order['plat_order_state'] <> 9) {
                // 60108 => '只有已完成的订单才能删除'
                return Api::responseMessage(60108);
            }
        }


        // 1、设置订单状态及删除标识
        $order->plat_order_state = -9;
        $order->delete_state = 2;
        $return_value = $order->save();
        if (!$return_value) {
            return Api::responseMessage(1, '', '删除失败！');
        }

        // 2、填写日志
        LogInfoFacade::logOrderPlat($order_id, '删除订单', -9);
        return Api::responseMessage(0);
    }

    /** 撤销某一个未支付的订单
     * @param  $order_id         int     订单ID
     * @return $return_value     0/1     0(撤销成功)/1（其它问题，撤销不成功）
     */
    public function cancel($order_id)
    {
        /* 只有订单状态为“9:已完成;”的订单，可以删除，并且为逻辑删除
            1、plat_order_state    订单状态
                1:（已下单）待付款; 2:（已付款）待发货; 3:（已发货）待收货;
                4:（已收货）待评价; 9:已完成;
                -1:已取消; -2:已退单; -9:已删除;
            2、delete_state tinyint(4) 删除状态(0:未删除;1:放入回收站; 2:逻辑删除);
        */
        $return_value = 0;
        $order_id = (int)$order_id;
        $order = Order::where('plat_order_id', $order_id)->first();
        if (empty($order)) {
            // 60102 => '订单id不存在',
            return Api::responseMessage(60101, 0, '订单id不存在');

        } else {
            // 已发货的不能退单
            if ($order['plat_order_state'] > 2) {
                // 60200 => '只有未支付的订单才能撤单',
                return Api::responseMessage(60200, 0, '已发货的订单不能撤单');
            }
        }


        // 1、订单未（确认）支付撤单,原预支付的虚拟资产，要解冻
        $str_payment = trim($order->payment . '');
        $arr_payment_info = unserialize($str_payment);
        if (count($arr_payment_info) > 0) {
            foreach ($arr_payment_info as & $row) {
                $data = array();
                $data['member_id'] = $order->member_id;
                $data['busine_id'] = $order_id;

                $pay_type = $row['pay_type'];
                switch ($pay_type) {
                    case "rmb":
                        break;
                    case "vrb":
                        $data['id'] = $row['pay_id'];
                        $data['yesb_amount'] = $row['pay_amount'];
                        $busine_content = '订单未（确认）支付撤单，订单号为：' . $order_id;

                        // 2003	订单未（确认）支付撤单
                        $obj_log = MemberYesbLog::ChangeBalance(2003, $data, $busine_content);
                        break;
                    case "wallet":
                        //  DDWZFCD  2003【订单未（确认）支付撤单】；可用余额增加，冻结额度减少，总额度不变。
                        $busine_type = 'DDWZFCD';
                        $data['balance_amount'] = $row['pay_amount'];
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
                                $balance_amount += $card['balance_pay_amount'];
                                $obj_card = MemberRechargeCard::where('rechargecard_id', $card['rechargecard_id'])->first();
                                if ($obj_card) {
                                    // 原卡（余额）可用余额减少此次支付部分，注意此操作没有冻结操作
                                    $obj_card->balance_available += $card['balance_pay_amount'];
                                    $obj_card->updated_at = time();
                                    $obj_card->save();
                                }
                            }

                            // 2、卡余额收支明细表增加一条支出记录
                            // DDWZFCD  2003【订单未（确认）支付撤单】；可用余额增加，冻结额度减少，总额度不变。
                            $busine_type = 'DDWZFCD';
                            $data['balance_amount'] = $balance_amount;
                            $obj_log = MemberBalanceLog::changeBalance($busine_type, $data);
                        }
                        break;
                    case "voucher":
                        break;
                }
            }
        }


        // 2、根据订单SKU 来源类型，更新关联的商品信息
        /*
            sku_source_type tinyint(4) SKU来源类型(0:立即购买;1:购物车;7:购买充值卡;8:立即充值;9:礼品（礼券）;10:（实物）奖品);)
            sku_source_info text sku来源信息【数组序列化】
        */
        $obj_order_extend = OrderExtend::select('sku_source_type', 'sku_source_info')
            ->where('plat_order_id', $order_id)
            ->first();
        if ($obj_order_extend) {
            $sku_source_type = (int)($obj_order_extend->sku_source_type);
            $sku_lst = unserialize(trim($obj_order_extend->sku_source_info . ''));
            switch ($sku_source_type) {
                case 7;
                    // 7:电子充值卡订单【支付成功后不兑换、不充值，发验证码充值，不配送无物流】
                    // 电子充值卡订单，变更相关充值卡信息，操作类型【0:下订单增加销售量；1:撤单或退单减少销售量】
                    // 这里特别要注意的是，$sku_source_type=8 的是直接充值订单，不限购、不配送无物流
                    $this->updateRechargeCard($sku_lst, 1);
                    break;
                case 9;
                    // 9:礼品（礼券）订单，变更相关礼品信息，操作类型【0:下订单增加兑换量；1:撤单或退单减少兑换量】
                    $this->updateGiftSku($sku_lst, 1);
                    break;
                case 10;
                    // 10:奖品订单，变更相关奖品领取信息，操作类型【0:下订单领取；1:撤单或退单撤销领取】
                    $this->updateAwardsRecord($sku_lst, 1);
                    break;
                default:
                    break;
            }
        }

        //2017/5/19
        //若进行微信支付，这退款（若有支付时间，说明该订单经过了微信支付，则需wx退款）
        if ($order->pay_rmb_time) {
            $pay_con = new WxPayController(new Application(config('wechat')));
            $result = $pay_con->wxRefund($order_id);
            if (!$result) {
                $log = '买家微信退款失败，订单id: ' . $order_id . '； ' .
                    '退款的人民币：' . $order->pay_rmb_amount . ' 元， ' .
                    '申请退款时间： ' . date('Y-m-d H:i:s') . '； ';

                LogInfoFacade::logOrderPlat($order_id, $log, 2, $order->member_id, '买家');
            }
        }
        // 9、设置订单状态
        $order->plat_order_state = -1;
        $return_value = $order->save();
        if (!$return_value) {
            return Api::responseMessage(1, '', '撤单失败！');
        }

        // 4、填写日志
        LogInfoFacade::logOrderPlat($order_id, '撤单', -1);
        return Api::responseMessage(0);
    }

    /**
     * 确认收货
     * @param $plat_order_id
     */
    public function confirmReceipt($plat_order_id)
    {
        // 买家
        $member = Auth::user();

        /**
         * 更新订单状态 记录操作日志
         */

        //判断是否有第三方订单
        $db_prefix = config('database')['connections']['mysql']['prefix'];
        $isThirdSql="SELECT sku.from_plat_code,count(1) AS cnt FROM " . $db_prefix . "order_goods d,
                           " . $db_prefix . "goods_sku sku WHERE d.sku_id = sku.sku_id AND d.plat_order_id = ? GROUP BY sku.from_plat_code";
        $isThird = DB::select($isThirdSql,[$plat_order_id]);
        if($isThird){
            foreach($isThird as $third){
                if($third->from_plat_code=="2002"){
                    $is_success=false;
                    $soc=new SupplierOrderController();
                    $return_info=$soc->thirdConfirmOrder($plat_order_id, $third->from_plat_code);
                    if($return_info){
                        if ($return_info['code'] == 200) {//成功发送确认收货到第三方
                            $is_success=true;
                            Log::notice('send confirm to thirdWyyx....');
                            Log::alert($return_info);
                        }
                        Log::notice("third return.....". $plat_order_id. ".........");
                        Log::alert($return_info);
                    }

                    if(!$is_success){
                        return Api::responseMessage(5000,'确认订单失败！');
                    }
                }
            }
        }

        DB::transaction(function () use ($plat_order_id, $member) {
            /* 更新平台订单状态, plat_order_state 订单状态
                    1:（已下单）待付款; 2:（已付款）待发货; 3:（已发货）待收货;
                    4:（已收货）待评价; 9:已完成;
                    -1:已取消; -2:已退单; -9:已删除;
            */
            $plat_order = Order::where('plat_order_id', $plat_order_id)->first();
            $plat_order->plat_order_state = 4;
            $plat_order->save();

            // 记录平台操作日志
            $content = '买家 "' . $member->nick_name . '" 已收货! 订单编号为:' . $plat_order->plat_order_sn;
            LogInfoFacade::logOrderPlat($plat_order_id, $content, 4, $member->member_id, $member->nick_name);

            // 修改供应商订单状态
            $supplier_orders = OrderSupplier::where('plat_order_id', $plat_order_id)->get();
            foreach ($supplier_orders as $supplier_order) {
                // 更新每个供应商订单状态
                $supplier_order->supplier_order_state = 4;
                $supplier_order->save();

                // 记录供应商订单操作日志
                $so_content = '买家 "' . $member->nick_name . '" 已收货!';
                LogInfoFacade::logOrderSupplier($supplier_order->supplier_id, $supplier_order->supplier_order_id,
                    $so_content, 4, $member->member_id, $member->nick_name);
            }

            // 修改仓点发货表状态
            $store_goods = StoreDeliverGoods::where('plat_order_id', $plat_order_id)->get();
            foreach ($store_goods as $store_good) {
                // 更新仓点发货表中的状态
                $store_good->deliver_state = 4;
                // 回填到货时间(签收时间)
                $store_good->arrival_time = time();
                $store_good->save();

                // 记录仓点发货表日志
                $sdg_content = '买家 "' . $member->nick_name . '" 已收货,商品配送完成!';
                LogInfoFacade::logOrderStore($store_good, $sdg_content, $member->member_id, $member->member_name);
            }

        });

        return Api::responseMessage(0);
    }

    /** 根据前台传入的应付金额及拟支付的非人民币金额，重新计算人民币支付金额
     *  虚拟币、零钱、卡余额、优惠劵等，统称为非人民币支付
     *  传入参数：应付人民币金额、其它非人民币拟支付金额
     * 传出参数：
     * @param  $arr_payment_info  array     所有支付信息
     * @param  $pay_rmb    decimal(10,2)    人民币拟支付金额
     */
    public function getPayNumber(Request $request)
    {
        // 虚拟币支付额度、人民币支付额度、其它非人民币支付额度、所有拟支付信息【数组】
        $pay_vrb_amount = 0;
        $pay_rmb_amount = 0.00;
        $pay_wallet_amount = 0;
        $arr_payment_info = array();

        // 订单应付金额
        $payable_amount = bcadd($request->input('payable_amount'), 0.00, 2);
        if ($payable_amount >= 0) {
            // 将客户端传入的拟支付信息形成数组，拟支付财富的类别及额度合法性，由外部验证，例如是否超出应付金额。
            $arr_payment_info = $this->readPaymentInfo($request, $payable_amount);
            $pay_rmb_amount = $this->getPayRmbAmount($payable_amount, $pay_vrb_amount, $pay_wallet_amount, $arr_payment_info);
        } else {
            Log::error('应付金额不能为负数  控制器:OrderController@getPayNumber');
            return view('errors.error');
        }

        return Api::responseMessage(0, array(
            'new_pay_vrb' => $pay_vrb_amount,
            'new_pay_rmb' => $pay_rmb_amount,
            'arr_payment_info' => $arr_payment_info
        ));
    }

    /**
     * 售后
     * @param Request $request
     * @return  int
     */
    public function saleOrder(Request $request)
    {
        try {
            DB::beginTransaction();

            $detailId = $request->input('orderDetailId');
            $sku = $request->input('skuId');
            $orderGoods = OrderGoods::find($detailId);
            $orderGoods->goods_state = 1;
            //修改状态
            $orderGoods->save();
            //生成记录
            ServiceOrder::create([
                "plat_order_id" => $orderGoods->plat_order_id,      //平台订单id
                "sku_id" => $sku,                                   // skuid
                "price" => $orderGoods->settlement_price,           //价格
                "number" => 0,                                      //数量

                "service_content" => $request->input('text'),       //服务内容
                "buy_mobile" => $request->input('phone'),           //手机号
                "service_state" => 20,                              //服务状态  目前就只用者2个 20,90
                "apply_time" => time(),                             //申请时间
                "buy_id" => Auth::user()->member_id,                //买家id
                "buy_name" => $request->input('name'),              //买家姓名
                "apply_member_id" => Auth::user()->member_id,       //申请者ID
                "apply_member_name" => $request->input('name')      //申请者姓名

            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return 'error';
        }
        return 'success';
    }

    /**
     * 退货退款
     * @return  mixed
     */
    public function afterOrder()
    {
        //1当前用户  退货退款信息
        $orders = ServiceOrder::where('buy_id', Auth::user()->member_id)->get();
        foreach ($orders as $order) {
            $result = DB::table('goods_sku')->leftjoin('goods_sku_images', 'goods_sku_images.sku_id', '=', 'goods_sku.sku_id')
                ->where('goods_sku.sku_id', $order->sku_id)
                ->first();
            if (empty($result->image_url)) {  //sku图不存在就去spu的主图
                $url = GoodsSpu::where('spu_id', $result->spu_id)->value('main_image');
                $result->image_url = $url;
            }
            $order['image_url'] = $result->image_url;  //主图
            $order['spec'] = unserialize($result->sku_spec);  //主图
            $order['sku_name'] = $result->sku_name;  //名称
            $orderRefund = OrderRefund::where('service_record_id', $order->id)->first();
            if (!$orderRefund) {
                $order['status'] = 0;
            } else {          //如果是退货退款 就有状态
                $order['status'] = 1;
                $order['refund_price'] = $orderRefund->refund_amount;
            }
        }
        return view('order/afterOrder')->withOrders($orders);
    }

    /**
     * 查看物流
     * @return  mixed
     */
    public function logistics($id)
    {
        $delGoods = StoreDeliverGoods::where('plat_order_id', $id)->get();//仓点（供应商)发货表  得到所有运单
        $num = [];
        foreach ($delGoods as $delGood) {
            if ($delGood->waybill_id != 0) {
                array_push($num, $delGood->waybill_id);
            }
        }
        $num = array_unique($num);              //1一个订单拆成多少运单
        $result = [];
        foreach ($num as $item) {
            //判断是否是第三方物流公司的
            $type = StoreWayBill::where('id', $item)->value('transport_type');
            $lo = [];
            if ($type == 0) {
                $lo = Logistics::query($item);   //第三方物流
                $lo['id'] = $item;
                rsort($lo['res']);
            } elseif ($type == 1) {                //自提
                $wayBill = StoreWayBill::where('id', $item)->first();
                $lo['id'] = $item;
                $lo['delivery_name'] = $wayBill->delivery_name;
                $lo['delivery_info'] = $wayBill->delivery_info;
                $lo['dlyo_pickup_code'] = $wayBill->dlyo_pickup_code;
                $lo['transport_type'] = 1;
            } else {                            //稍货
                $wayBill = StoreWayBill::where('id', $item)->first();
                $lo['id'] = $item;
                $lo['transport_plate_number'] = $wayBill->transport_plate_number;
                $lo['transport_driver_name'] = $wayBill->transport_driver_name;
                $lo['transport_tel_num'] = $wayBill->transport_tel_num;
                $lo['transport_type'] = 2;
            }
            array_push($result, $lo);
        }
        foreach ($result as &$item) {
            $store = StoreDeliverGoods::where('waybill_id', $item['id']);
            $item['img'] = $store->value('sku_image');
            $item['num'] = $store->count();
        }
        //没有发货(重复上面)
        if (count($result) == 0) {

            $delGoods = StoreDeliverGoods::where('plat_order_id', $id)->first();//仓点（供应商)发货表  得到所有运单
            if ($delGoods != null) {
                array_push($result, [
                    'img' => $delGoods->sku_image,
                    'num' => $delGoods->number,
                    'transport_type' => -10
                ]);
            } else {
                //刚下单的商品
                $result = null;
                return view('order/logistics')->withResult($result);
            }

        }
        return view('order/logistics')->withResult($result);
    }

    /**
     * 订单未（确认）支付撤单，把使用的非人民币支付进行回退
     */
    public function cancelNotRmbPayLog($plat_order_id = 0)
    {
        /* 订单非人民币支付信息（payment）为序列化数组字符串，数组格式如下：
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
        // select('plat_order_id','payment','pay_rmb_sn','member_id')
        $return_value = 0;
        $plat_order_id = (int)$plat_order_id;
        $order = Order::where('plat_order_id', $plat_order_id)
            ->first();
        if (empty($order)) {
            // 逻辑上，订单不应该不存在
            $return_value = 1;
            return $return_value;
        }

        $member_id = $order->member_id;
        $pay_rmb_sn = trim($order->pay_rmb_sn . '');
        $str_payment = trim($order->payment . '');
        $arr_payment_info = unserialize($str_payment);
        if (count($arr_payment_info) > 0) {
            foreach ($arr_payment_info as & $row) {
                $data = array();
                $data['member_id'] = $member_id;
                $data['busine_id'] = $plat_order_id;

                $pay_type = $row['pay_type'];
                switch ($pay_type) {
                    case "rmb":
                        $row['pay_id'] = $pay_rmb_sn;
                        break;
                    case "vrb":
                        $data['yesb_amount'] = $row['pay_amount'];
                        $data['id'] = $row['pay_id'];
                        $busine_content = '订单取消预（冻结）支付，订单号为：' . $plat_order_id;

                        // 2003	订单取消预（冻结）支付
                        $obj_log = MemberYesbLog::ChangeBalance(2003, $data, $busine_content);
                        if ($obj_log) {
                            $row['pay_id'] = $obj_log->id;
                        }
                        break;
                    case "wallet":
                        // 取消冻结支付(订单)零钱
                        $busine_type = 'DDWZFCD';
                        $data['balance_amount'] = $row['pay_amount'];
                        $obj_log = MemberWalletLog::changeBalance($busine_type, $data);
                        if ($obj_log) {
                            $row['pay_id'] = $obj_log->id;
                        }

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
                                $balance_amount += $card['balance_pay_amount'];
                                $obj_card = MemberRechargeCard::where('rechargecard_id', $card['rechargecard_id'])->first();
                                if ($obj_card) {
                                    // 原卡（余额）可用余额恢复原来扣掉的，注意此操作没有冻结操作
                                    $obj_card->balance_available += $card['balance_pay_amount'];
                                    $obj_card->updated_at = time();
                                    $obj_card->save();
                                }
                            }

                            // 2、卡余额收支明细表增加一条支出记录
                            // 冻结支付(订单)卡余额
                            $busine_type = 'DDWZFCD';
                            $data['balance_amount'] = $balance_amount;
                            $obj_log = MemberBalanceLog::changeBalance($busine_type, $data);
                            if ($obj_log) {
                                $row['pay_id'] = $obj_log->id;
                            }
                        }
                        break;
                    case "voucher":
                        break;
                }
            }

            // 更新当前订单支付信息，主要回填支付记录ID
            $str_payment = serialize($arr_payment_info);
            $order->payment = $str_payment;
            $order->save();
        }

        return $return_value;
    }

    /**
     * 代理用户或者团采用户上传电子支付凭证
     */
    public function uploadImg(Request $request)
    {

        if (empty($_FILES)) return Api::responseMessage(5000, '', '上传图片不存在');

        $images = isset($_FILES['uploadPic']) ? $_FILES['uploadPic'] : '';
        //上传图片

        $rootPath = config('upload')['rootPath'];
        $path = '/payorders/' . date('Y-m-d') . '/' . rand(0, 100);
        $imgPath = $rootPath . $path;
        if (!file_exists($imgPath)) {
            mkdir($imgPath, 0777, true);
        }
        $ext = strtolower(pathinfo($images['name'], PATHINFO_EXTENSION));

        $file_name = $this->getUniName() . '.' . $ext;
        $imgPath = $imgPath . '/' . $file_name;

        //这里调用的比例压缩图片大小的函数
        if (@move_uploaded_file($images['tmp_name'], $imgPath)) {

            //保存数据库中
            $order_id = $request->input('order_id');
            $img = $path . '/' . $file_name;

            Order::where('plat_order_id', $order_id)
                ->update(['pay_cert' => $img]);

            $result['file_name'] = $this->img_domain . $path . '/' . $file_name;
            exit(json_encode($result));
        }
    }

    /**
     * 产生唯一字符串(文件名)
     * @return string
     */
    private function getUniName()
    {
        return md5(uniqid(microtime(true), true));
    }

    public function delImg(Request $request)
    {
        $data_img = $request->input('data_img');
        if (empty($data_img)) return Api::responseMessage(5000, '', '图片不存在');

        $pos = stripos($data_img, $this->img_domain);
        $data_img = substr($data_img, ($pos) + strlen($this->img_domain));

        $file_path = config('upload')['rootPath'] . $data_img;

        @unlink($file_path);

        //把该订单上传的的图片清空
        $order_id = $request->input('order_id');
        Order::where('plat_order_id', $order_id)
            ->update(['pay_cert' => '']);

        return Api::responseMessage(0);
    }



//  有关微信分享礼品生成的订单

    /**
     * @param Request $request
     *
     *
     */
    public function gift_order_add(Request $request)
    {
        // 买家信息
        $grade = 10;
        $member = Auth::user();
        if ($member) {
            $grade = $member->grade;
            $member_id = $member->member_id;
        }

        // ---------------------------------- 1 验证传入信息的合法性 ------------------
        $sku = $request->input('skus'); //礼品分享只分享一个sku的商品
        if (!$sku) {
            Log::error('传入的 skus 为空!  控制器:OrderController@gift_order_add');
            return Api::responseMessage(2, null, '没有传入下单商品信息！');
        }

        $obj_sku = GoodsSku::select('sku_id', 'spu_id',
            'sku_name', 'sku_title', 'market_price', 'price', 'groupbuy_price', 'trade_price', 'partner_price',
            'points_limit', 'sku_spec')
            ->where('sku_id', $sku)
            ->first();
        // 如果为空跳过本次循环
        if (!$obj_sku) {
            // 如果数组为空 说明传入的数组sku_id中,平台没有或者未上架
            Log::error('传入的商品信息无效! 控制器:OrderController@gift_order_add');
            return Api::responseMessage(2, null, '传入的商品信息无效! ');
        }

        $spu_id = $obj_sku->spu_id;
        $obj_spu = GoodsSpu::where('spu_id', $spu_id)->first();
        $sku_main_img = GoodsSkuImages::mainImg($sku);
        if (is_object($sku_main_img)) {
            $sku_main_img = '';
        }

        // 图片格式化为带域名的字符串
        $sku_main_img = $this->getFullPictureUrl($sku_main_img);

        $price = $this->getSkuPrice($grade, $obj_sku); //获取用户级别对应的价格
        $gift_num = $request->input('num'); //商品购买数量
        $freight_total = $request->input('g_freight'); //商品总运费
        $goods_amount_totals = bcmul($price, $gift_num, 2); // 商品结算金额
        $order_amount_totals = bcadd($goods_amount_totals, $freight_total, 2);// 订单结算金额合计(商品金额+运费)

        // 将客户端传入的拟支付信息形成数组
        $arr_payment_info = $this->readPaymentInfo($request, $order_amount_totals);

        // 根据订单应付金额及非人民币支付额度，计算人民币支付额度
        $payable_amount = bcadd($order_amount_totals, 0.00, 2);

        // 虚拟币支付额度、人民币支付额度、其它非人民币支付额度
        $pay_vrb_amount = 0;
        $pay_rmb_amount = 0.00;
        $pay_wallet_amount = 0.00;

        if ($payable_amount > 0) {
            $pay_rmb_amount = $this->getPayRmbAmount($payable_amount, $pay_vrb_amount,
                $pay_wallet_amount, $arr_payment_info);
        }

        // 支付信息数组序列化
        $payment = serialize($arr_payment_info);

        // 保存订单主表、明细表、预支付信息等
        try {
            DB::beginTransaction();
            /* 目前的订单都是平台订单，暂时不考虑分销商因素，也就是暂时不考虑分销商和平台间结算问题
                如果是分销商订单（买家从分销商微商城下的单，销售方为分销商），要考虑下列信息项
                -- 订单主表（yyd_order）
                seller_id				销售方ID			int(10)
                seller_name				销售方名称			varchar(200)
                seller_goods_amount_totals		销售方结算金额	decimal(10,2)

                -- 订单SKU表（yyd_order_goods）
                seller_sku_id				销售方SKUid			bigint(20)
                seller_price		销售方价格【分销商与平台间结算价】		decimal(10,2)
            */
            // -------------------------------- 3.1 生成订单主表 ---------------------------------------------
            // 平台订单编号(调用数据库存储过程获取)
            $plat_order_sn = $this->getPlatOrderSn();
            if (!$plat_order_sn) {
                Log::error('获取平台订单编号失败  控制器:OrderController@add');
                return view('errors.error');                // 数据有误
            }

            // 组装生成订单数据
            $data = [
                'plat_order_sn' => $plat_order_sn,

                //  买家会员信息 即登录者信息
                'member_id' => $member->member_id,
                'member_name' => $member->nick_name,
                'member_email' => $member->email,
                'member_mobile' => $member->mobile,

                // 结算金额
                'goods_amount_totals' => $goods_amount_totals,               // 商品结算金额
                'transport_cost_totals' => $freight_total,           // 运费金额
                'order_amount_totals' => $goods_amount_totals,               // 订单总结算价钱

                // 优惠金额
                'goods_preferential' => 0,                 // 商品优惠金额
                'transport_preferential' => 0,         // 运费优惠金额

                // 支付金额
                'payable_amount' => $payable_amount,                                        // 应付金额
                'pay_points_amount' => $pay_vrb_amount,                                     // 虚拟币支付金额
                'pay_rmb_amount' => $pay_rmb_amount,                                         // 人民币支付额
                'pay_wallet_amount' => $pay_wallet_amount,                                  // 其它非人民币支付额度
                'payment' => $payment,                                                       // 支付信息数组序列化

                // 下单信息
                'plat_order_state' => 2,                                           // 订单状态 1:（已下单）待付款
                'from_media' => 1,                                                 // 下单媒介【0:WEB; 1:mobile】
                'create_time' => time(),                                           // 下单时间【成交时间】(整型时间戳)
                'is_share_gifts' => 1,       //表明该订单是分享礼品订单，后台不对此分单。
            ];

            // 生成订单
            $order = Order::create($data);
            if (!$order->exists) {
                Log::error('用户平台订单生成失败  控制器:OrderController@gift_order_add');
                DB::rollBack();
                return Api::responseMessage(1, null, '保存订单出错! ');
            }

            $plat_order_id = $order->plat_order_id;
            // ---------------------------------- 3.2 生成订单扩展表 ---------------------------
            // 初始化订单扩展表数据
            $extend_data = array('plat_order_id' => $plat_order_id);
            OrderExtend::create($extend_data);

            // --------------------------------- 3.3 生成订单明细表(订单商品信息) -----------------------------------------
            $g_data = [
                'order_detail_index' => 1,
                'plat_order_id' => $plat_order_id,

                'sku_id' => $obj_sku['sku_id'],
                'sku_name' => $obj_sku['sku_name'],
                'sku_title' => $obj_sku['sku_title'],

                'sku_image' => $sku_main_img,
                'sku_spec' => $obj_sku['sku_spec'],
                'spu_id' => $obj_sku['spu_id'],
                'gc_id' => $obj_spu->gc_id,
                'gc_name' => $obj_spu->gc_id,

                'transport_cost' => $freight_total,

                // 商品定价、商品结算价【优惠后价】
                'goods_price' => $price,
                'settlement_price' => $price,

                'number' => $gift_num,
            ];

            $order_goods = OrderGoods::create($g_data);
            if (!$order_goods->exists) {
                DB::rollBack();
                Log::error('用户平台订单明细表生成失败  控制器:OrderController@gift_order_add');
                return Api::responseMessage(1, null, '保存订单明细出错! ');
            }

            //分享信息的基本信息

            $s_data = [
                'member_id' => $member_id,
                'sku_id' => $sku,
                'gifts_num' => $gift_num,
                'create_time' => time(),
                'expiration_time' => time() + 72 * 3600, //72小时失效
                'plat_order_id' => $plat_order_id,
                'gifts_title' => $request->input('title'),
                'gifts_message' => $request->input('message'),

                'nick_name' => $member->nick_name,
                'avatar' => $member->avatar,
                'sku_name' => $obj_sku['sku_name'],
                'sku_image' => $sku_main_img,
                'sku_price' => $price,
            ];

            $share_gifts_info_id = DB::table('share_gifts_info')->insertGetId($s_data);
            if (!$share_gifts_info_id) {
                Log::error($member->member_name . '分享信息的基本信息生成失败  控制器:OrderController@gift_order_add');
                DB::rollBack();
                return Api::responseMessage(1, null, '保存订单出错! ');
            }

            // ---------------------------------- 3.4 保存虚拟币支付日志 -----------------
            // 订单保存成功后，如果订单支付使用了非人民币支付，
            // 诸如“虚拟币、零钱、卡余额、优惠劵”等，这些支付信息要保存到相关账户收支明细表中
            $this->saveNotRmbPayLog($plat_order_id);

            //若非人民币支付成功，则更改日志
            $wxpayc = new WxPayController(new Application(config('wechat')));

            if (bccomp($pay_rmb_amount, 0.00, 2) == 0) {
                $plat_order = PlatOrder::where('plat_order_id', $plat_order_id)->first();
                $wxpayc->wxShareGiftConfirmNotRmbPay($plat_order);
            } else {
                //生成平台预支付订单
                $wx_json = $wxpayc->wx_share_gift_pay($plat_order_id);
                if (!$wx_json) {
                    Log::error($member->member_name . '生成平台预支付订单失败  控制器:OrderController@gift_order_add');
                    DB::rollBack();
                    return Api::responseMessage(1, null, '生成平台预支付订单失败! ');
                }

            }

            // ---------------------------------- 3.5 保存订单操作日志【下单待付款】 ------
            // -----------
            $content = '用户' . $member->member_name . ', 生成了一个用于微信分享礼品订单！ 订单编号为:' . $order->plat_order_sn;
            LogInfoFacade::logOrderPlat($plat_order_id, $content, 1, $member->member_id, $member->nick_name);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('订单提交失败  控制器:OrderController@gift_order_add');
            return Api::responseMessage(1, null, '保存订单出错! ');
        }

        //若全部用非人民币支付
        if (bccomp($pay_rmb_amount, 0.00, 2) == 0) {
            $return_data = array('plat_order_id' => $plat_order_id, 'share_gifts_info_id' => $share_gifts_info_id, 'type' => 1);
            return Api::responseMessage(0, $return_data, '订单保存成功！');
        } else { //需要用wx支付
            $return_data = array('plat_order_id' => $plat_order_id, 'share_gifts_info_id' => $share_gifts_info_id, 'type' => 0, 'wx_json' => $wx_json);
            return Api::responseMessage(0, $return_data, '订单保存成功,去微信支付！');
        }

    }


    /**
     * @param Request $request
     *
     * 领取微信礼品者生成订单
     */
    public function get_gift_order_add(Request $request)
    {

        $member_id = 0;
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;

        } else {
            // 当前买家未登录，导航到登录界面
            return redirect('/oauth');
        }

        //判断该礼品是否可领取
        $share_gifts_info_id = $request->input('share_gifts_info_id');
        $share_info = DB::table('share_gifts_info')->where('share_gifts_info_id', $share_gifts_info_id)->first();
        if (!$share_info) {
            return Api::responseMessage(1, null, '该礼品已失效! ');
        } elseif ($share_info->current_num >= $share_info->gifts_num) {
            return Api::responseMessage(1, null, '该礼品已被领取完! ');
        }

        //获得收货地址
        // 当前登录用户的默认地址(用户新增或者修改后,前台展示的都是默认地址,用于订单主表的配送信息字段)
        $address_id = (int)$request->input('address_id');

        if ($address_id > 0) {
            $sendee_address = MemberAddress::where('address_id', $address_id)->first();
        }

        // 没有指定收货地址，启用买家的默认地址
        if (empty($sendee_address)) {
            $sendee_address = MemberAddress::where('member_id', $member_id)
                ->where('is_default', 1)->first();
        }

        if (empty($sendee_address)) {
            Log::error('没有指定收货人地址  控制器:OrderController@get_gift_order_add');
            return Api::responseMessage(2, null, '没有指定收货人地址! ');
        }

        //生成订单
        try {
            DB::beginTransaction();

            // 平台订单编号(调用数据库存储过程获取)
            $plat_order_sn = $this->getPlatOrderSn();
            if (!$plat_order_sn) {
                Log::error('获取平台订单编号失败  控制器:OrderController@get_gift_order_add');
                DB::rollBack();
                return Api::responseMessage(2, null, '获取平台订单编号失败! ');

            }

            // 订单配送地址信息
            $sendee_province_id = 0;
            $sendee_city_id = 0;
            $sendee_area_id = 0;
            $sendee_address_id = 0;
            $sendee_address_info = '';

            if ($sendee_address) {
                $province_name = DctArea::find($sendee_address->province_id)->name;            // 省级名称
                $city_name = DctArea::find($sendee_address->city_id)->name;                    // 城市名称
                $area_name = DctArea::find($sendee_address->area_id)->name;                    // 地区名称

                $sendee_province_id = $sendee_address->province_id;                      // 收货人所在省ID
                $sendee_city_id = $sendee_address->city_id;                              // 收货人所在市ID
                $sendee_area_id = $sendee_address->area_id;                              // 收货人所在区ID
                $sendee_address_id = $sendee_address->address_id;                        // 收货人地址ID

                // 收货人地址信息
                $sendee_address_info = '收货人姓名:' . $sendee_address->recipient_name .
                    '  收货人手机号:' . $sendee_address->mobile .
                    '  收货地址:' . $province_name . $area_name . $city_name .
                    '  ' . $sendee_address->address;
            }

            // 组装生成订单数据
            $arr_payment_info = array();
            $payment = serialize($arr_payment_info); //由于领取礼品不需要支付，序列化为空
            $data = [
                'plat_order_sn' => $plat_order_sn,

                //  买家会员信息 即登录者信息
                'member_id' => $member->member_id,
                'member_name' => $member->nick_name,
                'member_email' => $member->email,
                'member_mobile' => $member->mobile,

                // 配送
                'sendee_time_type' => 0,                                          // 配送时间类型 默认:不限
                'sendee_province_id' => $sendee_province_id,                      // 收货人所在省份ID
                'sendee_area_id' => $sendee_area_id,                              // 收货人所在地区ID
                'sendee_city_id' => $sendee_city_id,                              // 收货人所在区县ID
                'sendee_address_id' => $sendee_address_id,                        // 收货人地址ID
                'sendee_address_info' => $sendee_address_info,                    // 收货人地址信息

                // 下单信息
                'plat_order_state' => 2,                                           // 订单状态 1:（已下单）待付款
                'from_media' => 1,                                                 // 下单媒介【0:WEB; 1:mobile】
                'create_time' => time(),                                           // 下单时间【成交时间】(整型时间戳)

                'is_share_gifts' => 0,
                'is_get_gift' => 1,

                'payment' => $payment,
                // 结算金额
                'goods_amount_totals' => $share_info->sku_price,               // 商品结算金额
                'transport_cost_totals' => 0,           // 运费金额
                'order_amount_totals' => $share_info->sku_price,               // 订单总结算价钱
            ];

            // 生成订单
            $order = Order::create($data);
            if (!$order->exists) {
                DB::rollBack();
                return Api::responseMessage(2, null, '获取微信礼品订单生成失败! ');
            }
            $plat_order_id = $order->plat_order_id;
            // ---------------------------------- 3.2 生成订单扩展表 ---------------------------
            // 初始化订单扩展表数据
            $extend_data = array(
                'plat_order_id' => $plat_order_id);

            // 买家留言(未作任何验证,可以为空)
            $message = '';
            if ($request->input('message')) {
                $message = trim($request->input('message') . '');
            }
            $extend_data['order_message'] = $message;
            OrderExtend::create($extend_data);

            // --------------------------------- 3.3 生成订单明细表(订单商品信息) -----------------------------------------
            $obj_sku = GoodsSku::select('sku_id', 'spu_id',
                'sku_name', 'sku_title', 'market_price', 'price', 'groupbuy_price', 'trade_price', 'partner_price',
                'points_limit', 'sku_spec')
                ->where('sku_id', $share_info->sku_id)
                ->first();
            // 如果为空跳过本次循环
            if (!$obj_sku) {
                // 如果数组为空 说明传入的数组sku_id中,平台没有或者未上架
                Log::error('传入的商品信息无效! 控制器:OrderController@get_gift_order_add');
                return Api::responseMessage(2, null, '传入的商品信息无效! ');
            }

            $spu_id = $obj_sku->spu_id;
            $obj_spu = GoodsSpu::where('spu_id', $spu_id)->first();

            $g_data = [
                'order_detail_index' => 1,
                'plat_order_id' => $plat_order_id,

                'sku_id' => $obj_sku['sku_id'],
                'sku_name' => $obj_sku['sku_name'],
                'sku_title' => $obj_sku['sku_title'],

                'sku_image' => $share_info->sku_image,
                'sku_spec' => $obj_sku['sku_spec'],
                'spu_id' => $obj_sku['spu_id'],
                'gc_id' => $obj_spu->gc_id,
                'gc_name' => $obj_spu->gc_id,

                // 商品定价、商品结算价【优惠后价】
                'goods_price' => $share_info->sku_price,
                'settlement_price' => $share_info->sku_price,

                'number' => 1,
            ];

            $order_goods = OrderGoods::create($g_data);
            if (!$order_goods->exists) {
                DB::rollBack();
                Log::error('用户平台订单明细表生成失败  控制器:OrderController@get_gift_order_add');
                return Api::responseMessage(1, null, '保存订单明细出错! ');
            }


            //记录得到礼品的基本信息
            $s_data = [
                'share_gifts_info_id' => $share_gifts_info_id,
                'member_id' => $member_id,
                'plat_order_id' => $plat_order_id,
                'sku_id' => $share_info->sku_id,
                'is_share_sponsor' => 0,
                'create_time' => time(),
                'nick_name' => $member->nick_name,
                'avatar' => $member->avatar,
                'sex' => $member->sex,
                'thanks_content' => $message,
            ];

            $get_gifts_info_id = DB::table('get_gifts_info')->insertGetId($s_data);
            if (!$get_gifts_info_id) {
                Log::error($member->member_name . '用户得到微信礼品的基本信息生成失败  控制器:OrderController@get_gift_order_add');
                DB::rollBack();
                return Api::responseMessage(1, null, '得到微信礼品的信息生成出错! ');
            }

            //更新礼品数量
            DB::table('share_gifts_info')->where('share_gifts_info_id', $share_gifts_info_id)->update(['current_num' => $share_info->current_num + 1]);

            // ---------------------------------- 3.5 保存订单操作日志【下单待付款】 -----------------
            $content = '用户' . $member->member_name . ', 生成了一个得到微信礼品的订单！ 订单编号为:' . $order->plat_order_sn;
            LogInfoFacade::logOrderPlat($plat_order_id, $content, 1, $member->member_id, $member->nick_name);

            // 将奖品订单加入拆单工作队列
            $this->addDismantleOrderJobToQueue($order->plat_order_id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('订单提交失败  控制器:OrderController@get_gift_order_add');
            return Api::responseMessage(1, null, '保存订单出错! ');
        }

        return Api::responseMessage(0, null, '保存订单成功! ');
    }


}