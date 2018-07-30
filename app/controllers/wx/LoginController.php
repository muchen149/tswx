<?php

/**
 * Created by PhpStorm.
 * User: shuo
 * Date: 16-9-1
 * Time: 上午11:01
 */
namespace App\controllers\wx;

use App\controllers\BaseController;

use EasyWeChat\Foundation\Application;
use App\models\member\MemberGiftCoupon;
use App\models\member\MemberPublicInfo;
use App\models\order\Order;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\models\member\Member;
use App\models\member\MemberExtend;
use App\models\member\MemberOtherAccount;
use App\models\member\MemberGrowthSystem;
use App\models\member\MemberShareholdGrowth;

use App\facades\Api;
use Auth;
use CoinRule;

/**
 * 微信登录
 * @author      :lishuo
 * Class        :LoginController
 * @package     :App\controllers\wx
 */
class LoginController extends BaseController
{
    /**
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 添加用户
     * @return  mixed
     */
    public function createOrUpdate(Request $request)
    {
        try {
            DB::beginTransaction();

            $rul_param = intval($_GET['member']);   // 路由参数（例如：会员ID）

            $user = session('wechat.oauth_user');   //存入数据库
            $user = $user->original;
            //首先判断用户是否存在，
            $moa = MemberOtherAccount::where('account_id', $user['openid'])->first();
            if (!$moa) {   //添加

                $member = [
                    // 会员名称 唯一的@todo：暂时（只要唯一就行）
                    'member_name' => $user['nickname'] . time() . rand(0, 999),
                    'nick_name' => $user['nickname'],
                    'avatar' => $user['headimgurl'],
                    "sex" => $user['sex'],

                    "login_ip" => Api::getIp(),      //获取当前IP
                    "regist_time" => time(),
                    "login_time" => time(),
                    "login_num" => 1,               //登录次数（当前第一次）
                ];

                $moa = Member::create($member);
                $member_other_account = [
                    'account_name' => '微信号',
                    'account_id' => $user['openid'],
                    'member_id' => $moa->member_id,
                ];
                MemberOtherAccount::create($member_other_account);
                Log::info('用户注册');
                //用户注册加酒币--------------------------
                CoinRule::add(1, $moa->member_id, 1, $rul_param);
                Log::info('用户注册->add酒币');
                if ($rul_param != 0) {
                    //如果存在上下级关系就存进去(只有第一次传建用户的时候才会添加)
                    MemberExtend::create([
                        'member_id' => $moa->member_id,
                        'pid' => $rul_param,
                    ]);
                    Log::info('存在上下级关系,上级ID' . $rul_param);
                    CoinRule::add(2, $rul_param, 1);
                    Log::info('上级邀请->add酒币');
                } else {
                    MemberExtend::create([
                        'member_id' => $moa->member_id,
                    ]);
                }
            } else {      //更新
                Log::info('用户登录->更新');
                $member_other_account = [
                    'account_name' => '微信号',
                    'account_id' => $user['openid'],
                    'member_id' => $moa->member_id,
                ];

                MemberOtherAccount::where('id', $moa->id)->update($member_other_account);
                $mm = Member::where('member_id', $moa->member_id)->first();
                $member = [
                    'nick_name' => $user['nickname'],
                    'avatar' => $user['headimgurl'],
                    "sex" => $user['sex'],

                    "login_time" => time(),          //当前登录时间
                    "login_ip" => Api::getIp(),          //获取当前IP
                    "old_login_time" => $mm->login_time == '' ? 0 : $mm->login_time,    //上次登录时间
                    "old_login_ip" => $mm->login_ip,     //上次登录的IP
                    "login_num" => $mm->login_num + 1,    //登录次数
                ];

                CoinRule::grade($moa->member_id);       // 更新等级
                $mm->update($member);

            }

            Auth::loginUsingId($moa->member_id);
            DB::commit();

            Cookie::queue('member_id', $moa->member_id, 1 * 24 * 60);  //暂定1天，方便调试
        } catch (\Exception $e) {
            DB::rollBack();
            dd('错误页面暂时没有写，');
            return view('错误页面');  //@todo 后续会添加相应的错误页面
        }

        $url = session('redirect_url');
        return Redirect::to(config('path.base_path') . $url);
    }

    /**
     * 微信注册登录
     */
    public function wxLoginCreate()
    {
        /*$user = session('wechat.oauth_user');   //存入数据库
        $user = $user->original;
        //首先判断用户是否存在，
        $moa = MemberOtherAccount::where('account_id', $user['openid'])->first();
        if (!$moa) {   //添加
            $member = [
                // 会员名称 唯一的@todo：暂时（只要唯一就行）
                'member_name' => $user['nickname'] . time() . rand(0, 999),
                'nick_name' => $user['nickname'],
                'avatar' => $user['headimgurl'],
                "sex" => $user['sex'],
                "login_ip" => Api::getIp(),      //获取当前IP
                "regist_time" => time(),
                "login_time" => time(),
                "login_num" => 1,               //登录次数（当前第一次）
            ];

            $moa = Member::create($member);
            $member_other_account = [
                'account_name' => '微信号',
                'account_id' => $user['openid'],
                'member_id' => $moa->member_id,
            ];
            MemberOtherAccount::create($member_other_account);
        }*/

        $moa = Auth::user();
        $all_num = Order::where('member_id', $moa->member_id)->count();

        $payment_num = Order::where('member_id', $moa->member_id)
            ->where('plat_order_state', 1)->count();
        $delivered_num = Order::where('member_id', $moa->member_id)
            ->where('plat_order_state', 2)->count();
        $received_num = Order::where('member_id', $moa->member_id)
            ->where('plat_order_state', 3)->count();

        // 系统目前没有设置评价业务，待评价（4）和已完成（9） 都是完成
        $pingjia_num = Order::where('member_id', $moa->member_id)
            ->where('plat_order_state', 4)->count();
        $success_num = Order::where('member_id', $moa->member_id)
            ->where(function ($query) {
                $query->where('plat_order_state', 9)
                    ->orwhere(function ($query) {
                        $query->where('plat_order_state', 4);
                    });
            })->count();

        $state_num_arr = array('all_num' => $all_num,
            'payment_num' => $payment_num,
            'delivered_num' => $delivered_num,
            'received_num' => $received_num,
            'pingjia_num' => $pingjia_num,
            'success_num' => $success_num);

        //我的资产-》礼券数量
        $coupon_num = MemberGiftCoupon::where('member_id', $moa->member_id)
            ->where('use_state', '<>', -1)->count();
        $wallet_num_arr = array('coupon_num' => $coupon_num);

        //获取虚拟币名称【外部简称】
        $base_class = new BaseController();
        $plat_vrb_caption = $base_class->getPlatVrbCaption();

        $member = $moa;
        $class_arr = $this->getMemberClass();
        $member->grade_name = $class_arr[$member->grade]['grade_name'];

        //判断是否关注公众号
        $public_info=MemberPublicInfo::where('member_id',$moa->member_id)->first();
        $openid = $public_info->openid;
        if($public_info->company_id==0) {
            $app = new Application(config('wechat'));
            $userService = $app->user;
            $wechat_user = $userService->get($openid);
        }else{
            $company_token = $this->getCompanyAccessTokenById($public_info->company_id);
            $get_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $company_token .
                '&openid=' . $openid . '&lang=zh_CN';

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

        return view("user.wx_user", compact('member', 'state_num_arr', 'wallet_num_arr', 'plat_vrb_caption','subscribe'));
    }


}