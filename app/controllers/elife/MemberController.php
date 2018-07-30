<?php

namespace App\controllers\elife;

use App\facades\Api;
use App\Http\Middleware\WechatOauthMiddleware;
use App\models\article\Document;
use App\models\cjk\CjkBecome;
use App\models\dct\DctBusineType;
use App\models\dct\DctYesbRule;
use App\models\member\Member;
use App\models\member\MemberAwardsRecord;
use App\models\member\MemberExtend;
use App\models\member\MemberGiftCoupon;
use App\models\member\MemberOtherAccount;
use App\models\member\MemberPublicInfo;
use App\models\member\MemberShareCards;
use App\models\member\MemberShip;
use App\models\member\MemberShipActivity;
use App\models\member\MemberYesbLog;
use App\models\member\MemberWalletLog;
use App\models\member\MemberBalanceLog;
use App\models\member\MemberCart;
use App\models\member\MemberAddress;
use App\models\dct\DctArea;
use App\models\member\ShareCardGetRecords;
use App\models\order\Order;
use App\models\plat\PlatSetting;
use App\models\store\StoreNoticeMan;
use App\pro\dao\member\MemberShipDao;
use App\pro\dao\member\MemberShipGoodsSkuDao;
use App\pro\dao\website\AdvertDao;
use Carbon\Carbon;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MemberController extends BaseController
{
    /**
     * 我的(个人中心首页)
     * @return View
     */
    public function index() {

        $member = $this->loseEfficacy();
		//判断窗口类型
		$getEntranceType = DB::table('member')->where('cust_id',$member->cust_id)->first();
        // 订单总数
        $all_num = Order::where('member_id', $member->member_id)->where('entrance_type',$getEntranceType->entrance_type)->count();

        // 1:（已下单）待付款
        $payment_num = Order::where('member_id', $member->member_id)
            ->where('plat_order_state', 1)->where('entrance_type',$getEntranceType->entrance_type)->count();

        // 2:（已付款）待发货
        $delivered_num = Order::where('member_id', $member->member_id)
            ->where('plat_order_state', 2)->where('entrance_type',$getEntranceType->entrance_type)->count();

        // 3:（已发货）待收货
        $received_num = Order::where('member_id', $member->member_id)
            ->where('plat_order_state', 3)->where('entrance_type',$getEntranceType->entrance_type)->count();

        // 系统目前没有设置评价业务，待评价（4）和已完成（9） 都是完成
        $pingjia_num = Order::where('member_id', $member->member_id)
            ->where('plat_order_state',4)->where('entrance_type',$getEntranceType->entrance_type)->count();

        $success_num = Order::where('member_id', $member->member_id)
            ->where(function ($query) {
                $query->where('plat_order_state', 9)
                    ->orwhere(function ($query) {
                        $query->where('plat_order_state', 4);
                    });
            })->where('entrance_type',$getEntranceType->entrance_type)->count();
        //-2:(退款)
        $refund_num = DB::table('service_order')->where('buy_id',$member->member_id)->where('supplier_state',3)->where('entrance_type',$getEntranceType->entrance_type)->count();

        $state_num_arr = array('all_num' => $all_num,
            'payment_num' => $payment_num,
            'delivered_num' => $delivered_num,
            'received_num' => $received_num,
            'pingjia_num' => $pingjia_num,
            'success_num' => $success_num,
            'refund_num' => $refund_num);

        //统计购物车商品数量
        $goods_num_in_cart = $this->shop_cart();
        return view("elife.user.wx_user", compact('member', 'state_num_arr', 'goods_num_in_cart'));
    }

    private function shop_cart()
    {
        $member = $this->loseEfficacy();
		//判断窗口类型
		$getEntranceType = DB::table('member')->where('cust_id',$member->cust_id)->first();
        //统计购物车中商品的总数量，显示出目前购物车中商品中的数量
		$goods_num_in_cart = DB::table('member_cart')->where('member_id',$member->member_id)->where('entrance_type',$getEntranceType->entrance_type)->sum('number');
        return $goods_num_in_cart;
    }


    /**
     * 如果传入会员等级参数, 则指定购买, 相当于续费;
     * 如果不指定, 则展示比当前会员级别高的会员等级进行选择购买
     * @param int $grade 会员等级
     * @return Redirect | View
     */
    public function buy($grade = 0)
    {
        $member_obj = Auth::user();
        // 购买指定会员等级
        if ($grade) {
            // 查询该等级对应的线上会员卡活动(相当于会员支付项目)
            $activity = MemberShipActivity::getByGradeWithLineOn($grade)->first()->toArray();
            if (!$activity) {
                $errorData = [
                    'message' => '该会员等级已停止服务, 无法购买, 详情请咨询客服'
                ];
                return view("errors.e_error", compact('errorData'));
            }

            // 获取该会员等级下的可续费的缴费项目（就是线上会员活动），如日卡、月卡、年卡
            $member_class_arr = [];
            $result = PlatSetting::memberClassWithNotFree()->get()->toArray();
            $result = $this->getMemberClass($result);
            $pay_item_arr = MemberShipActivity::getByGradeWithLineOn($grade)->get();
            $member_class_arr[$grade] = $result[$grade];
            $member_class_arr[$grade]['pay_item_arr'] = $pay_item_arr;

        }else{

            // 如果没有可以使用的线上会员活动, 则报错提示用户平台暂时无法提升更高的等级
            $is_none_enable = true;

            // 未指定要购买的会员等级, 则展示比当前会员等级高的会员级别在进行购买
            $member = Auth::user();
            $member_class_arr = PlatSetting::memberClassWithNotFree()->get()->toArray();
            $member_class_arr = $this->getMemberClass($member_class_arr);
            foreach ($member_class_arr as $key => $member_class) {
                // 如果小于当前用户会员等级, 这不在供用户选择
                if ($key <= $member->grade) {
                    unset($member_class_arr[$key]);
                } else {
                    $pay_item_arr = MemberShipActivity::getByGradeWithLineOn($member_class['grade_code'])->get()->toArray();
                    $member_class_arr[$key]['pay_item_arr'] = $pay_item_arr;
                    if ($pay_item_arr) {
                        $is_none_enable = false;
                    }
                }
            }

            if ($is_none_enable) {
                $errorData = [
                    'message' => '该会员等级已是最高等级, 无法再进行提升, 详情请咨询客服'
                ];
                return view("errors.e_error", compact('errorData'));
            }

        }
        //体验会员
        $activity = MemberShipActivity::where('grade',20)->where('use_type',2)->where('activity_state',1)->where('exp_date',30)->first(['activity_id']);
        $param_grade=$grade;
        return view('member.buy', compact('member_class_arr','member_obj','activity','param_grade'));
    }

    /**
     * 会员详请(管家中心 => 点击会员图标 => 会员详情)
     * @param MemberShipDao $memberShipDao
     * @return View
     */
    /*public function detail(MemberShipDao $memberShipDao)
    {
        $member = Auth::user()->toArray();

        $member['membership_record'] = $memberShipDao->getRecordByMemberIdForViewData($member['member_id']);

        return view('member.detail', compact('member'));
    }*/

    /**
     * 更多卡券
     */
    public function moreCard()
    {
        if (Auth::user()) {
            $member = Auth::user();
            $member_id = $member->member_id;
        } else {
            redirect('/oauth');
        }
        $member = Member::find($member_id);

        //我的资产-》礼券数量
        $coupon_num = MemberGiftCoupon::where('member_id', $member->member_id)
            ->where('use_state', '<>', -1)->count();
        $wallet_num_arr = array('coupon_num' => $coupon_num);

        //当前登录者，所邀请的总人数
        // $num = MemberExtend::where('pid', Auth::user()->member_id)->count();*/

        //获取虚拟币名称【外部简称】
        $plat_vrb_caption = $this->getPlatVrbCaption();

        return view("user.my_more_card", compact('member', 'plat_vrb_caption', 'wallet_num_arr'));


    }

    /**
     *  用户注册信息保存
     */
    public function saveRegisterSave(Request $request)
    {
        // 目前仅支持手机号注册
        $login_name = trim($request->input('username') . '');
        $password = trim($request->input('password') . '');
        $login_phonenum = trim($request->input('username') . '');
        $yzm_msg = trim($request->input('vcode') . '');

        // 用户注册时，只能用手机号注册，判断用户是否注册过
        if ($this->ckLoginName($login_name)) {
            $data['error'] = "用户名已存在！";
            $data['state'] = false;
            return Api::responseMessage(0, $data, "false");
        }


        // 手机号是否绑定到其它账户
        $bind_member_id = Member::CheckMobileIsNotBind($login_phonenum);
        if ($bind_member_id != -1) {
            $data['error'] = "该手机号已经绑定到其它用户，不能再注册了！";
            $data['state'] = false;
            return Api::responseMessage(0, $data, "false");
        }

        if ($password == '') {
            $data['error'] = "登录密码不能为空！";
            $data['state'] = false;
            return Api::responseMessage(0, $data, "false");
        }

        //判断验证码是否正确【-1 验证码失效， 0 验证码不正确， 1 验证码正确】
        $code_info = $this->phoneMsgVeify($yzm_msg, $login_phonenum);
        if ($code_info == 0) {
            $data['error'] = "手机验证码错误！";
            $data['state'] = false;
            return Api::responseMessage(0, $data, "false");
        } else if ($code_info == -1) {
            $data['error'] = "验证码已失效，请重新获取！";
            $data['state'] = false;
            return Api::responseMessage(0, $data, "false");
        }


        // 添加用户信息，默认手机通过验证，已经绑定手机
        // grade 会员类别【10:普通会员;20:黄金会员;30:钻石会员;40:黑卡VIP】
        // 创建用户时，默认登录密码和支付密码一致
        $data = [
            'member_name' => $login_name,
            'passwd' => md5($password),
            'pay_paypwd' => md5($password),
            'regist_time' => time(),
            'mobile' => $login_phonenum,
            'grade' => 10,
            'mobile_bind' => 1,
            'use_state' => 1
        ];

        $member = Member::create($data);
        if (!$member->exists) {
            $data['error'] = "注册失败！";
            $data['state'] = false;
            return Api::responseMessage(0, $data, "false");
        } else {
            $data['error'] = "注册成功！";
            $data['state'] = true;
            $member_id = $member->member_id;

            //登录
            Auth::loginUsingId($member_id);
            return Api::responseMessage(1, $data, "");
        }
    }

    /**  检查数据库中是否存在该用户名
     *  $login_name     string  登录用户名
     * @return boolean 存在（true）/不存在（false）
     */
    private function ckLoginName($login_name)
    {
        $member = Member::where('member_name', $login_name)->first();
        if ($member) {
            return true;
        } else {
            return false;
        }
    }

    /** 检查验证码及手机号是否为原来发送的手机号及验证码
     * @param $vcode    string  验证码
     * @param $phonenum string  手机号
     * @return int -1 验证码失效， 0 验证码不正确， 1 验证码正确
     */
    private function phoneMsgVeify($vcode, $phonenum)
    {
        // 传入验证码 md5 加密
        $vcode = trim($vcode . '');
        $vcode = md5($vcode);

        // 读取缓存验证码，通过 GetPhoneMsgCodeController.sendPhoneMessage() 保存缓存
        $verification_code = Cache::get('PhoneMsgCode');
        if (empty($verification_code)) {
            // 验证码失效
            return -1;
        }

        // 读取缓存验证手机号，通过 GetPhoneMsgCodeController.sendPhoneMessage() 保存缓存
        $verification_phone = Cache::get('PhoneNumber');
        if (($vcode == $verification_code) &&
            ($phonenum == $verification_phone)
        ) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     *  用户密码登录提交
     */
    public function userLoginSubmit(Request $request)
    {
        $login_name = $request->input('username');
        $password = md5($request->input('password'));
        $member = Member::where('member_name', $login_name)->first();

        if ($member) {
            if ($member->passwd == $password) {
                Auth::loginUsingId($member->member_id);
                return Api::responseMessage(1, '', "登录成功！");
            } else {
                return Api::responseMessage(0, '', "密码不正确！");
            }
        } else {
            return Api::responseMessage(0, '', "用户名不存在！");
        }
    }

    /**
     *  注册用户更改密码
     */
    public function userPasswordUpdate(Request $request)
    {
        $login_name = $request->input('username');
        $password = $request->input('password');
        $login_phonenum = $request->input('username');
        $yzm_msg = $request->input('vcode');

        // 用户注册时，只能用手机号注册，判断用户是否注册过
        if (!$this->ckLoginName($login_name)) {
            $data['msg'] = "该用户不存在！";
            $data['state'] = false;
            return Api::responseMessage(0, $data, "false");
        }

        //判断验证码是否正确【-1 验证码失效， 0 验证码不正确， 1 验证码正确】
        $code_info = $this->phoneMsgVeify($yzm_msg, $login_phonenum);
        if ($code_info == 0) {
            $data['msg'] = "手机验证码错误！";
            $data['state'] = false;
            return Api::responseMessage(0, $data, "false");
        } else if ($code_info == -1) {
            $data['msg'] = "验证码已失效，请重新获取！";
            $data['state'] = false;
            return Api::responseMessage(0, $data, "false");
        }

        Member::where('member_name', $login_name)->update(['passwd' => md5($password)]);

        //修改成功后，注销以前登陆的，重新进行登录
        Auth::logout();
        $data['msg'] = "密码修改成功，请重新登陆！！";
        $data['state'] = true;
        return Api::responseMessage(1, $data, "");
    }

    /** 重置虚拟币及钱包支付密码，该业务需要手机验证码
     *  传入参数：
     * @param $member_id int    当前用户ID
     * @param $phonenum string  手机号
     * @param $vcode    string  验证码
     * @param $paypwd   string  支付密码
     *  传出参数：
     * @return $return_value  int 【0:重置成功;1:重置失败;
     *      1000:用户不存在;1001:手机号为空;1002:验证码不正确;1003:验证码失效;
     *      1005:支付密码为空;】
     */
    public function userPayPwdUpdate(Request $request)
    {
        // 如果不传入用户ID，默认当前登录用户
        $member_id = (int)($request->input('member_id'));
        if ($member_id <= 0) {
            $member = Auth::user();
            if ($member) {
                $member_id = $member->member_id;
            }
        }

        // 需绑定的用户
        $obj_member = Member::where('member_id', $member_id)->first();
        if (!$obj_member) {
            return Api::responseMessage(1000, null, "用户不存在！");
        }

        $mobile = trim($request->input('mobile') . '');
        if ($mobile == '') {
            return Api::responseMessage(1001, null, "手机号不能为空！");
        }


        //判断验证码是否正确【-1 验证码失效， 0 验证码不正确， 1 验证码正确】
        $vcode = trim($request->input('vcode') . '');
        $code_info = $this->phoneMsgVeify($vcode, $mobile);
        if ($code_info == 0) {
            return Api::responseMessage(1002, null, "手机验证码错误！");
        } else if ($code_info == -1) {
            return Api::responseMessage(1003, null, "验证码已失效，请重新获取！");
        }


        $paypwd = trim($request->input('paypwd') . '');
        if ($paypwd == '') {
            return Api::responseMessage(1005, null, "支付密码不能为空！");
        }


        // 更新支付密码
        $data = array();
        $data['pay_paypwd'] = md5($paypwd);
        $obj_member->update($data);
        // Member::where('member_id',$member_id)->update($data);
        return Api::responseMessage(0, (array)$obj_member, "支付密码保存成功");
    }

    /** 验证支付密码
     *  传入参数：
     * @param $member_id int    当前用户ID
     * @param $paypwd  string   支付密码
     *  传出参数：
     * @return $return_value  int 【0:重置成功;1:重置失败;
     *      1000:用户不存在;1001:手机号为空;1002:验证码不正确;1003:验证码失效;
     *      1005:支付密码为空;1006:支付密码不正确;】
     */
    public function checkPayPwd(Request $request)
    {
        // 如果不传入用户ID，默认当前登录用户
        $member_id = (int)($request->input('member_id'));
        if ($member_id <= 0) {
            $member = Auth::user();
            if ($member) {
                $member_id = $member->member_id;
            }
        }

        // 当前支付用户
        $obj_member = Member::where('member_id', $member_id)->first();
        if (!$obj_member) {
            return Api::responseMessage(1000, null, "用户不存在！");
        }


        $paypwd = trim($request->input('paypwd') . '');
        /*if ($paypwd == '')
        {
            return Api::responseMessage(1005, null, "支付密码不能为空！");
        }*/

        $paypwd = md5($paypwd);
        if ($paypwd == ($obj_member->pay_paypwd)) {
            return Api::responseMessage(0, null, "支付密码正确！");
        } else {
            return Api::responseMessage(1006, null, "支付密码不正确！");
        }
    }

    /** 通过微信注册的用户，如果没有绑定手机，需要绑定手机
     *  因为所有的支付、密码变更等，需要通过手机获取验证码
     *  传入参数：
     * @param $member_id int    当前用户ID
     * @param $phonenum string  手机号
     * @param $vcode    string  验证码
     *  传出参数：
     * @return $return_value  int 【0:绑定成功;1:绑定失败;
     *      1000:用户不存在;1001:手机号为空;1002:验证码不正确;1003:验证码失效;
     *      1004:手机已绑定到其它用户;】
     */
    public function mobileBind(Request $request)
    {
        // 如果不传入用户ID，默认当前登录用户
        $member_id = (int)($request->input('member_id'));
        if ($member_id <= 0) {
            $member = Auth::user();
            if ($member) {
                $member_id = $member->member_id;
            }
        }

        // 需绑定的用户
        $obj_member = Member::where('member_id', $member_id)->first();
        if (!$obj_member) {
            return Api::responseMessage(1000, null, "用户不存在！");
        }

        $mobile = trim($request->input('mobile') . '');
        if ($mobile == '') {
            return Api::responseMessage(1001, null, "手机号不能为空！");
        }


        //判断验证码是否正确【-1 验证码失效， 0 验证码不正确， 1 验证码正确】
        $vcode = trim($request->input('vcode') . '');
        $code_info = $this->phoneMsgVeify($vcode, $mobile);
        if ($code_info == 0) {
            return Api::responseMessage(1002, null, "手机验证码错误！");
        } else if ($code_info == -1) {
            return Api::responseMessage(1003, null, "验证码已失效，请重新获取！");
        }


        // 手机号是否绑定到其它账户,
        // $bind_member_id -1，手机号未绑定；$bind_member_id 非-1，已和某一个用户绑定
        $bind_member_id = Member::CheckMobileIsNotBind($mobile);
        if ($bind_member_id != -1) {

            if ($bind_member_id <> $member_id) {
                // 1004:手机已绑定到其它用户;
                return Api::responseMessage(1004, null, "手机已绑定到其它用户！");
            }
        }

        // 当前用户绑定手机
        $data = array();
        $data['mobile'] = $mobile;
        $data['mobile_bind'] = 1;
        $obj_member->update($data);
        // Member::where('member_id',$member_id)->update($data);

        // 0:绑定成功
        return Api::responseMessage(0, (array)$obj_member, "绑定成功");
    }

    /**
     * 虚拟币明细
     */
    public function vrcoinLog()
    {
        $member = Member::where('member_id', Auth::user()->member_id)->first();  //当前用户

        //获得商城业务字典
        $busine_arr = [];
        $busine_code = DctBusineType::select('code_id', 'code_name')
            ->where('is_use', 1)
            ->get()
            ->toArray();
        foreach ($busine_code as $item) {
            $busine_arr[$item['code_id']] = $item['code_name'];
        }


        //当前用户虚拟币全部明细
        $earnLogs = [];     //当前用户虚拟币收入明细
        $payLogs = [];      //当前用户虚拟币支出明细
        $allLogs = MemberYesbLog::select('create_time', 'yesb_amount',
            'busine_type', 'busine_content', 'rule_name')
            ->where('member_id', Auth::user()->member_id)->orderBy('create_time', 'desc')
            ->get();
        if ($allLogs) {
            $allLogs = $allLogs->toArray();
            foreach ($allLogs as $k => & $log) {
                $log['create_time'] = date('Y-m-d H:i:s', $log['create_time']);
                $log['color'] = $log['yesb_amount'] > 0 ? 'red' : 'green';
                $log['busine_type'] = $busine_arr[$log['busine_type']];

                if ($log['yesb_amount'] > 0) {
                    $log['yesb_amount'] = '+' . $log['yesb_amount'];
                    $earnLogs[] = $log;
                } else {
                    $payLogs[] = $log;
                }
            }
        }

        // 抵现金额【即虚拟币相当于多少人民币】
        $cashAmount = 0;
        foreach ($payLogs as $log) {
            $cashAmount += floatval($log['yesb_amount']);
        }

        // 计算支出的虚拟币相当于多少人民币，保留2位小数
        $plat_vrb_rate = $this->getPlatVrbRate();
        $cashAmount = bcdiv(abs($cashAmount), $plat_vrb_rate, 2);

        //获取虚拟币名称【外部简称】
        $plat_vrb_caption = $this->getPlatVrbCaption();

        return view("user.vrcoin_log", compact('member', 'allLogs',
            'payLogs', 'earnLogs', 'cashAmount', 'plat_vrb_caption'));
    }

    /**
     * 卡余额明细
     */
    public function balanceLog()
    {
        $member = Member::where('member_id', Auth::user()->member_id)->first();  //当前用户

        //当前用户卡余额明细
        $logs = MemberBalanceLog::select('create_time', 'av_amount',
            'busine_type', 'busine_content', 'realtime_balance')
            ->where('member_id', Auth::user()->member_id)
            ->where('av_amount', '<>', 0)->orderBy('create_time', 'desc')
            ->get();

        //当前用户卡余额支出总额
        $payLogs = DB::table('member_balance_log')
            ->select(DB::raw('SUM(av_amount) as total_pay'))
            ->where('member_id', Auth::user()->member_id)
            ->where('av_amount', '<', 0)
            ->get();

        $totalPay = empty($payLogs[0]->total_pay) ? '0.00' : abs($payLogs[0]->total_pay);
        return view("user.balance_log", compact('member', 'logs', 'totalPay'));
    }

    /**
     * 零钱明细
     */
    public function walletLog()
    {
        $member = Member::where('member_id', Auth::user()->member_id)->first();  //当前用户

        //获得商城业务字典
        $busine_arr = [];
        $busine_code = DctBusineType::select('code_id', 'code_name')->where('is_use', 1)->get()->toArray();
        foreach ($busine_code as $item) {
            $busine_arr[$item['code_id']] = $item['code_name'];
        }

        //当前用户零钱明细
        $logs = MemberWalletLog::select('create_time', 'av_amount',
            'busine_type', 'busine_content', 'realtime_balance')
            ->where('member_id', Auth::user()->member_id)
            ->where('av_amount', '<>', 0)
            ->orderBy('create_time', 'desc')
            ->get();

        if ($logs) {
            $logs = $logs->toArray();
            foreach ($logs as $k => & $log) {
                $log['create_time'] = date('Y-m-d H:i:s', $log['create_time']);
                $log['av_amount'] = $log['av_amount'] > 0 ? '+' . $log['av_amount'] : $log['av_amount'];
                $log['color'] = $log['av_amount'] > 0 ? 'red' : 'green';
                $log['busine_type'] = $busine_arr[$log['busine_type']];
            }
        }

        //当前用户支出总额
        $payLogs = DB::table('member_wallet_log')
            ->select(DB::raw('SUM(av_amount) as total_pay'))
            ->where('member_id', Auth::user()->member_id)
            ->where('av_amount', '<', 0)
            ->get();

        //当前用户充值总额
        $rechargeLogs = DB::table('member_wallet_log')
            ->select(DB::raw('SUM(av_amount) as total_recharge'))
            ->where('member_id', Auth::user()->member_id)
            ->where('av_amount', '>', 0)
            ->get();

        $totalPay = empty($payLogs[0]->total_pay) ? '0.00' : abs($payLogs[0]->total_pay);
        $totalRecharge = empty($rechargeLogs[0]->total_recharge) ? '0.00' : $rechargeLogs[0]->total_recharge;
        return view("user.wallet_log", compact('member', 'logs', 'totalPay', 'totalRecharge'));
    }

    /**
     * 中奖记录
     * 传入参数：
     * @return mixed
     */
    public function awardsRecord()
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

        //获取用户抽奖卡列表，同时计算最大可提现金额
        $awardsRecordList = MemberAwardsRecord::select(
            'awardsrecord_id', 'company_id', 'activity_id', 'order_id', 'activity_type', 'sku_name', 'sku_images',
            'prize_level', 'prize', 'exchange_type', 'prize_type', 'exchange_state', 'created_at'
        )->where('member_id', $member_id)->orderBy('created_at', 'desc')->get();
        if ($awardsRecordList) {
            $awardsRecordList = $awardsRecordList->toArray();
        }
        foreach ($awardsRecordList as &$record) {
            if ($record['exchange_type'] == 1) {
                $record['prize_name'] = $record['sku_name'];
                if ($record['prize_type'] == 0) {
                    $record['prize_image'] = $record['sku_images'];
                } else {
                    $record['prize_image'] = asset('img/lottery/redpack.png');
                }
            } else if ($record['exchange_type'] == 2) {
                $record['prize_name'] = $record['sku_name'];
                $record['prize_image'] = asset('img/lottery/vrcoin.png');
            } else {
                $record['prize_name'] = '谢谢参与';
                $record['prize_image'] = asset('img/lottery/nothing.png');
            }
            if ($record['exchange_state'] == 0 && empty($record['order_id'])) {
                $record['btn_class'] = 'btn';
                $record['btn_name'] = '立即领取';
            } else {
                $record['btn_class'] = 'btn1';
                $record['btn_name'] = '已领取';
            }
            $record['prize_time'] = date('Y.m.d H:i:s', $record['created_at']);
        }

        return view('user.awards_record', compact('awardsRecordList'));
    }

    /**
     * 成为纯酒客页面
     * @return  mixed
     */
    public function Shareholder(Request $request)
    {
        $cjkBecome = CjkBecome::where('member_id', Auth::user()->member_id)->get();
        return view("personal.becomeShareholders", compact('cjkBecome'));
    }

    /**
     * 人脉
     *  1获取用户
     *  2取出 会员扩展表 pid = 当前用户
     * @return  mixed
     */
    public function contacts(Request $request)
    {
        $id = Auth::user()->member_id;

        // 2——会员注册（邀请注册，邀请者可获得 5 个虚拟币）
        $count = DctYesbRule::where('rule_id', 2)->value('yesb_amount');

        //首先获取我的推荐人
        $pid = MemberExtend::where('member_id', $id)->value('pid');
        $pmember = null;

        // 我的邀请者
        if ($pid) {
            $pmember = Member::where('member_id', $pid)->first();
        }

        $ones = MemberExtend::where('pid', $id)->get();
        $members = [];
        $num = (MemberExtend::where('pid', $id)->count()) * $count;   //注册一个人是多少酒币
        $twos = null;
        $thress = null;
        foreach ($ones as $one) {
            //获取二级推荐 （我推荐的人又去推荐别的人）
            $twos = MemberExtend::where('pid', $one->member_id)->get();
            array_push($members, Member::where('member_id', $one->member_id)->first()->toArray());
            foreach ($twos as $two) {
                $thress = MemberExtend::where('pid', $two->member_id)->get();
            }
        }

        return view("personal.contacts")
            ->withPmember($pmember)//我的推荐人
            ->withNum($num)//推荐获取的总酒币
            ->withCount($count)//积分规则，丛数据库中取出来的（定死的）
            ->withOnes($ones)//我的一级推荐记录
            ->withMembers($members)//我的一级推荐r人
            ->withTwos($twos)//我的二级推荐记录
            ->withThress($thress);      //我的三级推荐记录
    }

    /**
     * 成为纯酒客
     * @return  string
     */
    public function becomeCjk(Request $request)
    {
        try {
            $data = [
                'member_id' => $request->input('member_id'),
                'real_name' => $request->input('name'),
                'regist_money' => $request->input('money'),
                'real_money' => 0,
                'regist_time' => time(),
                'real_time' => "",
                'mobile' => $request->input('tel'),
                'state' => 0,
            ];

            $cjkBecome = CjkBecome::create($data);
            return $cjkBecome;

            //'申请成功，稍后会有客服跟您联系';
//            $user = CjkBecome::where('id', $request->input('member_id'))->count();
//            if ($user != 0) {
//                return 1;//"该用户已经申请，稍后会有客服跟您联系";
//            } else {
//                CjkBecome::create($data);
//                return 2;//'申请成功，稍后会有客服跟您联系';
//            }
        } catch (\Exception $e) {
            return null;//'申请失败，请稍后再试';
        }
    }

    /**
     * 管家中心
     * 如果是会员, 进入管家会员中心服务页面
     * 如果不是会员, 进入开通会员页面
     * @return View
     */
    public function center(MemberShipGoodsSkuDao $memberShipGoodsSkuDao,MemberShipDao $memberShipDao, AdvertDao $advertDao)
    {
        if (Auth::user()) {
            $member_obj = Auth::user();
        } else {
            redirect('/oauth');
        }

        //微信jsapi
        $signPackage = session('signPackage');

        // 如果会员等级是 10:普通会员, 页面为购买会员页
        if ($member_obj->grade == 10) {

            // 获取平台设置的会员等级
            if($member_obj->exp_member == 1){
                $member_class_arr = PlatSetting::memberClassWithNotFree()->get()->toArray();
                unset($member_class_arr[0]);
            }else{
                $member_class_arr = PlatSetting::memberClassWithNotFree()->get()->toArray();
            }
            // 匹配添加会员等级码(grade)
            $member_class_arr = $this->getMemberClass($member_class_arr);

            // 每个等级下可充值购买的会员卡(相当于用于开通会员的缴费项目)
            foreach ($member_class_arr as & $member_class) {
                $member_class['pay_item_arr'] = MemberShipActivity::getByGradeWithLineOn($member_class['grade_code'])->get()->toArray();
            }

            $param_grade=0;
            $activity = MemberShipActivity::where('grade',20)->where('use_type',2)->where('activity_state',1)->where('exp_date',30)->first(['activity_id']);
            return view('member.buy', compact('member_class_arr','signPackage','member_obj','activity','param_grade'));
        }

        $class_info = $this->getMemberClass();

        $mc_advert = $advertDao->getMemberCenterAdvert();
        $goods_list = $memberShipGoodsSkuDao->getGoodsListByGradeForViewData($member_obj->grade);
        foreach($goods_list as $img){
            $img->main_image = $this->img_domain.'/'.$img->main_image;
        }
        $member_info_arr = [
            'is_show_expire_notice' => $member_obj->grade_expire_time <= time() + 5 * 24 * 60 * 60, // 距离过期时间还有五天的时候提醒
            'avatar' => $member_obj->avatar,
            'grade_expire_time' => $member_obj->grade_expire_time,
            'diff_now_grade_expire_time' => Carbon::createFromTimestamp($member_obj->grade_expire_time)->diffForHumans(Carbon::now()),
            'grade_code' => $member_obj->grade,
            'grade_name' => $class_info[$member_obj->grade]['grade_name'],
            'goods_list' => $goods_list,
            'advert' => $mc_advert
        ];
        $member = $memberShipDao->getRecordByMemberIdForViewData($member_obj->member_id);

        return view('member.center', compact('member_info_arr','signPackage','member_obj','member_class_arr','member'));
    }

    public function tongjiNeida(){
        $sql='SELECT member_name,cnt,5*cnt as total from
(SELECT  member_id,member_name,sum(cnt) as cnt from
(SELECT od.member_id,od.member_name,od.plat_order_id,FROM_UNIXTIME(od.create_time, \'%Y-%c-%d %h:%i:%s\' ) as tm,FROM_UNIXTIME(od.create_time, \'%Y-%c-%d\' ) as dt FROM yyd_order od where od.member_id in(
select ms.member_id FROM yyd_member_ship ms where ms.activity_id=15 and member_id not in(3,6,28,45,40))
and od.plat_order_id in(SELECT plat_order_id FROM yyd_order_goods og where og.sku_id in(703,704,705,706,707)) and od.is_get_gift=0 and od.plat_order_state>1 )a,

(SELECT plat_order_id,sum(number) as cnt FROM yyd_order_goods og where og.sku_id in(703,704,705,706,707) GROUP BY plat_order_id) b where a.plat_order_id=b.plat_order_id
GROUP BY member_id,member_name ) tt order by total desc';
        $list = DB::select($sql);

        //微信jsapi
        $signPackage = session('signPackage');

        return view('member.tongjiNeida', compact('list','signPackage'));

    }

    public function bindCdAdmin($store_id){
        if($store_id) {
            // 当前登录用户信息
            $member = Auth::user();
            if ($member) {
                $member_id = $member->member_id;
            } else {
                // 当前用户没有登录
                return redirect('/oauth');
            }
            /*$subscribe = $this->getSubscribe($member_id);
            if ($subscribe == 0) {
                return view('member.bindCDAdminSubscribe');
            }*/
            $moinfo = MemberOtherAccount::where('member_id', $member->member_id)->first();
            $openid = $moinfo->account_id;
            $data = array();
            $data['open_id'] = $openid;
            $data['store_id'] = $store_id;
            $data['member_name'] = $member->nick_name;
            $man=StoreNoticeMan::where('store_id',$store_id)->where('open_id',$openid)->first();
            if(!$man){//不存在则添加，否则跳过
                StoreNoticeMan::create($data);
            }
            //$old_password = md5($old_password);
            return view('member.bindCDAdminSuccess');
        }

    }

    //分享会员卡活动首页
    public function shareIndex(){
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }
        $shareCards=MemberShareCards::where('member_id',$member_id)->first();
        $user_data=[];
        if($shareCards){
            $user_data = Db::table('share_cardgetrecords')->join('member','share_cardgetrecords.member_id','=','member.member_id')->
            where('share_id',$shareCards->id)->select('share_cardgetrecords.member_id','share_cardgetrecords.nickname','share_cardgetrecords.get_time','member.avatar')->get()->toArray();

            if($user_data){
                foreach($user_data as &$user){
                    $user->get_time=date("Y-m-d H:i:s",$user->get_time);
                }
            }
        }

        //微信jsapi
        $signPackage = session('signPackage');

        //$subscribe = $this->getSubscribe($member_id);
        return view('member.shareCard.shareActivity', compact('subscribe','shareCards','user_data','signPackage'));

    }

    //得到要分享的卡片
    public function getShareCards(){
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }
        $hasGot="0";

        $shareCards=MemberShareCards::where('member_id',$member_id)->first();
        if(!$shareCards) {
            $get_time = time();
            $cards_num = $this->getPlatSetting('share_cards_num');

            $data = array(
                'member_id' => $member_id,
                'nickname' => $member->nick_name,
                'share_num' => $cards_num,//10
                'current_num' => 0,
                'get_time' => $get_time,
            );

            $shareCards = MemberShareCards::create($data);

        }else{
            $hasGot="1";
        }

        //微信jsapi
        $signPackage = session('signPackage');

        $url = "http://$_SERVER[HTTP_HOST]";
        $share_link = $url . "/member/shareCardInfo/" . $shareCards->getAttribute('id');
        //$subscribe = $this->getSubscribe($member_id);
        return view('member.shareCard.shareCards', compact('shareCards', 'signPackage', 'share_link','hasGot'));

    }


    public function shareCardInfo($share_id){
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }

        $shareCards=MemberShareCards::where('id',$share_id)->first();
        $getInfo = ShareCardGetRecords::where('member_id',$member_id)->first();
        $hasGot="0";

        if(!$getInfo){//未领过，可以领取
            $num=$shareCards->share_num-$shareCards->current_num;
            if($num==0){
                $result = array('code' => -1, 'message' => '会员卡已被领完，谢谢关注！');
            }else{
                    $result = array('code' => 0, 'message' => '可以领取');
            }
        }else{//已领过不能在领取
            $hasGot="1";
            $result = array('code' => -3, 'message' => '已领过');
        }

        //微信jsapi
        $signPackage = session('signPackage');

        //$subscribe = $this->getSubscribe($member_id);
        return view('member.shareCard.shareCardInfo', compact('shareCards', 'getInfo','result', 'hasGot','subscribe','share_id','signPackage'));

    }


    public function getshareCard(Request $request){
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }

        $share_id=$request->input('share_id');

        $shareCards=MemberShareCards::where('id',$share_id)->first();
        $getInfo = ShareCardGetRecords::where('member_id',$member_id)->first();
        $hasGot="0";

        if($shareCards->member_id==$member_id){
            $result = array('code' => -4, 'message' => '对不起，您不能领取自己分享的会员卡！');
        }else{
            if(!$getInfo){//未领过，可以领取
                $num=$shareCards->share_num-$shareCards->current_num;
                if($num==0){
                    $result = array('code' => -1, 'message' => '会员卡已被领完，谢谢关注！');
                }else{
                    $get_time = time();

                    $data = array(
                        'member_id' => $member_id,
                        'nickname' => $member->nick_name,
                        'get_time' => $get_time,
                        'share_id'=> $share_id,
                    );
                    //17
                    // 免费领取是线上会员活动
                    $activity_id = 17;
                    $ms = MemberShip::where('activity_id', $activity_id)->where('member_id', $member_id)->first();

                    if(!$ms) {

                        try {
                            DB::beginTransaction();

                            $current_num=++$shareCards->current_num;
                            $shareCards->current_num = $current_num;
                            $shareCards->save();
                            $member->share_from = $shareCards->member_id;
                            $member->save();
                            $getInfo = ShareCardGetRecords::create($data);
                            $activity = DB::table('membership_activity')
                                ->where('activity_id', $activity_id)
                                ->where('supplier_id', 0)
                                ->where('use_type', 2)
                                ->first();

                            // 生成线上会员免费体验订单记录,没有支付

                            $memnerShip = MemberShip::create([
                                'member_id' => $member_id,
                                'activity_id' => $activity_id,
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
                                'use_state' => 1,   // 使用状态: 0:暂不可使用(预付单, 暂不可使用)
                                'created_at' => time(),
                                'updated_at' => 0,
                                'close_time' =>$activity->close_time,
                            ]);
                            if ($memnerShip) {
                                //171122-去掉使用会员卡功能，改为只领取-start
                                /*// 使用的会员卡信息
                                $member_ship = $memnerShip;
                                $membership_id = $memnerShip->membership_id;
                                // 如果存在指定会员卡
                                if ($member_ship) {
                                    // 当前会员等级大于会员卡等级, 不可使用
                                    if ($member_ship->grade < $member->grade) {
                                        $result = array('code' => -5, 'message' => '当前会员等级大于该会员卡等级, 不可使用');
                                    } else {
                                        $memberShipDao = new MemberShipDao();
                                        // 使用会员卡, 提升用户等级, 成功跳转使用页面
                                        $flag = $memberShipDao->useCardByMemberShipId($membership_id, $member->member_id);
                                        if ($flag) {
                                            //return redirect('/membership/getCardList/2');
                                            $result = array('code' => 0, 'message' => '领取成功');
                                        } else {
                                            $result = array('code' => -7, 'message' => '领取失败，会员卡已过期');
                                        }
                                    }
                                } else {
                                    $result = array('code' => -6, 'message' => '该会员卡信息不存在或已被使用');
                                }*/
                                //171122-去掉使用会员卡功能，改为只领取-end

                                $result = array('code' => 0, 'message' => '领取成功');
                            }
                            if ($result['code'] == 0) {
                                DB::commit();
                            } else {
                                DB::rollBack();
                            }
                            //$result = array('code' => 0, 'message' => '领取成功');
                        } catch (\Exception $e) {
                            DB::rollBack();
                            $result = array('code' => -2, 'message' => '领取失败，请稍后重试！');
                        }
                    }else{
                        $hasGot="1";
                        $result = array('code' => -3, 'message' => '您已领过该会员卡，不能重复领取！');
                    }
                }
            }else{//已领过不能在领取
                $hasGot="1";
                $result = array('code' => -3, 'message' => '您已领过该会员卡，不能重复领取！');
            }
        }

        return $result;

    }

    /**
     * jiang
     * 地址管理中用户的地址列表
     */

    public function getAddressList(){

        // ---------------------------------------1、买家收货人地址----------------------------------------
        //判断当前用户是否登陆过
		$member = Auth::user();
		//判断窗口类型
		$getEntranceType = DB::table('member')->where('cust_id',$member->cust_id)->first();
        // 当前买家收货地址列表
        $address_info = MemberAddress::selectZd()
            ->where('member_id', $member->member_id)
            ->where('use_state', 0)
            //->where('entrance_type',$getEntranceType->entrance_type)
            ->orderBy('created_at', 'desc')
            ->get();

        // 如果当前买家没有收货地址，提示买家增加
        $is_hasAddress = $address_info->isEmpty() ? 0 : 1;

        // 当前买家默认收货地址
        $default_address = MemberAddress::selectZd()
            ->where('member_id', $member->member_id)
            ->where('use_state', 0)
            ->where('is_default', 1)
			//->where('entrance_type',$getEntranceType->entrance_type)
            ->first();

        // 省地址数组（新建地址信息需要）
        $province_dct = DctArea::select('id', 'name', 'pid')
            ->where('pid', 0)
            ->where('is_use', 1)
            ->get()
            ->toArray();

        return view('elife.address.address_list', compact('address_info','is_hasAddress','default_address','province_dct'));
    }

    /*public function bingIdCard(Request $request)
    {
        //判断当前用户是否登陆过
        $member_id = $this->getLoginUserId();
        $idCard = $request->input('ID_card');
        $idName = $request->input('ID_name');
        if($idCard && $idName){
            $card = Member::where('member_id',$member_id)->update(['ID_card'=>$idCard,'ID_name'=>$idName]);
            if($card){
                return Api::responseMessage(0);
            }
        }
    }*/

    /**
     * 帮助说明
     */
    public function Explain()
    {
        $explainList = DB::table('document')->where('doc_code','bzsm')->get();
        return view('elife.explain.explain_show', compact('explainList'));
    }
    /**
     * 身份证上传验证
     */
    public function IdcardImg()
    {
        $explainList = DB::table('document')->where('doc_code','wyksfz')->get();
        return view('elife.explain.idcardimg_show', compact('explainList'));
    }
}
