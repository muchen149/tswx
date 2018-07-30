<?php

namespace App\controllers\ys;

use App\facades\Api;
use App\lib\ApiResponseByHttp;
use App\models\dct\DctBusineType;
use App\models\marketing\RechargeActivity;
use App\models\member\Member;
use App\models\member\MemberBalanceCash;
use App\models\member\MemberBalanceLog;
use App\models\member\MemberGiftCoupon;
use App\models\member\MemberGiftCouponGoods;
use App\models\member\MemberRechargeCard;
use App\models\member\MemberWalletLog;
use App\models\member\MemberOtherAccount;
use App\models\order\OrderExtend;
use App\models\qrcode\TwoDimensionCode;
use App\models\marketing\RechargeCard;
use App\models\marketing\RechargeActivityGoods;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\models\order\OrderPrepay;
use App\models\order\Order as PlatOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\models\article\Document;
use App\models\goods\GoodsSku;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;


class MemberRechargeController extends BaseController
{
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
     * 用户中心-》卡余额总账
     * 传入参数：
     * @return mixed
     */
    public function myRechargeCard()
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
            $member = Member::where('member_id', $member_id)->first()->toArray();  //当前用户
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }

        $totalCashAmount = 0;

        //获取用户充值卡列表，同时计算最大可提现金额
        $rechargeCardList = MemberRechargeCard::select('rechargecard_id', 'total_amount', 'balance_amount', 'balance_freeze', 'balance_available', 'balance_cash_type', 'month_total', 'month_available', 'created_at', 'member_id')
            ->where('member_id', $member_id)->where('use_state', 0)->orderBy('created_at', 'desc')->get();
        if ($rechargeCardList) {
            $rechargeCardList = $rechargeCardList->toArray();
        }
        foreach ($rechargeCardList as &$card) {
            $card = $this->calcMaxCashAmount($card);
            $totalCashAmount += $card['max_cash_amount'];
        }

        //获取京东E卡列表


        //获取卡列表
        //获取用户充值卡列表，同时计算最大可提现金额
        $rechargeCardList = MemberRechargeCard::select('rechargecard_id', 'activity_id', 'total_amount', 'balance_amount', 'balance_freeze', 'balance_available', 'balance_cash_type', 'month_total', 'month_available', 'created_at', 'member_id')
            ->where('member_id', $member_id)->where('use_state', 0)->orderBy('created_at', 'desc')->get();
        if ($rechargeCardList) {
            $rechargeCardList = $rechargeCardList->toArray();
        }
        $colorArr = ['color1', 'color2', 'color3'];
        $i = 0;
        foreach ($rechargeCardList as $k => &$card) {
            $card = $this->calcMaxCashAmount($card);
            $activity = RechargeActivity::select('activity_images')->find($card['activity_id'])->toArray();
            $card['activity_images'] = $this->getFullPictureUrl($activity['activity_images']);
            $card['create_time'] = date('Y-m-d H:i:s', $card['created_at']);
            if ($i >= 3) {
                $i = 0;
            }
            $card['color'] = $colorArr[$i];
            $i++;
        }


        return view('marketing.my_recharge_card', compact('totalCashAmount', 'member','rechargeCardList'));
    }

    /**
     * 充值卡列表 （用于充值）
     * 传入参数：
     * @return mixed
     */
    public function rechargeCardList()
    {
        // 当前登录用户信息
        $member = Auth::user();
        if (!$member) {
            // 当前用户没有登录
            return redirect('/oauth');
        }

        $db_prefix = config('database')['connections']['mysql']['prefix'];

        //获取充值卡列表
        $sql = "SELECT gsk.spu_id,gsk.sku_name,gsk.price,gsk.market_price,gsk.groupbuy_price,gsk.trade_price,gsk.sku_id,
                gsk.partner_price,concat('" . $this->img_domain . "/',gsp.main_image) as main_image,ra.activity_images,ra.total_amount,ra.balance_amount,ra.balance_cash_type,ra.activity_id
                FROM " . $db_prefix . "goods_sku AS gsk
                INNER JOIN " . $db_prefix . "goods_spu AS gsp ON gsp.spu_id=gsk.spu_id
                INNER JOIN " . $db_prefix . "recharge_activity AS ra ON ra.sku_id=gsk.sku_id
                WHERE gsp.state=1 AND gsk.use_state=1 AND ra.activity_state=1 AND ra.start_time <= " . time() . " AND ra.end_time >= " . time();
        $vrgoodsList = DB::select($sql);

        return view('marketing.recharge_card_list', compact('vrgoodsList'));
    }

    /**
     * 用户中心-》账户详情 ：充值卡列表、卡余额变更明细
     * 传入参数：
     * @return mixed
     */
    public function rechargeAccountDetail()
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }

        //获取用户充值卡列表，同时计算最大可提现金额
        $rechargeCardList = MemberRechargeCard::select('rechargecard_id', 'activity_id', 'total_amount', 'balance_amount', 'balance_freeze', 'balance_available', 'balance_cash_type', 'month_total', 'month_available', 'created_at', 'member_id')
            ->where('member_id', $member_id)->where('use_state', 0)->orderBy('created_at', 'desc')->get();
        if ($rechargeCardList) {
            $rechargeCardList = $rechargeCardList->toArray();
        }
        $colorArr = ['color1', 'color2', 'color3'];
        $i = 0;
        foreach ($rechargeCardList as $k => &$card) {
            $card = $this->calcMaxCashAmount($card);
            $activity = RechargeActivity::select('activity_images')->find($card['activity_id'])->toArray();
            $card['activity_images'] = $this->getFullPictureUrl($activity['activity_images']);
            $card['create_time'] = date('Y-m-d H:i:s', $card['created_at']);
            if ($i >= 3) {
                $i = 0;
            }
            $card['color'] = $colorArr[$i];
            $i++;
        }
        //获得商城业务字典
        $busine_arr = [];
        $busine_code = DctBusineType::select('code_id', 'code_name')->where('is_use', 1)->get()->toArray();
        foreach ($busine_code as $item) {
            $busine_arr[$item['code_id']] = $item['code_name'];
        }

        //当前用户卡余额明细
        $logs = MemberBalanceLog::select('create_time', 'av_amount', 'busine_type', 'busine_content', 'realtime_balance')
            ->where('member_id', Auth::user()->member_id)
            ->where('av_amount', '<>', 0)
            ->orderBy('create_time', 'desc')->get();
        if ($logs) {
            $logs = $logs->toArray();
            foreach ($logs as $k => &$log) {
                $log['create_time'] = date('Y-m-d H:i:s', $log['create_time']);
                $log['av_amount'] = $log['av_amount'] > 0 ? '+' . $log['av_amount'] : $log['av_amount'];
                $log['color'] = $log['av_amount'] > 0 ? 'red' : 'green';
                $log['busine_type'] = $busine_arr[$log['busine_type']];
            }
        }
        return view('marketing.recharge_account', compact('rechargeCardList', 'logs'));
    }

    /**
     * 充值卡详情
     * 传入参数：
     * @return mixed
     */
    public function rechargeCardDetail($cardId)
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
            $member = Member::where('member_id', Auth::user()->member_id)->first()->toArray();  //当前用户
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }

        //获取用户单个充值卡详情，同时计算最大可提现金额
        $rechargeCardDetail = MemberRechargeCard::select('rechargecard_id', 'activity_id', 'total_amount', 'balance_amount', 'balance_freeze', 'balance_available', 'balance_cash_type', 'month_total', 'month_available', 'created_at', 'member_id')
            ->where('member_id', $member_id)->where('use_state', 0)->where('rechargecard_id', intval($cardId))->first();
        if ($rechargeCardDetail) {
            $rechargeCardDetail = $rechargeCardDetail->toArray();
            $rechargeCardDetail['create_time'] = date('Y-m-d H:i:s', $rechargeCardDetail['created_at']);
            $rechargeCardDetail = $this->calcMaxCashAmount($rechargeCardDetail);
            //活动图片
            $activity = RechargeActivity::select('activity_images')->find($rechargeCardDetail['activity_id'])->toArray();
            $rechargeCardDetail['activity_images'] = $this->getFullPictureUrl($activity['activity_images']);
        } else {
            // 充值卡不存在
            $errorData = array('code' => 50002, 'message' => '充值卡不存在', 'url' => url('/personal/index'));
            return view("errors.error", compact('errorData'));
        }
        return view('marketing.recharge_card_detail', compact('rechargeCardDetail', 'member'));
    }

    /**
     * 扫码详情展示
     * @return mixed
     */
    public function scan($member_id, $activity_id, $card_id)
    {
        $db_prefix = config('database')['connections']['mysql']['prefix'];

        //检查充值卡与二维码使用、激活情况，以及虚拟商品订单的支付等情况
        $card_res = $this->checkRechargeCard($member_id, $activity_id, $card_id);
        if (!empty($card_res['code'])) {
            return array('code' => $card_res['code'], 'message' => $card_res['message']);
        }
        //活动图片
        $activity = RechargeActivity::select('activity_images')->find($card_res['data']['activity_id'])->toArray();

        $data = [
            'max_cash_amount' => $card_res['data']['balance_cash_type'] == 0 ? 0 : floatval($card_res['data']['balance_amount']),
            'activity_images' => $this->getFullPictureUrl($activity['activity_images']),
            'end_time' => date('Y-m-d', time() + 365 * 24 * 3600),
        ];
        $act_goods = RechargeActivityGoods::select('sku_id', 'sku_name', 'sku_title', 'sku_image', 'price', 'number')
            ->where('activity_id', $card_res['data']['activity_id'])
            ->where('use_state', 1)->orderBy('goods_index', 'desc')->get();
        if($act_goods){
            $act_goods = $act_goods->toArray();
            foreach($act_goods as &$item){
                $item['sku_image'] = $this->getFullPictureUrl($item['sku_image']);
            }
            $data['goods'] = $act_goods;
            $data['goods_num'] = count($act_goods);
        }
        $data = array_merge($data, $card_res['data']);

        return array('code' => 0, 'message' => '', 'data' => $data);
    }

    /**
     * 充值
     * @return mixed
     */
    public function submit($member_id, $activity_id, $card_id)
    {
        //检查充值卡与二维码使用、激活情况，以及虚拟商品订单的支付等情况
        $card_res = $this->checkRechargeCard($member_id, $activity_id, $card_id);
        if (!empty($card_res['code'])) {
            return array('code' => $card_res['code'], 'message' => $card_res['message']);
        }

        //使用充值卡，主要包括：余额充值、商品放入我的礼包、订单状态修改为已完成、二维码与充值卡使用状态修改
        $use_res = $this->useRechargeCard($card_res['data']);
        if (!empty($use_res['code'])) {
            return array('code' => $use_res['code'], 'message' => $use_res['message']);
        }
        return array('code' => 0, 'message' => '', 'data' => $use_res['data']);//充值成功
    }

    /**
     * 充值
     * @return mixed
     */
    public function bind(Request $request)
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            return Api::responseMessage(50000, '', '登陆信息失效，请重新登陆');
        }

        $activity_id = $request->input('activity_id');
        $card_id = $request->input('card_id');
        if (!$activity_id || !$card_id) {
            return Api::responseMessage(50002, '', '参数格式错误');
        }

        //检查充值卡与二维码使用、激活情况，以及虚拟商品订单的支付等情况
        $card_res = $this->checkRechargeCard($member_id, $activity_id, $card_id);
        if (!empty($card_res['code'])) {
            return Api::responseMessage($card_res['code'], '', $card_res['message']);
        }

        //使用充值卡，主要包括：余额充值、商品放入我的礼包、订单状态修改为已完成、二维码与充值卡使用状态修改
        $use_res = $this->useRechargeCard($card_res['data']);
        if (!empty($use_res['code'])) {
            return Api::responseMessage($use_res['code'], '', $use_res['message']);
        }
        return Api::responseMessage(0, $use_res['data']);//充值成功
    }

    /**
     * 检查充值卡与二维码使用、激活情况，以及虚拟商品订单的支付等情况
     * @return mixed
     */
    private function checkRechargeCard($member_id, $activity_id, $card_id)
    {
        $rechargeActivity = RechargeActivity::select('total_amount', 'activity_state', 'activity_id', 'goods_amount', 'balance_amount', 'balance_cash_type', 'month_total', 'start_time', 'end_time', 'sale_num', 'exchange_num', 'card_amount', 'grade')
            ->where('activity_id', $activity_id)->first();
        $rechargeCard = RechargeCard::select('card_state', 'two_dimension_number_code', 'two_dimension_code', 'card_id')
            ->where('card_id', $card_id)->first();
        if (!$rechargeActivity) {
            return array('code' => 50002, 'message' => '充值活动不存在');//充值活动不存在
        }
        $rechargeActivity = $rechargeActivity->toArray();
        if (!$rechargeCard) {
            return array('code' => 50002, 'message' => '卡密错误');//卡密错误
        }
        $rechargeCard = $rechargeCard->toArray();
        if ($rechargeActivity['activity_state'] == 0) {
            return array('code' => 50002, 'message' => '活动未开启');//活动未开启
        }
        if ($rechargeCard['card_state'] == 1) {
            return array('code' => 50002, 'message' => '充值卡已被充值');//充值卡已被充值
        }
        if ($rechargeActivity['start_time'] > time()) {
            return array('code' => 50002, 'message' => '充值卡活动尚未开始');//充值卡活动尚未开始
        }
        if ($rechargeActivity['end_time'] < time()) {
            return array('code' => 50002, 'message' => '充值卡活动已结束');//充值卡活动已结束
        }
        if ($rechargeActivity['card_amount'] - $rechargeActivity['sale_num'] < 1 || $rechargeActivity['card_amount'] - $rechargeActivity['exchange_num'] < 1) {
            return array('code' => 50002, 'message' => '库存不足');//库存不足
        }

        $orderDetail = DB::table('order')->select('order.plat_order_state', 'order.plat_order_id', 'order.member_id', 'order.plat_order_sn')
            ->join('order_extend', 'order_extend.plat_order_id', '=', 'order.plat_order_id')
            ->where('order_extend.two_dimension_code', $rechargeCard['two_dimension_code'])
            ->where('order.delete_state', 0)->where('order.lock_state', 0)
            ->first();
        if ($orderDetail && $orderDetail->plat_order_state < 2) {
            return array('code' => 50002, 'message' => '充值卡订单未支付');//充值卡订单未支付
        }
        if ($orderDetail && $orderDetail->member_id != $member_id) {
            return array('code' => 50002, 'message' => '与订单付款人不一致，不能操作');//与订单付款人不一致，不能操作
        }

        $data = [
            'member_id' => $member_id,
            'activity_id' => $rechargeActivity['activity_id'],
            'card_id' => $rechargeCard['card_id'],
            'plat_order_id' => !empty($orderDetail) ? $orderDetail->plat_order_id : 0,
            'total_amount' => $rechargeActivity['total_amount'],
            'goods_amount' => $rechargeActivity['goods_amount'],
            'balance_amount' => $rechargeActivity['balance_amount'],
            'balance_cash_type' => $rechargeActivity['balance_cash_type'],
            'month_total' => $rechargeActivity['month_total'],
            'grade' => $rechargeActivity['grade'],
            'two_dimension_code' => $rechargeCard['two_dimension_code'],
            'pay_sn' => date('YmdHis') . sprintf('%04d', mt_rand(1000, 9999)),
        ];

        return array('code' => 0, 'message' => '', 'data' => $data);
    }

    /**
     * 立即充值 调用支付接口 成功支付后回调url执行该方法
     * 发放充值卡
     * 该方法认为在线支付是安全的(以此完成自动充值)
     *
     * @return mixed
     */
    public function sendRechargeCard($plat_order, $activitys)
    {
        //个人中心->立即充值 不需要关联充值卡
        foreach ($activitys as $activity) {
            $data = [
                'member_id' => $plat_order->member_id,
                'activity_id' => $activity->activity_id,
                'card_id' => 0,
                'plat_order_id' => $plat_order->plat_order_id,
                'total_amount' => $activity->total_amount,
                'grade' => $activity->grade,
                'goods_amount' => $activity->goods_amount,
                'balance_amount' => $activity->balance_amount,
                'balance_cash_type' => $activity->balance_cash_type,
                'month_total' => $activity->month_total,
                'two_dimension_code' => 0,
                'pay_sn' => date('YmdHis') . sprintf('%04d', mt_rand(1000, 9999)),
            ];
            //充值充值卡
            $result = $this->useRechargeCard($data);
            if (!empty($result['code'])) {
                return $this->api->responseMessage($result['code'], null, $result['message']);
            }
        }
        return $this->api->responseMessage(0, null, '充值成功');
    }

    /**
     * 使用充值卡，主要包括：余额充值、我的礼券、我的充值卡、订单状态修改为已完成、二维码与充值卡使用状态修改
     * @return mixed
     */
    public function useRechargeCard($data)
    {
        extract($data);
        $time = time();
        try {
            Log::notice('充值开始');
            DB::beginTransaction();

            //商品放入我的礼包
            $this->pushRechargeGoods($member_id, $activity_id, $card_id);

            Log::notice('商品放入我的礼包完成');

            if (floatval($data['balance_amount']) > 0) {
                //我的充值卡插入记录
                $insert_arr = [
                    'card_id' => $card_id,
                    'order_id' => !empty($plat_order_id) ? $plat_order_id : 0,
                    'member_id' => $member_id,
                    'activity_id' => $activity_id,
                    'total_amount' => $total_amount,
                    'goods_amount' => $goods_amount,
                    'balance_amount' => $balance_amount,
                    'balance_available' => $balance_amount,
                    'balance_cash_type' => $balance_cash_type,
                    'month_total' => $month_total,
                    'month_available' => $month_total,
                    'created_at' => $time,
                ];
                $rechargecard_id = DB::table('member_rechargecard')->insertGetId($insert_arr);
                $data['rechargecard_id'] = $rechargecard_id;

                //余额充值
                $data['busine_id'] = $rechargecard_id;
                MemberBalanceLog::changeBalance('KYECZ', $data);
                Log::notice('余额明细变更完成');
            }

            //修改会员等级
            $member = Member::find($member_id);
            $member->grade = $grade;
            $member->save();

            if (!empty($plat_order_id)) {
                //订单状态修改
                $order = PlatOrder::where('plat_order_id', $plat_order_id)->first();
                $order->plat_order_state = 9;//已完成
                $order->arrival_time = $time;
                $order->save();
                Log::notice('订单修改完成');
            }

            //充值卡、二维码状态修改
            if (!empty($card_id) && !empty($two_dimension_code)) {
                $card = RechargeCard::where('card_id', $card_id)->first();
                $card->card_state = 1;//已使用
                $card->updated_time = $time;
                $card->save();

                $card = TwoDimensionCode::where('two_dimension_code', $two_dimension_code)->first();
                $card->scan_count += 1;//扫描次数+1
                $card->updated_time = $time;
                $card->save();

                //插入二维码扫描记录
                $insert_arr = [
                    'member_id' => $member_id,
                    'two_dimension_code' => $two_dimension_code,
                    'result_code' => '0',
                    'result_message' => '',
                    'location_id' => 0,
                    'operate_time' => time()
                ];
                DB::table('two_dimension_scan_log')->insert($insert_arr);
                Log::notice('二维码状态修改完成');
            }

            DB::commit();
            return array('code' => 0, 'message' => '', 'data' => $data);
        } catch (\Exception $e) {
            Log::error('充值过程出错, 错误信息:' . $e->getMessage());
            DB::rollBack();
            return array('code' => 50002, 'message' => '充值过程出错，请稍后重试');//充值过程出错，请稍后重试
        }
    }

    /**
     * 提现至零钱
     * @return mixed
     */
    public function cash(Request $request)
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return Api::responseMessage(50002, '', '登陆信息失效，请重新登陆');
        }

        $db_prefix = config('database')['connections']['mysql']['prefix'];

        $cash_amount = $request->input('cash_amount');
        $rechargecard_id = $request->input('rechargecard_id');
        if (!$cash_amount || !$rechargecard_id) {
            return Api::responseMessage(50002, '', '参数格式错误');
        }

        //获取用户单个充值卡，同时计算最大可提现金额
        $rechargeCardInfo = MemberRechargeCard::select('rechargecard_id', 'card_id', 'total_amount', 'balance_amount', 'balance_freeze', 'balance_available', 'balance_cash_type', 'month_total', 'month_available', 'created_at', 'member_id')
            ->where('member_id', $member_id)->where('use_state', 0)->where('rechargecard_id', $rechargecard_id)->first();
        if ($rechargeCardInfo) {
            $rechargeCardInfo = $rechargeCardInfo->toArray();
            $rechargeCardInfo = $this->calcMaxCashAmount($rechargeCardInfo);
            if (floatval($cash_amount) == floatval($rechargeCardInfo['max_cash_amount']) && floatval($rechargeCardInfo['max_cash_amount']) != 0) {
                $data = [
                    'member_id' => $member_id,
                    'balance_amount' => $rechargeCardInfo['max_cash_amount'],
                    'busine_id' => $rechargeCardInfo['card_id'],
                    'pay_sn' => date('YmdHis') . sprintf('%04d', mt_rand(1000, 9999)),
                    'rechargecard_id' => $rechargeCardInfo['rechargecard_id'],
                    'payment_code' => 'balance_cash',
                    'payment_name' => '卡余额提现',
                    'trade_sn' => '',
                    'payment_state' => '1',
                    'payment_time' => time(),
                ];
                //申请提现，主要包括卡余额变更、零钱变更、充值卡信息修改
                try {
                    DB::beginTransaction();

                    MemberBalanceLog::changeBalance('KYETX', $data);//卡余额提现至零钱
                    MemberWalletLog::changeBalance('LQCZ', $data);//零钱充值

                    $card = MemberRechargeCard::where('rechargecard_id', $rechargeCardInfo['rechargecard_id'])->first();
                    $card->balance_available -= $rechargeCardInfo['max_cash_amount'];//减少可用余额
                    $card->month_available -= $rechargeCardInfo['max_cash_month'];//减少剩余提现月份数
                    $card->updated_at = time();
                    $card->save();

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    return Api::responseMessage(50002, '', '提现至零钱过程出错，请稍后重试');
                }
            } else {
                return Api::responseMessage(50002, '', '提现金额错误，不能提现');
            }
        } else {
            return Api::responseMessage(50002, '', '充值卡不存在');
        }

        return Api::responseMessage(0, $data);//提现成功
    }

    /**
     * 判断当前充值卡活动是否赠送商品，如果有存入我的礼包
     * @return mixed
     */
    private function pushRechargeGoods($member_id, $activity_id, $card_id)
    {
        //查询充值卡活动信息
        $rechargeActivity = RechargeActivity::select('activity_id', 'activity_name', 'activity_images', 'introduction')
            ->where('activity_id', $activity_id)->first()->toArray();
        //查询是否有商品可以兑换
        $rechargeGoodsList = RechargeActivityGoods::select('sku_id', 'sku_name', 'sku_image', 'price', 'number')
            ->where('activity_id', $activity_id)
            ->where('use_state', 1)->get();
        if ($rechargeGoodsList) {
            $rechargeGoodsList = $rechargeGoodsList->toArray();
            //查询我的礼包是否又此SKU，如果有更新数量，否则插入记录
            $giftcoupon_id = MemberGiftCoupon::create([
                'member_id' => $member_id,
                'card_id' => $card_id,
                'activity_id' => $activity_id,
                'activity_name' => $rechargeActivity['activity_name'],
                'activity_images' => $rechargeActivity['activity_images'],
                'introduction' => $rechargeActivity['introduction'],
                'activity_type' => 4,
                'created_at' => time(),
                'updated_at' => time()
            ])->giftcoupon_id;
            foreach ($rechargeGoodsList as $goods) {
                MemberGiftCouponGoods::create([
                    'member_id' => $member_id,
                    'giftcoupon_id' => $giftcoupon_id,
                    'sku_id' => $goods['sku_id'],
                    'sku_name' => $goods['sku_name'],
                    'sku_image' => $goods['sku_image'],
                    'price' => $goods['price'],
                    'total_num' => $goods['number'],
                    'exchanged_num' => 0,
                    'updated_at' => time()
                ]);
            }
        }
    }

    /**
     * 获取用户充值卡列表，同时计算用户每一张充值卡的最大可提现金额
     * @return mixed
     */
    private function calcMaxCashAmount($rechargeCardDetail)
    {
        //取当前时间月份数
        $nowtime_month_str = date("m", time());
        $can_cash_amount = 0;
        $can_cash_month = 0;
        switch (intval($rechargeCardDetail['balance_cash_type'])) {
            case 1: //按月提现
                //查询用户每一张充值卡的最后一次提现日志
                $log_info = MemberBalanceCash::select('id', 'cash_sn', 'amount', 'create_time')->where('rechargecard_id', $rechargeCardDetail['rechargecard_id'])
                    ->where('member_id', $rechargeCardDetail['member_id'])->orderBy('create_time', 'desc')->first();
                //上次提现月份数，如果没有有提现记录，默认月份数为记录生成时间月份数
                $prev_cash_month_str = date("m", $rechargeCardDetail['created_at']);
                if ($log_info) {
                    $log_info = $log_info->toArray();
                    $prev_cash_month_str = date("m", $log_info['create_time']);
                }
                $everyMonthCashAmount = $rechargeCardDetail['balance_amount'] / $rechargeCardDetail['month_total'];
                if (intval($prev_cash_month_str) > intval($nowtime_month_str)) { //跨年
                    $can_cash_month = 12 - intval($prev_cash_month_str) + intval($nowtime_month_str);
                    $can_cash_amount = $can_cash_month * $everyMonthCashAmount;
                } else if (intval($prev_cash_month_str) < intval($nowtime_month_str)) { //同年
                    $can_cash_month = intval($nowtime_month_str) - intval($prev_cash_month_str);
                    $can_cash_amount = $can_cash_month * $everyMonthCashAmount;
                }
                if ($rechargeCardDetail['balance_available'] < $can_cash_amount) {
                    $can_cash_amount = $rechargeCardDetail['balance_available'];
                }
                break;
            case 3: //全额提现
                $can_cash_amount = $rechargeCardDetail['balance_available'];
                break;
        }
        $rechargeCardDetail['max_cash_amount'] = $can_cash_amount;
        $rechargeCardDetail['max_cash_month'] = $can_cash_month;

        return $rechargeCardDetail;
    }

    /**
     * 点击页面充值缴费项目, 跳转至立即支付页面(然后支付json数据通过ajax获取)
     * @param $activity_id
     * @return View
     */
    public function payRecharge(Request $request)
    {
        $activity_id = (int)$request->input('id');
        $activity = RechargeActivity::find($activity_id);
        if (!$activity->exists) {
            Log::error('充值失败, 该充值卡项目不存在 => 控制器:MemberRechargeController@payRecharge');
            return view('errors.error');
        }

        $class_arr = $this->getMemberClass();
        $activity->grade_name = $class_arr[$activity->grade]['grade_name'];
        $document = Document::where('doc_code', 'sdgjhyfwxy')->first();

        return view('member.show_member_pay_info', compact('activity', 'document'));
    }


    public function getPayJosnForRechargeByajax(Request $request)
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            return Api::responseMessage(50000, '', '登陆信息失效，请重新登陆');
        }

        $sku_source_type = 0;
        $sku_source_info = '';
        if ($request->input('sku_source_type')) {
            $sku_source_type = (int)($request->input('sku_source_type'));
        }

        $activity_id=0;
        if ($request->input('activity_id')) {
            $activity_id = (int)($request->input('activity_id'));
        }
        $amount=$request->input('amount');


        $activity = RechargeActivity::find($activity_id);
        // ---------------------------------- 1 验证传入信息的合法性 ------------------
        $skus = array();
        if ($request->input('skus')) {
            $skus = explode(',', $request->input('skus'));
        } else {
            Log::error('传入的 skus 为空!  控制器:MemberRechargeController@getPayJosnForRechargeByajax');
            return Api::responseMessage(2, null, '没有传入商品信息！');
        }

        /*// 结算的商品信息
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

            $define_sku = array(
                'sku_id' => $sku_id,
                'number' => $array_sku[1],
                'price' => 0,
                'promotions_type' => 1,
                'promotions_id' => 0
            );
            // 一个或者多个商品(商品的二维数组信息)
            array_push($define_skus, $define_sku);
        }

        if (empty($define_skus)) {
            // 如果数组为空 说明传入的数组sku_id中,平台没有或者未上架
            Log::error('传入的商品信息无效! 控制器:MemberRechargeController@getPayJosnForRechargeByajax');
            return Api::responseMessage(2, null, '传入的商品信息无效! ');
        } else {
            // 将SKU 信息序列化为字符，保存到 $sku_source_info 中
            $sku_source_info = serialize($define_skus);
        }*/

