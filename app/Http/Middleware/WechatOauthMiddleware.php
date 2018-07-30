<?php

namespace App\Http\Middleware;

use App\facades\Api;
use App\models\company\CompanyOfficialAccount;
use App\models\member\Member;
use App\models\member\MemberOtherAccount;
use App\models\member\MemberPublicInfo;
use App\models\qrcode\TwoDimensionCode;

use App\models\member\MemberYesbLog;
use App\models\member\MemberWalletLog;
use App\models\member\MemberBalanceLog;

use App\controllers\BaseController;
use Illuminate\Support\Facades\DB;

use Closure;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * 检查用户是否登录, 及完成登录授权中间件
 * Class WechatOauthMiddleware
 * @auth yangrui
 * @package App\Http\Middleware
 */
class WechatOauthMiddleware
{
    const API_GET_CODE = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    const API_GET_USER_INFO = 'https://api.weixin.qq.com/sns/userinfo';
    const API_GET_OAUTH_ACCESS_TOKEN = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    const API_GET_OAUTH_COMPONENT_ACCESS_TOKEN = 'https://api.weixin.qq.com/sns/oauth2/component/access_token';

    protected $httpClient;
    protected $config;
    protected $redirectUrl;
    protected $requestUri;
    protected $serverName;

    /**
     * 中间件初始化配置
     * @param $request
     */
    protected function initConfig($request)
    {
        $this->config = config('wechat');
        $this->httpClient = new Client();

        $this->serverName = $request->server('SERVER_NAME');
        $this->requestUri = $request->server('REQUEST_URI');
        $this->redirectUrl = "http://" . $this->serverName . $this->requestUri;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 若是点击邀请者分享的链接，会有邀请者的id
        $inviter_id = $request->get('member_id');

        // 通过扫码进入
        $pathInfo = $request->getPathInfo();
        $scan_pos = stripos($pathInfo, '/scan/');

        // 初始化参数
        $this->initConfig($request);

        // 微信code授权回调
        if (!empty($request->get('code'))) {

            /**
             * 本平台自己公众号授权: 如果用户同意授权，页面将跳转至 redirect_uri/?code=CODE&state=STATE。(这时候没有appid)
             * 代授权: 用户允许授权后，将会重定向到redirect_uri的网址上，并且带上code, state以及appid
             *
             * 如果为空, 说明是本公众号的授权, 不为空说明是代授权
             */
            $auth_appid = empty($request->get('appid')) ? $this->config['app_id'] : $request->get('appid');

            $res_token_arr = $this->getAccessToken($request->get('code'), $auth_appid, $request->input('state'));

            if (isset($res_token_arr['errcode'])) {
                Log::error('通过code获取信息失败, 微信错误码: ' . $res_token_arr['errcode'] . '; 微信错误信息: ' . $res_token_arr['errmsg'] .
                    ' => 控制器:AccessController@handle');
                exit();
            }

            // 说明是静默授权的回调code(现在静默授权只用在了扫码时, 先静默授权本平台公众号, 再代授权分销商公众号, 所以该判断直接认为是扫码业务)
            if ($res_token_arr['scope'] == 'snsapi_base' && $request->session()->has('auth_company_info_arr')) {

                $auth_company_info_arr = $request->session()->get('auth_company_info_arr');
                $auth_company_info_arr['old_openid'] = $res_token_arr['openid'];

                $companyGZH = CompanyOfficialAccount::select('authorizer_appid', 'authorizer_access_token')
                    ->where('company_id', $auth_company_info_arr['company_id'])->first();

                $auth_company_info_arr['authorizer_appid'] = $companyGZH->authorizer_appid;

                $request->session()->put('auth_company_info_arr', $auth_company_info_arr);

                // 本次是静默授权带code回调, 需要处理一下url ?后的参数
                $this->redirectUrl = "http://" . $this->serverName . substr($this->requestUri, 0, stripos($this->requestUri, '?'));

                return redirect()->to($this->getAuthUrl($companyGZH->authorizer_appid, 'snsapi_userinfo', 'other'));

            } elseif ($res_token_arr['scope'] == 'snsapi_userinfo' && $request->session()->has('auth_company_info_arr')) {

                $user = $this->getUserByToken($res_token_arr);

                $auth_company_info_arr = $request->session()->get('auth_company_info_arr');
                $user['old_openid'] = $auth_company_info_arr['old_openid'];
                $user['company_id'] = $auth_company_info_arr['company_id'];
                $user['inviter_id'] = empty($inviter_id) ? '' : $inviter_id;

                // 使用的授权公众号appid, 这个一定是分销商的公众号appid(在回调code里面会带上)
                $user['appid'] = $request->get('appid');

                $this->saveUser($user);

                $auth_company_info_arr['openid'] = $res_token_arr['openid'];
                $request->session()->put('auth_company_info_arr', $auth_company_info_arr);

                // 本平台公众号授权, 现在的用户授权的作用域为snsapi_userinfo
            } else {

                $user = $this->getUserByToken($res_token_arr);

                // 授权的公众号appid(进入这里是本平台的公众号授权)、分销商id=0代表本平台、邀请者id
                $user['appid'] = $this->config['app_id'];
                $user['company_id'] = 0;
                $user['inviter_id'] = empty($inviter_id) ? '' : $inviter_id;

                $this->saveUser($user);
            }


            // 具体逻辑
        } else {

            // 扫码
            if ($scan_pos > 0) {
                $qrcode = substr($pathInfo, ($scan_pos) + 6);
                $twoDimensionCode = TwoDimensionCode::select('company_id')->where('two_dimension_code', $qrcode)->first();

                /**
                 * 如果存在分销商二维码, 则先进行本公众号的静默授权, 再使用分销商的公众号进行详细信息授权(我们使用开放平台代分销商公众号授权)
                 * 这样是为了在前台展示具体分销商公众号的授权标志, 使用我们本平台公众号静默授权的openid替换, 相当于我们的详细授权
                 */
                if ($twoDimensionCode && !empty($twoDimensionCode->company_id)) {

                    // 先使用本平台的静默授权
                    $request->session()->put('auth_company_info_arr', ['company_id' => $twoDimensionCode->company_id]);

                    return redirect()->to($this->getAuthUrl($this->config['app_id'], 'snsapi_base'));

                } else {
                    // 使用本平台公众号授权(详细信息授权)
                    return redirect()->to($this->getAuthUrl($this->config['app_id']));
                }

            } else {
                if (!Auth::user()) {
                    // 使用本平台公众号授权(详细信息授权)
                    return redirect()->to($this->getAuthUrl($this->config['app_id']));
                }
            }
        }

        // 这里用户已经登录, 检查登录用户会员是否过期, 如果过期回到默认等级
        $member_obj = Auth::user();
        $member_obj->grade = $member_obj->grade != 10 && $member_obj->grade_expire_time < time() ? 10 : $member_obj->grade;
        $member_obj->save();

        return $next($request);
    }

