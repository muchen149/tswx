<?php

namespace App\controllers;

use App\facades\Api;
use App\lib\ApiResponseByHttp;
use App\models\marketing\GiftActivity;
use App\models\marketing\GiftActivityPackage;
use App\models\marketing\GiftCard;
use App\models\marketing\GiftPackage;
use App\models\marketing\GiftPackageGoods;
use App\models\member\Member;
use App\models\member\MemberGiftCoupon;
use App\models\member\MemberGiftCouponGoods;
use App\models\member\MemberRechargeCard;
use App\models\qrcode\TwoDimensionCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\models\order\Order as PlatOrder;
use Illuminate\Http\Request;

/**
 * 用户礼券控制器
 */
class MemberCouponController extends BaseController
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
     * 用户中心-》我的礼券
     * 传入参数：
     * @return mixed
     */
    public function myGiftCoupon()
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }

        //获取用户礼券列表
        $giftCouponList = MemberGiftCoupon::select(
            'giftcoupon_id', 'member_id', 'activity_id', 'activity_name', 'activity_images', 'introduction', 'activity_type', 'memo_id', 'use_state', 'created_at'
        )->where('member_id', $member_id)->where('use_state', 0)->orderBy('created_at', 'desc')->get();
        if ($giftCouponList) {
            $giftCouponList = $giftCouponList->toArray();
        }
        foreach ($giftCouponList as &$card) {
            $card['expire_time'] = date('Y-m-d', strtotime('+1 year', $card['created_at']));
            $card['activity_images'] = $this->getFullPictureUrl($card['activity_images']);
        }

        return view('user.my_gift_coupon', compact('giftCouponList'));
    }

    /**
     * 礼券详情  选择礼券商品
     * 进入选择礼券商品页面的方式
     * 1、扫码
     * 用户首次扫码进入，系统只会检查礼品卡、礼品活动状态等信息，不会插入memberGiftCoupon、memberGiftCouponGoods表记录
     * 2、绑卡
     * 用户首次绑卡进入，系统会插入表，表中memo_id值为0，但不会插入memberGiftCouponGoods表
     * 3、从个人中心进入
     * 3.1 memo_id==0
     * 因为充值卡插入memberGiftCoupon记录，activity_type==4，会插入memberGiftCouponGoods表
     * 因为礼品卡插入memberGiftCoupon记录，activity_type==3，不会插入memberGiftCouponGoods表，需要用户选择礼包后才会插入goods表
     * 3.2 memo_id!=0
     * 因为礼品卡插入memberGiftCoupon记录，activity_type==3，会插入memberGiftCouponGoods表，表示用户已选择过礼品包
     * 传入参数：
     * @return mixed
     */
    public function choseGiftCouponGoods($giftCouponId)
    {
        header("Cache-Control:no-cache,must-revalidate,no-store"); //这个no-store加了之后，Firefox下有效
        header("Pragma:no-cache");
        header("Expires:-1");

        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
            $member = Member::find($member_id)->toArray();  //当前用户
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }

        //展示礼品卡详情，包括里面礼品包以及商品内容
        $memberCoupon = MemberGiftCoupon::where('member_id', $member_id)
            ->where('giftcoupon_id', $giftCouponId)->first();
        if ($memberCoupon) {
            $memberCoupon = $memberCoupon->toArray();
            if (($memberCoupon['activity_type'] == 3 && !empty($memberCoupon['memo_id'])) || $memberCoupon['activity_type'] == 4) {
                $couponGoods = $this->getActivityGoods($memberCoupon['activity_id'], 1, $giftCouponId);
            } else {
                $couponGoods = $this->getActivityGoods($memberCoupon['activity_id'], 2, $memberCoupon['card_id']);
            }
        } else {
            $errorData = array('code' => 50002, 'message' => '礼券不存在');
            return view("errors.error", compact('errorData'));
        }

        $scanData['member_id'] = $member_id;
        $scanData['activity_id'] = $memberCoupon['activity_id'];
        $scanData['activity_name'] = $memberCoupon['activity_name'];
        $scanData['activity_images'] = $this->getFullPictureUrl($memberCoupon['activity_images']);
        $scanData['introduction'] = $memberCoupon['introduction'];
        $scanData['activity_type'] = $memberCoupon['activity_type'];
        $scanData['giftcoupon_id'] = $memberCoupon['giftcoupon_id'];
        $scanData['packages'] = $couponGoods;
        $scanData['card_id'] = $memberCoupon['card_id'];
        $scanData['is_many_times'] = $memberCoupon['is_many_times'];
        $scanData['member'] = $member;

        return view('marketing.chose_gift_goods', compact('scanData'));
    }

    /**
     * 保存礼券商品
     * 用户选择礼品生成订单前系统会先判断memberGiftCoupon以及memberGiftCouponGoods两表是否有数据
     * 如果存在将会直接更新已兑换数量（exchanged_num），并返回couponGoods表主键id，生成订单时需要使用
     * 如果没有会先插入数据，并返回couponGoods表主键id，生成订单时需要使用
     * @return mixed
     */
    public function saveCouponGoods(Request $request)
    {
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return Api::responseMessage(5000, '', '登陆信息失效，请重新登陆');
        }

        $giftcoupon_id = $request->input('giftcoupon_id'); //礼券id，可能为0 表示用户是扫码进入的
        $card_id = $request->input('card_id'); //礼品卡id，当giftcoupon_id==0时 启用此字段
        $package_id = $request->input('package_id'); //礼品包id ，可能为0 该礼券是通过充值卡充值的
        $skus = explode(',', $request->input('skus')); //用户此次领取的礼品

        $ids = [];
        if (!empty($giftcoupon_id)) {
            $counponGoodsList = MemberGiftCouponGoods::where('giftcoupon_id', $giftcoupon_id)->get()->toArray();
            if (count($counponGoodsList) > 0) {
                foreach ($counponGoodsList as $couponGoods) {
                    foreach ($skus as $sku) {
                        if ($sku == $couponGoods['sku_id']) {
                            $ids[] = $couponGoods['sku_id'] . '-' . $couponGoods['id'];
                            break;
                        }
                    }
                }
            } else {
                $giftGoodsList = GiftPackageGoods::select('sku_id', 'sku_name', 'sku_image', 'gift_price', 'gift_number')
                    ->where('package_id', $package_id)
                    ->where('gift_state', 1)->get();
                if ($giftGoodsList) {
                    $giftGoodsList = $giftGoodsList->toArray();
                    //查询我的礼包是否又此SKU，如果有更新数量，否则插入记录
                    foreach ($giftGoodsList as $goods) {
                        $id = MemberGiftCouponGoods::create([
                            'member_id' => $member_id,
                            'giftcoupon_id' => $giftcoupon_id,
                            'sku_id' => $goods['sku_id'],
                            'sku_name' => $goods['sku_name'],
                            'sku_image' => $goods['sku_image'],
                            'price' => $goods['gift_price'],
                            'total_num' => $goods['gift_number'],
                            'exchanged_num' => 0,
                            'updated_at' => time()
                        ])->id;
                        foreach ($skus as $sku) {
                            if ($sku == $goods['sku_id']) {
                                $ids[] = $goods['sku_id'] . '-' . $id;
                                break;
                            }
                        }
                    }
                }
                $giftPackage = GiftPackage::select('package_name', 'package_images')->find($package_id);//获得礼品包信息
                $memberCoupon = MemberGiftCoupon::find($giftcoupon_id);
                $memberCoupon->memo_id = $package_id;
                $memberCoupon->memo_name = $giftPackage->package_name;
                $memberCoupon->memo_images = $giftPackage->package_images;
                $memberCoupon->updated_at = time();
                $memberCoupon->save();
            }
        } else {
            $giftCard = GiftCard::find($card_id)->toArray();
            //检查礼品卡与二维码使用、激活情况，以及虚拟商品订单的支付等情况
            $card_res = $this->checkGiftCard($member_id, $giftCard['activity_id'], $card_id);
            if (!empty($card_res['code'])) {
                return Api::responseMessage($card_res['code'], '', $card_res['message']);
            }
            $use_res = $this->useGiftCard([
                'member_id' => $member_id,
                'activity_id' => $card_res['data']['activity_id'],
                'activity_name' => $card_res['data']['activity_name'],
                'activity_images' => $card_res['data']['activity_images'],
                'introduction' => $card_res['data']['introduction'],
                'card_id' => $card_res['data']['card_id'],
                'plat_order_id' => 0,
                'two_dimension_code' => $card_res['data']['two_dimension_code'],
                'package_id' => $package_id,
                'skus' => $skus,
                'ids' => $ids,
            ]);
            if (!empty($use_res['code'])) {
                return Api::responseMessage($use_res['code'], '', $use_res['message']);
            }
            $ids = $use_res['data']['ids'];
        }
        //返回数据格式1-2,3-4（sku_id-giftCouponGoods.id,多个用英文“,”分割）
        $data = [
            'ids' => join(',', $ids)
        ];
        return Api::responseMessage(0, $data);
    }

    /**
     * 扫码详情展示
     * @return mixed
     */
    public function scan($member_id, $activity_id, $card_id)
    {
        $member = Member::where('member_id', $member_id)->first()->toArray();  //当前用户

        $db_prefix = config('database')['connections']['mysql']['prefix'];

        //检查礼品卡与二维码使用、激活情况，以及虚拟商品订单的支付等情况
        $card_res = $this->checkGiftCard($member_id, $activity_id, $card_id);
        if (!empty($card_res['code'])) {
            return array('code' => $card_res['code'], 'message' => $card_res['message']);
        }

        $goods_data = $this->getActivityGoods($activity_id, 2, $card_id);

        //活动图片
        $data = [
            'activity_images' => $this->getFullPictureUrl($card_res['data']['activity_images']),
            'giftcoupon_id' => 0,
            'packages' => $goods_data,
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

        //检查礼品卡与二维码使用、激活情况，以及虚拟商品订单的支付等情况
        $card_res = $this->checkGiftCard($member_id, $activity_id, $card_id);
        if (!empty($card_res['code'])) {
            return array('code' => $card_res['code'], 'message' => $card_res['message']);
        }

        //使用礼品卡，主要包括：商品放入我的礼包、订单状态修改为已完成、二维码与礼品卡使用状态修改
        $use_res = $this->useGiftCard($card_res['data']);
        if (!empty($use_res['code'])) {
            return array('code' => $use_res['code'], 'message' => $use_res['message']);
        }

        $goods_data = $this->getActivityGoods($activity_id, 2, $card_id);

        $use_res['data']['activity_images'] = $this->getFullPictureUrl($use_res['data']['activity_images']);
        $use_res['data']['packages'] = $goods_data;
        $use_res['data']['member'] = $member;

        return array('code' => 0, 'message' => '', 'data' => $use_res['data']);//绑卡成功
    }

    /**
     * 检查礼品卡与二维码使用、激活情况，以及虚拟商品订单的支付等情况
     * @return mixed
     */
    private function checkGiftCard($member_id, $activity_id, $card_id)
    {
        $giftActivity = GiftActivity::select('activity_state', 'activity_id', 'activity_name', 'activity_images', 'introduction', 'start_time', 'end_time', 'is_many_times')
            ->where('activity_id', $activity_id)->first();
        $giftCard = GiftCard::select('card_state', 'two_dimension_code', 'card_id')
            ->where('card_id', $card_id)->first();
        if (!$giftActivity) {
            return array('code' => 50002, 'message' => '礼品卡活动不存在');//礼品卡活动不存在
        }
        $giftActivity = $giftActivity->toArray();
        if (!$giftCard) {
            return array('code' => 50002, 'message' => '卡密错误');//卡密错误
        }
        $giftCard = $giftCard->toArray();
        if ($giftActivity['activity_state'] == 0) {
            return array('code' => 50002, 'message' => '活动未开启');//活动未开启
        }
        if ($giftCard['card_state'] == 1) {
            return array('code' => 50002, 'message' => '礼品卡已被使用');//礼品卡已被使用
        }
        if ($giftActivity['start_time'] > time()) {
            return array('code' => 50002, 'message' => '礼品卡活动尚未开始');//礼品卡活动尚未开始
        }
        if ($giftActivity['end_time'] < time()) {
            return array('code' => 50002, 'message' => '礼品卡活动已结束');//礼品卡活动已结束
        }

        $orderDetail = DB::table('order')->select('order.plat_order_state', 'order.plat_order_id', 'order.member_id', 'order.plat_order_sn')
            ->join('order_extend', 'order_extend.plat_order_id', '=', 'order.plat_order_id')
            ->where('order_extend.two_dimension_code', $giftCard['two_dimension_code'])
            ->where('order.delete_state', 0)->where('order.lock_state', 0)
            ->first();
        if ($orderDetail && $orderDetail->plat_order_state != 2) {
            return array('code' => 50002, 'message' => '礼品卡订单未支付');//礼品卡订单未支付
        }
        if ($orderDetail && $orderDetail->member_id != $member_id) {
            return array('code' => 50002, 'message' => '与订单付款人不一致，不能操作');//与订单付款人不一致，不能操作
        }

        $data = [
            'member_id' => $member_id,
            'activity_id' => $giftActivity['activity_id'],
            'activity_name' => $giftActivity['activity_name'],
            'activity_images' => $giftActivity['activity_images'],
            'introduction' => $giftActivity['introduction'],
            'is_many_times' => $giftActivity['is_many_times'],
            'card_id' => $giftCard['card_id'],
            'plat_order_id' => !empty($orderDetail) ? $orderDetail->plat_order_id : 0,
            'two_dimension_code' => $giftCard['two_dimension_code'],
            'package_id' => '',
            'skus' => [],
            'ids' => [],
        ];

        return array('code' => 0, 'message' => '', 'data' => $data);
    }

    /**
     * 获得礼品卡或充值卡商品信息
     * $source_type == 1 时  $source_id为 giftcoupon_id
     * $source_type == 2 时  $source_id为 card_id
     */
    private function getActivityGoods($activity_id, $source_type, $source_id)
    {
        switch ($source_type) {
            case 1:
                $memberCoupon = MemberGiftCoupon::where('giftcoupon_id', $source_id)
                    ->where('use_state', 0)->first()->toArray();
                $packageGoodsList = MemberGiftCouponGoods::where('giftcoupon_id', $source_id)
                    ->where('use_state', 0)->get()->toArray();
                $packageGoodsNum = count($packageGoodsList);//礼品包内可用的商品种类
                foreach ($packageGoodsList as &$goods) {
                    $goods['sku_image'] = $this->getFullPictureUrl($goods['sku_image']);
                    $goods['useable_num'] = $goods['total_num'] - $goods['exchanged_num'];
                    if ($goods['useable_num'] == 0) {
                        $packageGoodsNum--;
                    }
                }
                $giftCouponDetail[0]['package_id'] = !empty($memberCoupon['memo_id']) ? $memberCoupon['memo_id'] : 0;
                $giftCouponDetail[0]['package_name'] = !empty($memberCoupon['memo_name']) ? $memberCoupon['memo_name'] : '礼品套餐';
                $giftCouponDetail[0]['package_images'] = !empty($memberCoupon['memo_images']) ? $this->getFullPictureUrl($memberCoupon['memo_images']) : asset('img/giftcard/gift-default.png');
                $giftCouponDetail[0]['useable_num'] = $packageGoodsNum;
                $giftCouponDetail[0]['goods_num'] = count($packageGoodsList);
                $giftCouponDetail[0]['goodsList'] = $packageGoodsList;
                break;
            case 2:
                //展示礼品卡详情，包括里面礼品包以及商品内容
                $giftCouponDetail = GiftActivityPackage::select('package_id', 'package_name', 'package_images')
                    ->where('activity_id', $activity_id)->orderBy('package_index', 'desc')->get();
                if ($giftCouponDetail) {
                    $giftCouponDetail = $giftCouponDetail->toArray();
                    foreach ($giftCouponDetail as &$packageInfo) {
                        $packageInfo['package_images'] = $this->getFullPictureUrl($packageInfo['package_images']);
                        $packageGoodsList = GiftPackageGoods::select('sku_id', 'sku_name', 'sku_title', 'sku_image', 'gift_price', 'gift_number')
                            ->where('package_id', $packageInfo['package_id'])->where('gift_state', 1)->orderBy('gift_index', 'desc')->get();
                        if ($packageGoodsList) {
                            $packageGoodsList = $packageGoodsList->toArray();
                            $packageGoodsNum = count($packageGoodsList);//礼品包内可用的礼品种类
                            foreach ($packageGoodsList as &$goods) {
                                $goods['price'] = $goods['gift_price'];
                                $goods['useable_num'] = $goods['gift_number'];
                                $goods['sku_image'] = $this->getFullPictureUrl($goods['sku_image']);
                                if ($goods['useable_num'] == 0) {
                                    $packageGoodsNum--;
                                }
                            }
                            $packageInfo['goodsList'] = $packageGoodsList;
                            $packageInfo['goods_num'] = count($packageGoodsList);
                            $packageInfo['useable_num'] = $packageGoodsNum;
                        }
                    }
                }
                break;
        }
        return $giftCouponDetail;
    }

    /**
     * 立即充值 调用支付接口 成功支付后回调url执行该方法
     * 发放礼品卡
     * 该方法认为在线支付是安全的(以此完成自动充值)
     *
     * @return mixed
     */
    public function sendGiftCard($plat_order, $activitys)
    {
        //个人中心->立即充值礼品卡 不需要关联gift_card （该功能暂无）
        foreach ($activitys as $activity) {
            $data = [
                'member_id' => $plat_order->member_id,
                'activity_id' => $activity->activity_id,
                'card_id' => 0,
                'plat_order_id' => $plat_order->plat_order_id,
                'two_dimension_code' => 0,
            ];
            //使用礼券
            $result = $this->useGiftCard($data);
            if (!empty($result['code'])) {
                return $this->api->responseMessage($result['code'], null, $result['message']);
            }
        }
        return $this->api->responseMessage(0, null, '充值成功');
    }

    /**
     * 使用礼品卡，主要包括：我的礼券、订单状态修改为已完成、二维码与礼品卡使用状态修改
     * @return mixed
     */
    public function useGiftCard($data)
    {
        extract($data);
        $time = time();
        try {
            DB::beginTransaction();

            //商品放入我的礼包
            $giftcoupon_id = $this->pushRechargeGoods($member_id, $activity_id, $card_id, $package_id, $skus, $ids);
            $data['giftcoupon_id'] = $giftcoupon_id;
            $data['ids'] = $ids;

            if (!empty($plat_order_id)) {
                //订单状态修改
                $order = PlatOrder::where('plat_order_id', $plat_order_id)->first();
                $order->plat_order_state = 9;//已完成
                $order->arrival_time = $time;
                $order->save();
            }

            //礼品卡、二维码状态修改
            if (!empty($card_id) && !empty($two_dimension_code)) {
                $card = GiftCard::where('card_id', $card_id)->first();
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
            }

            DB::commit();
            return array('code' => 0, 'message' => '', 'data' => $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return array('code' => 50002, 'message' => '礼品过程出错，请稍后重试');//礼品过程出错，请稍后重试
        }
    }

    /**
     * 判断当前礼品卡活动是否赠送商品，如果有存入我的礼包
     * @return mixed
     */
    private function pushRechargeGoods($member_id, $activity_id, $card_id, $package_id = '', $skus = [], &$ids = [])
    {
        //查询礼品卡活动信息
        $giftActivity = GiftActivity::select('activity_id', 'activity_name', 'activity_images', 'introduction', 'is_many_times')
            ->where('activity_id', $activity_id)->first()->toArray();
        $insert_arr = [
            'member_id' => $member_id,
            'activity_id' => $activity_id,
            'card_id' => $card_id,
            'activity_name' => $giftActivity['activity_name'],
            'activity_images' => $giftActivity['activity_images'],
            'introduction' => $giftActivity['introduction'],
            'is_many_times' => $giftActivity['is_many_times'],
            'activity_type' => 3,
            'memo_id' => !empty($package_id) ? $package_id : 0,
            'created_at' => time(),
            'updated_at' => time()
        ];
        if (!empty($package_id)) {
            $giftPackage = GiftPackage::select('package_name', 'package_images')->find($package_id);
            $insert_arr['memo_name'] = $giftPackage->package_name;
            $insert_arr['memo_images'] = $giftPackage->package_images;
        }
        $giftcoupon_id = MemberGiftCoupon::create($insert_arr)->giftcoupon_id;

        //查询是否选择礼品包
        if (!empty($package_id)) {
            $giftGoodsList = GiftPackageGoods::select('sku_id', 'sku_name', 'sku_image', 'gift_price', 'gift_number')
                ->where('package_id', $package_id)
                ->where('gift_state', 1)->get();
            if ($giftGoodsList) {
                $giftGoodsList = $giftGoodsList->toArray();
                //查询我的礼包是否又此SKU，如果有更新数量，否则插入记录
                foreach ($giftGoodsList as $goods) {
                    $id = MemberGiftCouponGoods::create([
                        'member_id' => $member_id,
                        'giftcoupon_id' => $giftcoupon_id,
                        'sku_id' => $goods['sku_id'],
                        'sku_name' => $goods['sku_name'],
                        'sku_image' => $goods['sku_image'],
                        'price' => $goods['gift_price'],
                        'total_num' => $goods['gift_number'],
                        'exchanged_num' => 0,
                        'updated_at' => time()
                    ])->id;
                    foreach ($skus as $sku) {
                        if ($sku == $goods['sku_id']) {
                            $ids[] = $goods['sku_id'] . '-' . $id;
                            break;
                        }
                    }
                }
            }
        }
        return $giftcoupon_id;
    }
}