//        $activity_id = $request->input('activity_id');
//        $activity = MemberShipActivity::find($activity_id);

        // 现在用微信支付，需要微信账号, 后期添加支付类型再完善
        $open_id = MemberOtherAccount::where('member_id', $member_id)->where('account_type', 2)->value('account_id');
        if (empty($open_id)) {
            Log::error('买家微信账户不存在 无法申请微信支付  控制器:MemberRechargeController@getPayJosnForRechargeByajax');
            return Api::responseMessage(50000, '', '微信账户信息获取失败, 无法为您申请微信支付, 请重新登录');
        }

        // 支付后通知回调
        $wxPayCallbackUrl = $request->server('HTTP_ORIGIN') . '/wx/pay/callbackWithRecharge';

        // 生成微信支付订单信息
        $out_trade_no = time() . date('YmdHis') . rand(pow(10, 7), pow(10, 8) - 1);
        $trade_no = date('YmdHis') . rand(pow(10, 7), pow(10, 8) - 1) . time();
        $attributes = [
            'openid' => $open_id,
            'trade_type' => 'JSAPI',
            'body' => '水丁平台 - 卡余额充值',
            'detail' => '卡余额',
            'out_trade_no' => $out_trade_no,
            //'total_fee' => (int)(bcmul($activity->price, 100)),
            'total_fee' => (int)(bcmul($amount, 100)),
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
            $content = '1、生成卡余额充值交易单，编号为:' . $trade_no . '的平台订单预付单, 交易起始时间为:'
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

            // 记录会员充值信息
            MemberRechargeCard::create([
                'member_id' => $member_id,
                'trade_no' => $trade_no,
                'pay_sn' => $out_trade_no,
                'activity_id' => $activity_id,
                'card_id' => 0,
                'use_state'=>1,
                'total_amount'=> $amount,
                'balance_amount' => $amount,
                'created_at' => time(),
                'updated_at' => time()
            ]);

        } else {
            Log::error('预付订单生成失败, 返回信息为:' . $result->toJson() . '控制器:MemberRechargeController@getPayJosnForRechargeByajax');
            return Api::responseMessage(50000, '', '系统异常, 请稍后重试');
        }

        $wx_json = $payment->configForPayment($prepayId, false);
        return Api::responseMessage(0, ['wx_json' => $wx_json]);
    }
    public function wxPayCallback()
    {
        $response = app('wechat')->payment->handleNotify(function ($notify, $successful) {

            $out_trade_no = $notify->out_trade_no;
            $trade = MemberRechargeCard::where('pay_sn', $out_trade_no)->first();

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

                // 修改充值卡记录状态为 有效记录(use_state=0)
                $trade->use_state = 0;
                $trade->updated_at = time();
                $trade->save();

                // 充值
                $rechargeActivity=RechargeActivity::find($trade->activity_id)->toarray();
                //余额充值
                $data = [
                    'member_id' => $trade->member_id,
                    'activity_id' => $rechargeActivity['activity_id'],
                    'rechargecard_id' => 0,//$trade->rechargecard_id,
                    'plat_order_id' => 0,
                    'total_amount' => $rechargeActivity['total_amount'],
                    'goods_amount' => $rechargeActivity['goods_amount'],
                    'balance_amount' => $rechargeActivity['balance_amount'],
                    'balance_cash_type' => $rechargeActivity['balance_cash_type'],
                    'month_total' => $rechargeActivity['month_total'],
                    'grade' => $rechargeActivity['grade'],
                    'two_dimension_code' => '',
                    'pay_sn' => 0,//$trade->pay_sn,
                ];

                $data['busine_id'] = $trade->rechargecard_id;
                MemberBalanceLog::changeBalance('KYECZ', $data);
                Log::notice('余额明细变更完成');
            } else {
                Log::warning('用户支付失败, 交易单号为:' . $trade->trade_no . ' => 控制器:MemberRechargeController@wxPayCallback');
                return false;
            }

            // 告诉微信已接收到支付通知, 并处理完成 false再稍后还会重新得到微信的支付通知
            return true;
        });

        return $response;
    }
}
