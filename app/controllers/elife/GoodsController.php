<?php
/**
 * 用户【会员】管理
*/
namespace App\controllers\elife;

use App\facades\Api;
use App\Http\Controllers\Controller;

use App\models\goods\GoodsClass;
use App\models\goods\GoodsSpu;
use App\models\goods\GoodsSpuImages;
use App\models\goods\GoodsSpecDefine;
use App\models\goods\GoodsSpecValueDct;
use App\models\elife\ElifeGoodsLabel;

use App\models\goods\GoodsSku;
use App\models\goods\GoodsSkuImages;
use App\models\member\MemberCollect;

use App\models\member\MemberCart;

use App\models\plat\PlatSetting;
use App\models\tpl\TplTransport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GoodsController  extends BaseController
{

    /** 商品SPU详情
     *  传入参数
     *      $spuId  int     商品SPU id
     *  传出关联的SPU 详情信息
     */
    public function spuDetail($spuId = 0) {
		$member = $this->loseEfficacy();
        // 会员类别($member_grade)【10:普通会员;20:黄金会员;30:钻石会员;40:黑卡VIP】
        $grade = 20;
        $member_class_arr = PlatSetting::memberClassWithNotFree()->get()->toArray();
        $member_class_arr = $this->getMemberClass($member_class_arr);

        if($grade <30){
            $supG=$grade+10;
        }else{
            $supG=$grade;
        }

        $isHighest=0;
        $nextPriv=[];
        if($supG!=50 && $supG < 40)
            $nextPriv=$member_class_arr[$supG];
        else
            $isHighest=1;

        // 1、SPU基本信息
        /* spu 价格体系：
        spu_market_price	市场价【通过模型计算的国内市场均价，指导价】
        spu_plat_price		平台价格【官网价、官网零售价】
        spu_groupbuy_price	团购价
        spu_trade_price		批发价
        spu_partner_price	(分销伙伴)抄底价【成本价 + 管理费】
        spu_cost_price		成本价【平台进价 + 运费】
        */
        $spuId = (int)$spuId;
        $goods = GoodsSpu::select('spu_id', 'spu_code',
            'gc_id', 'gb_name', 'keywords',
            'spu_name', 'ad_info' ,'ad_link_url', 'spu_attr',
            'spec_name', 'spec_value',
            'spu_market_price', 'spu_plat_price',
            'spu_groupbuy_price', 'spu_trade_price',
            'spu_partner_price', 'spu_points_limit', 'spu_storage_num', 'is_virtual',
            'main_image', 'mobile_content', 'state','from_plat_code',
            'from_plat_name','tpl_transport_id')//'importType'
            ->where("spu_id", $spuId)
            ->first();

        // SPU 不存在，直接退出
        if (!$goods) {
            return view('errors.e_error');
        }

        $db_prefix = config('database')['connections']['mysql']['prefix'];
        $spbsel= "SELECT spb.supplier_name FROM  ".$db_prefix."supplier_baseinfo spb where spb.supplier_id in(
            SELECT  spsku.supplier_id from ".$db_prefix."supplier_goods_sku spsku where sku_id in
            (SELECT sku_id from ".$db_prefix."goods_sku sku where sku.spu_id=".$spuId."))";
        $spb_name=DB::select($spbsel);
        $p_name='';
        if(!empty($spb_name)){
            $p_name=$spb_name[0]->supplier_name.$p_name;
            $goods->from_plat_name=$p_name;
        }

        //运费模板
        if($goods->tpl_transport_id != 0){
            $tpl = TplTransport::select('isFreeDelivery','limitNum')->where('id',$goods->tpl_transport_id)->first();
        }

        // 1、处理图片地址
        $goods->main_image = $this->getFullPictureUrl($goods->main_image);

        // 根据当前登录人员类别（grade）【10:普通会员;20:股东;30:分销伙伴;40:合伙人】不同，
        // 显示不同的销售价格
        $goods->spu_price = $this->getSpuPrice($grade, $goods);
        $goods->supper_spu_price=$this->getSupperSpuPrice($supG,$goods);

        /*if($grade==10){
            $goods->sup_discount= $goods->spu_market_price - $goods->supper_spu_price;
        }else{
            $goods->sup_discount= $goods->spu_market_price - $goods->spu_price;
        }*/
        //运费模板
        if($grade < 30){
            $goods->sup_discount= $goods->spu_price - $goods->supper_spu_price;
        }else{
            $goods->sup_discount= $goods->spu_market_price - $goods->spu_price;
        }

        // 该商品的虚拟币支付限额
        $goods->spu_points = $this->getPointsLimit($goods->spu_points_limit, $goods->spu_price);

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
        foreach ($goods->spec_value as $key => $item) {
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

        // 4、商品SPU属性(spu_attr),数据格式为：
        /*
            Array([80768] => Array ([name] => 风格,[445080] => 淑女),
                      [80769] => Array ([name] => 裙长, [445095] => 短裙 ),
                      [80770] => Array ([name] => 版型, [445100] => 修身型))
           其中：
			“80768”为属性ID（attr_id）
			“风格”为属性名称（attr_name）

			“445080”为属性值ID（attr_value_id）
			“淑女”为属性值ID名称（attr_value_name）
        */
        $spuAttr = (unserialize($goods->spu_attr) == false ?
            [] : unserialize($goods->spu_attr));

        // 5、SPU 图片，注意图片地址是相对目录
        $spuImages = GoodsSpuImages::where('spu_id', $spuId)->get();
        if ($spuImages) {
            // 处理图片地址，由相对地址处理成绝对地址
            foreach ($spuImages as $row) {
                $row->image_url = $this->getFullPictureUrl($row->image_url);
            }
        }

        // 9、虚拟币名称
        $plat_vrb_caption = $this->getPlatVrbCaption();
		//10、判断窗口类型
		$getEntranceType = DB::table('member')->where('cust_id',$member->cust_id)->first();
        //11、统计购物车中商品的总数量，显示出目前购物车中商品中的数量
        $goods_num_in_cart = MemberCart::where('member_id', $member->member_id)->where('entrance_type',$getEntranceType->entrance_type)->sum('number');
        //12、购物须知
        $shopNote = DB::table('document')->where('doc_code','gwxz')->first();
		$getSkuMaxNum = DB::table('goods_sku')->where('spu_id',$goods['spu_id'])->where('sku_storage_num','>',3)->count();
		$getSkuMinNum = DB::table('goods_sku')->where('spu_id',$goods['spu_id'])->where('sku_storage_num','<=',3)->sum('sku_storage_num');
		$goods['spu_storage_num'] = $goods['spu_storage_num'] - ((3 * $getSkuMaxNum) + $getSkuMinNum);
		if ($goods['spu_storage_num'] <= 3) {
			$goods['spu_storage_num'] = 0;
		}
        return view("elife.good_detail",
            compact('goods', 'spuSpec', 'spuAttr','tpl','spuImages','plat_vrb_caption','isHighest','nextPriv','grade','member_class_arr','goods_num_in_cart','shopNote'));
    }

// 获取若干个规格值($ids)关联的规格，在某一个SPU内这些规格定义的所有规格值ID，以数组形式传入
    // $all_ids 为某一个SPU内有效的SKU的所有规格值ID【无效的SKU不含在内】

    /** 根据产品规格属性列表，动态确定一个SKU。本接口调用方法 post
     * 传入参数：
     *
     *  $spu_id     int     Spu id
     *  $ids        array   一个完整的SKU规格值ID数组
     *  传出参数 $sku或$spu(该产品没有规格)数据，Json 格式
     */
    public function getSku(Request $request) {
        // 当前登录用户信息，会员类别(grade)【10:普通会员;20:股东;30:分销伙伴;40:合伙人】
        $grade = 20;
		$member = $this->loseEfficacy();
        if ($member) {
            $grade = $member->grade;
            $member_id = $member->member_id;
        }

        // 虚拟币名称
        $plat_vrb_caption = $this->getPlatVrbCaption();

        // 传入参数【分享者等会员等级加密token、产品spu_id、拟确认的某一个SKU规格值ID(数组)】
        $stoken = $request->input('stoken');
        $spuId = $request->input('spu_id');
        $ids = (array)$request->input('ids');

        // 获取指定的某一个产品【SPU】信息
        $spuId = (int)$spuId;
        $spu = GoodsSpu::select('spu_id', 'spu_name',
            'ad_info', 'ad_link_title', 'ad_link_url',
            'gc_id', 'gc_name', 'gb_id', 'gb_name',
            'spu_attr', 'spec_name', 'spec_value',
            'main_image', 'mobile_content', 'keywords',
            'producter', 'spu_code',
            'spu_market_price', 'spu_plat_price',
            'spu_groupbuy_price', 'spu_trade_price', 'spu_partner_price',
            'spu_points_limit', 'spu_storage_num',
            'click_count', 'collect_count',
            'is_commend', 'state')
            ->where('spu_id', $spuId)
            ->first();

        // A、如果SPU 不存在，直接退出
        if ($spu) {
            // 根据当前登录人员类别（grade）【10:普通会员;20:股东;30:分销伙伴;40:合伙人】不同，
            // 显示不同的销售价格
            $spu->spu_price = $this->getSpuPrice($grade, $spu);

            // 该商品的虚拟币支付限额
            $spu->spu_points = $this->getPointsLimit($spu->spu_points_limit, $spu->spu_price);

            // SPU 主图地址
            $spu->main_image = $this->getFullPictureUrl($spu->main_image);
        } else {
            // 返回空的产品信息
            return response()->json([
                'type' => 0,
                'spu' => [],
                'ids' => [],
                'plat_vrb_caption' => $plat_vrb_caption
            ]);
        }

        // $result 保存一些规格值ID，这些规格值ID 的条件
        //  1、和 $ids 规格值不属于同一个规格系列
        //  2、不在 $ids 关联的有效SKU的规格值ID集合内
        $result = [];

        /* 该产品定义的规格名称数组、规格值数组
            1、SPU规格名称（spec_name）数组格式为：
                    Array([1] => 颜色, [23] => 铝锭规格)
                    其中：“1”和“23”为sp_id，“颜色”和“铝锭规格”为sp_name

            2、SPU规格值（spec_value）格式为：
                    Array([1] => Array([1529] => 纯白),
                         [23] => Array ([1523]=>1000*500*200,[1524] => 1000*1000*500))
                    其中：“1”和“23”为sp_id，
                          “1529”、“1523”、“1524”等为规格值ID（sp_value_id）
                          “纯白”、“1000*500*200”、“1000*1000*500”等为规格值名称（sp_value_name）
        */
        // B、如果SPU 没有定义规格或传入的规格值（$ids）不完备，例如：维数和SPU定义的不符，直接退出
        $spec_name = unserialize($spu->spec_name);
        $spec_value = unserialize($spu->spec_value);
        if (count($spec_name) == 0 ||
            count($ids) == 0 ||
            count($spec_name) != count($ids)
        ) {
            return response()->json([
                'type' => 0,
                'spu' => $spu,
                'ids' => array_unique($result),
                'plat_vrb_caption' => $plat_vrb_caption
            ]);
        }


        // C、确定一个完备的SKU信息
        $sku_item = [];

        //获取所有sku ,状态为启用（1）;sku_spec 规格值(数组序列化)

        $db_prefix = config('database')['connections']['mysql']['prefix'];

        $info_sql = "SELECT g.sku_id,g.sku_name,g.sku_title,
                      g.market_price,g.price,g.groupbuy_price,g.trade_price,
                      g.partner_price,g.sku_storage_num,g.points_limit,g.sku_spec,
                      m.member_id,m.minimum_limit,m.storage_num,m.base_discount_rate,m.use_state,g.grade_limit
                     FROM " . $db_prefix . "goods_sku AS g
                        LEFT JOIN ( select sku_id,member_id,minimum_limit,storage_num,base_discount_rate,use_state
                                    from " . $db_prefix . "member_goods_sku
                                    where member_id = " . $member_id . " and use_state = 1) m
                        ON g.sku_id = m.sku_id
                     WHERE g.spu_id =" . $spuId . " AND g.use_state = 1 ORDER BY g.sku_id";
        $skus = DB::select($info_sql);


//        $skus = GoodsSku::select('goods_sku.sku_id', 'goods_sku.sku_name', 'goods_sku.sku_title',
//            'goods_sku.market_price',
//            'goods_sku.price', 'goods_sku.groupbuy_price',
//            'goods_sku.trade_price', 'goods_sku.partner_price',
//            'goods_sku.points_limit', 'goods_sku.sku_spec',
//            'member_goods_sku.member_id','member_goods_sku.minimum_limit','member_goods_sku.storage_num','member_goods_sku.base_discount_rate','member_goods_sku.use_state')
//            ->leftJoin('member_goods_sku',function($leftJoin) use($member_id){
//                $leftJoin->on('member_goods_sku.sku_id', '=', 'goods_sku.sku_id')
//                         ->on('member_goods_sku.member_id', '=',$member_id); //左连接时没有判断member_goods_sku的use_state，若加这个判断会把左边的表记录失去，所以后面判断sku价格时候要加上use_state是否为有效1
//            })
//
//            ->where('goods_sku.spu_id', $spuId)
//            ->where('goods_sku.use_state', 1)
//            ->orderBy('goods_sku.sku_id')
////            ->toSql();
//            ->get();

        // 获取当前spu下的有效的skus的所有规格值ID【数组，不含无效的SKU的规格值ID】
        $all_ids = $this->getAllSpec($skus);

        // 获取虚拟币与人民币间的汇率【1元（人民币）等于多少依你币】，计算商品价格兑换成虚拟币数值用
        $plat_vrb_rate = $this->getPlatVrbRate();

        // 1、根据传入的规格值ID【$ids，数组】确定关联的SKU对象
        foreach ($skus as & $sku) {
            /*
                SKU规格值(sku_spec)数组格式为：
                Array ([1529] => 纯白,[1523] => 1000*500*200 )
                其中：“1529”、“1523”等为规格值ID（sp_value_id）
                      “纯白”、“1000*500*200”等为规格值名称（sp_value_name）
            */
            // 1、获取当前行SKU的规格值ID，并压入数组 $all_ids 中
            /*foreach (array_keys(unserialize($sku->sku_spec)) as $item)
            {
                array_push($all_ids, $item);
            }*/

            // $current_spec_ids(当前SKU的规格值ID列，数组)、$ids（拟确认SKU的规格值ID列，数组）
            $current_spec_ids = array_keys(unserialize($sku->sku_spec));
            if (count(array_diff($ids, $current_spec_ids)) == 0 &&
                count(array_diff($current_spec_ids, $ids)) == 0
            ) {
                // 说明当前sku就是拟确认的SKU
                $sku_id = $sku->sku_id;
                $sku_name = $sku->sku_title;
                if (!$sku_name) {
                    $sku_name = $sku->sku_name;
                }

                // 根据当前登录用户级别，获取其应享受的价格，非登录情况下，显示平台价【官网零售价】
                $price = $this->getSkuPrice($grade, $sku);

                // 该商品的虚拟币支付限额
                $sku_points = $this->getPointsLimit($sku->points_limit, $price, $plat_vrb_rate);

                // SKU 信息项：ID、名称、价格、虚拟币限额、规格
                $sku_item['sku_id'] = $sku_id;
                $sku_item['sku_name'] = $sku_name;
                $sku_item['price'] = $price;
                $sku_item['sku_points'] = $sku_points;
                $sku_item['points_limit'] = $sku->points_limit;
                $sku_item['sku_storage_num'] = $sku->sku_storage_num - 3;
				if ($sku->sku_storage_num <= 3) {
					$sku_item['sku_storage_num'] = 0;
				};
                $sku_item['sku_spec'] = $sku->sku_spec;
                $sku_item['minimum_limit'] = 1; //默认为1
                $sku_item['grade_limit'] = $sku->grade_limit;
                //团采会员，代理会员 用户能享受的折扣率，每个商品的购买下线
                if (($grade == 20 || $grade == 30) && $sku->use_state == 1) {
                    $sku_item['minimum_limit'] = $sku->minimum_limit;
                }

                /*
                |--------------------------------------------------------------------------
                | 如果是分享的链接，需要比较分享者与被分享者的会员等级，较大的与SKU购买等级下限作比较
                |--------------------------------------------------------------------------
                */
                $sgrade = !empty($stoken) ? openssl_decrypt($stoken, config('yydwx')['cipher'], config('yydwx')['key'], 0, config('yydwx')['iv']) : 0;
                $grade = !empty($sgrade) && in_array(intval($sgrade), [10, 20, 30, 40]) && $sgrade > $grade ? $sgrade : $grade;
                $sku_item['is_can_buy'] = $sku->grade_limit <= $grade ? true : false;
                break;
            }
        }

        //取sku的图片，当图片为空的时候，取spu的图片
        if (count($sku_item) > 0) {
            $img = GoodsSkuImages::where('sku_id', $sku_item['sku_id'])
                ->where('is_default', 1)
                ->value('image_url');
            $img = trim($img . '');
            if ($img == '') {
                // 如果SKU没有图片，启用SPU关联的图片
                $img = GoodsSpu::where('spu_id', $spuId)->value('main_image');
            }

            $sku_item['img'] = $this->getFullPictureUrl($img);
        }


        // D、获取与$ids 规格值无关的其它规格值集合【2017-01-19 经阅读代码，好似 $result 永远为空】
        // $ids 为规格值ID数组，这个数组可确定一个唯一的SKU 对象
        foreach ($ids as $id) {
            // 获取某一个规格值ID($id)关联的规格，在某一个SPU内此规格定义的所有已经使用的规格值ID集合【没有使用的，不在此列】
            $ids_array = $this->getSelfGradeById($id, $all_ids);
            foreach ($skus as $sku) {
                // $current_spec_ids(当前SKU的规格值ID列，数组)
                $current_spec_ids = array_keys(unserialize($sku->sku_spec));
                $array_spec_id = [];
                array_push($array_spec_id, $id);
                if (count(array_diff($array_spec_id, $current_spec_ids)) == 0) {
                    //说明当前sku包含：ids
                    foreach ($current_spec_ids as $value) {
                        array_push($ids_array, $value);
                    }
                }
            }

            // $ids_array 由两部分组成：
            //  1、与 $id（铁）同规格的所有规格值ID，例如“金属含量”为一个规格，那么“金”、“银”等都含在内
            //  2、含$id（铁）规格值ID的若干个有效SKU的所有规格值ID，例如：红（颜色）、水淬火（加工工艺）、铁（金属含量）
            $ids_array = array_values(array_unique($ids_array));
            //找出2个索引数组的不同
            foreach ($all_ids as $item) {
                if (!in_array($item, $ids_array)) {
                    array_push($result, $item);
                }
            }
        }

        // E、返回一个完整的SKU 或 SPU 对象
        if (count($sku_item) == 0) {
            return response()->json([
                'type' => 0,
                'spu' => $spu,
                'ids' => array_unique($result),
                'plat_vrb_caption' => $plat_vrb_caption
            ]);
        } else {
            // 返回某一个 SKU
            return response()->json([
                'type' => 1,
                'sku' => $sku_item,
                'ids' => array_unique($result),
                'plat_vrb_caption' => $plat_vrb_caption
            ]);
        }
    }

 /**
     * 返回某一个SPU下的有效的skus的所有规格值ID
     * 输入：skus（对象列）
     * 输出：所有sku的所有规格值IDs（索引数组）
     * @param $skus
     * @return  array
     */
    public function getAllSpec($skus) {
        /*
            SKU规格值(sku_spec)数组格式为：
            Array ([1529] => 纯白,[1523] => 1000*500*200 )
            其中：“1529”、“1523”等为规格值ID（sp_value_id）
                  “纯白”、“1000*500*200”等为规格值名称（sp_value_name）
        */
        $all_ids = [];
        foreach ($skus as $sku) {
            foreach (array_keys(unserialize($sku->sku_spec)) as $item) {
                array_push($all_ids, $item);
            }
        }

        $all_ids = array_values(array_unique($all_ids));
        return $all_ids;
    }
	public function getSelfGradeById($id, $all_ids) {
			$ids_array = [];

			// 1、根据规格值获取规格ID
			$sp_id = GoodsSpecValueDct::where('id', $id)->value('sp_id');

			// 2、根据规格ID获取所有的规格值
			$specs = GoodsSpecValueDct::where('sp_id', $sp_id)->get();
			foreach ($specs as $spec) {
				if (in_array($spec->id, $all_ids)) {
					array_push($ids_array, $spec->id);
				}
			}

			// SPU定义的同一个规格的值ID
			return $ids_array;
	}
    /**
     * 根据当前登录用户类型, 获取spu商品对应价格
     * @param int $member_grade 用户会员等级
     * @param GoodsSpu $obj_spu 商品spu
     * @return float 返回对应商品spu价格
     */
    function getSupperSpuPrice($member_grade = 10, $obj_spu)
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

    public function spuShopQuery($pageNumber = 1, $pageSize = 10, $gcId = 0)
    {
        $code = 0;
        $message = '';

        $classGoods = ElifeGoodsLabel::where('elife_goods_label_id',$gcId)->first(['goods_list']);
        if($classGoods){
            $goods = unserialize($classGoods->goods_list);
            $goods_list = array_chunk($goods,$pageSize);
            if($goods_list){
                if(count($goods_list) >= (int)$pageNumber){
                    $code = 200;
                    $data = $goods_list[$pageNumber-1];
                    $message = '正在加载中...';
                }else{
                    $code = 300;
                    $data = '';
                    $message = '没有更多了';
                }
            }else{
                $code = 100;
                $data = '';
                $message = '未配置商品';
            }

        }else{
            $code = 100;
            $data = '';
            $message = '未配置商品';
        }
        return Api::responseMessage($code, $data, $message);
    }

}