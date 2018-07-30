<?php

namespace App\controllers\ys;

use App\facades\Api;
use App\models\company\CompanyOfficialAccount;
use App\models\dct\DctArea;
use App\models\marketing\LotteryActivity;
use App\models\marketing\LotteryCard;
use App\models\marketing\LotteryInfo;
use App\models\member\Member;
use App\models\member\MemberAddress;
use App\models\member\MemberAwardsRecord;
use App\models\member\MemberExtend;
use App\models\member\MemberOtherAccount;
use App\models\member\MemberPublicInfo;
use App\models\member\MemberYesbLog;
use App\models\company\CompanyBaseinfo;
use App\models\qrcode\TwoDimensionCode;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\models\company\CompanyPayAccount;
use App\models\goods\GoodsSku;


class MemberLotteryController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 扫码详情展示
     * @return mixed
     */
    public function scan($member_id, $activity_id, $card_id)
    {
        $member = Member::where('member_id', $member_id)->first()->toArray();  //当前用户

        //获取活动信息
        $activity=LotteryActivity::where('activity_id',$activity_id)->first()->toArray();

        //检查抽奖卡与二维码使用、激活情况
        $card_res = $this->checkLotteryCard($member_id, $activity_id, $card_id);
        if (!empty($card_res['code'])) {
            return array('code' => $card_res['code'], 'message' => $card_res['message']);
        }

        if ($card_res['data']['activity_type'] != 1) {
            return array('code' => 50002, 'message' => '活动不存在');
        }

        if (!empty($card_res['data']['company_id'])) {
            $company = CompanyBaseinfo::select('company_qrcode', 'company_logo')->find($card_res['data']['company_id'])->toArray();
        }

        $lotteryInfoArr = $this->getLotteryInfo($activity_id);
        //20170524--说明： data中的 'subscribe' => 1, 是判断是否关注的字段，此处设置为了1 默认为关注，从接口判断即可。。。
        if (Cache::has( 'subscribe_' . $member_id  )) {
            //缓存存在，获取数据并删除
            $value = Cache::pull( 'subscribe_' . $member_id );
            Log::info('======20170524-1=======subscribe===exist=====》' . $value  . 'test' );
            Log::alert($value);
        }else{
            $value = 0;
            Log::info('======20170524-2=======subscribe===not exist=====》' . $value . 'test' );
        }
        //end of 20170524--说明

        //如果设置位不必关注，则subscribe字段设置位1 假设已关注
        if($activity['subscribe']==0)
            $value=1;


        $activity_imageUrl=$this->getFullPictureUrl($card_res['data']['activity_images']);

        $data = array(
            'activity_images' => $this->getFullPictureUrl($card_res['data']['activity_images']),
            'lottery_info' => $lotteryInfoArr,
            'lottery_infonum' => count($lotteryInfoArr),
            'member_id' => $member_id,
            'activity_id' => $card_res['data']['activity_id'],
            'card_id' => $card_res['data']['card_id'],
            'activity_name' => $card_res['data']['activity_name'],
            'introduction' => $card_res['data']['introduction'],
            'tools_type' => $card_res['data']['tools_type'],
            'activity_type' => $card_res['data']['activity_type'],
            'cost_vrcoin' => $card_res['data']['cost_vrcoin'],
            'company_id' => $card_res['data']['company_id'],
            // 'subscribe' => 1,  //170524 -说明：以前写死，现在改为从微信获取是否关注
            'subscribe' => $value,
            'yesb_available' => $member['yesb_available'],
            'company_qrcode' => isset($company) && !empty($company['company_qrcode']) ?
                $this->getFullPictureUrl($company['company_qrcode']) : asset('img/sd_qrcode.png'),
            'company_logo' => isset($company) && !empty($company['company_qrcode']) ?
                $this->getFullPictureUrl($company['company_logo']) : $activity_imageUrl,//20170607 如果默认是平台的抽红包，则用活动图片代替company_logo       asset('img/home_logo.png'),

            'isLogo' =>isset($company) && !empty($company['company_qrcode']) ?1:0, //20170607 是否使用logo字段，平台的抽红包 使用活动图片，分销商的抽红包使用对应公众号的logo

        );

        return array('code' => 0, 'message' => '', 'data' => $data);
    }

    /**
     * 玩一玩
     * @return mixed
     */
    public function play($activity_id)
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

        //检查活动激活、开放时间等情况
        $card_res = $this->checkLotteryCard($member_id, $activity_id);
        if (!empty($card_res['code'])) {
            $errorData = array('code' => $card_res['code'], 'message' => $card_res['message'], 'url' => url('/personal/index'));
            return view("errors.error", compact('errorData'));
        }

        if ($card_res['data']['activity_type'] != 2) {
            return array('code' => 50002, 'message' => '活动不存在');
        }

        if (!empty($card_res['data']['company_id'])) {
            $company = CompanyBaseinfo::select('company_qrcode', 'company_logo')->find($card_res['data']['company_id'])->toArray();
        }

        $lotteryInfoArr = $this->getLotteryInfo($activity_id);
        $scanData = [
            'activity_images' => $this->getFullPictureUrl($card_res['data']['activity_images']),
            'lottery_info' => $lotteryInfoArr,
            'lottery_infonum' => count($lotteryInfoArr),
            'member_id' => $member_id,
            'activity_id' => $card_res['data']['activity_id'],
            'card_id' => $card_res['data']['card_id'],
            'activity_name' => $card_res['data']['activity_name'],
            'introduction' => $card_res['data']['introduction'],
            'tools_type' => $card_res['data']['tools_type'],
            'activity_type' => $card_res['data']['activity_type'],
            'cost_vrcoin' => $card_res['data']['cost_vrcoin'],
            'company_id' => $card_res['data']['company_id'],
            'subscribe' => 1,
            'yesb_available' => $member['yesb_available'],
            'company_qrcode' => isset($company) && !empty($company['company_qrcode']) ?
                $this->getFullPictureUrl($company['company_qrcode']) : asset('img/sd_qrcode.png'),
            'company_logo' => isset($company) && !empty($company['company_logo']) ?
                $this->getFullPictureUrl($company['company_logo']) : asset('img/home_logo.png'),
        ];

        $view = $scanData['tools_type'] == 5 ? 'marketing.lottery' : 'marketing.redpack';
        return view($view, compact('scanData'));
    }

    /**
     * 根据中奖概率计算所中奖项，同时记录中奖记录等信息
     * @return mixed
     */
    public function handleLottery(Request $request)
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
            $member = Member::where('member_id', $member_id)->first()->toArray();  //当前用户
        } else {
            // 当前用户没有登录
            return array('code' => 50002, 'message' => '登陆信息失效，请重新登陆');
        }

        //平台配置内容
        $plat_vrb_name = $this->getPlatSetting('plat_vrb_name');

        $activity_id = $request->input('activity_id');
        $card_id = $request->input('card_id');

        //检查抽奖卡与二维码使用、激活情况，以及虚拟商品订单的支付等情况
        $card_res = $this->checkLotteryCard($member_id, $activity_id, $card_id);
        if (!empty($card_res['code'])) {
            return array('code' => $card_res['code'], 'message' => $card_res['message']);
        }

        //检查玩一玩活动用户积分是否足够
        if ($card_res['data']['activity_type'] == 2 && $member['yesb_available'] < $card_res['data']['cost_vrcoin']) {
            $message = $plat_vrb_name.'数量不足';
            return array('code' => 50002, 'message' => $message);
        }

        //开始抽奖，主要包括：计算中奖奖项、保存中奖记录、更新用户虚拟币数、奖项奖品数量修改、二维码与抽奖卡使用状态修改
        $use_res = $this->useLotteryCard($card_res['data']);
        if (!empty($use_res['code'])) {
            return array('code' => $use_res['code'], 'message' => $use_res['message']);
        }
        return array('code' => 0, 'message' => '', 'data' => $use_res['data']);//抽奖成功
    }

    /**
     * 完善领奖信息
     * @return mixed
     */
    public function perfectAwardInfo($awardsrecord_id)
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

        //用户扩展信息
        $member_extend = MemberExtend::find($member_id);
        if ($member_extend) {
            $member_extend = $member_extend->toArray();
            $member = array_merge($member, $member_extend);
        }

        //检查奖品领取等情况
        $awardRecord = MemberAwardsRecord::select('awardsrecord_id', 'company_id', 'activity_id', 'order_id', 'activity_type', 'prize_level', 'prize', 'exchange_type', 'prize_type', 'exchange_state')
            ->where('awardsrecord_id', $awardsrecord_id)->first();
        if ($awardRecord) {
            $awardRecord->toArray();
            if (!empty($awardRecord['order_id'])) {
                $errorData = array('code' => 50002, 'message' => '您已经领取过奖品，请勿重复领取', 'url' => url('/personal/index'));
                return view("errors.error", compact('errorData'));
            }
            if ($awardRecord['exchange_type'] != 1) {
                $errorData = array('code' => 50002, 'message' => '只有实物奖品需要完善领奖信息', 'url' => url('/personal/index'));
                return view("errors.error", compact('errorData'));
            }
            if ($awardRecord['prize_type'] != 0) {
                $errorData = array('code' => 50002, 'message' => '虚拟商品不能申领！', 'url' => url('/personal/index'));
                return view("errors.error", compact('errorData'));
            }
        } else {
            $errorData = array('code' => 50002, 'message' => '中奖记录不存在', 'url' => url('/personal/index'));
            return view("errors.error", compact('errorData'));
        }

        // 当前买家收货地址列表
        $address_info = MemberAddress::selectZd()
            ->where('member_id', $member_id)
            ->where('use_state', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        // 如果当前买家没有收货地址，提示买家增加
        $is_hasAddress = $address_info->isEmpty() ? 0 : 1;

        // 省地址数组（新建地址信息需要）
        $province_dct = DctArea::select('id', 'name', 'pid')
            ->where('pid', 0)
            ->where('is_use', 1)
            ->get()
            ->toArray();

        $company = [
            'subscribe' => 1,
            'qrcode' => asset('img/sd_qrcode.png')
        ];
        return view('marketing.perfect_award_info', compact(
            'member', 'awardRecord', 'address_info', 'is_hasAddress', 'province_dct', 'member_extend', 'company'));
    }

    /**
     * 完善用户基本信息
     * @return mixed
     */
    public function perfectMemberInfo(Request $request)
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
            $member = Member::where('member_id', $member_id)->first();  //当前用户
        } else {
            // 当前用户没有登录
            return Api::responseMessage(50002, '', "登陆信息失效，请重新登陆");
        }

        $true_name = $request->input('true_name');
        $mobile = $request->input('mobile');
        $awardsrecord_id = $request->input('awardsrecord_id');

        //检查奖品领取等情况
        $awardRecord = MemberAwardsRecord::select('awardsrecord_id', 'company_id', 'activity_id', 'order_id', 'activity_type', 'prize_level', 'prize', 'exchange_type', 'prize_type', 'exchange_state')
            ->where('awardsrecord_id', $awardsrecord_id)->first();
        if (!empty($awardRecord->order_id) || $awardRecord->exchange_state == 1) {
            return Api::responseMessage(50002, '', "奖品已经申领，请勿重复申领！");
        }
        $member_extend = MemberExtend::find($member_id);
        if (!$member_extend) {
            MemberExtend::create([
                'member_id' => $member_id,
                'true_name' => $true_name,
            ]);
        } else {
            $member_extend->true_name = $true_name;
            $member_extend->save();
        }
        $member->mobile = $mobile;
        $member->save();

        return Api::responseMessage(0);
    }

    /**
     * 现场领奖
     * @return mixed
     */
    public function receiveAward(Request $request)
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return Api::responseMessage(50002, '', "登陆信息失效，请重新登陆");
        }

        $awardsrecord_id = $request->input('awardsrecord_id');
        $awardRecord = MemberAwardsRecord::where('awardsrecord_id', $awardsrecord_id)->where('member_id', $member_id)->first();
        if ($awardRecord->exchange_state == 1) {
            return Api::responseMessage(50002, '', "您已申领奖品，请不要重复申领！");
        }
        $awardRecord->exchange_state = 1;
        $awardRecord->save();

        return Api::responseMessage(0);
    }


    /**
     * 领取红包
     * @return mixed
     */
    public function receiveRedpack(Request $request)
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
            $member = Member::where('member_id', $member_id)->first();  //当前用户
        } else {
            // 当前用户没有登录
            return Api::responseMessage(50002, '', "登陆信息失效，请重新登陆");
        }

        $awardsrecord_id = $request->input('awardsrecord_id');
        $awardRecord = MemberAwardsRecord::select(
            'awardsrecord_id', 'company_id', 'activity_id', 'order_id', 'activity_type',
            'prize_level', 'prize', 'exchange_type', 'prize_type', 'exchange_state'
        )->where('awardsrecord_id', $awardsrecord_id)->first();
        if ($awardRecord) {
            $awardRecord->toArray();
            if (!empty($awardRecord['exchange_state']) || !empty($awardRecord['order_id'])) {
                return Api::responseMessage(50002, '', "您已经领取过奖品，请勿重复领取");
            }
            if ($awardRecord['exchange_type'] != 1 || $awardRecord['prize_type'] != 1) {
                return Api::responseMessage(50002, '', "此奖品不是红包！");
            }
        } else {
            return Api::responseMessage(50002, '', "中奖记录不存在");
        }

        $res_data = $this->sendRedpack($member_id, $awardRecord);
        if (!empty($res_data['code'])) {
            return Api::responseMessage($res_data['code'], '', $res_data['message']);
        }
        MemberAwardsRecord::where('awardsrecord_id', $awardsrecord_id)
            ->update([
                'exchange_state' => 1
            ]);
        //根据中奖记录中的红包对应sku_id 活动红包金额
        $redpackInfo=GoodsSku::where('sku_id',$awardRecord['prize'])->first();

        $price=$redpackInfo->price;
        $data = [
            'prize' => $price
        ];
        return Api::responseMessage(0, $data);
    }

    /**
     * easyWechat 发红包
     * @return mixed
     */
    private function sendRedpack($member_id, $awardRecord)
    {
        /*
        $member_info = MemberOtherAccount::where('member_id', $member_id)->where('account_type', 2)->first();
        if (!$member_info) {
            return array('code' => 50002, 'message' => '请使用微信登陆');
        }
        */

        //第三方支付发放红包处理,根据配置表中的信息进行第三方公众号支付信息发放红包 20170525

        if (!empty($awardRecord['company_id'])) {
            $company = CompanyBaseinfo::select('company_name')->where('company_id', $awardRecord['company_id'])->first();
        }

        $value = empty($awardRecord['company_id'])?0:$awardRecord['company_id'];
        //根据中奖记录里面的company_id + member_id 查到对应分销商的用户信息
        Log:info('-------20170525-----vvv---'.$value.'----');
        $member_info = MemberPublicInfo::where('member_id', $member_id)->where('company_id', $value )->first();
        if (!$member_info) {
            return array('code' => 50002, 'message' => '请使用微信登陆');
        }
        //根据中奖记录中的红包对应sku_id 活动红包金额
        $redpackInfo=GoodsSku::where('sku_id',$awardRecord['prize'])->first();

        $price=$redpackInfo->price;
        $luckyMoneyData = [
            'mch_billno' => date("YmdHis") . rand(1000, 9999),
            'send_name' => isset($company) && !empty($company['company_name']) ? $company['company_name'] : '水丁网',
            're_openid' => $member_info['openid'],
            'total_num' => 1,  //普通红包固定为1，裂变红包不小于3
            'total_amount' => $price * 100,  //单位为分，普通红包不小于100，裂变红包不小于300
            'wishing' => "码上有礼，好运连连！",
            'act_name' => (isset($company) && !empty($company['company_name']) ? $company['company_name'] : '水丁网') .$awardRecord['activity_name']."活动",
            'remark' => '',
        ];

        //查询支付商户号信息

        $payAccount = CompanyPayAccount::where('company_id', $value)->first();
        if($payAccount){
            $merchant_id = $payAccount->merchant_id;
            $pay_key = $payAccount->key;

            $cert_path_t = 'wx/cert/'. ($value==0?'':$value.'/').'apiclient_cert.pem';
            $key_path_t = 'wx/cert/'. ($value==0?'':$value.'/').'apiclient_key.pem';

            Log::info('---cert_path_t--'. $cert_path_t .'----');
            Log::info('---key_path_t--'. $key_path_t .'----');

            $cert_path = public_path($cert_path_t);
            $key_path  = public_path($key_path_t);

            //配置微信红包参数

            $app_id=$member_info['authorizer_appid'];

            $options = [
                //app_id
                'app_id'  =>  $app_id , //'wx22d541111c00fce1'
                // payment
                'payment' => [
                    'merchant_id'        =>  $merchant_id ,                           //WECHAT_PAYMENT_MERCHANT_ID=1388054602
                    'key'                => $pay_key ,            //WECHAT_PAYMENT_KEY=8sU63K7d0n3QM4Deq3sa924bksd7f23U
                    'cert_path'          =>  $cert_path ,  // WECHAT_PAYMENT_CERT_PATH='http://ynmo.yininet.com/cert.pem'
                    'key_path'           =>  $key_path ,        //WECHAT_PAYMENT_KEY_PATH='http://ynmo.yininet.com/key.pem'
                    'notify_url'         => 'http://sdwx.shuitine.com/wx/wxCallback',                                        // 你也可以在下单时单独设置来想覆盖它
                ],
            ];

            $result = (new Application($options))->lucky_money->sendNormal($luckyMoneyData);

        }else{
            //没有支付商户号信息
            return array('code' => 50002, 'message' => '公众号未配置商户号信息');

        }





        //$result = (new Application(config('wechat')))->lucky_money->sendNormal($luckyMoneyData);


        if ($result['return_code'] == "SUCCESS" && $result['result_code'] == "SUCCESS") {
            return array('code' => 0);
        } else {
            return array('code' => 50002, 'message' => $result['return_msg']);
        }
    }

    /**
     * 检查抽奖卡与二维码使用、激活情况，以及虚拟商品订单的支付等情况
     * @return mixed
     */
    private function checkLotteryCard($member_id, $activity_id, $card_id = 0)
    {
        $lotteryActivity = LotteryActivity::where('activity_id', $activity_id)->first(); //抽奖活动
        if (!$lotteryActivity) {
            return array('code' => 50002, 'message' => '抽奖活动不存在');//抽奖活动不存在
        }
        $lotteryActivity = $lotteryActivity->toArray();
        if ($lotteryActivity['activity_state'] == 0) {
            return array('code' => 50002, 'message' => '活动未开启');//活动未开启
        }
        if ($lotteryActivity['start_time'] > time()) {
            return array('code' => 50002, 'message' => '抽奖活动尚未开始');//抽奖卡活动尚未开始
        }
        if ($lotteryActivity['end_time'] < time()) {
            return array('code' => 50002, 'message' => '抽奖活动已结束');//抽奖卡活动已结束
        }
        //玩一玩活动不存在card_id
        if (!empty($card_id)) {
            $lotteryCard = LotteryCard::select('card_state', 'two_dimension_number_code', 'two_dimension_code', 'card_id')
                ->where('card_id', $card_id)->first();
            if (!$lotteryCard) {
                return array('code' => 50002, 'message' => '卡密错误');//卡密错误
            }
            $lotteryCard = $lotteryCard->toArray();
            if ($lotteryCard['card_state'] == 1) {
                return array('code' => 50002, 'message' => '二维码已被使用');//抽奖卡已被抽奖
            }
        }

        $data = [
            'member_id' => $member_id,
            'activity_id' => $lotteryActivity['activity_id'],
            'card_id' => !empty($lotteryCard['card_id']) ? $lotteryCard['card_id'] : 0,
            'activity_name' => $lotteryActivity['activity_name'],
            'introduction' => $lotteryActivity['introduction'],
            'activity_images' => $lotteryActivity['activity_images'],
            'tools_type' => $lotteryActivity['tools_type'],
            'activity_type' => $lotteryActivity['activity_type'],
            'cost_vrcoin' => $lotteryActivity['cost_vrcoin'],
            'company_id' => $lotteryActivity['company_id'],
            'two_dimension_code' => !empty($lotteryCard['two_dimension_code']) ? $lotteryCard['two_dimension_code'] : 0,
        ];

        return array('code' => 0, 'message' => '', 'data' => $data);
    }

    /**
     * 开始抽奖，主要包括：计算中奖奖项、保存中奖记录、更新用户虚拟币数、奖项奖品数量修改、二维码与抽奖卡使用状态修改
     * @return mixed
     */
    public function useLotteryCard($data)
    {
        Log::alert($data);
        extract($data);
        $time = time();
        try {
            DB::beginTransaction();

            $lotteryInfoList = $this->getLotteryInfo($activity_id);//获得所有奖项列表，用于计算概率
            $lotteryInfo = $this->calcLotteryProb($lotteryInfoList);//计算出所中奖项

            //中奖记录插入记录
            $awardsrecord_id = DB::table('member_awardsrecord')->insertGetId([
                'card_id' => $card_id,
                'order_id' => 0,
                'member_id' => $member_id,
                'company_id' => $company_id,
                'activity_id' => $activity_id,
                'activity_name' => $activity_name,
                'activity_images' => $activity_images,
                'tools_type' => $tools_type,
                'activity_type' => $activity_type,
                'prize_level' => $lotteryInfo['prize_level'],
                'prize' => $lotteryInfo['prize'],
                'sku_name' => $lotteryInfo['sku_name'],
                'sku_images' => $lotteryInfo['prize_image'],
                'exchange_type' => $lotteryInfo['exchange_type'],
                'prize_type' => $lotteryInfo['is_virtual'],
                'exchange_state' => $lotteryInfo['exchange_type'] == 1 ? 0 : 1,
                'created_at' => $time,
            ]);
            $data['awardsrecord_id'] = $awardsrecord_id;

            //玩一玩（花费）或抽奖（获得）虚拟币，更新用户虚拟币总账并记录明细
            if ($activity_type == 2 || $lotteryInfo['exchange_type'] == 2) {
                $yesb_data = [
                    'busine_id' => $awardsrecord_id,
                    'member_id' => $member_id,
                ];
                if ($activity_type == 2) {
                    $yesb_data['yesb_amount'] = $cost_vrcoin;
                    MemberYesbLog::ChangeBalance(13, $yesb_data);
                }
                if ($lotteryInfo['exchange_type'] == 2) {
                    $yesb_data['yesb_amount'] = $lotteryInfo['prize'];
                    MemberYesbLog::ChangeBalance(12, $yesb_data);
                }
            }

            if ($lotteryInfo['prize_level'] != 0) {
                //更新奖项奖品数量
                $update_info = LotteryInfo::find($lotteryInfo['id']);
                $update_info->prize_had_exchanged += 1;
                $update_info->save();
            }

            //抽奖卡、二维码状态修改
            if (!empty($card_id) && !empty($two_dimension_code)) {
                $card = LotteryCard::where('card_id', $card_id)->first();
                $card->card_state = 1;//已使用
                $card->updated_time = $time;
                $card->save();

                $card = TwoDimensionCode::where('two_dimension_code', $two_dimension_code)->first();
                $card->scan_count += 1;//扫描次数+1
                $card->updated_time = $time;
                $card->save();

                //插入二维码扫描记录
                DB::table('two_dimension_scan_log')->insert([
                    'member_id' => $member_id,
                    'two_dimension_code' => $two_dimension_code,
                    'result_code' => '0',
                    'result_message' => '',
                    'location_id' => 0,
                    'operate_time' => time()
                ]);
            }

            $data = array_merge($data, $lotteryInfo);
            DB::commit();
            return array('code' => 0, 'message' => '', 'data' => $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return array('code' => 50002, 'message' => '抽奖过程出错，请稍后重试');//抽奖过程出错，请稍后重试
        }
    }

    /**
     * 根据活动ID获得奖项设置信息
     */
    private function getLotteryInfo($activity_id)
    {
        $db_prefix = config('database')['connections']['mysql']['prefix'];

        //平台配置内容
        $plat_vrb_name = $this->getPlatSetting('plat_vrb_name');

        $lotteryInfoArr = [];
        $info_sql = "SELECT li.id,li.exchange_type,li.prize,li.prize_level,li.probability,gsk.sku_name,concat('" . $this->img_domain . "/',gsp.main_image) as prize_image,
                        gsp.mobile_content,li.prize_num,li.prize_had_exchanged,gsk.price,gsp.is_virtual
                        FROM " . $db_prefix . "lottery_info AS li
                        LEFT JOIN " . $db_prefix . "goods_sku AS gsk ON gsk.sku_id=li.prize
                        LEFT JOIN " . $db_prefix . "goods_spu AS gsp ON gsk.spu_id=gsp.spu_id
                        WHERE li.activity_id=" . $activity_id . " AND (li.use_state=1 OR li.prize_level=0) ORDER BY li.prize_level";
        $lotteryInfo = DB::select($info_sql);
        foreach ($lotteryInfo as $k => $info) {
            $infoArr = [];
            $infoArr['sku_name'] = $info->sku_name;
            $infoArr['prize_image'] = $info->prize_image;
            $infoArr['is_virtual'] = $info->is_virtual;;
            if ($info->exchange_type == 2) {
                $infoArr['prize_image'] = asset("img/lottery/zp_result_p.png");
                $infoArr['sku_name'] = $info->prize . $plat_vrb_name;
                $infoArr['is_virtual'] = 1;
            } else if ($info->exchange_type == 1) {
                if ($info->is_virtual == 1) {
                    $infoArr['prize_image'] = asset("img/lottery/zp_result_m.png");
                }
            }
            $infoArr['id'] = $info->id;
            $infoArr['exchange_type'] = $info->exchange_type;
            $infoArr['prize'] = $info->prize;
            $infoArr['prize_level'] = $info->prize_level;
            $infoArr['probability'] = $info->probability;
            $infoArr['prize_num'] = $info->prize_num;
            $infoArr['prize_had_exchanged'] = $info->prize_had_exchanged;
            $infoArr['price'] = $info->price;
            $lotteryInfoArr[$k] = $infoArr;
            $lotteryInfoArr[$k]['prizeProduct'] = array('sku_name' => $infoArr['sku_name'], 'prize_image' => $infoArr['prize_image'], 'price' => $info->price, 'introduction' => $info->mobile_content);
        }

        return $lotteryInfoArr;
    }

    /**
     * 计算中奖奖项
     */
    private function calcLotteryProb($lotteryInfoList)
    {
        $prizeArr = array();
        $prizenum = count($lotteryInfoList);

        //平台配置内容
        $plat_vrb_name = $this->getPlatSetting('plat_vrb_name');

        //奖项内容初始化
        for ($i = 0; $i < $prizenum; $i++) {
            $prizeArr[$i] = array('id' => $i, 'v' => $lotteryInfoList[$i % $prizenum]['probability']);
        }
        /*抽奖开始：1、将各个奖项概率形成概率数组；2、根据getRand()函数获得中奖项；
        3、中奖后立即判断是否剩余库存，如果没有，直接将中奖项修改为谢谢参与奖；*/
        foreach ($prizeArr as $val) {
            $arr[$val['id']] = $val['v'];
        }
        $rid = $this->wxgetRand($arr); //根据概率获取奖项id
        $prize = $lotteryInfoList[$rid % $prizenum];//奖项内容
        //判断是否剩余库存
        if ($prize['prize_level'] != 0 && $prize['prize_num'] < $prize['prize_had_exchanged'] + 1) {
            $prize = $lotteryInfoList[0];
        }
        $level_str_arr = array('谢谢参与', '一等奖', '二等奖', '三等奖', '四等奖', '五等奖', '六等奖', '七等奖');
        $prize['prize_level_str'] = $level_str_arr[$prize['prize_level']];
        $prize = array_merge($prize, empty($prize['prizeProduct']) ? array() : $prize['prizeProduct']);
        switch ($prize['exchange_type']) {
            case 2:
                $prize['prize_image'] = asset("img/lottery/lottery_points.png");
                $prize['sku_name'] = $prize['prize'] . $plat_vrb_name;
                break;
            case 1:
                if ($prize['is_virtual'] == 1) {
                    $prize['prize_image'] = asset("img/lottery/lottery_redpack.png");
//                    if ($prize['price'] >= 1) {
//                        Log::notice('explode');
//                        Log::alert(explode(".", $prize['price'])[0]);
//                        $prize['sku_name'] = (explode(".", $prize['price'])[0]) . "元红包";
//                        $prize['prize'] = (explode(".", $prize['price'])[0]);
//                    } else {
                        $prize['sku_name'] = $prize['price'] . "元红包";
                        //$prize['prize'] = $prize['price'];
//                    }
                }
                break;
        }
        return $prize;
    }

    function wxgetRand($proArr)
    {
        $result = '';
        //概率数组的总概率精度*100以适应小数概率
        $proSum = 1000 * array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $proCur *= 1000;
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }
}
