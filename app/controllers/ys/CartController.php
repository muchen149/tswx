<?php

namespace App\controllers\ys;

use App\facades\Api;
use App\Jobs\AutoDismantleOrder;

use App\models\member\MemberCart;
use App\models\goods\GoodsSku;
use App\models\goods\GoodsSpecDefine;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends BaseController
{
    /**
     * 购物车列表页
     * 传入参数：
     *      @param int $num
     * @return mixed
     */
    public function index($num = 100)
    {
        // 当前登录用户信息
        $grade = 10;
        $member_id = 0;
        $member = Auth::user();
        if ($member){
            $member_id = $member->member_id;
            $grade = $member->grade;
        }else{
            // 当前用户没有登录，跳到登录页面进行登陆
            return redirect('/oauth');
        }

        // 标识购物车内是否存在有失效的SKU，默认没有
        $is_showExpire = 0;

        // 购物车内SKU 列表，数组
        $skus = array();
        $db_prefix = $this->db_prefix;
        $sql_query = "select a.cart_id,a.sku_id,a.number,
                        b.sku_name,b.sku_title,b.sku_spec,
                        b.market_price,b.price,b.groupbuy_price,b.trade_price,b.partner_price,
                        b.use_state as sku_state,b.spu_id,
                        c.state as spu_state,c.main_image as spu_image,c.spec_value,
                        d.image_url as sku_image,
                        m.minimum_limit,m.base_discount_rate,m.use_state
                    from " . $db_prefix . "member_cart as a
                        inner join " . $db_prefix . "goods_sku as b on a.sku_id = b.sku_id
                        inner join " . $db_prefix . "goods_spu as c on b.spu_id = c.spu_id
                        left join " . $db_prefix . "goods_sku_images as d on a.sku_id = d.sku_id and d.is_default = 1
                        left join " . $db_prefix . "member_goods_sku as m on a.sku_id = m.sku_id and a.member_id = m.member_id
                    where a.member_id = " . $member_id . "
                    order by a.updated_at desc ";
        $query_result = DB::select($sql_query, []);
        foreach ($query_result as $cart_sku)
        {
            $sku_id = $cart_sku->sku_id;

            // SKU名称显示标题
            $sku_title = trim($cart_sku->sku_title);
            if (!$sku_title) {
                $sku_title = $cart_sku->sku_name;
            }

            // 根据买家类别，获取应享受的价格
            $price = $this->getSkuPrice($grade, $cart_sku);

            // 获取商品SKU展示图，如果SKU没有设置，启用SPU图
            $sku_img = trim($cart_sku->sku_image . '');
            if (!$sku_img)
            {
                $sku_img = trim($cart_sku->spu_image . '');
            }

            // 图片的相对目录增加上域名
            $sku_img = $this->getFullPictureUrl($sku_img);

            // 购物车中商品SKU状态，默认无效（$state = 0）
            // sku_state SKU有效状态【0:无效;1:有效;-1:已删除】
            // spu_state SPU有效状态【0:未上架（待上架）; 1:上架在售;
            //                      2:自主下架; 3:系统下架（违规下架）; 5:初建待申请;7:待审核;8:审核不通过;】
            $state = 0;
            $sku_state = $cart_sku->sku_state;
            $spu_state = $cart_sku->spu_state;
            if ($sku_state == 1 && $spu_state == 1)
            {
                $state = 1;
            }

            $arr = [
                'cart_id' => $cart_sku->cart_id,
                'sku_id' => $sku_id,
                'spu_id' => $cart_sku->spu_id,
                'sku_name' => $sku_title,
                'price' => $price,
                'number' => $cart_sku->number,
                'main_img' => $sku_img,
                'sku_spec' => empty($cart_sku->sku_spec) ? array() : unserialize($cart_sku->sku_spec),
                'state' => $state,
                'minimum_limit' => 1 //默认购买最低下限为1
            ];

//            //对于团采用户或代理用户，若member_goods_sku记录有效，购买下限即为设置的下限，否则默认为1
//            if(($grade == 20 || $grade == 30) && $cart_sku->use_state == 1){
//                $arr['minimum_limit'] =  $cart_sku->minimum_limit;
//            }

            if ($state == 0)
            {
                // 存在有失效的SKU
                $is_showExpire = 1;
            }


           //处理spu规格，在修改购物车中可以选择规格
            $cart_sku->spec_value = (unserialize($cart_sku->spec_value) == false ?
                [] : unserialize($cart_sku->spec_value));
            $spuSpec = [];
            foreach ($cart_sku->spec_value as $key => $item) {
                // $item 为规格值数组，$key 为spec_id；
                $spec = GoodsSpecDefine::find($key);
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

            $arr['spuSpec'] = $spuSpec;


            array_push($skus, $arr);
        }

        return view('cart.cart_list', compact('skus', 'is_showExpire'));
    }


    /**
     * 浏览商品, 将商品添加购物车
     * @param Request $request
     * @return mixed
     */
    public function add(Request $request)
    {
        // 获取当前登录用户信息
        $member = Auth::user();
        //用户没登录，去登陆
        if(!$member){
            return Api::responseMessage(10010);
        }

        // 获取spu_id 如果为空 返回错误
        $spu_id = $request->input('spu_id');
        if (empty($spu_id)) {
            return Api::responseMessage(10000);
        }

        // 获取规格 不存在规格 可能为无规格商品 即spu对应一件sku商品
        $spec = $request->input('spec');
        if (empty($spec)){
            $sku_id = GoodsSku::where('spu_id', $spu_id)->value('sku_id');
            if (empty($sku_id)) {
                return Api::responseMessage(10000);
            }

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
            $sku_id = GoodsSku::where('spu_id', $spu_id)->where('sku_spec', $sku_spec)->value('sku_id');

            if (!$sku_id) {
                return Api::responseMessage(10001);
            }

        }

        // 添加购物车中商品数量
        $num = $request->input('shuliang');
        if ($num <= 0) {
            return Api::responseMessage(10001);
        }

        //对于团采用户和代理用户，加入购物车前，判断购买商品是否符合最低购买下限，
        //若传过来的数量小于购买最低下限，默认保存为最低下限（因为点击加入购物车过快时，传过来的值可能小于最低下限）
//        if($member->grade = 20 || $member->grade = 30){
//            //查看该sku对应的商品有没有最低购买限制限制
//            $sql_info = DB::table('member_goods_sku')->select('member_id', 'minimum_limit', 'sku_id')
//                ->where('sku_id', $sku_id)
//                ->where('member_id',$member->member_id)
//                ->first();
//            if($sql_info){
//                //若有记录，则判断传过来商品数量是否小于购买最低下限，若小于，则保存最低购买下限
//                if($sql_info->minimum_limit > $num){
//                    $num = $sql_info->minimum_limit;
//                }
//            }
//        }

        // 加入购物车
        $cart_sku = MemberCart::skuByMember($member->member_id, $sku_id)->first();
        if (!$cart_sku) {
            MemberCart::create([
                'member_id' => $member->member_id,
                'member_name' => $member->member_name,
                'sku_id' => $sku_id,
                'number' => $num
            ]);

        } else {

            // 购物车存在该商品
            $cart_sku->number += $num;
            $cart_sku->save();
        }

        //统计购物车中商品的总数量，显示出目前购物车中商品中的数量
        $goods_num_in_cart = MemberCart::where('member_id', $member->member_id)->sum('number');

        return Api::responseMessage(0,$goods_num_in_cart);
    }


    /**
     * 通过输入框直接修改购物车商品数量
     * @param $sku_id
     * @param $num
     * @return mixed
     */
    public function updateNumBySkuId($cart_id, $num)
    {
        // 获取当前登录用户信息,用户合法性由本控制器构造函数验证
        $member_id = Auth::user()->member_id;
        if (is_numeric($num) && $num > 0)
        {
            // 直接修改数量
            MemberCart::skuByCartId($member_id, $cart_id)
                ->update(['number' => $num]);
            return Api::responseMessage(0);
        }

        // 数据格式不正确
        return Api::responseMessage(50001);
    }


    /**
     * 根据sku数组 skuIds 批量删除(一个或者多个)
     *
     * @param Request $request
     * @return mixed
     *
     * POST localhost/cart/delete HTTP/1.1
     * Content-Type: application/json
     * {
     *   "skuIds" : [44, 50]
     * }
     */
    public function delete(Request $request)
    {
        // 获取当前登录用户信息,用户合法性由本控制器构造函数验证
        $member_id = Auth::user()->member_id;

        // skuIds 数据格式为数组，形如：[65,100,20]
        $cartIds = $request->input('cartIds');
        $affect_num = MemberCart::where('member_id', $member_id)
            ->whereIn('cart_id', $cartIds)
            ->delete();

        if ($affect_num == 0) {
            return Api::responseMessage(10000);  // ID无效
        }

        return Api::responseMessage(0); // 删除成功
    }


    /** ajax
     * 清除当前用户添加到购物车的无效商品
     * @return mixed
     */
    public function cleanNullGoods()
    {
        $expired_skuIds = array();
        $db_prefix = $this->db_prefix;

        // 获取当前登录用户信息,用户合法性由本控制器构造函数验证
        $member_id = Auth::user()->member_id;

        // b.use_state SKU有效状态【0:无效;1:有效;-1:已删除】
        // c.state     SPU有效状态【0:未上架（待上架）; 1:上架在售;
        //                      2:自主下架; 3:系统下架（违规下架）; 5:初建待申请;7:待审核;8:审核不通过;】
        $sql_query = "select a.sku_id
                    from " . $db_prefix . "member_cart as a
                        inner join " . $db_prefix . "goods_sku as b on a.sku_id = b.sku_id
                        inner join " . $db_prefix . "goods_spu as c on b.spu_id = c.spu_id
                    where b.use_state <> 1
                    and c.state <> 1
                    and a.member_id = " . $member_id ;
        $expired_skuIds = DB::select($sql_query, []);
        if ($expired_skuIds)
        {
            MemberCart::where('member_id', $member_id)
                ->whereIn('sku_id', $expired_skuIds)
                ->delete();
        }

        // 无效商品 删除成功
        return Api::responseMessage(0);
    }


    public function log($id, $delay = 0)
    {
        if ((int)$delay) {
            $this->dispatch((new AutoDismantleOrder($id))->delay($delay));
        } else {
            $this->dispatch(new AutoDismantleOrder($id));
        }
    }

    public function addQueue($id)
    {
        $this->dispatch(new AutoDismantleOrder($id));

        return Api::responseMessage(0, '', '订单id:' . $id . '已加入队列');
    }

}
