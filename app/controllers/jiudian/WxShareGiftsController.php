<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/11
 * Time: 10:04
 */

namespace App\controllers\jiudian;


use App\facades\Api;
use App\controllers\ys\OrderController;
use App\controllers\wx\WxPayController;
use App\models\goods\GoodsClass;
use App\models\goods\GoodsSpu;
use App\models\goods\GoodsSpuImages;
use App\models\goods\GoodsSpecDefine;
use App\models\goods\GoodsSpecValueDct;
use App\models\dct\DctArea;
use EasyWeChat\Foundation\Application;
use App\models\order\Order as PlatOrder;

use App\models\goods\GoodsSku;
use App\models\goods\GoodsSkuImages;
use App\models\member\MemberCollect;
use App\models\order\Order;
use App\models\member\MemberAddress;


use App\models\member\MemberCart;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class WxShareGiftsController extends BaseController
{

    /**
     * 微信送礼首页，用于显示微信送礼中各个标签页下的商品列表
     */
    public function index(){

        //查找微信送礼下各个标签及标签下商品列表
        $db_prefix = $this->db_prefix;
        $sql_query = "select share_gifts_label_id,share_gifts_label_name,gifts_list
                      from " . $db_prefix . "share_gifts_label_list
                      where is_usable = 1
                      order by sort desc ";
        $query_result = DB::select($sql_query, []);

        //遍历各个标签，得到标签下各个spu信息
        $label_goods_list = array();
        foreach($query_result as $k => $label){
            $tem = array();
            $tem['share_gifts_label_id'] = $label->share_gifts_label_id;
            $tem['share_gifts_label_name'] = $label->share_gifts_label_name;
            $tem['g_list'] = array();
            $goods_list = unserialize($label->gifts_list);
            foreach($goods_list as $key => $good){
                $g_info = array();
                //取出每个商品spu的规格
                $goods = GoodsSpu::select('spu_id', 'spu_code',
                    'gc_id', 'gb_name', 'keywords',
                    'spu_name', 'ad_link_url', 'spu_attr',
                    'spec_name', 'spec_value',
                    'spu_market_price', 'spu_plat_price',
                    'spu_groupbuy_price', 'spu_trade_price',
                    'spu_partner_price', 'spu_points_limit', 'is_virtual',
                    'main_image', 'mobile_content', 'state')
                    ->where("spu_id", $good['spu_id'])
                    ->first();
                // SPU 不存在，直接退出
                if (!$goods) {
                    continue;
                }

                $g_info['spu_id'] = $goods->spu_id;
                $g_info['spu_name'] = $good['spu_name']; //商品名称和图片读取序列化中的
                $g_info['main_image'] = $this->getFullPictureUrl($good['main_image']);
                $g_info['spu_plat_price'] = $goods->spu_plat_price;
                $g_info['spu_market_price'] = $goods->spu_market_price;
                $g_info['spu_partner_price'] = $goods->spu_partner_price;
                $g_info['spu_trade_price'] = $goods->spu_trade_price;
                $g_info['spu_groupbuy_price'] = $goods->spu_groupbuy_price;

                $g_info['spu_price'] = $goods->spu_plat_price;

                // 3、SPU规格(名称、值)
                /* SPU规格值（spec_value），序列化数组，数据格式如下：
                Array (
                    [1] => Array (
                            [126] => 黑西装,
                            [127] => 黑西装+半裙,
                            [128] => 黑西装+衬衫+西裤,
                            [129] => 四件套裙裤),
                    [104] => Array (
                            [114] => 165/92A,
                            [117] => 170/100A)
                    )

                这里：1、104 为spec_id；
                      126、127、129、114、117等为 spec_value_id
                */
                $goods->spec_value = (unserialize($goods->spec_value) == false ?
                    [] : unserialize($goods->spec_value));
                $spuSpec = [];
                foreach ($goods->spec_value as $g_key => $item) {
                    // $item 为规格值数组，$g_key 为spec_id；
                    $spec = GoodsSpecDefine::find($g_key);
                    $data = [
                        'data_type' => $spec->data_type,
                        'spec_name' => $spec->name,
                        'spec_value' => []
                    ];

                    foreach ($item as $key1 => $value) {
                        $data['spec_value'][$key1] = $value;
                    }

                    // $data 信息项：规格名、规格值（数组）、规格值类型
                    array_push($spuSpec, $data);
                }
                $g_info['spuSpec'] = $spuSpec;

                array_push($tem['g_list'], $g_info);
            }

            array_push($label_goods_list, $tem);
        }

        $wx_share_gift_index_img = $this->getWxShareGiftIndexImg();
        return view("wx_gift.wxgift_index", compact('wx_share_gift_index_img','label_goods_list'));
    }

    /**
     * 微信送礼购买商品，sku商品详情
     */
     public function order_confirm(Request $request){

         // 当前登录用户信息，会员类别(grade)【10:普通会员;20:股东;30:分销伙伴;40:合伙人】
         $grade = 10;
         $member = Auth::user();
         if ($member) {
             $grade = $member->grade;
             $member_id = $member->member_id;
         }

         // 获取spu_id 如果为空 返回错误
         $spu_id = $request->input('spu_id');
         if (empty($spu_id)) {
             $errorData = array('code' => 10000, 'message' => '该商品已下架!');
             return view("errors.error", compact('errorData'));
         }

         // 获取规格 不存在规格 可能为无规格商品 即spu对应一件sku商品
         $spec = $request->input('spec');
         if (empty($spec)){
             $sku_info = GoodsSku::where('spu_id', $spu_id)->first();
             if (!$sku_info) {
                 $errorData = array('code' => 10000, 'message' => '该商品规格错误!');
                 return view("errors.error", compact('errorData'));
             }

             $sku_id = $sku_info->sku_id;
             $price = $this->getSkuPrice($grade, $sku_info); //获取用户级别对应的价格

             // 存在规格 则找出指定的sku商品
         } else {
             // 处理前台数据 得到规格数组信息 $spec_arr
             $spec_arr = array();
             $arr = explode('SEPARATOR', $spec);
             foreach ($arr as $spec) {
                 $temp_arr = explode('CONNECTOR', $spec);
                 $spec_arr[$temp_arr[0]] = $temp_arr[1];
             }

             // 比对规格数组序列化值 找出sku商品
             $sku_spec = serialize($spec_arr);
             $sku_info = GoodsSku::where('spu_id', $spu_id)->where('sku_spec', $sku_spec)->first();
             $sku_id = $sku_info->sku_id;
             $price = $this->getSkuPrice($grade, $sku_info); //获取用户级别对应的价格
             if (!$sku_id) {
                 $errorData = array('code' => 10000, 'message' => '该商品sku不存在!');
                 return view("errors.error", compact('errorData'));
             }
         }

         //获取商品的sku主图及运费
         $obj_spu = GoodsSpu::select('freight')->where('spu_id', $spu_id)->first();
         $sku_main_img = GoodsSkuImages::mainImg($sku_id);
         // 图片格式化为带域名的字符串
         $sku_main_img = $this->getFullPictureUrl($sku_main_img);

         $gift_num = $request->input('gift_num');

         $sku_arr = array(
             'spu_id' => $spu_id,
             'sku_id' => $sku_id,
             'sku_name' => $sku_info->sku_name,
             'price'  => $price,
             'main_img' => $sku_main_img,
             'goods_num' => $gift_num,
             'sku_spec' => empty($sku_info->sku_spec) ? array() : unserialize($sku_info->sku_spec),
         );


         $plat_vrb_rate = $this->getPlatVrbRate();

         $freight_total = 0.0;  //购买商品的总运费
         $goods_amount_totals = 0.00;  // 商品结算金额
         $all_amount_totals = 0.00; //订单的总金额 = 商品总金额 + 运费总金额
         $transport_to_v = 0; //运费对应的虚拟币总额
         $goods_amount_to_v = 0; //商品总金额对应的虚拟币总数
         $all_amount_to_v = 0; //订单总金额对应的虚拟币
         $amount_arr = array(); //记录各种费用信息
         $amount_arr['freight_total'] = 0; //运费总额
         $amount_arr['transport_to_v'] = 0; //运费总额对应的虚拟币
         if($obj_spu->freight > 0){ //不免运费,读取微信分享礼品系统设置的固定值
             $gift_freight= $this->getWxShareGiftFreight();
             $freight_total = bcmul($gift_freight, $gift_num, 2); //运费的总金额，目前运费就是商品的个数乘以固定运费
             $transport_to_v = floor($freight_total) * $plat_vrb_rate; //总运费对应的虚拟币
             $amount_arr['freight_total'] = $freight_total; //运费总额
             $amount_arr['transport_to_v'] = $transport_to_v; //运费总额对应的虚拟币
         }

         $goods_amount_totals =  bcmul($price, $gift_num, 2); //商品的总金额
         $all_amount_totals =  bcadd($goods_amount_totals,$freight_total,2);  //订单的总金额

         $goods_amount_to_v = floor($goods_amount_totals) * $plat_vrb_rate; //商品总金额对应的虚拟币
         $all_amount_to_v = $transport_to_v + $goods_amount_to_v; //订单总金额对应的虚拟币

         $amount_arr['goods_amount_totals'] = $goods_amount_totals; //商品总额
         $amount_arr['goods_amount_to_v'] = $goods_amount_to_v; //商品总额对应的虚拟币
         $amount_arr['all_amount_totals'] = $all_amount_totals; //订单总金额
         $amount_arr['all_amount_to_v'] = $all_amount_to_v; //订单总额对应的虚拟
         $amount_arr['plat_vrb_rate'] = $plat_vrb_rate; //比率

         //获取支付列表
         $define_skus = array();
         $data['sku_id'] = $sku_id;
         array_push($define_skus, $data);

         $OrderController = new OrderController();
         $my_money_info = $OrderController->iniMyMoneyInfo($all_amount_totals,$all_amount_to_v,$define_skus);

         return view('wx_gift.wxgift_pay', compact(
             'my_money_info','amount_arr','sku_arr'
         ));

     }

     /**
      * 当微信分享礼品订单支付取消时，删除相关订单，并回退所有的支付金额（虚拟币，卡余额， 零钱等）
      * 当订单存在支付时间，则表明该订单已用微信金进行支付了，则需要微信腿短
      *
      */
    public function cancelShareGiftOrder($plat_order_id){

        $plat_order_id = (int)$plat_order_id;
        //取消前先判断是否能取消，也即是有没有被人领过，若有一个人领过，就不能取消
        $share_info = DB::table('share_gifts_info')->where('plat_order_id',$plat_order_id)->first();
        if(!$share_info){
            return Api::responseMessage(1, '', '该礼品已失效！');
        }

        //判断当前领取人数大于零，则表示有人领取了，则不能取消
        if($share_info->current_num > 0){
            return Api::responseMessage(1, '', '该礼品已有人领取，不能取消该订单！');
        }

        //若订单中存在支付时间，这说明订单用微信支付了，则需要微信退款
        $plat_order = PlatOrder::where('plat_order_id', $plat_order_id)->first();
        if (empty($plat_order)) {
            return Api::responseMessage(1, '', '平台订单id不存在 无法申请退款！');
        }
        if(!($plat_order->pay_rmb_time)){
            //回退各个非人民币支付金额
            $orderCon = new orderController();
            $orderCon->cancelNotRmbPayLog($plat_order_id);

            //删除订单订单
            DB::table('order_goods')->where('plat_order_id',$plat_order_id)->delete();
            DB::table('share_gifts_info')->where('plat_order_id',$plat_order_id)->delete();
            DB::table('order_extend')->where('plat_order_id',$plat_order_id)->delete();
            DB::table('order')->where('plat_order_id',$plat_order_id)->delete();

            return Api::responseMessage(0, null, '取消订单成功! ');
        }

        $pay_con = new WxPayController(new Application(config('wechat')));

        $result  = $pay_con->wxRefund($plat_order_id);
        if($result){

            //回退各个非人民币支付金额
            $orderCon = new orderController();
            $orderCon->cancelNotRmbPayLog($plat_order_id);

            //删除订单订单
            DB::table('order_goods')->where('plat_order_id',$plat_order_id)->delete();
            DB::table('share_gifts_info')->where('plat_order_id',$plat_order_id)->delete();
            DB::table('order_extend')->where('plat_order_id',$plat_order_id)->delete();
            DB::table('order')->where('plat_order_id',$plat_order_id)->delete();

            return Api::responseMessage(0, null, '微信退款成功! ');
        }else{

            return Api::responseMessage(1, null, '微信退款失败! ');
        }


    }


    /**
     * 用户点击别人分享的微信礼品链接看到的商品信息
     */
   public function shareGiftInfo($share_gifts_info_id){

       $share_info = DB::table('share_gifts_info')->where('share_gifts_info_id',$share_gifts_info_id)->first();

       if(!$share_info){
           $errorData = array('code' => 50002, 'message' => '该礼品已失效');
           return view("errors.error", compact('errorData'));
       }

       return view('wx_gift.wxgift_toget', compact(
           'order_info','share_info'
       ));

   }

    /**
     * @param $share_gifts_info_id
     * 检查礼品是否可以领取
     */
    public function checkToGetGift($share_gifts_info_id){
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        }

        $share_info = DB::table('share_gifts_info')->where('share_gifts_info_id',$share_gifts_info_id)->first();
        if(!$share_info){
            return Api::responseMessage(0, '', '该礼品已失效！');
        }

        //发起着不能领自己的礼品，领取者只能领一次
        if($member_id == $share_info->member_id){
            return Api::responseMessage(0, '', '不能领取您自己的微信礼品');
        }

        //判断领取者是否领取过
        $get_gifts = DB::table('get_gifts_info')
                      ->where('share_gifts_info_id',$share_gifts_info_id)
                      ->where('member_id',$member_id)
                      ->first();
        if($get_gifts){
            return Api::responseMessage(0, '', '您已领取过该礼品！');
        }

        //判断当前领取人数是否达到上限
        if($share_info->gifts_num > $share_info->current_num){
            return Api::responseMessage(1, '', '可以领取');
        }else{ //已达到上限
            return Api::responseMessage(0, '', '该礼品已被领取完！');
        }

    }


    /**
     * @param $plat_order_id
     * 用户领取礼品时，在礼品详情中填写收货地址
     *
     */
    public function getGiftDetailInfo($share_gifts_info_id){

        $member_id = 0;
        $member = Auth::user();
        if ($member)
        {
            $member_id = $member->member_id;
        }
        else
        {
            // 当前买家未登录，导航到登录界面
            return redirect('/oauth');
        }

        $share_info = DB::table('share_gifts_info')->where('share_gifts_info_id',$share_gifts_info_id)->first();
        if(!$share_info){
            $errorData = array('code' => 50002, 'message' => '该礼品已失效');
            return view("errors.error", compact('errorData'));
        }


        // ---------------------------------------领取礼品的收货人地址----------------------------------------
        // 当前领取礼品人的收货地址列表
        $address_info = MemberAddress::selectZd()
            ->where('member_id', $member_id)
            ->where('use_state', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        // 如果当前买家没有收货地址，提示买家增加
        $is_hasAddress = $address_info->isEmpty() ? 0 : 1;

        // 当前买家默认收货地址
        $default_address = MemberAddress::selectZd()
            ->where('member_id', $member_id)
            ->where('use_state', 0)
            ->where('is_default', 1)
            ->first();

        // 省地址数组（新建地址信息需要）
        $province_dct = DctArea::select('id', 'name', 'pid')
            ->where('pid', 0)
            ->where('is_use', 1)
            ->get()
            ->toArray();

        return view('wx_gift.wxgift_toget_detailInfo', compact(
            'is_hasAddress', 'address_info', 'default_address','province_dct','share_info'
        ));

    }


    /**
     * @param $share_gifts_info_id
     * 用户生成微信送礼订单后，跳到分享页面进行分享
     */
   public function giftToShare($share_gifts_info_id){
       $member = Auth::user();
       if ($member) {
           $member_id = $member->member_id;
           $nick_name = $member->nick_name;
       }

       $share_info = DB::table('share_gifts_info')->where('share_gifts_info_id',$share_gifts_info_id)->first();
       if(!$share_info){
           return Api::responseMessage(0, '', '该礼品已失效！');
       }


       //微信jsapi
       $signPackage = session('signPackage');

       $url = "http://$_SERVER[HTTP_HOST]";
       $share_link = $url . "/gift/shareGiftInfo/".$share_info->share_gifts_info_id;

       return view('wx_gift.wxgift_share', compact('share_info','signPackage','share_link','nick_name'));


   }

    /**
     * 用户领取礼品成功后跳到领取成功页面
     */
    public function getGiftSuccess($share_gifts_info_id){

        $share_info = DB::table('share_gifts_info')->where('share_gifts_info_id',$share_gifts_info_id)->first();
        if(!$share_info){
            return Api::responseMessage(0, '', '该礼品已失效！');
        }

        //用户去分享，引流其他用户到该网站
        //微信jsapi
        $member = Auth::user();

        $signPackage = session('signPackage');

        $url = "http://$_SERVER[HTTP_HOST]";
        $share_link = $url . "/shop/index";

        return view('wx_gift.wxgift_getSuccess', compact('share_info','signPackage','share_link','member'));


    }


}