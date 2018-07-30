<?php
/**
 * e生活首页
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/27 0027
 * Time: 上午 9:44
 */

namespace App\controllers\elife;

use App\models\goods\GoodsSpu;
use App\models\goods\GoodsSpuImages;
use App\models\goods\GoodsSpecDefine;
use App\models\plat\PlatSetting;
use App\models\elife\ElifeColumn;
use App\models\elife\ElifeGoodsLabel;
use App\models\member\MemberCart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndexController extends BaseController
{
    /**
     * 2018年6月22日10:33:32
     * @param string $enter_id [（string）eLife_shop ,（int）3(主站类型)];[int:1,2(活动类型)]
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function eLifeIndex(Request $request) {
		$member = $this->loseEfficacy();
        //e生活用户都默认为普通用户
        $grade = 20;
		//判断窗口类型('eLifeShop':分行商城主站;通过member表数据类型判断入口类型)
        $eLifeShop = $request->input('eLifeShop');
        $getEntranceType = DB::table('member')->where('cust_id',$member->cust_id)->first();
        $enter_id = $getEntranceType->entrance_type;
        if(empty($enter_id)){
            $enter_id = 1;
        }
		switch($enter_id){
            case 1:
                $advertList = $this->getAdvertList("'A0501'", 1);
                break;
            case 2:
                $advertList = $this->getAdvertList("'A0601'", 1);
                break;
            case 3:
                $advertList = $this->getAdvertList("'A0701'", 1);
                break;
        }
            //查找e生活栏目下各个标签及标签下商品列表
            $db_prefix = $this->db_prefix;
            $sql_query = "select elife_column_id,elife_column_name,goods_list,image_url,sort,column_url
                      from " . $db_prefix . "elife_column_list
                      where is_usable = 1 and exit_type = ".$enter_id."
                      order by sort desc ";
            $query_result = DB::select($sql_query, []);
            //遍历各个标签，得到标签下各个spu信息
            $label_goods_list = array();
            foreach($query_result as $k => $label){
                $tem = array();
                $tem['elife_column_id'] = $label->elife_column_id;
                $tem['elife_column_name'] = $label->elife_column_name;
                $tem['sort'] = $label->sort;
                $tem['image_url'] = $this->getFullPictureUrl($label->image_url);
                $tem['column_url'] = $this->getFullPictureUrl($label->column_url);
                $tem['g_list'] = array();
                $goods_list = unserialize($label->goods_list);
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

                    //$g_info['spu_price'] = $goods->spu_plat_price;//20170911-根据会员等级取得相应价格信息
                    $g_info['spu_price'] = $this->getSpuPrice($grade, $goods);
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
        //统计购物车商品数量
        $goods_num_in_cart = $this->shop_cart();

		//1、分行活动页 2、总行A商城主站 3、总行B活动页
		$getEntranceTypeA = DB::table('article_info')->where('is_show',1)->where('enter_type',1)->first(['out_url']);
		$getEntranceTypeB = DB::table('article_info')->where('is_show',1)->where('enter_type',2)->first(['out_url']);
		switch ($enter_id) {
			case 1:
				if ($eLifeShop) {
					return view("elife.index_goods", compact('grade','advertList','label_goods_list','goods_num_in_cart','enter_id'));
				}
				return redirect("$getEntranceTypeA->out_url");
			break;
			case 2:
				return view("elife.kaolaindex.klindex_goods", compact('grade','advertList','label_goods_list','goods_num_in_cart','enter_id'));
			break;
			case 3:
				if ($eLifeShop) {
					return view("elife.kaolaindex.klindex_goods", compact('grade','advertList','label_goods_list','goods_num_in_cart','enter_id'));
				}
				return redirect("$getEntranceTypeB->out_url");
			break;
		}
		return view("elife.index_goods", compact('grade','advertList','label_goods_list','goods_num_in_cart'));
    }

    public function classGoods($column_id) {
        //e生活用户都默认为普通用户
        $grade = 20;
        $column = ElifeColumn::where('elife_column_id',$column_id)->where('is_usable',1)->first(['elife_column_name','image_url']);
        $column->image_url = $this->getFullPictureUrl($column->image_url);

        $label_goods_list = ElifeGoodsLabel::where('elife_column_id',$column_id)->where('is_usable',1)->orderBy('sort','desc')->get(['elife_goods_label_id','elife_goods_label_name','goods_list']);
        if(!empty($label_goods_list[0])){
            if(isset($label_goods_list[0]->goods_list)){
                $goods_list = unserialize($label_goods_list[0]->goods_list);
                $goods_list = array_chunk($goods_list,10);
                if(!empty($goods_list)){
                    $label_goods_list[0]->goods_list = $goods_list[0];
                }else{
                    $label_goods_list[0]->goods_list = '';
                }
            }
        }
        //统计购物车商品数量
        $goods_num_in_cart = $this->shop_cart();

        return view("elife.class_goods",compact('grade','advertList','label_goods_list','column','goods_num_in_cart'));
    }

    /**
     * 购物车数量
     * return number
     */
    private function shop_cart()
    {
		$member = $this->loseEfficacy();
		//判断窗口类型
		$getEntranceType = DB::table('member')->where('cust_id',$member->cust_id)->first();
        //统计购物车中商品的总数量，显示出目前购物车中商品中的数量
        $goods_num_in_cart = MemberCart::where('member_id', $member->member_id)->where('entrance_type',$getEntranceType->entrance_type)->sum('number');
        return $goods_num_in_cart;
    }
}