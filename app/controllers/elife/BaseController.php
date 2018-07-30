<?php
/** 本项目 controllers 基类,保存公有变量、函数
 * Created by PhpStorm.
 * User: dell
 * Date: 2016/12/24
 * Time: 18:01
 */
namespace App\controllers\elife;

use App\Jobs\AutoDismantleOrder;
use App\Http\Controllers\Controller;
use App\icbc\IcbcEncrypt;
use App\models\company\CompanyOfficialAccount;
use App\models\goods\GoodsSku;
use App\models\goods\GoodsSpu;
use App\models\member\MemberPublicInfo;
use App\models\plat\PlatSetting;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{
    protected $db_prefix;
    protected $img_domain;

    public function __construct() {
        $this->db_prefix = config('database')['connections']['mysql']['prefix'];
        $this->img_domain = config('upload')['imgDomain'];
    }
	/**
	 * 公共记录跳转页面
	 * @author muchen
	 * @time 2018/7/10
	 */
	public function loseEfficacy() {
		//构造自动验证身份信息
		$this->GetBaseMemberInfo = Auth::user();
		if (!$this->GetBaseMemberInfo) {
			Log::error('用户信息已过期,请重新登录！');
			echo view('errors.abateError');die;
		}
		Auth::loginUsingId($this->GetBaseMemberInfo->member_id);
		return $this->GetBaseMemberInfo;
	}

    /**
     * 获取当前登录用户ID，如果没有登录，返回 0
     */
    function getLoginUserId()
    {
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        }else{
            return view('errors.e_error');
        }

        return $member_id;
    }

	/**
	 * E生活AES解密
	 * @param $res
	 * @author 沐辰
	 * @time 2018-06-01
	 */
	 public function aesDecrypt($res = '') {
		 //字符串过滤替换
		$stroutString = str_replace(' ',"+", $res);
		//AES解密 测试 InAop3i4KTAXjsSKI1CTsg== 生产 X3KBHDDEXPpB++JNQ0p42g==
		$AesdecodeData = IcbcEncrypt::decryptContent($stroutString,'AES','InAop3i4KTAXjsSKI1CTsg==','UTF-8');
		//base64解码
		$Base64Data = base64_decode($AesdecodeData);
		//替换个别字符
		$strout = preg_replace('/[\x00-\x1F\x80-\x9F]/u', '',trim($Base64Data));
		//替换单引号
		$str_strout = str_replace('\'','"',$strout);
		//转换格式
		$data = json_decode($str_strout,true);

		return $data;
	 }

    /**
     * 将图片的相对地址转换为绝对地址(fullPictureUrl)
     * @param string $pictureUrl 要处理的图片地址
     *  （含有“http://”等字符的为绝对地址，不处理，直接返回；其它处理）
     */
    function getFullPictureUrl($pictureUrl = '')
    {
        $fullPictureUrl = trim($pictureUrl . "");
        if ($fullPictureUrl == "") {
            // 如果为空字符串，直接退出
            return $fullPictureUrl;
        }

        // 如果含有“http://”等字符的为绝对地址，不处理
        if (strpos(strtolower($pictureUrl), "http://", 0) === false) {
            $fist_str = mb_substr($pictureUrl, 0, 1, 'utf-8');
            if ($fist_str != '/') {
                $pictureUrl = '/' . $pictureUrl;
            }
            $fullPictureUrl = $this->img_domain . $pictureUrl;
        }

        return $fullPictureUrl;
    }

    /**
     * 根据当前登录用户类型, 获取spu商品对应价格
     * @param int $member_grade 用户会员等级
     * @param GoodsSpu $obj_spu 商品spu
     * @return float 返回对应商品spu价格
     */
    function getSpuPrice($member_grade = 10, $obj_spu)
    {
        /*
            会员类别(grade) => 10:普通会员(市场价); 20:一级会员(平台价格); 30:二级会员(团购价); 40:三级会员(批发价)
            spu 价格体系：
            spu_market_price	市场价 10
            spu_plat_price		平台价格 20
            spu_groupbuy_price	团购价 30
            spu_trade_price		批发价 40
            spu_partner_price	(分销伙伴)抄底价【成本价 + 管理费】
            spu_cost_price		成本价【平台进价 + 运费】
        */
        $spu_price = 0.00;

        switch ($member_grade) {
            case 10 :
                $spu_price = isset($obj_spu->spu_market_price) ? $obj_spu->spu_market_price : $spu_price;
                break;

            case 20 :
                $spu_price = isset($obj_spu->spu_plat_price) ? $obj_spu->spu_plat_price : $spu_price;
                break;

            case 30 :
                $spu_price = isset($obj_spu->spu_groupbuy_price) ? $obj_spu->spu_groupbuy_price : $spu_price;
                break;

            case 40 :
                $spu_price = isset($obj_spu->spu_trade_price) ? $obj_spu->spu_trade_price : $spu_price;
                break;
        }

        return $spu_price;
    }

    /**
     * 根据当前登录用户类型, 获取sku商品对应价格
     * @param int $member_grade 用户会员等级
     * @param GoodsSku $obj_sku 商品sku
     * @return float 返回对应商品sku价格
     */
    function getSkuPrice($member_grade = 10, $obj_sku)
    {
        /*
            会员类别(grade) => 10:普通会员(市场价); 20:一级会员(商品价格); 30:二级会员(团购价); 40:三级会员(批发价)
            sku价格体系：
            market_price		市场价 10
            price			    商品价格 20
            groupbuy_price		团购价 30
            trade_price		    批发价 40
            partner_price		(分销伙伴)抄底价【成本价 + 管理费】
            cost_price		    成本价【平台进价 + 运费】
        */
        $sku_price = 0.00;

        switch ($member_grade) {
            case 10 :
                $sku_price = isset($obj_sku->market_price) ? $obj_sku->market_price : $sku_price;
                break;

            case 20 :
                $sku_price = isset($obj_sku->price) ? $obj_sku->price : $sku_price;
                break;

            case 30 :
                if (isset($obj_sku->groupbuy_price)) {
                    // 团采会员享受的是团采价(折扣乘以团购价)
                    $sku_price = !empty($obj_sku->base_discount_rate) && $obj_sku->use_state == 1 ?
                        bcmul($obj_sku->groupbuy_price, $obj_sku->base_discount_rate, 2) : $obj_sku->groupbuy_price;

                }
                break;

            case 40 :
                if (isset($obj_sku->trade_price)) {
                    // 代理会员享受的是代理价(折扣乘以批发价)
                    $sku_price = !empty($obj_sku->base_discount_rate) && $obj_sku->use_state == 1 ?
                        bcmul($obj_sku->trade_price, $obj_sku->base_discount_rate, 2) : $obj_sku->trade_price;
                }
                break;
        }

        return $sku_price;
    }

    /**
     * 根据商品虚拟币定义规则，获取该商品可支付的虚拟币额度，返回整数
     * 传入参数
     * @param  $points_limit   int     商品虚拟币定义支付规则【0:不限制;-1:不支持;其它为限支付额；】
     * @param  $goods_price    decimal(10,2)   商品价格【人民币】
     * @param  $plat_vrb_rate int     虚拟币兑换人民币汇率，即：1元人民币等于多少虚拟币
     * 传出参数
     * @param   $points         int
     */
    function getPointsLimit($points_limit = 0, $goods_price = 0.00, $plat_vrb_rate = 0)
    {
        // 商品可支付的虚拟币额度，如果不支持虚拟币支付（-1）,直接返回零
        $points = 0;
        if ($points_limit >= 0) {
            // 1、首先虚拟币限额取整
            $points = floor($points_limit);

            // 2、根据虚拟币与人民币间的汇率，计算商品价格兑换成虚拟币的数值
            // plat_vrb_rate	1	1元（人民币）等于多少依你币【依你币汇率】
            // plat_points_rate	100	积分汇率【1虚拟币等于多少积分】
            if ($plat_vrb_rate == 0) {
                $plat_vrb_rate = intval($this->getPlatSetting('plat_vrb_rate'));
                if ($plat_vrb_rate <= 0) {
                    $plat_vrb_rate = 1;
                }
            }

            // 商品价格兑换成虚拟币额度
            $goods_points = floor($goods_price * $plat_vrb_rate);
            if ($points == 0) {
                $points = $goods_points;
            } else {
                $points = min($points, $goods_points);
            }
        }else{
            $points = -1;
        }

        return $points;
    }

    /**
     *  获取虚拟币名称【外部简称】
     */
    function getPlatVrbCaption()
    {
        $plat_vrb_caption = $this->getPlatSetting('plat_vrb_name');
        if ($plat_vrb_caption == '') {
            $plat_vrb_caption = '积分';
        }

        return $plat_vrb_caption;
    }

    /**
     *  获取虚拟币兑换人民币汇率，即 1元人民币等于多少虚拟币，默认 1个 $plat_vrb_rate
     */
    function getPlatVrbRate()
    {
        // 获取虚拟币与人民币间的汇率【1元（人民币）等于多少依你币】，计算商品价格兑换成虚拟币数值用
        $plat_vrb_rate = intval($this->getPlatSetting('plat_vrb_rate'));
        if ($plat_vrb_rate <= 0) {
            $plat_vrb_rate = 1;
        }

        return $plat_vrb_rate;
    }

    /**
     * 获取用户申请采购时要申请的用户级别
     */
    function get_apply_grade($apply_grade)
    {

        $grade = 0;
        $table = $this->db_prefix . 'plat_setting ';
        $sql = "select value as parameter_value
                from " . $table . "
                where name = '" . $apply_grade . "'
                limit 0,1 ";
        $result = DB::select($sql, []);
        if (count($result) == 1) {
            $grade = $result[0]->parameter_value;
        }

        return $grade;
    }

    /**
     * 获取邀请好友功能中邀请者和被邀请者的奖励礼品
     */
    function get_invite_friend_reward($invite_name)
    {

        $reward_id = 0; //为0说明没有礼品
        $table = $this->db_prefix . 'plat_setting ';
        $sql = "select value as parameter_value
                from " . $table . "
                where name = '" . $invite_name . "'
                limit 0,1 ";
        $result = DB::select($sql, []);
        if (count($result) == 1) {
            $reward_id = $result[0]->parameter_value;
        }

        return $reward_id;
    }

    /**
     * 获取微信分享礼品首页中首页图片
     */
    function getWxShareGiftIndexImg()
    {
        $WxShareGiftIndexImg = $this->getPlatSetting('wx_share_gift_index_img');
        return $WxShareGiftIndexImg;
    }

    /**
     * 获取微信分享礼品的固定运费
     */
    function getWxShareGiftFreight()
    {
        $WxShareGiftFreight = $this->getPlatSetting('wx_share_gifts_freight');
        return $WxShareGiftFreight;
    }

    /**
     * 获取平台设置的参数值
     */
    function getPlatSetting($parameter_name = '')
    {
        $parameter_value = '';
        $parameter_name = $parameter_name . '';
        $table = $this->db_prefix . 'plat_setting ';
        $sql = "select value as parameter_value
                from " . $table . "
                where name = '" . $parameter_name . "'
                limit 0,1 ";
        $result = DB::select($sql, []);
        if (count($result) == 1) {
            $parameter_value = $result[0]->parameter_value;
        }

        return trim($parameter_value . '');
    }

    /**
     * 根据平台设置的会员等级信息, 为不同会员添加等级码(grade)
     * @param array $class_arr
     * @return array
     */
    function getMemberClass($class_arr = [])
    {
        if (!$class_arr) {
            $class_arr = PlatSetting::where('name', 'like', '%_class_member')->get()->toArray();
        }

        $new_class_arr = [];
        foreach ($class_arr as $item) {

            switch ($item['name']) {

                case 'first_class_member':
                    $grade_code = 20;
                    break;

                case 'second_class_member':
                    $grade_code = 30;
                    break;

                case 'third_class_member':
                    $grade_code = 40;
                    break;

                default :
                    $grade_code = 10;
                    break;
            }

            $new_class_arr[$grade_code] = [
                'class_code' => $item['name'],
                'class_name' => $item['value'],
                'description' => $item['description'],
                'grade_code' => $grade_code,
                'grade_name' => $item['value'] // 现在暂时和class_name一样, 到时候优化可以用于丰富前台页面展示
            ];
        }

        return $new_class_arr;
    }

    /**
     *  验证当前用户是否绑定手机，
     *      1、如果没有绑定，或导航到绑定页面，或返回空字符串；
     *      2、如果绑定，返回绑定的手机号
     */
    function getMemberBindMobile($member_id = 0, $returnView = 0)
    {
        $member_id = (int)$member_id;
        if ($member_id == 0) {
            $member = Auth::user();
            if ($member) {
                $member_id = $member->member_id;
            }
        }

        /*
        member_id	int(10) 	    会员id
        mobile		varchar(20) 	手机号
        mobile_bind	tinyint(4)	    手机是否绑定(0:未绑定;1:已绑定)
        */
        $bind_mobile_num = '';
        $table = $this->db_prefix . 'member ';
        $sql = "select member_id,mobile,mobile_bind
                from " . $table . "
                where member_id = '" . $member_id . "'
                limit 0,1 ";
        $result = DB::select($sql, []);
        if (count($result) == 1) {
            if ($result[0]->mobile_bind) {
                $bind_mobile_num = trim($result[0]->mobile . '');
            }
        }

        if ($bind_mobile_num) {
            // 返回绑定的手机号
            return $bind_mobile_num;
        } else {
            if ($returnView) {
                // 导航到绑定手机页面
                return view('user.wx_user_mobileBind', compact('member_id'));
            } else {
                // 返回空字符串
                return '';
            }
        }
    }

    // 如果平台订单是自动拆单给供应商，自动化任务队列增加拆单业务
    function addDismantleOrderJobToQueue($plat_order_id)
    {
        // 平台订单是否自动派单（给供货商）【0人工（默认）; 1自动】
        $is_auto_order_split = (int)($this->getPlatSetting('is_auto_order_split'));
        if ($is_auto_order_split == 1) {
            $this->dispatch(new AutoDismantleOrder($plat_order_id));
        }
    }

    /**
     * 根据分销商id获取其微信公众平台调用令牌(保存方式:缓存)
     * @param $company_id
     * @return null|string
     */
    public function getCompanyAccessTokenById($company_id)
    {
        // 分销商公众号信息
        $company_gzh_info_obj = CompanyOfficialAccount::where('company_id', $company_id)->first();
        if (!$company_gzh_info_obj) {
            return null;
        }

        // 是否存在缓存中
        $cache_key = 'company_gzh_info_with_' . $company_id;
        $cache_value = Cache::get($cache_key);

        // 如果存在并且分销商公众号没有变更, 返回其调用令牌
        if ($cache_value && $cache_value['appid'] == $company_gzh_info_obj->authorizer_appid) {
            return $cache_value['access_token'];
        };

        // 该分销商刷新令牌已过期, 需要重新授权(待优化)
        if ($company_gzh_info_obj->refresh_token_expires <= time()) {
            // ... 需要重新授权
        }

        $token_url = 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token=' . $this->getComponent_at();
        $post_data = '{
                        "component_appid":"' . config('wechat.open_platform.app_id') . '",
                        "authorizer_appid":"' . $company_gzh_info_obj->authorizer_appid . '",
                        "authorizer_refresh_token": "' . $company_gzh_info_obj->authorizer_refresh_token . '"
                      }';

        $result = json_decode($this->postData($token_url, $post_data));
        if (isset($result->errcode)) {
            Log::error('接口调用失败(调用"获取公众号调用令牌"接口), 微信错误码: ' . $result->errcode .
                '; 微信错误信息: ' . $result->errmsg . ' => 控制器:BaseController@getCompanyAccessTokenById');
            exit();
        }

        $company_gzh_info_obj->authorizer_access_token = $result->authorizer_access_token;
        $company_gzh_info_obj->authorizer_refresh_token = $result->authorizer_refresh_token;

        // 将要保存到缓存中的数组
        $cache_value = [
            'appid' => $company_gzh_info_obj->authorizer_appid,
            'access_token' => $result->authorizer_access_token
        ];

        // 存入缓存, 失效期提前30分钟
        Cache::put($cache_key, $cache_value, $result->expires_in / 60 - 30);

        return $result->authorizer_access_token;
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
            Log::error('component_verify_ticket 为空或已过有效期！=> 控制器:AccessController@getComponent_at');
            exit();
        }

        // 根据 verify_ticket 获取开放平台 component_access_token
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
        $post_data = '{
                        "component_appid":"' . config('wechat.open_platform.app_id') . '",
                        "component_appsecret":"' . config('wechat.open_platform.secret') . '",
                        "component_verify_ticket": "' . $own_open_plat_info->ticket . '"
                      }';
        $result = json_decode($this->postData($url, $post_data));

        if (isset($result->errcode)) {
            Log::error('请求微信接口(获取第三方平台component_access_token失败), 微信错误码: ' .
                $result->errcode . '微信错误信息: ' . $result->errmsg . ' => 控制器:BaseController@getComponent_at');
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
     * 获取是否关注信息
     * @return string
     */
   /* public function getSubscribe($member_id)
    {

        //判断是否关注公众号
        $public_info=MemberPublicInfo::where('member_id',$member_id)->first();
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

        return $subscribe;

    }
*/
    /** 获取某一个广告位可投放的广告，以数组格式返回
     * 传入参数：
     *  $position_code string 广告位，数据格式为：“'A0100','A0111'”，空字符串为所有；
     *  $advert_num  int 广告数量，默认1行
     *  传出参数 data Json 格式的数据，其中主数据有：
     *      $advertList     array   广告列表（数组）
     */
    public function getAdvertList($position_code = '', $advert_num = 1)
    {
        $advertList = array();
        $position_code = trim($position_code . '');
        $advert_num = (int)$advert_num;
        if ($advert_num <= 0) {
            $advert_num = 1;
        }

        // 当前时间
        $current_time = time();
        $db_prefix = $this->db_prefix;
        $where = " where client_flag = 'A'
                    and advert_state = 1
                    and (put_start_time = 0 or put_start_time <= " . $current_time . ")
                    and (put_end_time=0 or put_end_time >=" . $current_time . ")";
        if ($position_code <> "") {
            // 广告位条件
            $where .= " and position_code in (" . $position_code . ")";
        }

        // 1、统计符合查询条件的行数
        $sql_query = "select advert_id,advert_title,images,out_url
                    from " . $db_prefix . "advert " .
            $where .
            " order by put_weight desc
                    limit 0," . $advert_num;
        $query_result = DB::select($sql_query, []);
        foreach ($query_result as $row) {
            // 广告图片地址是相对目录，要添加上域名
            $row->images = $this->getFullPictureUrl($row->images);
        }

        if (count($query_result) > 0) {
            $advertList = $query_result;
        }

        return $advertList;
    }

}