    /**
     * 微信用户注册登陆
     * @param array $user (通过网页授权获取的用户信息
     * @return Member 登录用户信息
     */
    protected function saveUser($user)
    {
        // 是平台公众号授权
        if ($user['company_id'] == 0) {

            // 本平台是否存在该微信登录用户(通过openid)
            $moa_obj = MemberOtherAccount::where('account_id', $user['openid'])->first();

            // 没有创建该登录用户
            if (!$moa_obj) {

                // 用户基本信息表
                $member_obj = Member::create([
                    'member_name' => $user['nickname'] . time() . rand(0, 999),
                    'nick_name' => isset($user['nickname']) ? $user['nickname'] : '',
                    'avatar' => isset($user['headimgurl']) ? $user['headimgurl'] : '',
                    "sex" => isset($user['sex']) ? $user['sex'] : '',
                    "login_ip" => Api::getIp(),
                    "regist_time" => time(),
                    "login_time" => time(),
                    "login_num" => 1,
                ]);

                // 创建会员第三方账号表信息(现在只用于是微信登录)
                MemberOtherAccount::create([
                    'account_name' => '微信号',
                    'account_id' => $user['openid'],
                    'member_id' => $member_obj->member_id,
                ]);

                // 会员公众号信息表, company_id=0 代表本平台的公众号
                MemberPublicInfo::create([
                    'company_id' => 0,
                    'openid' => $user['openid'],
                    'authorizer_appid' => $user['appid'],
                    'unionid' => isset($user['unionid']) ? $user['unionid'] : '',
                    'member_id' => $member_obj->member_id,
                    'create_time' => time()
                ]);

                // 若新注册的用户是通过点击别人邀请好友链接进行注册的，则获取相应奖励
                if ($user['inviter_id']) {
                    $this->get_reware($member_obj->member_id, $user['inviter_id']);
                }

                // 存在该微信用户
            } else {

                $member_obj = Member::find($moa_obj->member_id);

                $member_obj->nick_name = isset($user['nickname']) ? $user['nickname'] : '';
                $member_obj->avatar = isset($user['headimgurl']) ? $user['headimgurl'] : '';
                $member_obj->sex = isset($user['sex']) ? $user['sex'] : '';
                $member_obj->login_num += 1;

                $member_obj->old_login_time = $member_obj->login_time;
                $member_obj->old_login_ip = $member_obj->login_ip;

                $member_obj->login_time = time();
                $member_obj->login_ip = Api::getIp();
                $member_obj->save();

                // 会员公众号信息表, company_id=0 代表本平台的公众号
                $mpi = MemberPublicInfo::where('member_id', $member_obj->member_id)
                    ->where('openid', $user['openid'])
                    ->where('company_id', 0)
                    ->first();

                // 新增
                if (!$mpi) {
                    MemberPublicInfo::create([
                        'company_id' => 0,
                        'openid' => $user['openid'],
                        'authorizer_appid' => $user['appid'],
                        'unionid' => isset($user['unionid']) ? $user['unionid'] : '',
                        'member_id' => $member_obj->member_id,
                        'create_time' => time()
                    ]);

                    // 更新
                } else {
                    if (isset($user['unionid'])) {
                        $mpi->unionid = $user['unionid'];
                        $mpi->save();
                    }
                }
            }

            // 扫码登录保存用户信息, 使用的是分销商公众号授权的用户信息, old_openid是本平台公众号静默授权获取的openid
        } else {

            // 平台是否存在该微信登录用户(通过old_openid)
            $moa_obj = MemberOtherAccount::where('account_id', $user['old_openid'])->first();

            // 只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段(UnionID机制详细说明见微信开发文档)
            $unionid = isset($user['unionid']) ? $user['unionid'] : '';

            // 没有创建该登录用户
            if (!$moa_obj) {

                // 用户基本信息表
                $member_obj = Member::create([
                    'member_name' => $user['nickname'] . time() . rand(0, 999),
                    'nick_name' => isset($user['nickname']) ? $user['nickname'] : '',
                    'avatar' => isset($user['headimgurl']) ? $user['headimgurl'] : '',
                    "sex" => isset($user['sex']) ? $user['sex'] : '',
                    "login_ip" => Api::getIp(),
                    "regist_time" => time(),
                    "login_time" => time(),
                    "login_num" => 1,
                ]);

                // 创建会员第三方账号表信息(现在只用于是微信登录)
                MemberOtherAccount::create([
                    'account_name' => '微信号',
                    'account_id' => $user['openid'],
                    'member_id' => $member_obj->member_id,
                ]);

                // 会员公众号信息表(保存平台公众号信息), company_id=0 代表本平台的公众号
                MemberPublicInfo::create([
                    'company_id' => 0,
                    'openid' => $user['old_openid'],
                    'authorizer_appid' => $this->config['app_id'],
                    'unionid' => $unionid,
                    'member_id' => $member_obj->member_id,
                    'create_time' => time()
                ]);

                // 会员公众号信息表(保存分销商公众号信息)
                MemberPublicInfo::create([
                    'company_id' => $user['company_id'],
                    'openid' => $user['openid'],
                    'authorizer_appid' => $user['appid'],
                    'unionid' => $unionid,
                    'member_id' => $member_obj->member_id,
                    'create_time' => time()
                ]);

                // 若新注册的用户是通过点击别人邀请好友链接进行注册的，则获取相应奖励
                if ($user['inviter_id']) {
                    $this->get_reware($member_obj->member_id, $user['inviter_id']);
                }

            } else {

                $member_obj = Member::find($moa_obj->member_id);

                $member_obj->nick_name = isset($user['nickname']) ? $user['nickname'] : '';
                $member_obj->avatar = isset($user['headimgurl']) ? $user['headimgurl'] : '';
                $member_obj->sex = isset($user['sex']) ? $user['sex'] : '';
                $member_obj->login_num += 1;

                $member_obj->old_login_time = $member_obj->login_time;
                $member_obj->old_login_ip = $member_obj->login_ip;

                $member_obj->login_time = time();
                $member_obj->login_ip = Api::getIp();
                $member_obj->save();

                // 会员公众号信息表(保存平台公众号信息), company_id=0 代表本平台的公众号
                $mpi_self = MemberPublicInfo::where('member_id', $member_obj->member_id)
                    ->where('openid', $user['old_openid'])
                    ->where('company_id', 0)
                    ->first();

                // 添加
                if (!$mpi_self) {
                    MemberPublicInfo::create([
                        'company_id' => 0,
                        'openid' => $user['old_openid'],
                        'authorizer_appid' => $this->config['app_id'],
                        'unionid' => $unionid,
                        'member_id' => $member_obj->member_id,
                        'create_time' => time()
                    ]);

                    // 更新
                } else {
                    if (isset($user['unionid'])) {
                        $mpi_self->unionid = $user['unionid'];
                        $mpi_self->save();
                    }
                }

                // 会员公众号信息表(保存分销商公众号信息)
                $mpi_other = MemberPublicInfo::where('member_id', $member_obj->member_id)
                    ->where('openid', $user['openid'])
                    ->where('company_id', $user['company_id'])
                    ->first();

                // 新增
                if (!$mpi_other) {
                    MemberPublicInfo::create([
                        'company_id' => $user['company_id'],
                        'openid' => $user['openid'],
                        'authorizer_appid' => $user['appid'],
                        'unionid' => $unionid,
                        'member_id' => $member_obj->member_id,
                        'create_time' => time()
                    ]);

                    // 更新
                } else {
                    if (isset($user['unionid'])) {
                        $mpi_other->unionid = $user['unionid'];
                        $mpi_other->save();
                    }
                }
            }
        }

        // 用户登录
        Auth::loginUsingId($member_obj->member_id);

        return $member_obj;
    }

