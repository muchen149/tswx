<?php

namespace App\controllers;

use App\facades\Api;
use App\lib\ApiResponseByHttp;
use App\models\article\Document;
use App\models\member\Member;
use App\models\member\MemberOtherAccount;
use App\models\member\MemberShip;
use App\models\member\MemberShipActivity;
use App\models\member\MemberShipCard;
use App\models\order\OrderPrepay;
use App\models\qrcode\TwoDimensionCode;
use App\pro\dao\member\MemberShipDao;
use Carbon\Carbon;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\models\order\Order as PlatOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class MemberShipController extends BaseController
{
    /**
     * 定义返回Http响应, 还是数组返回值
     * @var ApiResponseByHttp|string
     */
    protected $api;

    /**
     * 实例化的时候指定要使用的api类
     * 不指定则使用ApiResponseByHttp类
     * RechargeController constructor.
     * @param string $api
     */
    public function __construct($api = '')
    {
        parent::__construct();
        $this->api = empty($api) ? new ApiResponseByHttp() : $api;
    }

    /**
     * 可以使用的会员卡列表(个人中心 => 更多卡券 => 我的会员卡)
     * @return View
     */
    public function enableCardList(MemberShipDao $memberShipDao,$use_state)
    {
        $member = Auth::user();
        $member_id = $member->member_id;
        if(empty($use_state)){
            $use_state = 1;
        }
        //获取未使用的卡列表
        $cardList = $memberShipDao->getEnableCardByMemberId($member_id,$use_state);

        // 使用的会员卡信息
        $use_card = DB::table('member_ship')->where('member_id', $member_id)->where('use_state', 2)->orderBy('created_at','desc')->first();
        if($use_card){
                $class_arr = $this->getMemberClass();
                $use_card->grade_name = $class_arr[$use_card->grade]['grade_name'];
                $use_card->exp_date = floor((($member->grade_expire_time)-(time()))/86400);
                $use_card->end_time = $member->grade_expire_time;

        }

        $subscribe = $this->getSubscribe($member_id);
        return view('member.membership_card', compact('cardList','use_state','use_card','subscribe','member'));
    }

    /**
     * 扫码获得会员卡后, 立即领取至我的会员卡包
     * @param Request $request
     * @return View
     */
    public function fastGetByScan(Request $request)
    {
        $member_id = $request->input('mid');
        $activity_id = $request->input('aid');
        $card_id = $request->input('cid');

        // 验证会员卡信息合法性
        $data_res = $this->checkMemberShip($member_id, $activity_id, $card_id);
        if (!empty($data_res['code'])) {
            $errorData = array('code' => $data_res['code'], 'message' => $data_res['message']);
            return view("errors.error", compact('errorData'));
        }

        // 放入我的会员卡包
        $result = $this->getMemberShip($member_id, $card_id, $data_res['data']['two_dimension_code'], $plat_order_id = 0);
        if ($result['code']) {
            return view("errors.error", compact($result['message']));
        }

        return redirect('/membership/getCardList/1');
    }

    /**
     * 检查会员卡与二维码使用、激活情况，以及虚拟商品订单的支付等情况
     * @return mixed
     */
    private function checkMemberShip($member_id, $activity_id, $card_id)
    {
        $shipActivity = MemberShipActivity::where('activity_id', $activity_id)->first();

        $shipCard = MemberShipCard::select('card_state', 'two_dimension_code', 'card_id')
            ->where('card_id', $card_id)->first();

        if (!$shipActivity) {
            return array('code' => 50002, 'message' => '会员卡活动不存在');//会员卡活动不存在
        }

        $shipActivity = $shipActivity->toArray();
        if (!$shipCard) {
            return array('code' => 50002, 'message' => '卡密错误');//卡密错误
        }

        $shipCard = $shipCard->toArray();
        if ($shipActivity['activity_state'] == 0) {
            return array('code' => 50002, 'message' => '活动未开启');//活动未开启
        }

        if ($shipCard['card_state'] == 1) {
            return array('code' => 50002, 'message' => '会员卡已被使用');//会员卡已被使用
        }

        if ($shipActivity['start_time'] > time()) {
            return array('code' => 50002, 'message' => '会员卡活动尚未开始');//会员卡活动尚未开始
        }

        if ($shipActivity['end_time'] < time()) {
            return array('code' => 50002, 'message' => '会员卡活动已结束');//会员卡活动已结束
        }

        $orderDetail = DB::table('order')
            ->select('order.plat_order_state', 'order.plat_order_id', 'order.member_id', 'order.plat_order_sn')
            ->join('order_extend', 'order_extend.plat_order_id', '=', 'order.plat_order_id')
            ->where('order_extend.two_dimension_code', $shipCard['two_dimension_code'])
            ->where('order.delete_state', 0)->where('order.lock_state', 0)
            ->first();
        if ($orderDetail && $orderDetail->plat_order_state != 2) {
            return array('code' => 50002, 'message' => '会员卡订单未支付');//会员卡订单未支付
        }

        if ($orderDetail && $orderDetail->member_id != $member_id) {
            return array('code' => 50002, 'message' => '与订单付款人不一致，不能操作');//与订单付款人不一致，不能操作
        }

        $member_class = $this->getMemberClass();

        $data = [
            'member_id' => $member_id,
            'activity_id' => $shipActivity['activity_id'],
            'activity_name' => $shipActivity['activity_name'],
            'activity_images' => $shipActivity['activity_images'],
            'card_id' => $shipCard['card_id'],
            'grade_name' => $member_class[$shipActivity['grade']]['grade_name'],
            'exp_date' => $shipActivity['exp_date'],
            'exp_date_name' => $shipActivity['exp_date_name'],
            'plat_order_id' => !empty($orderDetail) ? $orderDetail->plat_order_id : 0,
            'two_dimension_code' => $shipCard['two_dimension_code'],
        ];

        return array('code' => 0, 'message' => '', 'data' => $data);
    }

    /**
     * 扫描二维码, 将得到的会晕卡立即领取进我的会员卡包
     * @param int $member_id 会员用户id
     * @param int $card_id 会员卡id
     * @param int $two_dimension_code 二维码
     * @param int $plat_order_id 如果是平台订单购买的二维码, 需要指定平台订单id
     * @return array
     */
    private function getMemberShip($member_id, $card_id, $two_dimension_code, $plat_order_id = 0)
    {
        $time = time();
        try {
            DB::beginTransaction();

            // 该二维码来源于订单支付购买, 如果是更新订单状态
            if (!empty($plat_order_id)) {
                $order = PlatOrder::where('plat_order_id', $plat_order_id)->first();
                $order->plat_order_state = 9;//已完成
                $order->arrival_time = $time;
                $order->save();
            }

            // 会员卡改为已使用
            $card = MemberShipCard::where('card_id', $card_id)->first();
            $card->card_state = 1;
            $card->updated_time = $time;
            $card->save();

            // 二维码扫码次数 +1
            $code = TwoDimensionCode::where('two_dimension_code', $two_dimension_code)->first();
            $code->scan_count += 1;
            $code->updated_time = $time;
            $code->save();

            // 插入二维码扫描记录
            DB::table('two_dimension_scan_log')->insert([
                'member_id' => $member_id,
                'two_dimension_code' => $two_dimension_code,
                'result_code' => '0',
                'result_message' => '',
                'location_id' => 0,
                'operate_time' => time()
            ]);

            // 会员卡活动信息
            $activity = DB::table('membership_activity')
                ->where('activity_id', $card->activity_id)
                ->where('supplier_id', 0)
                ->where('use_type', 1)
                ->first();

            // 把领取的会员卡存入 member_ship 表中(以后在我的卡包 => 会员卡 => 激活使用)
            DB::table('member_ship')->insert([
                'member_id' => $member_id,
                'activity_id' => $activity->activity_id,
                'activity_name' => $activity->activity_name,
                'card_id' => $card_id,
                'trade_no' => 0,
                'pay_sn' => 0,
                'grade' => $activity->grade,
                'exp_date' => $activity->exp_date,
                'exp_date_code' => $activity->exp_date_code,
                'exp_date_name' => $activity->exp_date_name,
                'activity_images' => $activity->activity_images,
                'start_time' => $activity->start_time,
                'end_time' => $activity->end_time,
                'price' => $activity->price,
                'source_type' => 1,
                'use_state' => 1,
                'created_at' => $time,
                'updated_at' => 0,
                'close_time' => $activity->close_time,
            ]);

            DB::commit();
            return array('code' => 0, 'message' => '', 'data' => '');
        } catch (\Exception $e) {
            DB::rollBack();
            return array('code' => 50002, 'message' => '立即领取失败, 请稍后再试');
        }
    }

    /**
     * 扫码详情展示
     * @param int $member_id 用户id
     * @param int $activity_id 会员活动id
     * @param int $card_id 会员卡id
     * @return array
     */
    public function scan($member_id, $activity_id, $card_id)
    {
        // 当前用户
        $member = Member::where('member_id', $member_id)->first()->toArray();

        // 检查会员卡与二维码使用、激活情况，以及虚拟商品订单的支付等情况
        $card_res = $this->checkMemberShip($member_id, $activity_id, $card_id);
        if (!empty($card_res['code'])) {
            return array('code' => $card_res['code'], 'message' => $card_res['message']);
        }

        // 活动图片
        $data = [
            'activity_images' => $this->getFullPictureUrl($card_res['data']['activity_images']),
            'membership_id' => 0,
            'member' => $member,
        ];

        $data = array_merge($card_res['data'], $data);

        return array('code' => 0, 'message' => '', 'data' => $data);
    }

    /**
     * 绑卡
     * @return mixed
     */
    public function submit($member_id, $activity_id, $card_id)
    {
        $member = Member::where('member_id', $member_id)->first()->toArray();  //当前用户

        //检查会员卡与二维码使用、激活情况，以及虚拟商品订单的支付等情况
        $card_res = $this->checkMemberShip($member_id, $activity_id, $card_id);
        if (!empty($card_res['code'])) {
            return array('code' => $card_res['code'], 'message' => $card_res['message']);
        }

        //使用会员卡，主要包括：商品放入我的礼包、订单状态修改为已完成、二维码与会员卡使用状态修改
        $use_res = $this->useMemberShip($card_res['data']);
        if (!empty($use_res['code'])) {
            return array('code' => $use_res['code'], 'message' => $use_res['message']);
        }

        $use_res['data']['activity_images'] = $this->getFullPictureUrl($use_res['data']['activity_images']);
        $use_res['data']['member'] = $member;

        return array('code' => 0, 'message' => '', 'data' => $use_res['data']);//绑卡成功
    }

    /**
     * 使用获取的会员卡
     * @param $membership_id
     * @param MemberShipDao $memberShipDao
     * @return mixed
     */
    public function useMemberShip($membership_id, MemberShipDao $memberShipDao)
    {
        $member = Auth::user();

        // 使用的会员卡信息
        $member_ship = DB::table('member_ship')
            ->where('membership_id', $membership_id)
            ->where('member_id', $member->member_id)
            ->where('use_state', 1)
            ->first();

        // 定义错误信息
        $errorData = [
            'message' => '会员卡激活使用失败, 请稍后重试',
            'url' => '/personal/index'
        ];

        // 如果存在指定会员卡
        if ($member_ship) {
            // 当前会员等级大于会员卡等级, 不可使用
            if ($member_ship->grade < $member->grade) {
                $errorData['message'] = '当前会员等级大于该会员卡等级, 不可使用';
            } else {
                // 使用会员卡, 提升用户等级, 成功跳转使用页面
                $flag = $memberShipDao->useCardByMemberShipId($membership_id, $member->member_id);
                if ($flag) {
                    return redirect('/membership/getCardList/2');
                }else{
                    return redirect('/membership/getCardList/-1');
                }
            }
        } else {
            $errorData['message'] = '该会员卡信息不存在或已被使用';
        }

        return view('errors.error', compact('errorData'));
    }

    /**
     * 立即绑定 调用支付接口 成功支付后回调url执行该方法
     * 发放会员卡
     * 该方法认为在线支付是安全的(以此完成自动绑定)
     *
     * @return mixed
     */
    public function sendMemberShip($plat_order, $activitys)
    {
        // 个人中心 => 立即绑定会员卡
        foreach ($activitys as $activity) {
            $data = [
                'member_id' => $plat_order->member_id,
                'activity_id' => $activity->activity_id,
                'card_id' => 0,
                'plat_order_id' => $plat_order->plat_order_id,
                'two_dimension_code' => 0,
            ];
            // 使用礼券
            $result = $this->useMemberShip($data);
            if (!empty($result['code'])) {
                return $this->api->responseMessage($result['code'], null, $result['message']);
            }
        }
        return $this->api->responseMessage(0, null, '绑定成功');
    }

    /**
     * 点击页面(开通会员)缴费项目, 跳转至立即支付页面(然后支付json数据通过ajax获取)
     * @param $activity_id
     * @return View
     */
    public function buyMember(Request $request)
    {
        $activity_id = (int)$request->input('id');
        $activity = MemberShipActivity::find($activity_id);
        if (!$activity->exists) {
            Log::error('购买会员失败, 该会员缴费项目不存在 => 控制器:MemberController@buyMember');
            $errorData = [
                'message' => '购买会员失败, 该会员缴费项目不存在, 详情请咨询客服'
            ];
            return view("errors.error", compact('errorData'));
        }
        if($activity->exp_date_name == '日'){
            $end_time = date("Y-m-d", strtotime("+1 day", time()));
        }elseif($activity->exp_date_name == '月'){
            $end_time = date("Y-m-d", strtotime("+1 month", time()));
        }elseif($activity->exp_date_name == '年'){
            $end_time = date("Y-m-d", strtotime("+1 year", time()));
        }
        $class_arr = $this->getMemberClass();
        $activity->grade_name = $class_arr[$activity->grade]['grade_name'];
        $activity->activity_images = $this->img_domain.'/'.$activity->activity_images;
        $document = Document::where('doc_code', 'sdgjhyfwxy')->first();

        return view('member.show_member_pay_info', compact('activity', 'document','end_time'));
    }

    /**
     * 线上会员卡购买立即支付(现在是ajax调用)
     * @param Request $request
     * @return Api
     */
    public function getPayJosnForMemberShipByajax(Request $request)
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            return Api::responseMessage(50000, '', '登陆信息失效，请重新登陆');
        }

        // 开通使用的付费项目是线上会员活动
        $activity_id = $request->input('activity_id');
        $activity = DB::table('membership_activity')
            ->where('activity_id', $activity_id)
            ->where('supplier_id', 0)
            ->where('use_type', 2)
            ->first();

        // 现在用微信支付，需要微信账号, 后期添加支付类型再完善
        $open_id = MemberOtherAccount::where('member_id', $member_id)->where('account_type', 2)->value('account_id');
        if (empty($open_id)) {
            Log::error('买家微信账户不存在 无法申请微信支付  控制器:MemberShipController@buyMemberShip');
            return Api::responseMessage(50000, '', '微信账户信息获取失败, 无法为您申请微信支付, 请重新登录');
        }

        // 支付后通知回调
        $wxPayCallbackUrl = $request->server('HTTP_ORIGIN') . '/wx/pay/callbackWithMembership';

        // 生成微信支付订单信息
        $out_trade_no = time() . date('YmdHis') . rand(pow(10, 7), pow(10, 8) - 1);
        $trade_no = date('YmdHis') . rand(pow(10, 7), pow(10, 8) - 1) . time();
        $attributes = [
            'openid' => $open_id,
            'trade_type' => 'JSAPI',
            'body' => '水丁平台 - 会员等级',
            'detail' => '会员等级',
            'out_trade_no' => $out_trade_no,
            'total_fee' => (int)(bcmul($activity->price, 100)),
            'time_start' => date('YmdHis'),
            'time_expire' => date("YmdHis", strtotime("+15 minute")),   // 交易结束时间 定义15分钟后失效
            'notify_url' => $wxPayCallbackUrl,  // 支付结果通知网址，如果不设置则会使用配置里的默认地址
        ];

        // 创建微信支付订单、微信预支付记录
        $wx_order = new Order($attributes);
        $payment = (new Application(config('wechat')))->payment;
        $result = $payment->prepare($wx_order);

        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {

            $prepayId = $result->prepay_id;
            $content = '1、生成会员等级购买交易单，编号为:' . $trade_no . '的平台订单预付单, 交易起始时间为:'
                . date('Y-m-d H:i:s', strtotime($attributes['time_start'])) .
                '; 交易结束时间为:' . date('Y-m-d H:i:s', strtotime($attributes['time_expire'])) .
                '; 交易有效时间为15分钟；';

            // 创建平台预支付订单(有个问题, 未支付且未过期的预支付订单没有被利用)
            OrderPrepay::create(array(
                'pay_mode_code' => 'wxpay',
                'pay_mode_name' => '微信支付',
                'prepay_id' => $prepayId,
                'out_trade_no' => $attributes['out_trade_no'],
                'time_start' => $attributes['time_start'],
                'time_expire' => $attributes['time_expire'],
                'settlement_total_fee' => $attributes['total_fee'],
                'member_id' => $member_id,
                'openid' => $open_id,
                'spbill_create_ip' => Api::getIp(),
                'trade_state' => 'NOTPAY',
                'trade_state_desc' => $content
            ));

            // 生成线上会员预付订单记录, 等支付成功后
            MemberShip::create([
                'member_id' => $member_id,
                'activity_id' => $activity_id,
                'activity_name' => $activity->activity_name,
                'card_id' => 0,
                'trade_no' => $trade_no,
                'pay_sn' => $out_trade_no,
                'grade' => $activity->grade,
                'exp_date' => $activity->exp_date,
                'exp_date_code' => $activity->exp_date_code,
                'exp_date_name' => $activity->exp_date_name,
                'activity_images' => $activity->activity_images,
                'start_time' => $activity->start_time,
                'end_time' => $activity->end_time,
                'price' => $activity->price,
                'source_type' => 2, // 来源类型: 2：线上开通购买
                'use_state' => 0,   // 使用状态: 0:暂不可使用(预付单, 暂不可使用)
                'created_at' => time(),
                'updated_at' => 0
            ]);
        } else {
            Log::error('预付订单生成失败, 返回信息为:' . $result->toJson() . '控制器:MemberShipController@buyMemberShip');
            return Api::responseMessage(50000, '', '系统异常, 请稍后重试');
        }

        $wx_json = $payment->configForPayment($prepayId, false);
        return Api::responseMessage(0, ['wx_json' => $wx_json]);
    }

    /**
     * 线上免费领取会员卡(现在是ajax调用)
     * @param Request $request
     * @return Api
     */
    public function getExpJosnForMemberShip(Request $request)
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            return Api::responseMessage(50000, '', '登陆信息失效，请重新登陆');
        }

        // 免费领取是线上会员活动
        $grade = $request->input('grade');
        $ms = MemberShip::where('grade', $grade)->where('member_id', $member_id)
                        ->where('source_type',2)->first();
        $activity = DB::table('membership_activity')
            ->where('grade', $grade)
            ->where('use_type', 2)
            ->first();
        $grade_expire_time = time() + 31 * 24 * 60 * 60;
        // 生成线上会员免费体验订单记录,没有支付
        if(!$ms){
            $memnerShip = MemberShip::create([
                'member_id' => $member_id,
                'activity_id' => $activity->activity_id,
                'activity_name' => $activity->activity_name,
                'card_id' => 0,
                'trade_no' => 0,
                'pay_sn' => 0,
                'grade' => $activity->grade,
                'exp_date' => $activity->exp_date,
                'exp_date_code' => $activity->exp_date_code,
                'exp_date_name' => $activity->exp_date_name,
                'activity_images' => $activity->activity_images,
                'start_time' => $activity->start_time,
                'end_time' => $activity->end_time,
                'price' => 0,
                'source_type' => 2, // 来源类型: 2：线上开通购买
                'use_state' => 2,   // 使用状态: 0:暂不可使用(预付单, 暂不可使用)
                'created_at' => time(),
                'updated_at' => 0,
            ]);
            if($memnerShip){
                Member::where('member_id',$member_id)->update(['exp_member'=>1,'grade'=>$activity->grade,'grade_expire_time'=>$grade_expire_time]);
                return Api::responseMessage(0,'','领取成功');

            }
        }
        return Api::responseMessage(0,'','不可重复领取');

    }

    /**
     * 会员卡支付回调函数(根据支付情况回填信息)
     * @return mixed
     */
    public function wxPayCallback()
    {
        $response = app('wechat')->payment->handleNotify(function ($notify, $successful) {

            $out_trade_no = $notify->out_trade_no;
            $membership = MemberShip::where('pay_sn', $out_trade_no)->first();

            if ($successful) {
                // 1、回填订单预支付表信息
                $order_prepay = OrderPrepay::where('out_trade_no', $out_trade_no)->first();

                // 格式化支付完成时间
                $str_pay_rmb_time = date('Y-m-d H:i:s', strtotime($notify->time_end));
                $prepay_content = '2、完成对预付单的支付, 支付完成时间为:' . $str_pay_rmb_time . ', 预付单状态更新为: "SUCCESS"。  ';

                $order_prepay->transaction_id = $notify->transaction_id;        // 微信支付订单号
                $order_prepay->cash_fee = $notify->cash_fee;                    // 现金支付金额
                $order_prepay->bank_type = $notify->bank_type;                  // 付款银行
                $order_prepay->time_end = $notify->time_end;                    // 支付完成时间
                $order_prepay->trade_state = 'SUCCESS';                         // 支付状态为支付成功
                $order_prepay->trade_state_desc .= $prepay_content;             // 交易状态描述
                $order_prepay->is_update_order = 1;                             // 是否已更新订单(0:否;1:是;)
                $order_prepay->is_close = 1;                                    // 是否已关闭(0:否;1:是;)
                $order_prepay->save();

                // 修改会员购买记录状态为 有效记录(use_state=0)
                $membership->use_state = 2;
                $membership->updated_at = time();
                $membership->save();

                // 对应提升等级的用户
                $member = Member::find($membership->member_id);

                // Carbon方法的名字, 转换后如, 'year' => addYear、'month' => addMonth、 'day' => addDay // Carbon::now()->addMonth(1)->timestamp;
                $method_str = 'add' . ucfirst($membership->exp_date_code);

                // 同等级延长会员时间
                if ($membership->grade == $member->grade) {
                    $member->grade_expire_time = $member->grade_expire_time > time() ?
                        Carbon::createFromTimestamp($member->grade_expire_time)->$method_str($membership->exp_date)->timestamp :
                        Carbon::now()->$method_str($membership->exp_date)->timestamp;
                    // 会员等级高于或低于会员卡等级两种情况(如果要限制现有会员等级高于会员卡等级使用的话, 在controller层做限制), 覆盖之前等级及过期时间
                } else {
                    $member->grade = $membership->grade;
                    $member->grade_expire_time = Carbon::now()->$method_str($membership->exp_date)->timestamp;
                }

                // 保存
                $member->save();

            } else {
                Log::warning('用户支付失败, 交易单号为:' . $membership->trade_no . ' => 控制器:MemberShipController@wxPayCallback');
                return false;
            }

            // 告诉微信已接收到支付通知, 并处理完成 false再稍后还会重新得到微信的支付通知
            return true;
        });

        return $response;
    }
}
