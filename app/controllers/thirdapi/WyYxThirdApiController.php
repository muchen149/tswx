<?php

namespace App\controllers\thirdapi;

use App\controllers\SupplierOrderController;
use App\facades\Api;
use App\Http\Controllers\Controller;
use App\lib\WyYxThirdApi;
use App\models\dct\DctArea;
use App\models\dct\DctExpress;
use App\models\goods\GoodsSku;
use App\models\order\Order;
use App\models\store\StoreWayBill;
use App\models\supplier\OrderSupplier;
use App\models\supplier\StoreDeliverGoods;
use App\models\supplier\StoreGoodsSku;
use App\models\wyyx\WyyxCountChange;
use App\models\wyyx\WyyxCountCheck;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\models\goods\GoodsSpecDefine;
use App\models\goods\GoodsSpecValueDct;
use Illuminate\Support\Facades\Event;
use App\Events\StoreDeliverEvent;


class WyYxThirdApiController extends Controller
{
    /**
     * 订单取消回调
     * 传入参数：$order
     */
    public function createOrder($order)
    {
        //获得收货地址信息
        $receiverProvinceName = DctArea::find($order['sendee_province_id'])->name;            // 省级名称
        $receiverCityName = DctArea::find($order['sendee_area_id'])->name;                    // 地区名称
        $receiverDistrictName = DctArea::find($order['sendee_city_id'])->name;

        //获得收货人信息
        $receiver = preg_split('/ /', $order['sendee_address_info'], -1, PREG_SPLIT_NO_EMPTY);
        $receiverName = explode(':', $receiver[0])[1];
        $receiverPhone = explode(':', $receiver[1])[1];
        $receiverAddressDetail = $receiver[3];

        //订单商品信息
        $orderSkus = [];
        foreach ($order['orderSkus'] as $orderSku) {
            $sku = [
                'skuId' => $orderSku->from_plat_skuid,
                'productName' => $orderSku->sku_name,
                'saleCount' => $orderSku->number,
                'originPrice' => $orderSku->goods_price,
                'subtotalAmount' => bcmul($orderSku->goods_price, $orderSku->number, 2),
            ];
            $orderSkus[] = $sku;
        }
        //dd($orderSkus);

        //订单信息
        $third_order = [
            'orderId' => $order['plat_order_sn'],
            'submitTime' => date('Y-m-d H:i:s', $order['create_time']),
            'payTime' => date('Y-m-d H:i:s', $order['create_time']),
            'receiverName' => $receiverName,
            'receiverPhone' => $receiverPhone,
            'receiverProvinceName' => $receiverProvinceName,
            'receiverCityName' => $receiverCityName,
            'receiverDistrictName' => $receiverDistrictName,
            'receiverAddressDetail' => $receiverAddressDetail,
            'realPrice' => $order['order_amount_totals'],//pay_rmb_amount 修改为 order_amount_totals解决派单失败问题20180125
            'expFee' => $order['transport_cost_totals'],
            'payMethod' => '微信支付SDK',
            'orderSkus' => $orderSkus,
        ];
        return WyYxThirdApi::createOrder($third_order);
    }

    /**
     * 订单确认收货
     * 传入参数：$orderId $packageId $confirmTime
     */
    public function confirmOrder($orderId, $packageId, $confirmTime)
    {
        return WyYxThirdApi::confirmOrder($orderId, $packageId, $confirmTime);
    }

    /**
     * 更新网易严选商品SKU信息（主图、商品详情）
     */
    public function updateThirdGoodsSku()
    {
        $db_prefix = config('database')['connections']['mysql']['prefix'];
        $rskuIds = WyYxThirdApi::skuIds();
        //dd($rskuIds);
        if ($rskuIds['code'] == 200) {
            $nump = DB::table("wyyx_iteminfo")->truncate();//删除临时表数据
            $numk = DB::table("wyyx_skuinfo")->truncate();//删除临时表数据
            $numi = DB::table("wyyx_spuimgs")->truncate();//删除临时表数据

            $skuIdsT=array_chunk($rskuIds['result'],30);
            foreach($skuIdsT as $key=>$idsV) {
                $skuIds = join(',', $idsV);
                $rskuItems = WyYxThirdApi::skuItems($skuIds);
                if ($rskuItems['code'] == 200) {
                    $insert_arr = [];
                    $insert_sku = [];
                    $insert_imgs = [];
                    $spuarr = [];
                    $skuarr = [];
                    $spuspec = [];
                    $spuspecName = [];

                    foreach ($rskuItems['result'] as $item) {
                        unset($spuarr);
                        unset($insert_arr);
                        unset($insert_sku);
                        unset($insert_imgs);
                        unset($spuspecName);
                        unset($spuspec);

                        $categoryPathList = serialize($item['categoryPathList']);
                        $attrList = serialize($item['attrList']);
                        //$time = Carbon::now()->format('Y-m-d H:i:s');
                        $spuimgarr = [
                            'spu_id' => $item['id'],
                            'image_url' => $item['primaryPicUrl'],
                            'sort' => 0,
                            'is_default' => 1,
                        ];

                        $spuimgarr1 = [
                            'spu_id' => $item['id'],
                            'image_url' => $item['itemDetail']['picUrl1'],
                            'sort' => 1,
                            'is_default' => 0,
                        ];
                        $spuimgarr2 = [
                            'spu_id' => $item['id'],
                            'image_url' => $item['itemDetail']['picUrl2'],
                            'sort' => 2,
                            'is_default' => 0,
                        ];
                        $spuimgarr3 = [
                            'spu_id' => $item['id'],
                            'image_url' => $item['itemDetail']['picUrl3'],
                            'sort' => 3,
                            'is_default' => 0,
                        ];
                        $spuimgarr4 = [
                            'spu_id' => $item['id'],
                            'image_url' => $item['itemDetail']['picUrl4'],
                            'sort' => 4,
                            'is_default' => 0,
                        ];

                        $i = 0;
                        unset($spuspecx);
                        unset($spuspecName);
                        $spuspec=array();
                        foreach ($item['skuList'] as $sku) {
                            $i++;
                            $itemSkuSpecValueList = serialize($sku['itemSkuSpecValueList']);
                            $specValueList = $sku['itemSkuSpecValueList'];
                            $skuspec = $this->handleSpecInfo($specValueList);
                            $skuspect = sizeof($skuspec['specvalue']) == 1 ? $skuspec['specvalue'][0] : $skuspec['specvalue'];
                            $spuspecnt = sizeof($skuspec['specname']) == 1 ? $skuspec['specname'][0] : $skuspec['specname'];
                            //$spuspec[]=$skuspect;
                            if ($skuspec['isComp'] == 0) {
                                foreach ($skuspect as $key => $value) {
                                    $spuspecx[$key] = $value;
                                }
                                $spukey = '';
                                foreach ($spuspecnt as $key => $value) {
                                    $spuspecName[$key] = $value;
                                    $spukey = $key;
                                }
                                $spuspec = [$spukey => $spuspecx];
                            } else {
                                $skuspec4sku = array();
                                foreach ($skuspect as $key => $value) {
                                    //$spuspec[$key] = $value;
                                    if(!array_key_exists($key,$spuspec)){
                                        $spuspec[$key] = $value;
                                    }else{
                                        foreach ($value as $kk => $vv) {
                                            $spuspec[$key][$kk] = $vv;
                                        }
                                    }
                                    foreach ($value as $kk => $vv) {
                                        $skuspec4sku[$kk] = $vv;
                                    }
                                }
                                unset($skuspect);
                                $skuspect = $skuspec4sku;
                                foreach ($spuspecnt as $spn) {
                                    foreach ($spn as $key => $value) {
                                        $spuspecName[$key] = $value;
                                    }
                                }
                            }

                            $skuarr = [
                                'sku_id' => $sku['id'],
                                'name' => $item['name'],
                                'displayString' => $sku['displayString'],
                                'picUrl' => $sku['picUrl'],
                                'spu_id' => $item['id'],
                                'itemSkuSpecValueList' => $itemSkuSpecValueList,
                                //'attrList'=> $attrList,
                                'yanxuanPrice' => $sku['yanxuanPrice'],
                                'channelPrice' => $sku['channelPrice'],
                                'spec_value' => serialize($skuspect)
                            ];
                            $insert_sku[] = $skuarr;
                        }

                        //如果sku为一个默认sku的规格值为空，原系统逻辑--170713 去掉不用，改为保存单个规格值--start
                        /*if($i==1){
                            $insert_sku[0]['spec_value']='';
                            $spuspecName=[];
                            $spuspec=[];
                        }else{
                            //$spuspecName=[$spuspecName];
                            $t_spec=$spuspec;
                            unset($spuspec);
                            $spuspec=[1=>$t_spec];

                        }*/

                        /*//如果sku为一个默认sku的规格值为空，原系统逻辑--170713 去掉不用，改为保存单个规格值
                        //$spuspecName=[$spuspecName];
                        $t_spec=$spuspec;
                        unset($spuspec);
                        $spuspec=[1=>$t_spec];*/
                        //如果sku为一个默认sku的规格值为空，原系统逻辑--170713 去掉不用，改为保存单个规格值--end


                        $spuarr = [
                            'itemid' => $item['id'],
                            'name' => $item['name'],
                            'simpleDesc' => $item['simpleDesc'],
                            'listPicUrl' => $item['listPicUrl'],
                            'primarySkuId' => $item['primarySkuId'],
                            'primaryPicUrl' => $item['primaryPicUrl'],
                            'categoryPathList' => $categoryPathList,
                            'attrList' => $attrList,
                            'detailHtml' => $item['itemDetail']['detailHtml'],
                            'yanxuanPrice' => $item['skuList'][0]['yanxuanPrice'],
                            'channelPrice' => $item['skuList'][0]['channelPrice'],
                            'spec_value' => serialize($spuspec),
                            'spec_name' => serialize($spuspecName),
                        ];

                        $insert_arr[] = $spuarr;
                        $insert_imgs[] = $spuimgarr;
                        $insert_imgs[] = $spuimgarr1;
                        $insert_imgs[] = $spuimgarr2;
                        $insert_imgs[] = $spuimgarr3;
                        $insert_imgs[] = $spuimgarr4;


                        DB::table('wyyx_iteminfo')->insert($insert_arr);
                        DB::table('wyyx_skuinfo')->insert($insert_sku);
                        DB::table('wyyx_spuimgs')->insert($insert_imgs);
                    }
                }
            }
            $this->updateStorageNums();
        }
        dd($rskuIds);
    }

