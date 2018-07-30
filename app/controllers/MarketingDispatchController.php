<?php

namespace App\controllers;

use App\facades\Api;
use App\models\qrcode\TwoDimensionCode;
use App\lib\YnCacheManage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * 营销活动路由分发控制器
 * ScanDispatchController extends BaseController
 */
class MarketingDispatchController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 验证数字码页面
     * @return mixed
     */
    public function checkNumberPage()
    {
        $builder = new CaptchaBuilder;

        // 设置图片宽高及字体
        $builder->build(config('yydwx.captcha_width'), config('yydwx.captcha_height'), config('yydwx.captcha_font'));

        // 获取验证码的内容
        $phrase = $builder->getPhrase();

        // 标识是谁的
        $identity = substr(md5(time() . microtime() . rand(0, 10000)), 0, 16);

        $captcha = [
            'image' => $builder->inline(),  // 验证码的内容
            'identity' => $identity,
            'phrase' => $phrase,
        ];

        YnCacheManage::saveCaptchaByIdentity($captcha);

        return view('marketing.check_number', compact('captcha'));
    }

    /**
     * 获取验证码
     * @return mixed
     */
    public function captcha()
    {
        $builder = new CaptchaBuilder;

        $builder->build(config('yydwx.captcha_width'), config('yydwx.captcha_height'), config('yydwx.captcha_font'));  //设置图片宽高及字体
        $phrase = $builder->getPhrase(); //获取验证码的内容
        $identity = substr(md5(time() . microtime() . rand(0, 10000)), 0, 16);

        $captcha = [
            'image' => $builder->inline(),  //验证码的内容
            'identity' => $identity,        //标识是谁的
            'phrase' => $phrase,
        ];

        YnCacheManage::saveCaptchaByIdentity($captcha);

        return Api::responseMessage(0, $captcha);
    }

    /**
     * 验证验证码(通过cache来校验)
     * 请求数据
     * {
     *      "captcha" = "";      //验证码
     *      "identity" = "";    //身份标识
     * }
     * 返回数据
     * return={
     *      "code"  = ""；   //0 成功
     *      "data" = {};
     *      "message" = "";
     * }
     * @param Request $request
     * @return  mixed
     */
    public function checkCaptcha(Request $request)
    {

        $captcha = $request->input('captcha');  //验证码
        $identity = $request->input('identity'); //身份标识

        $cache_captcha = YnCacheManage::getCaptchaByIdentity($identity);

        if ($cache_captcha) {
            $code = $captcha == $cache_captcha['phrase'] ? 0 : 10101;
        } else {
            $code = 10101;
        }

        return Api::responseMessage($code);
    }

    /**
     * 扫码
     * @return mixed
     */
    public function scan($code, Request $request)
    {
        header("Cache-Control:no-cache,must-revalidate,no-store"); // 这个no-store加了之后，Firefox下有效
        header("Pragma:no-cache");
        header("Expires:-1");

        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }

        $twoDimensionCode = TwoDimensionCode::select('company_id')->where('two_dimension_code', $code)->first();

        // 平台二维码
        if ($twoDimensionCode && !$twoDimensionCode->company_id) {

            // 查 member_other_account 表, 判断是否存在平台用户
            $opneid = DB::table('member_other_account')->where('member_id', $member_id)->value('account_id');

            $wechat_user = app('wechat')->user->get($opneid);

        } else {

            $company_token = $this->getCompanyAccessTokenById($twoDimensionCode->company_id);

            $auth_company_info_arr = $request->session()->pull('auth_company_info_arr');

            $get_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $company_token .
                '&openid=' . $auth_company_info_arr['openid'] . '&lang=zh_CN';

            $result = json_decode($this->getData($get_url));
            if (isset($result->errcode)) {
                Log::error('接口调用失败(代公众号调用获取用户信息接口), 微信错误码: ' . $result->errcode .
                    '; 微信错误信息: ' . $result->errmsg . ' => 控制器:MarketingDispatchController@scan');
                exit();
            }

            $wechat_user = $result;
        }

        $subscribe = 0;
        if (!empty($wechat_user->subscribe)) {
            $subscribe = $wechat_user->subscribe;
        }

        //20170524--把subscribe存入缓存，（存入session，具体活动中取不到），用subscribe_+memberid 为key ，供其它地方使用
        Cache::put('subscribe_' . $member_id, $subscribe, 2);

        //dd($wechat_user);
        // end of 20170524--把subscribe存入缓存

        //查询并检查二维码信息
        $twoDimensionCode = TwoDimensionCode::select('code_id', 'two_dimension_code', 'bind_type', 'bind_data', 'active_state', 'scan_count', 'bind_state')
            ->where('two_dimension_code', $code)->first();
        if (!$twoDimensionCode) {
            $errorData = array('code' => 50002, 'message' => '二维码错误');
            return view("errors.error", compact('errorData'));
        }

        $twoDimensionCode = $twoDimensionCode->toArray();
        if ($twoDimensionCode['active_state'] != 1) {
            $errorData = array('code' => 50002, 'message' => '二维码尚未被激活，请联系管理员');
            return view("errors.error", compact('errorData'));
        }

        if ($twoDimensionCode['scan_count'] != 0) {
            $errorData = array('code' => 50002, 'message' => '二维码已被兑换');
            return view("errors.error", compact('errorData'));
        }

        if ($twoDimensionCode['bind_state'] != 1) {
            $errorData = array('code' => 50002, 'message' => '二维码尚未绑定任何活动，暂不可使用');
            return view("errors.error", compact('errorData'));
        }

        // 路由分发
        $data_res = $this->marketingDispatch($member_id, $twoDimensionCode, 'scan');
        if (!empty($data_res['code'])) {
            $errorData = array('code' => $data_res['code'], 'message' => $data_res['message']);
            return view("errors.error", compact('errorData'));
        }

        $scanData = $data_res['data'];
        $page = $data_res['page'];
        //平台配置内容
        $scanData['plat_vrb_name'] = $this->getPlatSetting('plat_vrb_name');
        return view($page, compact('scanData'));
    }

    /**
     * 二维码活动路由分发
     */
    private function marketingDispatch($member_id, $twoDimensionCode, $checkType)
    {
        $data = [];
        $ids = explode(',', $twoDimensionCode['bind_data']);

        switch ($twoDimensionCode['bind_type']) {

            // 购物扫码
            case 1:
                break;

            // 会员卡
            case 2:
                $membershipController = new MemberShipController();

                // 判断是扫码还是输入卡密方式进入系统
                $data = $checkType == 'scan' ?
                    $membershipController->scan($member_id, $ids[0], $ids[1]) :
                    $membershipController->submit($member_id, $ids[0], $ids[1]);

                break;

            // 礼品卡
            case 3:
                $couponController = new MemberCouponController();

                // 判断是扫码还是输入卡密方式进入系统
                $data = $checkType == 'scan' ?
                    $couponController->scan($member_id, $ids[0], $ids[1]) :
                    $couponController->submit($member_id, $ids[0], $ids[1]);

                break;

            // 充值卡
            case 4:
                $rechargeController = new MemberRechargeController();

                // 判断是扫码还是输入卡密方式进入系统
                $data = $checkType == 'scan' ?
                    $rechargeController->scan($member_id, $ids[0], $ids[1]) :
                    $rechargeController->submit($member_id, $ids[0], $ids[1]);

                break;

            // 抽奖
            case 5 || 7:
                $lotteryController = new MemberLotteryController();
                $data = $lotteryController->scan($member_id, $ids[0], $ids[1]);
                break;
        }

        if (empty($data['page'])) {
            $data['page'] = $this->getMarketingPage($twoDimensionCode['bind_type']);
        }

        return $data;
    }

    /**
     * 获得营销活动页面
     */
    private function getMarketingPage($type)
    {
        $page = '';
        switch ($type) {
            case 1: //购物扫码
                break;

            case 2: //会员卡
                $page = 'marketing.scan_membership';
                break;

            case 3: //礼品卡
                $page = 'marketing.chose_gift_goods';
                break;

            case 4: //充值卡
                $page = 'marketing.scan_recharge';
                break;

            case 5:  //抽奖
                $page = 'marketing.lottery';
                break;

            case 7:  //红包
                $page = 'marketing.redpack';
                break;
        }

        return $page;
    }

    /**
     * 提交验证数字码
     * @return mixed
     */
    public function submit(Request $request)
    {
        $numberCode = $request->input('numberCode');
        $mobile = $request->input('mobile');
        $vcode = trim($request->input('vcode') . '');
        $code_info = $this->phoneMsgVeify($vcode, $mobile);

        if ($code_info == 0) {
            return Api::responseMessage(1002, null, "手机验证码错误！");
        } else if ($code_info == -1) {
            return Api::responseMessage(1003, null, "验证码已失效，请重新获取！");
        }

        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return Api::responseMessage(50000, '', '登陆信息失效，请重新登陆');
        }

        //查询并检查二维码信息
        $twoDimensionCode = TwoDimensionCode::where('two_dimension_number_code', $numberCode)
            ->select('code_id', 'two_dimension_code', 'bind_type', 'bind_data', 'active_state', 'scan_count', 'bind_state')
            ->first();

        if (!$twoDimensionCode) {
            return Api::responseMessage(50002, '', '卡密错误');
        }

        $twoDimensionCode = $twoDimensionCode->toArray();

        if ($twoDimensionCode['active_state'] != 1) {
            return Api::responseMessage(50002, '', '该卡尚未被激活，请联系管理员');
        }

        if ($twoDimensionCode['scan_count'] != 0) {
            return Api::responseMessage(50002, '', '该卡已被兑换');
        }

        if ($twoDimensionCode['bind_state'] != 1) {
            return Api::responseMessage(50002, '', '该卡尚未绑定任何活动，暂不可使用');
        }

        //路由分发
        $data_res = $this->marketingDispatch($member_id, $twoDimensionCode, 'input');
        if (!empty($data_res['code'])) {
            return Api::responseMessage($data_res['code'], '', $data_res['message']);
        }

        $ids = explode(',', $twoDimensionCode['bind_data']);
        $data = [
            'activity_id' => $ids[0],
            'card_id' => $ids[1],
            'type' => $twoDimensionCode['bind_type']
        ];

        if (!empty($data_res['data']['giftcoupon_id'])) {
            $data['giftcoupon_id'] = $data_res['data']['giftcoupon_id'];
        }

        return Api::responseMessage(0, $data);
    }

    /**
     * 检查验证码及手机号是否为原来发送的手机号及验证码
     * @param $vcode    string  验证码
     * @param $phonenum string  手机号
     * @return int -1 验证码失效， 0 验证码不正确， 1 验证码正确
     */
    private function phoneMsgVeify($vcode, $phonenum)
    {
        // 传入验证码 md5 加密
        $vcode = trim($vcode . '');
        $vcode = md5($vcode);

        // 读取缓存中的验证码，通过 GetPhoneMsgCodeController.sendPhoneMessage() 保存到缓存中的
        $verification_code = Cache::get('PhoneMsgCode');
        if (empty($verification_code)) {
            // 验证码失效
            return -1;
        }

        // 读取缓存验证手机号，通过 GetPhoneMsgCodeController.sendPhoneMessage() 保存缓存
        $verification_phone = Cache::get('PhoneNumber');
        if ($vcode == $verification_code && $phonenum == $verification_phone) {
            return 1;
        } else {
            return 0;
        }
    }
}