    /**
     * 生成授权url
     * @param string $appid 使用授权公众号的appid
     * @param string $scopes 应用授权作用域，snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid），snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地。并且，即使在未关注的情况下，只要用户授权，也能获取其信息）
     * @param string $state 现在该参数用于区分是本平台授权('self')还是代授权;(重定向后会带上state参数，可以填写a-zA-Z0-9的参数值，最多128字节)
     * @return string
     */
    protected function getAuthUrl($appid, $scopes = 'snsapi_userinfo', $state = 'self')
    {
        $params_arr = [
            'appid' => $appid,
            'redirect_uri' => $this->redirectUrl,
            'response_type' => 'code',
            'scope' => $scopes,
            'state' => $state,
        ];

        // 如果不是本平台公众号授权, 是代授权还需要 component_appid(第三方的appid) 参数
        if ($state != 'self') {
            $params_arr['component_appid'] = $this->config['open_platform']['app_id'];
        }

        // 根据传入的数组生成 URL-encode 之后的请求字符串
        $query = http_build_query($params_arr, '', '&', PHP_QUERY_RFC1738);

        return self::API_GET_CODE . '?' . $query . '#wechat_redirect';
    }

    /**
     * 通过网页授权access_token, 获取用户信息(需scope为 snsapi_userinfo)
     * @param string $token 网页授权access_token
     * @return array
     */
    protected function getUserByToken($token)
    {
        if (empty($token['openid'])) {
            Log::error('授权失败, 无法获取授权后的openid => 控制器:WechatOauthMiddleware@getUserByToken');
            exit();
        }

        $response = $this->httpClient->get(self::API_GET_USER_INFO, [
            'query' => [
                'access_token' => $token['access_token'],
                'openid' => $token['openid'],
                'lang' => 'zh_CN',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * 获取网页授权access_token
     * @param string $code 微信授权code
     * @param string $appid 公众号appid
     * @param string $state 重定向前填写的state参数
     * @return array
     */
    public function getAccessToken($code, $appid, $state)
    {
        if ($state == 'self') {
            $token_url = self::API_GET_OAUTH_ACCESS_TOKEN;

            $params = [
                'query' => [
                    'appid' => $appid,
                    'secret' => $this->config['secret'],
                    'code' => $code,
                    'grant_type' => 'authorization_code',
                ]
            ];
        } else {
            $token_url = self::API_GET_OAUTH_COMPONENT_ACCESS_TOKEN;

            $params = [
                'query' => [
                    'appid' => $appid,
                    'code' => $code,
                    'grant_type' => 'authorization_code',
                    'component_appid' => $this->config['open_platform']['app_id'],
                    'component_access_token' => $this->getComponent_at()
                ]
            ];
        }

        $response = $this->httpClient->get($token_url, $params);
        $body = $response->getBody()->getContents();

        return !is_array($body) ? json_decode($body, true) : $body;
    }

    /**
     * 若是新用户是通过邀请者邀请好友链接进入的，会有新用户新注册的礼品奖励，比如奖励虚拟币200等，则进行礼品奖励，
     * 邀请者也有可能会有奖品，均在此奖励
     * $invited_id 被邀请者id（新注册用户id）
     * $inviter_id 邀请者id
     */
    private function get_reware($invited_id, $inviter_id)
    {

        //通过邀请新注册的用户获得的礼品 新注册的用户id <---> 邀请者id
        $this->updata_reward($invited_id, 'invited_reward', $inviter_id);

        //邀请者获得的礼品   邀请者id <---> 新注册的用户id
        $this->updata_reward($inviter_id, 'inviter_reward', $invited_id);

    }

    /**
     * 更新奖品金额
     * $member_id 自身id
     * $invited_type 类型，邀请者或者是被邀请者
     * $other_member_id 和自身关联的另一个人的id
     */
    private function updata_reward($member_id, $invited_type, $other_member_id)
    {

        $mem = Member::find($member_id);
        $BaseController = new BaseController();

        $r_id = $BaseController->get_invite_friend_reward($invited_type);
        if (!$r_id) { //若为0说明没有奖品，直接退出
            return false;
        }

        $reward_info = DB::table('invite_friend_reward')->where('id', $r_id)->first();

        //得到奖品日志
        $r_data = array();

        $busine_content = '';
        $busine_type = '';
        switch ($invited_type) {

            case 'invited_reward': //被邀请者
                $busine_content = '被邀请新会员注册奖励，会员id: ' . $mem->member_id;
                $busine_type = 15;

                $r_data['is_inviter'] = 0;

                break;

            case 'inviter_reward': //邀请者
                $busine_content = '邀请会员注册奖励，会员id: ' . $mem->member_id;
                $busine_type = 14;

                $r_data['is_inviter'] = 1;

                break;
        }

        $r_data['member_id'] = $member_id;
        $r_data['other_member_id'] = $other_member_id;
        $r_data['create_time'] = time();
        $r_data['reward_code'] = $reward_info->reward_code;
        $r_data['reward_name'] = $reward_info->reward_name;
        $r_data['reward_num'] = $reward_info->reward_num;


        $data_log['member_id'] = $mem->member_id;
        $data_log['member_name'] = $mem->member_name;
        $data_log['create_time'] = time();;
        $data_log['busine_id'] = $mem->member_id;

        $time = time();
        switch ($reward_info->reward_code) {

            case 'card_balance':
                //更新总额
                $mem->card_balance_total = $reward_info->reward_num + $mem->card_balance_total; //增加总额
                $mem->card_balance_available = $reward_info->reward_num + $mem->card_balance_available; //增加可用额

                //增加收支明细表，增加一条收入记录【卡收支记录】
                $data_log['busine_type'] = $busine_type;
                $data_log['av_amount'] = $reward_info->reward_num;
                $data_log['freeze_amount'] = 0;
                $data_log['busine_content'] = $busine_content;
                $data_log['realtime_balance'] = $mem->card_balance_available;
                $data_log['realtime_freeze'] = $mem->card_balance_freeze;

                $mem->save();
                MemberBalanceLog::create($data_log);
                break;

            case 'wallet':
                $mem->wallet_total = $reward_info->reward_num + $mem->wallet_total; //增加总额
                $mem->wallet_available = $reward_info->reward_num + $mem->wallet_available; //增加可用额

                $data_log['busine_type'] = $busine_type;
                $data_log['av_amount'] = $reward_info->reward_num;
                $data_log['freeze_amount'] = 0;
                $data_log['busine_content'] = $busine_content;
                $data_log['realtime_balance'] = $mem->wallet_available;
                $data_log['realtime_freeze'] = $mem->wallet_freeze;

                $mem->save();
                MemberWalletLog::create($data_log);
                break;

            case 'yesb':
                $mem->yesb_total = $reward_info->reward_num + $mem->yesb_total; //增加总额
                $mem->yesb_available = $reward_info->reward_num + $mem->yesb_available; //增加可用额

                // `yesb_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '虚拟币额【正数:收入;负数:支出】',
                $data_log['busine_type'] = $busine_type;
                $data_log['yesb_amount'] = $reward_info->reward_num;
                $data_log['busine_content'] = $busine_content;
                $data_log['operater'] = $mem->member_id;

                $mem->save();
                MemberYesbLog::create($data_log);
                break;
            case 'member_card':
                // 会员卡活动信息
                $activity = DB::table('membership_activity')
                    ->where('activity_id', 11) //邀请好友得会员专用活动id
                    ->where('supplier_id', 0)
                    ->where('use_type', 2)
                    ->first();

                // 把领取的会员卡存入 member_ship 表中(以后在我的卡包 => 会员卡 => 激活使用)
                DB::table('member_ship')->insert([
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
                    'price' => $activity->price,
                    'source_type' => 1,
                    'use_state' => 1,
                    'created_at' => $time,
                    'updated_at' => 0
                ]);

                break;

        }

        //记录中奖日志
        DB::table('invite_friend_reward_log')->insert($r_data);
    }

    /**
     * 获取第三方平台component_access_token
     * @return string
     */
    public function getComponent_at()
    {
        $own_open_plat_info = DB::table('verify_ticket')->where('id', 1)->first();

        // 从数据库中获取微信服务器给本开放平台的接入信息
        if (!$own_open_plat_info) {
            Log::error('从数据库表 verify_ticket 获取 id=1 的信息项失败！=> 控制器:WechatOauthMiddleware@getComponent_at');
            exit();
        }

        // 如果存在 component_access_token 且没有过期(过期时间在保存时已做处理, 提前半小时), 则直接返回
        if ($own_open_plat_info->component_access_token && $own_open_plat_info->token_expires_time >= time()) {
            return $own_open_plat_info->component_access_token;
        }

        // 说明数据库中不存在或已过期, 则重新获取
        if (!$own_open_plat_info->ticket || $own_open_plat_info->ticket_expires_time <= time()) {
            Log::alert('component_verify_ticket 为空或已过有效期！=> 控制器:AccessController@getComponent_at');
            exit();
        }

        // 根据 verify_ticket 获取开放平台 component_access_token
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
        $post_data = '{
                        "component_appid":"' . $this->config['open_platform']['app_id'] . '",
                        "component_appsecret":"' . $this->config['open_platform']['secret'] . '",
                        "component_verify_ticket": "' . $own_open_plat_info->ticket . '"
                      }';
        $result = json_decode($this->postData($url, $post_data));

        if (isset($result->errcode)) {
            Log::error('请求微信接口(获取第三方平台component_access_token失败), 微信错误码: ' .
                $result->errcode . '微信错误信息: ' . $result->errmsg . ' => 控制器:WechatOauthMiddleware@getComponent_at');
            exit();
        }

        // 更新数据库 component_access_token 信息
        DB::table('verify_ticket')->where('id', 1)->update(
            [
                'component_access_token' => $result->component_access_token,
                'token_create_time' => time(),
                'token_expires_time' => time() + 5400,
            ]
        );

        return $result->component_access_token;
    }

    /**
     * post请求
     * @param $url
     * @param $data
     * @return mixed
     */
    function postData($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $tmpInfo;
    }

}