    /**
     * 处理规格信息
     */
    public function handleSpecInfo($speclist){
        //dd($speclist);
        $r_data[]=[];
        $n_data[]=[];
        $rt_data[]=[];
        unset($r_data);
        unset($n_data);
        unset($rt_data);
        $i=0;
        foreach ($speclist as $spec) {
            $i++;
            $def=null;
            $dict=null;
            $goodsSpecDefine = GoodsSpecDefine::where('name',$spec['skuSpec']['name'])->where('is_use','1')->first();
            if (!$goodsSpecDefine) {//不存在新增规格和规格值
                $res = GoodsSpecDefine::create([
                    'caption' => $spec['skuSpec']['name'],  //后期启动：简称和全名  name为简称  caption为全名
                    'name' => $spec['skuSpec']['name'],     // 默认规格名称个简称都是一样的
                   // 'py_code' => ApiWrapperFacade::getPinyinCode($resp['name']),
                    'description'=>'',

                ]);
                $def=$res;
                $data = [                                     //参数没有去判断，，catch统一处理
                    'name' => $spec['skuSpecValue']['value'],
                    'sp_id' => $res->id,
                    'sp_color' =>'',
                    'sort' => '999',
                ];
                $spec_dct = GoodsSpecValueDct::create($data);
                $dict=$spec_dct;
            }else{
               $def=$goodsSpecDefine;
                $id=$goodsSpecDefine->id;
                $resd = GoodsSpecValueDct::where("name", $spec['skuSpecValue']['value'])->where('sp_id', $id)->first();//存在规格值
                if ($resd) {//存在
                    if ($resd->is_use == 0) {//如果曾经被禁用，则修改为启用
                        $resd->update(['is_use' => 1, 'sort' => '999']);
                    }
                }else{
                    $data = [                                     //参数没有去判断，，catch统一处理
                        'name' => $spec['skuSpecValue']['value'],
                        'sp_id' => $id,
                        'sp_color' =>'',
                        'sort' => '999',
                    ];
                    $resd = GoodsSpecValueDct::create($data);
                }
                $dict=$resd;
            }
            Log::notice('def----'.$def);
            Log::notice('dict-----'.$dict);
            $r_data[]= [$dict->id =>$dict->name];
            $rt_data[$def->id]= [$dict->id =>$dict->name];
            $n_data[]= [$def->id=>$def->name];
        }
        /*if(sizeof($r_data)==1){
            return $r_data[0];
        }else{
            return $r_data;
        }*/
        //$specname=[$def->id=>$def->name];



        if($i>1){
            $t_data=[
                'specname'=>$n_data,
                'specvalue'=>$rt_data,
                'isComp'=>1
            ];
        }else{
            $t_data=[
                'specname'=>$n_data,
                'specvalue'=>$r_data,
                'isComp'=>0
            ];
        }
        return $t_data;


    }

    /**
     * 更新网易严选库存信息到sku信息表
     */
    public function updateStorageNums()
    {
        $db_prefix = config('database')['connections']['mysql']['prefix'];
        $skusel="SELECT  s.sku_id  FROM " .  $db_prefix ."wyyx_skuinfo s";
        $updateStoreNum="UPDATE " .  $db_prefix ."wyyx_skuinfo s SET storage_num=? where sku_id =? ";
        $skuids=DB::select($skusel);
        $a_skuids=[];
        foreach($skuids as $k){
            array_push($a_skuids,$k->sku_id);
        }
        $skuIds =join(',', $a_skuids);
        $orderid='['.$skuIds.']';
        $result=$this->stockInfo($orderid);
        if ($result['code'] == 200) {
            $store_info=json_decode($result['result']);
            foreach($store_info as $store){
                DB::update($updateStoreNum,array($store->inventory,$store->skuId));
                //print($store->skuId . $store->inventory);
            }
            //dd($store_info);

        }
    }

    /**
     * 订单查询
     * 传入参数：$skuIds
     */
    public function stockInfo($skuIds)
    {
        return WyYxThirdApi::stockInfo($skuIds);
    }

    /**
     * 更新网易严选商品SKU信息（主图、商品详情）
     */
    public function updateThirdGoodsSku_old()
    {
        $db_prefix = config('database')['connections']['mysql']['prefix'];
        $rskuIds = WyYxThirdApi::skuIds();
        //dd($rskuIds);
        if ($rskuIds['code'] == 200) {
            $skuIds = join(',', $rskuIds['result']);
            $rskuItems = WyYxThirdApi::skuItems($skuIds);
            if ($rskuItems['code'] == 200) {
                foreach ($rskuItems['result'] as $item) {
                    $spuarr = $skuarr = [];



                    //$this->fillGoodsInfo($item, $spuarr, $skuarr);

                    $time = Carbon::now()->format('Y-m-d H:i:s');
                    $spuarr = [
                        'spu_name' => $item['name'],
                        'gc_id' => 0,
                        'gc_name' => '',
                        'spu_plat_price' => $item['skuList'][0]['yanxuanPrice'],
                        'spu_market_price' => $item['skuList'][0]['yanxuanPrice'],
                        'spu_groupbuy_price' => $item['skuList'][0]['yanxuanPrice'],
                        'spu_trade_price' => $item['skuList'][0]['yanxuanPrice'],
                        'spu_partner_price' => $item['skuList'][0]['yanxuanPrice'],
                        'spu_points_limit' => -1,
                        'main_image' => $item['primaryPicUrl'],
                        'mobile_content' => $item['itemDetail']['detailHtml'],
                        'from_plat_code' => '2002',
                        'from_plat_name' => '网易严选',
                        'from_plat_skuid' => $item['primarySkuId'],
                        'created_at' => $time,
                        'updated_at' => $time,
                    ];

                    $spuarr = [
                        'spu_id' => $item['id'],
                        'image_url' => $item['primaryPicUrl'],
                        'sort' => 0,
                        'is_default' => 1,
                    ];

                    $spuarr1 = [
                        'spu_id' => $item['id'],
                        'image_url' =>$item['itemDetail']['picUrl1'],
                        'sort' => 1,
                        'is_default' => 0,
                    ];
                    $spuarr2 = [
                        'spu_id' => $item['id'],
                        'image_url' => $item['itemDetail']['picUrl2'],
                        'sort' => 2,
                        'is_default' => 0,
                    ];
                    $spuarr3 = [
                        'spu_id' => $item['id'],
                        'image_url' => $item['itemDetail']['picUrl3'],
                        'sort' => 3,
                        'is_default' => 0,
                    ];
                    $spuarr4 = [
                        'spu_id' => $item['id'],
                        'image_url' => $item['itemDetail']['picUrl4'],
                        'sort' => 4,
                        'is_default' => 0,
                    ];

                    //$imgList=$item['itemDetail']['picUrl1'];

                    //$insert_arr[]= $spuarr4



                }

                /* DB::table('goods_spu')->insert($insert_arr);
                 DB::table('goods_sku')->insert($insert_skuarr);

                 $sql = "UPDATE " . $db_prefix . "goods_sku AS gsk
                         INNER JOIN " . $db_prefix . "goods_spu AS gsp ON gsp.spu_name=gsk.sku_name
                         set gsk.spu_id=gsp.spu_id WHERE gsp.from_plat_code='2002'";*/

                $nump=DB::table("goods_spu_tmp")->truncate();//删除临时表数据
                $numk=DB::table("goods_sku_tmp")->truncate();//删除临时表数据

                DB::table('goods_spu_tmp')->insert($insert_arr);
                DB::table('goods_sku_tmp')->insert($insert_skuarr);

                $sql = "UPDATE " . $db_prefix . "goods_sku_tmp AS gsk
                        INNER JOIN " . $db_prefix . "goods_spu_tmp AS gsp ON gsp.spu_name=gsk.sku_name
                        set gsk.spu_id=gsp.spu_id WHERE gsp.from_plat_code='2002'";
                DB::update($sql);

                $updatespu= "UPDATE " . $db_prefix . "goods_spu t INNER JOIN yyd_goods_spu_tmp s ON t.from_plat_skuid=s.from_plat_skuid
                        SET
                        t.spu_name=s.spu_name,
                        t.gc_id=s.gc_id,
                        t.gc_name=s.gc_name,
                        t.spu_plat_price=s.spu_plat_price,
                        t.spu_market_price=s.spu_market_price,
                        t.spu_groupbuy_price=s.spu_groupbuy_price,
                        t.spu_trade_price=s.spu_trade_price,
                        t.spu_partner_price=s.spu_partner_price,
                        t.spu_points_limit=s.spu_points_limit,
                        t.main_image=s.main_image,
                        t.mobile_content=s.mobile_content,
                        t.updated_at=s.created_at
                        where t.from_plat_code='2002' and t.from_plat_skuid=s.from_plat_skuid";

                $updatesku= "UPDATE " . $db_prefix ."goods_sku t INNER JOIN yyd_goods_sku_tmp s ON t.from_plat_skuid=s.from_plat_skuid
                        SET
                        t.sku_name=s.sku_name,
                        t.color_id=s.color_id,
                        t.price=s.price,
                        t.market_price=s.market_price,
                        t.groupbuy_price=s.groupbuy_price,
                        t.trade_price=s.trade_price,
                        t.partner_price=s.partner_price,
                        t.main_image=s.main_image,
                        t.points_limit=s.points_limit,
                        t.updated_at=s.updated_at
                        where t.from_plat_code='2002' and t.from_plat_skuid=s.from_plat_skuid";

                $insertspu="INSERT INTO " . $db_prefix ."goods_spu (spu_name,
                            gc_id,
                            gc_name,
                            spu_plat_price,
                            spu_market_price,
                            spu_groupbuy_price,
                            spu_trade_price,
                            spu_partner_price,
                            spu_points_limit,
                            main_image,
                            mobile_content,
                            from_plat_code,
                            from_plat_name,
                            from_plat_skuid,
                            created_at,
                            updated_at)
                        select
                            spu_name,
                            gc_id,
                            gc_name,
                            spu_plat_price,
                            spu_market_price,
                            spu_groupbuy_price,
                            spu_trade_price,
                            spu_partner_price,
                            spu_points_limit,
                            main_image,
                            mobile_content,
                            from_plat_code,
                            from_plat_name,
                            from_plat_skuid,
                            created_at,
                            updated_at
                        from " . $db_prefix ."goods_spu_tmp t where t.from_plat_skuid not in(select a.from_plat_skuid from " . $db_prefix ."goods_spu a)";


                $insertsku="INSERT INTO " . $db_prefix ."goods_sku
                            (sku_name,
                            spu_id,
                            color_id,
                            price,
                            market_price,
                            groupbuy_price,
                            trade_price,
                            partner_price,
                            main_image,
                            points_limit,
                            from_plat_code,
                            from_plat_name,
                            from_plat_skuid,
                            created_at,
                            updated_at)
                        select
                            sku_name,
                            spu_id,
                            color_id,
                            price,
                            market_price,
                            groupbuy_price,
                            trade_price,
                            partner_price,
                            main_image,
                            points_limit,
                            from_plat_code,
                            from_plat_name,
                            from_plat_skuid,
                            created_at,
                            updated_at
                        from " . $db_prefix ."goods_sku_tmp t where t.from_plat_skuid not in(select a.from_plat_skuid from " . $db_prefix ."goods_sku a)";

                DB::update($updatespu);
                DB::update($updatesku);
                DB::insert($insertspu);
                DB::insert($insertsku);

            }
        }
        dd($rskuIds);
    }

    /**
     * 更新网易严选商品图片表
     */
    public function updateThirdGoodsImage()
    {
        $spusql='INSERT INTO `yyd_goods_spu_images` (`spu_id`,`image_url`,`sort`,`is_default`) VALUES (?,?,?,?)'.
            'ON DUPLICATE KEY UPDATE `spu_id` = values(`spu_id`), `image_url` = values(`image_url`), `sort` = values(`sort`), `is_default` = values(`is_default`);';

        $skusql='INSERT INTO `yyd_goods_sku_images` (`sku_id`,`color_id`,`image_url`,`sort`,`is_default`) VALUES(?,?,?,?,?)'.
            'ON DUPLICATE KEY UPDATE `sku_id` = values(`sku_id`), `color_id` = values(`color_id`), `image_url` = values(`image_url`), `sort` = values(`sort`), `is_default` = values(`is_default`);';

        $rskuIds = WyYxThirdApi::skuIds();
        if ($rskuIds['code'] == 200) {
            $skuIds = join(',', $rskuIds['result']);
            $rskuItems = WyYxThirdApi::skuItems($skuIds);
            if ($rskuItems['code'] == 200) {
                foreach ($rskuItems['result'] as $item) {
                    $sku = GoodsSku::select('sku_id', 'spu_id')->where('from_plat_code', '2002')->where('from_plat_skuid', $item['primarySkuId'])->first();
                    $item['sku_id'] = $sku->sku_id;
                    $item['spu_id'] = $sku->spu_id;
                    $spuarr = $skuarr = [];
                    $this->fillGoodsImagesInfo($item, $spuarr, $skuarr);
                    //$insert_arr[] = $spuarr;
                    //$insert_skuarr[] = $skuarr;
                    DB::insert($spusql,[$spuarr['spu_id'],$spuarr['image_url'],$spuarr['sort'],$spuarr['is_default']]);
                    DB::insert($skusql,[$skuarr['sku_id'],$skuarr['color_id'],$skuarr['image_url'],$skuarr['sort'],$skuarr['is_default']]);

                }

                //DB::table('goods_spu_images')->insert($insert_arr);
                //DB::table('goods_sku_images')->insert($insert_skuarr);




                //dd($insert_arr);
                //DB::insert($spusql,[$insert_arr['spu_id'],$insert_arr['image_url'],$insert_arr['sort'],$insert_arr['is_default']]);
                //DB::insert($skusql,[$insert_skuarr['sku_id'],$insert_skuarr['color_id'],$insert_skuarr['image_url'],$insert_skuarr['sort'],$insert_skuarr['is_default']]);


            }
        }
        dd($rskuIds);
    }

    private function fillGoodsImagesInfo($item, &$spuarr, &$skuarr)
    {
        $spuarr = [
            'spu_id' => $item['spu_id'],
            'image_url' => $item['primaryPicUrl'],
            'sort' => 0,
            'is_default' => 1,
        ];

        $skuarr = [
            'sku_id' => $item['sku_id'],
            'color_id' => 0,
            'image_url' => $item['primaryPicUrl'],
            'sort' => 0,
            'is_default' => 1,
        ];
    }

    /**
     * 网易严选统一回调
     */
    public function wyyxCallback(Request $request)
    {
        $pam = $request->all();
        if (!array_key_exists("method", $pam) ) {
            return $this->resp(5000, '参数错误');//Api::responseMessage(5000, '参数错误');
        }
        //$data=$request->all();
        //$keyData=$this->array_remove($data,'sign');
        $method = $pam['method'];
        //dd($method);
        if($method=='yanxuan.callback.order.cancel'){//订单取消回调
            return $this->cancelOrderCallback($request);
        }else if($method=='yanxuan.notification.order.delivered'){//订单包裹物流绑单回调
            return $this->bindOrderExpressCallback($request);
        }else if($method=='yanxuan.notification.inventory.count.changed'){
            //渠道SKU库存划拨回调
            /*
             * SKU库存划拨回调(yanxuan.notification.inventory.count.changed)
             * 业务说明
             * 库存发生变化时（划拨、回拨），严选通过该回调告诉渠道，渠道应根据回调更新库存。
             * 默认通知渠道
             */
            return $this->countChangeCallback($request);
        }
        else if($method=='yanxuan.notification.inventory.count.check'){
            /*
             *SKU库存校准回调(yanxuan.notification.inventory.count.check)
             * 业务说明
             * 渠道需关心此回调
             * 严选将最新库存值告诉渠道，渠道应修改库存为通知中给的库存
             * 每隔15分钟，触发一次渠道SKU库存校准回调
             * 默认不通知渠道，需自助注册此回调
             */
            return $this->checkCallback($request);
        }
        else if($method=='yanxuan.notification.sku.alarm.close'){
            /*
             *渠道SKU低库存预警通知(yanxuan.notification.sku.alarm.close)
             * 业务说明
             * 渠道需关心此回调
             * sku因为库存偏低触发的通知
             * 建议渠道将此信息反馈给渠道内部的运营人士
             * 默认不通知渠道，需自助注册此回调
             */
            return $this->closeCallback($request);
        }
        else if($method=='yanxuan.notification.sku.reopen'){
            /*
             *渠道SKU再次开售通知(yanxuan.notification.sku.reopen)
             * 业务说明
             * 渠道需关心此回调
             * sku因为库存原因再次开售触发的通知，通知会告知当前库存inventory
             * 建议渠道对接此通知，并在收到此通知后上架sku
             * 默认不通知渠道，需自助注册此回调
             */
            return $this->reopenCallback($request);
        }else{
            //method 不存在
            return $this->resp(5000, '方法不存在');//Api::responseMessage(5000, '方法不存在');
        }



        //$db_prefix = config('database')['connections']['mysql']['prefix'];
        //$rskuIds = WyYxThirdApi::skuIds();
        //dd($rskuIds);

        /* $db_prefix = config('database')['connections']['mysql']['prefix'];
         //下单
         $order = Order::find(719);
         $sku_sql = "SELECT gsk.from_plat_skuid,og.number,og.sku_name,og.goods_price FROM " . $db_prefix . "order_goods AS og
                     INNER JOIN " . $db_prefix . "goods_sku AS gsk ON gsk.sku_id=og.sku_id
                     WHERE og.plat_order_id=" . $order->plat_order_id;
         $orderSkus = DB::select($sku_sql);
         $order->orderSkus = $orderSkus;
         $result = $this->createOrder($order);
         dd($result);*/

        /*//取消订单
        $this->cancelOrder($order->plat_order_sn);

        //查询订单
        $this->getOrder($order->plat_order_sn);*/

        /*//库存
        $this->stockInfo($order->plat_order_sn);*/
    }

    /**
     * 目前没有牵扯到加密 如果有加密的后续再据需添加
     * @param string $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function resp($code = "", $msg = "")
    {
        return response()->json(
            [
                "code" => $code,
                "msg" => $msg
            ]
        );
    }

    /**
     * 订单取消回调
     * 传入参数：$request
     */
    public function cancelOrderCallback(Request $request)
    {
        $data = $request->all();
        Log::notice('-------cancelOrderCallback---------');
        Log::alert($data);
        $orderCancelResult = \GuzzleHttp\json_decode($data['orderCancelResult'],true);
        //$orderCancelResult = \GuzzleHttp\json_decode($data['orderCancelResult']);

        if ($orderCancelResult && is_array($orderCancelResult)) {
            $order = OrderSupplier::where('plat_order_sn', $orderCancelResult['orderId'])->first();
            $service_order = DB::table('service_order')->where('plat_order_id',$order->plat_order_id)->first();
            if($order){
                switch ($orderCancelResult['cancelStatus']) {
                    case 1://允许取消
                        //撤单逻辑
                        /*...*/
                        log::alert('---撤单---------');
                        Log::notice('---撤单---------');
                        $order->supplier_order_state = -1;
                        $order->save();
                        $service_order->supplier_state = 1;
                        $service_order->save();
                        break;
                    case 0:
                        $service_order->supplier_state = 0;
                        $service_order->supplier_reason = $orderCancelResult['rejectReason'];
                        $service_order->save();
                        //不允许取消 rejectReason 原因描述

                        break;
                    case 2://待审核
                        $service_order->supplier_state = 2;
                        $service_order->save();
                        break;

                }

            }
        } else {
            return $this->resp(5000, '参数错误');//Api::responseMessage(5000, '参数错误');
        }
        return $this->resp(200, '');//Api::responseMessage(0);
    }

    /**
     * 订单包裹物流绑单回调
     * 传入参数：$request
     */
    public function bindOrderExpressCallback(Request $request)
    {
        $data = $request->all();
        Log::notice('------订单包裹物流绑单回调-------');
        Log::notice('-------bindOrderExpressCallback---------');
        Log::alert($data);
        $orderPackage = \GuzzleHttp\json_decode($data['orderPackage'],true);
        //$orderPackage = \GuzzleHttp\json_decode($data['orderPackage']);
        if ($orderPackage && is_array($orderPackage)) {
            $expressArray=[];
        //if ($orderPackage) {
            extract($orderPackage);
            $expCreateTime=round($expCreateTime/1000);
            $order = OrderSupplier::where('plat_order_sn', $orderId)->where('supplier_id',2)->first();
            /*$expressCompany = $expressDetailInfos[0]['expressCompany'];
            $expressNo = $expressDetailInfos[0]['expressNo'];*/

            foreach($expressDetailInfos as $expressInfo){//根据严选skuid获取平台skuid并保存快递信息数组供后面调用
                $skus = $expressInfo['skus'];
                foreach($skus as $sku){
                    $skuid=$sku['skuId'];
                    $gsku=GoodsSku::where('from_plat_skuid',$skuid)->first();
                    if($gsku){
                        $expressArray[$gsku->sku_id]=$expressInfo;
                    }
                }
            }

            /*$order = OrderSupplier::where('plat_order_sn', $orderPackage->orderId)->first();
            $expressCompany = $expressDetailInfos['expressCompany'];
            $expressNo = $expressDetailInfos['expressNo'];*/

            if($order){//订单存在
                //更新仓点订单物流信息
                $storeDeliverGoods = StoreDeliverGoods::where('supplier_order_id', $order->supplier_order_id)->get();
                $is_send=false;
                foreach ($storeDeliverGoods as $item) {
                    if(array_key_exists("$item->sku_id",$expressArray)){
                        $item->deliver_state = 3;
                        $item->transport_time = $expCreateTime;
                        $item->packageId=$packageId;
                        if($item->waybill_id==0){
                            $waybill = array();
                            $waybill['store_id'] = 2;
                            $waybill['transport_cost'] = 0.00;
                            // 0:第三方快递物流公司;1:买家自提; 2:捎货;
                            $waybill['transport_type'] = 0;
                            // 运单状态（1:待转运;2:在途中;3:（到达）待签收;4:已完成;-1:作废）
                            $waybill['waybill_state'] = 1;
                            $waybill['create_time'] = time();
                            $waybill['update_time'] = time();
                            $obj_waybill = StoreWaybill::create($waybill);
                            $item->waybill_id=$obj_waybill->id;
                        }
                        $item->save();
                        //修改SKU库存
                        $storeGoods = StoreGoodsSku::where('store_id', $item->store_id)->where('sku_id', $item->sku_id)->first();
                        $storeGoods->storage_num -= $item->number;
                        $storeGoods->save();

                        //根据skuid获取快递信息
                        $expressCompany = $expressArray["$item->sku_id"]['expressCompany'];
                        $expressNo = $expressArray["$item->sku_id"]['expressNo'];

                        $storeWayBill = StoreWayBill::find($item->waybill_id);
                        $expressCode = DctExpress::where('e_name', 'like', '%' . $expressCompany . '%')->first()->e_code;
                        $storeWayBill->express_name = $expressCompany;
                        $storeWayBill->express_code = $expressCode;
                        $storeWayBill->waybill_code = $expressNo;
                        $storeWayBill->save();
                        $is_send=true;
                    }
                }
                if($is_send){//如果处理了发货信息则更新状态，否则不更新
                    //更新供货商订单状态
                    $order->supplier_order_state = 3;
                    $order->transport_time = $expCreateTime;
                    $order->save();
                    $plat_order = Order::where('plat_order_sn', $orderId)->first();
                    if($plat_order->plat_order_state!=3){//如果平台订单不是已发货状态，将平台订单设置为已发货
                        $plat_order->plat_order_state=3;
                        $plat_order->save();
                    }
                }
            }
        } else {
            return $this->resp(5000, '参数错误');//Api::responseMessage(5000, '参数错误');
        }
        return $this->resp(200, '');//Api::responseMessage(0);

    }

    /**
     * 渠道SKU库存划拨回调
     * 传入参数 $request
     */
    public function countChangeCallback(Request $request)
    {
        $data = $request->all();
        Log::notice('------渠道SKU库存划拨回调-------');
        Log::notice('-------countChangeCallback---------');
        Log::alert($data);
        $transfers=\GuzzleHttp\json_decode($data['skuTransfer'],true);
        // extract($transfers);
        $transfers_id=$transfers['id'];
        $operateTime=$transfers['operateTime'];
        //$transfers['operateTime'];
        //dd($transfers);
        //$insert_attr=[];
        $countChage=[];
        $time = Carbon::now()->format('Y-m-d H:i:s');

        foreach ($transfers['skuTransfers'] as $skuTransfer) {
            $countChage=[
                'skuId'=>$skuTransfer['skuId'],
                'transferCount'=>$skuTransfer['transferCount'],
                'update_at'=>$time,
                ];
            WyyxCountChange::create($countChage);
            //$insert_attr[]=$countChage;

        }
        //DB::table('wyyx_countchange')->insert($insert_attr);
        return $this->resp(200,'');


    }

    /**
     * -SKU库存校准回调
     * 传入参数 $request
     */
    public function checkCallback(Request $request)
    {
        $data = $request->all();
        Log::notice('------SKU库存校准回调(yanxuan.notification.inventory.count.check)回调-------');
        Log::notice('-------checkCallback---------');
        Log::alert($data);
        $skuChecks=\GuzzleHttp\json_decode($data['skuCheck'],true);
        // extract($transfers);
        $transfers_id=$skuChecks['id'];
        $operateTime=$skuChecks['operateTime'];
        //$transfers['operateTime'];
        //dd($transfers);
        //$insert_attr=[];
        $countCheck=[];
        $time = Carbon::now()->format('Y-m-d H:i:s');

        foreach ($skuChecks['skuChecks'] as $skuCheck) {
            $countCheck=[
                'skuId'=>$skuCheck['skuId'],
                'count'=>$skuCheck['count'],
                'update_at'=>$time,
            ];
            //$insert_attr[]=$countCheck;
            WyyxCountCheck::create($countCheck);
        }
        //DB::table('wyyx_countcheck')->insert($insert_attr);
        return $this->resp(200,'');


    }
    /**
     * 渠道SKU库存划拨回调
     * 传入参数 $request
     */
    public function closeCallback(Request $request)
    {
        $data = $request->all();
        Log::notice('------渠道SKU低库存预警通知(yanxuan.notification.sku.alarm.close)回调-------');
        Log::notice('-------closeCallback---------');
        Log::alert($data);
        $closeAlarmSkus=\GuzzleHttp\json_decode($data['closeAlarmSkus'],true);


        $insert_attr=[];
        $close=[];
        $time = Carbon::now()->format('Y-m-d H:i:s');

        foreach ($closeAlarmSkus as $SkuCloseAlarmVO) {
            $close=[
                'skuId'=>$SkuCloseAlarmVO['skuId'],
                'inventory'=>$SkuCloseAlarmVO['inventory'],
                'update_at'=>$time,
            ];
            $insert_attr[]=$close;
        }
        DB::table('wyyx_close')->insert($insert_attr);
        return $this->resp(200,'');


    }
    /**
     * 渠道SKU再次开售通知回调
     * 传入参数 $request
     */
    public function reopenCallback(Request $request)
    {
        $data = $request->all();
        Log::notice('------渠道SKU再次开售通知(yanxuan.notification.sku.reopen)回调-------');
        Log::notice('-------reopenCallback---------');
        Log::alert($data);
        $timestamp=$data['timestamp'];
        $reopenSkus=\GuzzleHttp\json_decode($data['reopenSkus'],true);
        /*
         * 再次开售信息	reopenSkus	List<SkuReopenVO>的JSONArray
         * SkuReopenVO
         * 再次开售信息
         * 参数说明	参数名	类型	描述
         * Sku Id	skuId	long
         * 当前剩余库存	inventory	int
         */

        $insert_attr=[];
        $rskus=[];
        $time = Carbon::now()->format('Y-m-d H:i:s');

        foreach ($reopenSkus as $sku) {
            $rskus=[
                'skuId'=>$sku['skuId'],
                'inventory'=>$sku['inventory'],
                'update_at'=>$time,
            ];
            $insert_attr[]=$rskus;
        }
        DB::table('wyyx_reopen')->insert($insert_attr);
        return $this->resp(200,'');


    }


    function sendManual($plat_order_id){
        $db_prefix = config('database')['connections']['mysql']['prefix'];
        /*$checkSql="SELECT
                        t1.cnt = t2.cnt AS ready
                    FROM
                        (
                            SELECT
                                sku.from_plat_code,
                                count(1) AS cnt
                            FROM
                                yyd_store_deliver_goods d,
                                yyd_goods_sku sku
                            WHERE
                                d.sku_id = sku.sku_id
                            AND d.plat_order_id = ?
                            GROUP BY
                                sku.from_plat_code
                        ) t1
                    LEFT JOIN (
                        SELECT
                            sku.from_plat_code,
                            count(1) AS cnt
                        FROM
                            yyd_order_goods og,
                            yyd_goods_sku sku
                        WHERE
                            og.sku_id = sku.sku_id
                        AND og.plat_order_id = ?
                        GROUP BY
                            sku.from_plat_code
                    ) t2 ON t2.from_plat_code = t1.from_plat_code";

        */
        if($plat_order_id) {
            $order = Order::where('plat_order_id',$plat_order_id)->first();
            //dd($order);
            if ($order->is_share_gifts == 0) {//如果订单是普通订单（is_share_gifts==0）则进行处理，否则（值为1）为微信送礼单，不做第三方派单
                $checkSql = "SELECT t1.from_plat_code,t1.cnt = t2.cnt AS ready FROM ( SELECT sku.from_plat_code, count(1) AS cnt FROM " . $db_prefix . "store_deliver_goods d,  " . $db_prefix . "goods_sku sku WHERE d.sku_id = sku.sku_id AND d.plat_order_id =?  GROUP BY sku.from_plat_code ) t1 LEFT JOIN ( SELECT sku.from_plat_code, count(1) AS cnt FROM  " . $db_prefix . "order_goods og,  " . $db_prefix . "goods_sku sku WHERE og.sku_id = sku.sku_id AND og.plat_order_id =?  GROUP BY sku.from_plat_code ) t2 ON t2.from_plat_code = t1.from_plat_code";
                //$plat_order_id = $item->plat_order_id;//1120;//
                $result = DB::select($checkSql, [$plat_order_id, $plat_order_id]);
                foreach($result as $rst){
                    if($rst->from_plat_code>0){
                        if ($rst->ready == 1) {//所有商品都已派到仓点
                            $soc = new SupplierOrderController();
                            $isSend = $soc->thirdCreateOrder($plat_order_id, $rst->from_plat_code);
                            if ($isSend) {
                                if ($isSend['code'] == 200) {//成功发送订单到第三方
                                    //$storeDgoods = StoreDeliverGoods::leftjoin('goods_sku as a','a.sku_id=store_deliver_goods.sku_id')->where('plat_order_id', $plat_order_id)->get();
                                    //$storeDgoods = StoreDeliverGoods::where('plat_order_id', $plat_order_id)->get();
                                    $sku_sql = "SELECT gsk.sku_id FROM " . $db_prefix . "order_goods AS og
                                        INNER JOIN " . $db_prefix . "goods_sku AS gsk ON gsk.sku_id=og.sku_id
                                        WHERE og.plat_order_id=" . $order->plat_order_id . " and gsk.from_plat_code=" . $rst->from_plat_code ;
                                    $sku_ids = DB::select($sku_sql);
                                    $sids=array();
                                    foreach($sku_ids as $skuid){
                                        array_push($sids,$skuid->sku_id);
                                    }

                                    $storeDgoods = StoreDeliverGoods::where('plat_order_id', $order->plat_order_id)->whereIn('sku_id',$sids)->get();
                                    $to_plat_code = $rst->from_plat_code;
                                    foreach ($storeDgoods as $dgood) {
                                        $dgood->to_plat_code = $to_plat_code;
                                        $dgood->is_send = 1;
                                        $dgood->save();
                                    }
                                }
                            }
                            Log::notice("third return....." . $plat_order_id . ".........");
                            Log::alert($isSend);
                            //dd($isSend);

                        }
                    }
                }
                Log::alert($result);
            } else {
                Log::notice('weixin gift main order, not send to third ,id ' . $plat_order_id);
            }
        }
    }

    function cancelManual($plat_order_id){
        if($plat_order_id) {
            $order = Order::where('plat_order_id',$plat_order_id)->first();
            //取消订单
            $result=$this->cancelOrder($order->plat_order_sn);
            Log::alert($result);
            dd($result);
        }
    }

    /**
     * 订单取消申请
     * 传入参数：$orderId
     */
    public function cancelOrder($orderId)
    {
        return WyYxThirdApi::cancelOrder($orderId);
    }

    function queryOrder($plat_order_id){
        $db_prefix = config('database')['connections']['mysql']['prefix'];
        if($plat_order_id) {
            $order = Order::where('plat_order_id',$plat_order_id)->first();
            //dd($order);
            //查询订单
            $result=$this->getOrder($order->plat_order_sn);
            dd($result);

        }
    }

    /**
     * 订单查询
     * 传入参数：$orderId
     */
    public function getOrder($orderId)
    {
        return WyYxThirdApi::getOrder($orderId);
    }

    /**
     * 测试
     */
    public function test()
    {
        //$spec='a:1:{i:0;a:2:{s:12:"skuSpecValue";a:2:{s:6:"picUrl";s:65:"http://yanxuan.nosdn.127.net/091895b9e9a61148df666007653e7f29.jpg";s:5:"value";s:9:"组合装";}s:7:"skuSpec";a:1:{s:4:"name";s:6:"颜色";}}}';
        //$spec='a:2:{i:0;a:2:{s:12:"skuSpecValue";a:2:{s:6:"picUrl";s:65:"http://yanxuan.nosdn.127.net/0225f8ca844446c27f758a0608b38f88.jpg";s:5:"value";s:3:"红";}s:7:"skuSpec";a:1:{s:4:"name";s:6:"金属";}}i:1;a:2:{s:12:"skuSpecValue";a:2:{s:6:"picUrl";s:0:"";s:5:"value";s:9:"玫瑰金";}s:7:"skuSpec";a:1:{s:4:"name";s:6:"颜色";}}}';
        $spec='a:2:{i:0;a:2:{s:7:"skuSpec";a:1:{s:4:"name";s:6:"颜色";}s:12:"skuSpecValue";a:2:{s:6:"picUrl";s:65:"http://yanxuan.nosdn.127.net/ff44c4fb7853e42c2f852d8c6d712d26.png";s:5:"value";s:13:"麻灰+绿色";}}i:1;a:2:{s:7:"skuSpec";a:1:{s:4:"name";s:6:"尺码";}s:12:"skuSpecValue";a:2:{s:6:"picUrl";s:0:"";s:5:"value";s:1:"S";}}}';

        $specValueList=unserialize($spec);
        $skuspec=$this->handleSpecInfo($specValueList);
        $skuspect=sizeof($skuspec['specvalue'])==1?$skuspec['specvalue'][0]:$skuspec['specvalue'];
        $spuspecnt=sizeof($skuspec['specname'])==1?$skuspec['specname'][0]:$skuspec['specname'];
        //$spuspec[]=$skuspect;
        if($skuspec['isComp']==0) {
            foreach ($skuspect as $key => $value) {
                $spuspecx[$key] = $value;
            }
            $spukey='';
            foreach ($spuspecnt as $key => $value) {
                $spuspecName[$key] = $value;
                $spukey=$key;
            }
            $spuspec=[$spukey=>$spuspecx];
        }else{
            foreach ($skuspect as $key => $value) {
                $spuspec[$key] = $value;
            }
            foreach ($spuspecnt as $spn) {
                foreach ($spn as $key => $value) {
                    $spuspecName[$key] = $value;
                }
            }
        }

        dd();
        /*$rskuIds = WyYxThirdApi::skuIds();

        if ($rskuIds['code'] == 200) {
            $skuIds = join(',', $rskuIds['result']);
//            $skuIds='['.$skuIds.']';
//            //dd($skuIds);
//            $storeItems = WyYxThirdApi::stockInfo($skuIds);
//            dd($storeItems);
            $rskuItems = WyYxThirdApi::skuItems($skuIds);
            dd($rskuItems);
        }*/

        $db_prefix = config('database')['connections']['mysql']['prefix'];

        /*$checkSql="SELECT t1.cnt = t2.cnt AS ready FROM ( SELECT sku.from_plat_code, count(1) AS cnt FROM ". $db_prefix ."store_deliver_goods d,  ". $db_prefix ."goods_sku sku WHERE d.sku_id = sku.sku_id AND d.plat_order_id =?  GROUP BY sku.from_plat_code ) t1 LEFT JOIN ( SELECT sku.from_plat_code, count(1) AS cnt FROM  ". $db_prefix ."order_goods og,  ". $db_prefix ."goods_sku sku WHERE og.sku_id = sku.sku_id AND og.plat_order_id =?  GROUP BY sku.from_plat_code ) t2 ON t2.from_plat_code = t1.from_plat_code";
        $result=DB::select($checkSql,[1120,1120]);
        dd($result[0]->ready);*/

        /*$storeDgoods=StoreDeliverGoods::where('plat_order_id',1120)->get();
        //$storeDgoods=DB::table('store_deliver_goods')->where('plat_order_id',1120)->get();
        $to_plat_code=2002;
        //dd($storeDgoods);
        foreach($storeDgoods as $dgood){
            $dgood->to_plat_code=$to_plat_code;
            $dgood->is_send=1;
            $dgood->save();
        }

        $arr=[
            'plat_order_id'=>1,
            'plat_order_detail_id'=>1,
            'sku_id'=>1,
            'spu_id'=>1,
            'gc_id'=>1,
            'supplier_id'=>1,
            'supplier_name'=>1,
            'supplier_order_id'=>1,
            'supplier_order_detail_id'=>1,
            'supplier_sku_id'=>1,
            'supplier_settlement_price'=>1,
            'plat_settlement_price'=>1,
            'number'=>1,
            'store_id'=>1,
            'store_name'=>1,
            'deliver_state'=>1,
            'create_time'=>1,
            'transport_time'=>1,
        ];
        //Event::fire(new StoreDeliverEvent("1231111"));
        //StoreDeliverGoods::create($arr);


        dd();*/
        /*$skusel="SELECT  s.sku_id  FROM " .  $db_prefix ."wyyx_skuinfo s";
        $updateStoreNum="UPDATE " .  $db_prefix ."wyyx_skuinfo s SET storage_num=? where sku_id =? ";
        $skuids=DB::select($skusel);

        $a_skuids=[];
        foreach($skuids as $k){
            array_push($a_skuids,$k->sku_id);
        }
        $skuIds =join(',', $a_skuids);
//        dd($skuIds);
        $orderid='['.$skuIds.']';
//        dd($orderid);
        $result=$this->stockInfo($orderid);
        if ($result['code'] == 200) {
            $store_info=json_decode($result['result']);
            foreach($store_info as $store){
                DB::update($updateStoreNum,array($store->inventory,$store->skuId));
                print($store->skuId . $store->inventory);
            }
            dd($store_info);

        }*/

        /*$supplier_order_id=39;
        $from_plat_code='2002';

        $order_sql = "SELECT os.supplier_order_id,os.supplier_order_sn,os.supplier_order_amount_totals,os.supplier_transport_cost_totals,
                      os.create_time AS pay_time,o.create_time,o.sendee_province_id,o.sendee_area_id,o.sendee_city_id,o.sendee_address_info
                      FROM " . $db_prefix . "order_supplier AS os
                      INNER JOIN " . $db_prefix . "order AS o ON os.plat_order_id=o.plat_order_id
                      WHERE os.supplier_order_id=" . $supplier_order_id;
        $order = (array)DB::select($order_sql)[0];

        $sku_sql = "SELECT gsk.from_plat_skuid,og.number,og.sku_name,og.plat_settlement_price AS goods_price
                    FROM " . $db_prefix . "order_goods_supplier AS og
                    INNER JOIN " . $db_prefix . "goods_sku AS gsk ON gsk.sku_id=og.sku_id
                    WHERE og.supplier_order_id=" . $supplier_order_id ." and gsk.from_plat_code=" .$from_plat_code;
        $orderSkus = DB::select($sku_sql);
        $order['orderSkus'] = $orderSkus;
        $p_order=json_encode($order);
        dd($p_order);*/

        $skuids="[\"1131350\"]";


        $rst=$this->stockInfo($skuids);
        dd($rst);



        //查询平台订单
        /*$order = Order::find(1105); // 1103 1100 1089 1088  1062
        dd($order);*/
        /* $sku_sql = "SELECT gsk.from_plat_skuid,og.number,og.sku_name,og.goods_price FROM " . $db_prefix . "order_goods AS og
                     INNER JOIN " . $db_prefix . "goods_sku AS gsk ON gsk.sku_id=og.sku_id
                     WHERE og.plat_order_id=" . $order->plat_order_id;
         $orderSkus = DB::select($sku_sql);

         $order->orderSkus = $orderSkus;*/
        /*dd(\GuzzleHttp\json_encode($order));
        dd($order);*/

        //dd($orderSkus);
        //下单
        /* $order->orderSkus = $orderSkus;
         $result = $this->createOrder($order);
         dd($result);*/

        //取消订单
        /*$result=$this->cancelOrder($order->plat_order_sn);
        dd($result);*/

        //查询订单
        /* $result=$this->getOrder($order->plat_order_sn);
         dd($result);*/
        //确认收货
        /*$result=$this->confirmOrder($order->plat_order_sn,'10503013','2017-07-03 14:47:00');
        dd($result);*/


        /*
                //库存
                $a_skuids=[];
                foreach($orderSkus as $k){
                    array_push($a_skuids,$k->from_plat_skuid);
                }
                $skuIds =join(',', $a_skuids);
                //dd($skuIds);
                $orderid='['.$skuIds.']';
                $result=$this->stockInfo($orderid);
                dd($result);*/








        /*$skuIds='[111,222,333,444]';
        $param=\GuzzleHttp\json_encode($skuIds);
        $p=\GuzzleHttp\json_decode($skuIds);
        $skuIds='['.$skuIds.']';
        dd($p);*/


        /*$rskuIds = WyYxThirdApi::skuIds();

        if ($rskuIds['code'] == 200) {
            $skuIds = join(',', $rskuIds['result']);
            //$param=\MongoDB\BSON\toJSON($skuIds);
            $skuIds='['.$skuIds.']';
            //dd($skuIds);
            $storeItems = WyYxThirdApi::stockInfo($skuIds);
            dd($storeItems);
        }*/






        /*$aa='a:1:{i:0;a:2:{s:12:"skuSpecValue";a:2:{s:6:"picUrl";s:65:"http://yanxuan.nosdn.127.net/091895b9e9a61148df666007653e7f29.jpg";s:5:"value";s:9:"组合装";}s:7:"skuSpec";a:1:{s:4:"name";s:6:"测试";}}}';
        $bb='a:1:{i:0;a:2:{s:12:"skuSpecValue";a:2:{s:6:"picUrl";s:65:"http://yanxuan.nosdn.127.net/b563ff535e0554c5a9ea264b6d07e772.jpg";s:5:"value";s:7:"单品1";}s:7:"skuSpec";a:1:{s:4:"name";s:6:"测试";}}}';

        $cc='a:2:{i:110;a:2:{i:150;s:6:"组合";i:149;s:6:"单品";}i:101;a:2:{i:102;s:6:"1000ml";i:101;s:5:"500ml";}}';
        $ara=unserialize($aa);
        $arb=unserialize($bb);
        $arr=array_merge($ara,$arb);
        $arr3=unserialize($cc);

        //dd($arr);
        $guige=$this->handleSpecInfo($arr3);
        //$guige=$this->handleSpecInfo($arb);
        dd(serialize($guige));*/
        /*if(sizeof($guige)==1){
            dd(serialize($guige[0]));
        }else{
            dd(serialize($guige));
        }*/







//        //$db_prefix = config('database')['connections']['mysql']['prefix'];
//        $rskuIds = WyYxThirdApi::skuIds();
//
//        if ($rskuIds['code'] == 200) {
//            $skuIds = join(',', $rskuIds['result']);
//            $rskuItems = WyYxThirdApi::skuItems($skuIds);
//
//            //dd($rskuItems);
//
//            $insert_arr = [];
//            $insert_sku = [];
//            $insert_imgs = [];
//            $spuarr = [];
//            $skuarr = [];
//
//            foreach ($rskuItems['result'] as $item) {
//                unset($spuarr);
//                unset($insert_arr);
//                unset($insert_sku);
//                unset($insert_imgs);
//
//                $categoryPathList = \GuzzleHttp\json_encode($item['categoryPathList']);
//                $attrList = \GuzzleHttp\json_encode($item['attrList']);
//                //$time = Carbon::now()->format('Y-m-d H:i:s');
//                $spuarr = [
//                    'itemid' => $item['id'],
//                    'name' => $item['name'],
//                    'simpleDesc' => $item['simpleDesc'],
//                    'listPicUrl' => $item['listPicUrl'],
//                    'primarySkuId' => $item['primarySkuId'],
//                    'primaryPicUrl' => $item['primaryPicUrl'],
//                    'categoryPathList' => $categoryPathList,
//                    'attrList' => $attrList,
//                    'detailHtml'=> $item['itemDetail']['detailHtml'],
//                    'yanxuanPrice' => $item['skuList'][0]['yanxuanPrice'],
//                    'channelPrice' => $item['skuList'][0]['channelPrice']
//                ];
//
//                $spuimgarr = [
//                    'spu_id' => $item['id'],
//                    'image_url' => $item['primaryPicUrl'],
//                    'sort' => 0,
//                    'is_default' => 1,
//                ];
//
//                $spuimgarr1 = [
//                    'spu_id' => $item['id'],
//                    'image_url' => $item['itemDetail']['picUrl1'],
//                    'sort' => 1,
//                    'is_default' => 0,
//                ];
//                $spuimgarr2 = [
//                    'spu_id' => $item['id'],
//                    'image_url' => $item['itemDetail']['picUrl2'],
//                    'sort' => 2,
//                    'is_default' => 0,
//                ];
//                $spuimgarr3 = [
//                    'spu_id' => $item['id'],
//                    'image_url' => $item['itemDetail']['picUrl3'],
//                    'sort' => 3,
//                    'is_default' => 0,
//                ];
//                $spuimgarr4 = [
//                    'spu_id' => $item['id'],
//                    'image_url' => $item['itemDetail']['picUrl4'],
//                    'sort' => 4,
//                    'is_default' => 0,
//                ];
//
//                $insert_arr[] = $spuarr;
//                $insert_imgs[] = $spuimgarr;
//                $insert_imgs[] = $spuimgarr1;
//                $insert_imgs[] = $spuimgarr2;
//                $insert_imgs[] = $spuimgarr3;
//                $insert_imgs[] = $spuimgarr4;
//
//
//                //dd($item['skuList']);
//                foreach ($item['skuList'] as $sku) {
//                    $itemSkuSpecValueList = \GuzzleHttp\json_encode($sku['itemSkuSpecValueList']);
//
//                    //dd($sku['itemSkuSpecValueList']);
//                    foreach ($sku['itemSkuSpecValueList'] as $k) {
//                        Log::notice('--name--'.$k['skuSpec']['name']);
//                        Log::notice('--value--'.$k['skuSpecValue']['value']);
//                    }
//                    $skuarr = [
//                        'sku_id' => $sku['id'],
//                        'name' => $item['name'],
//                        'displayString' => $sku['displayString'],
//                        'picUrl' => $sku['picUrl'],
//                        'spu_id' => $item['id'],
//                        'itemSkuSpecValueList' => $itemSkuSpecValueList,
//                        //'attrList'=> $attrList,
//                        'yanxuanPrice' => $sku['yanxuanPrice'],
//                        'channelPrice' => $sku['channelPrice']
//                    ];
//                    $insert_sku[] = $skuarr;
//                }
//                /*DB::table('wyyx_iteminfo')->insert($insert_arr);
//                DB::table('wyyx_skuinfo')->insert($insert_sku);
//                DB::table('wyyx_spuimgs')->insert($insert_imgs);*/
//            }
//        }

        //dd($rskuItems);
    }

    protected function array_remove($data, $key){
        if(!array_key_exists($key, $data)){
            return $data;
        }
        $keys = array_keys($data);
        $index = array_search($key, $keys);
        if($index !== FALSE){
            array_splice($data, $index, 1);
        }
        return $data;

    }

    private function fillGoodsInfo($item, &$spuarr, &$skuarr)
    {
        $time = Carbon::now()->format('Y-m-d H:i:s');
        $spuarr = [
            'spu_name' => $item['name'],
            'gc_id' => 0,
            'gc_name' => '',
            'spu_plat_price' => $item['skuList'][0]['yanxuanPrice'],
            'spu_market_price' => $item['skuList'][0]['yanxuanPrice'],
            'spu_groupbuy_price' => $item['skuList'][0]['yanxuanPrice'],
            'spu_trade_price' => $item['skuList'][0]['yanxuanPrice'],
            'spu_partner_price' => $item['skuList'][0]['yanxuanPrice'],
            'spu_points_limit' => -1,
            'main_image' => $item['primaryPicUrl'],
            'mobile_content' => $item['simpleDesc'],
            'from_plat_code' => '2002',
            'from_plat_name' => '网易严选',
            'from_plat_skuid' => $item['primarySkuId'],
            'created_at' => $time,
            'updated_at' => $time,
        ];

        $skuarr = [
            'sku_name' => $item['name'],
            'spu_id' => 0,
            'color_id' => 0,
            'price' => $item['skuList'][0]['yanxuanPrice'],
            'market_price' => $item['skuList'][0]['yanxuanPrice'],
            'groupbuy_price' => $item['skuList'][0]['yanxuanPrice'],
            'trade_price' => $item['skuList'][0]['yanxuanPrice'],
            'partner_price' => $item['skuList'][0]['yanxuanPrice'],
            'main_image' => $item['primaryPicUrl'],
            'points_limit' => -1,
            'from_plat_code' => '2002',
            'from_plat_name' => '网易严选',
            'from_plat_skuid' => $item['primarySkuId'],
            'created_at' => $time,
            'updated_at' => $time,
        ];
    }
}
