<?php
/**
 * 考拉非一般贸易
 * Created by PhpStorm.
 * User: yu
 * Date: 2018/7/23 0023
 */
namespace App\controllers\thirdapi;

use App\controllers\SupplierOrderController;
use App\Http\Controllers\Controller;
use App\lib\WyKlThirdApiTwo;

use App\models\order\Order;
use App\models\dct\DctArea;
use App\models\supplier\StoreDeliverGoods;
use App\models\goods\GoodsSpecDefine;
use App\models\goods\GoodsSpecValueDct;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WyKlThirdApiControllerTwo extends Controller
{
    /**
     * 2018年7月24日10:15:12
     * 更新网易考拉商品SKU信息（主图、商品详情）
     * @author yu
     */
    public function updateThirdGoodsSku()
    {
        $rskuIds = WyKlThirdApiTwo::skuIds();

        if ($rskuIds['httpcode'] == 200) {
            DB::table("wykl_goodsInfo_two")->truncate();//删除临时表数据
            DB::table("wykl_skustores_two")->truncate();//删除临时表数据

            if($rskuIds['content']['recCode']== 200) {
                $skuIdsT = array_chunk($rskuIds['content']['goodsInfo'], 20);
                foreach ($skuIdsT as $key => $idsV) {
                    $skuIds = json_encode($idsV);
                    $rskuItems = WyKlThirdApiTwo::skuItems($skuIds);
                    if ($rskuItems['httpcode'] == 200) {
                        $content=$rskuItems['content'];
                        $skuStores=[];
                        foreach ($content as $ckey => $itemV) {
                            if($itemV['recCode']==200){
                                $goodInfo=$itemV['goodsInfo'];
                                $skuspec=$this->handleSpecInfo($goodInfo['skuProperty']);
                                if(empty($skuspec)){
                                    $skuspect='';
                                }else{
                                    $skuspect = sizeof($skuspec['specvalue']) == 1 ? current($skuspec['specvalue']) : $skuspec['specvalue'];

                                    if ($skuspec['isComp'] == 1) {
                                        $skuspec4sku = array();
                                        foreach ($skuspect as $key => $value) {
                                            $spuspec[$key] = $value;
                                            foreach ($value as $kk => $vv) {
                                                $skuspec4sku[$kk] = $vv;
                                            }
                                        }
                                        unset($skuspect);
                                        $skuspect = $skuspec4sku;
                                    }
                                }

                                $goodInfo['skuspec']=$skuspect==''?'':serialize($skuspect);
                                $warehouseStores=$goodInfo['warehouseStores'];
                                unset($skuStores);
                                foreach($warehouseStores as $store){
                                    $arr=array(
                                        'warehouseName'=>$store['warehouseName'],
                                        'warehouseId'=>$store['warehouseId'],
                                        'warehouseStore'=>$store['warehouseStore'],
                                        'skuId'=> $goodInfo['skuId'],
                                    );
                                    $skuStores[]=$arr;
                                }
                                $goodInfo['warehouseStores']=serialize($goodInfo['warehouseStores']);
                                $goodInfo['recommandStore']=serialize($goodInfo['recommandStore']);
                                $goodInfo['goodsProperty']=serialize($goodInfo['goodsProperty']);
                                $goodInfo['taxRates']=serialize($goodInfo['taxRates']);
                                $goodInfo['skuProperty']=serialize($goodInfo['skuProperty']);
                                $goodInfo['goodsImages']=serialize($goodInfo['goodsImages']);
                                $goodInfo['logisticsProperty']=serialize($goodInfo['logisticsProperty']);


                                DB::table('wykl_goodsInfo_two')->insert($goodInfo);
                                DB::table('wykl_skustores_two')->insert($skuStores);
                                //dd($goodInfo);
                            }
                        }
                    }
                }
            }

        }
        dd($rskuIds);
    }

    /**
     * 2018年7月23日19:39:36
     * 第三方GoodsId更新（上架和包税）
     * author yu
     */
    public function updateThirdGoodsSpu()
    {
        $skuList = DB::table("wykl_goodsInfo_two")->where('onlineStatus',1)->where('isFreeTax',1)->orderBy('skuId')->get();

        $spuspec = [];
        $spuspecs = [];
        $skuspecs = [];
        $spuspecName = [];
        $spuarr = [];
        $skuspect=[];
        $spuspecnt=[];
        $spuimgarr=[];
        $spuspeckey=0;
        $i=0;
        $goodId=0;
        $isFirstRecord=true;

        DB::table("wykl_goodsInfoSpu_two")->truncate();//删除临时表数据
        DB::table("wykl_spuimgs_two")->truncate();//删除临时表数据

        foreach($skuList as $key=>$sku){
            if($goodId!=$sku->goodsId){
                $goodId=$sku->goodsId;
                $skuProperty=unserialize($sku->skuProperty);
                //增加spu信息 ,第一次进来不做操作。
                if($isFirstRecord){
                    $isFirstRecord=false;
                }else{
                    //新增spu信息
                    if($skuspect!=''){
                        $spuspec=$skuspecs;//$spuspec[$spuspeckey]=$skuspecs;
                        $spuarr['spuspec'] = serialize($spuspec);
                        $spuarr['spuspecname'] = serialize($spuspecnt);
                    }else{
                        $spuarr['spuspec'] = '';
                        $spuarr['spuspecname'] = '';
                    }
                    $spuimgarr = [
                        'spu_id' => $spuarr['goodsId'],
                        'image_url' => $spuarr['imageUrl'],
                        'sort' => 0,
                        'is_default' => 1,
                    ];

                    DB::table('wykl_spuimgs_two')->insert($spuimgarr);
                    DB::table('wykl_goodsInfoSpu_two')->insert($spuarr);
                }

                unset($spuarr);
                unset($spuspecName);
                unset($spuspec);
                unset($spuspecs);
                unset($skuspecs);
                unset($spuspect);
                unset($spuspecnt);
                unset($spuimgarr);
                $i=0;

                $spuarr['goodsId'] = $sku->goodsId;
                $spuarr['productId'] = $sku->productId;
                $spuarr['title'] = $sku->title;
                $spuarr['subTitle'] = $sku->subTitle;
                $spuarr['shortTitle'] = $sku->shortTitle;
                $spuarr['brandName'] = $sku->brandName;
                $spuarr['onlineStatus'] = $sku->onlineStatus;
                $spuarr['price'] = $sku->price;
                $spuarr['suggestPrice'] = $sku->suggestPrice;
                $spuarr['marketPrice'] = $sku->marketPrice;
                $spuarr['store'] = $sku->store;
                $spuarr['category'] = $sku->category;
                $spuarr['storage'] = $sku->storage;
                $spuarr['brandCountryName'] = $sku->brandCountryName;
                $spuarr['goodsProperty'] = $sku->goodsProperty;
                $spuarr['logisticsProperty'] = $sku->logisticsProperty;
                $spuarr['detail'] = $sku->detail;
                $spuarr['goodsImages'] = $sku->goodsImages;
                $spuarr['taxRate'] = $sku->taxRate;
                $spuarr['warehouseStores'] = $sku->warehouseStores;
                $spuarr['imageUrl'] = $sku->imageUrl;
                $spuarr['isFreeShipping'] = $sku->isFreeShipping;
                $spuarr['brandId'] = $sku->brandId;
                $spuarr['recommandStore'] = $sku->recommandStore;
                $spuarr['thirdCategoryName'] = $sku->thirdCategoryName;
                $spuarr['leafCategoryId'] = $sku->leafCategoryId;
                $spuarr['isPresell'] = $sku->isPresell;
                $spuarr['taxRates'] = $sku->taxRates;
                $spuarr['memberCount'] = $sku->memberCount;
                $spuarr['importType'] = $sku->importType;
                $spuarr['isFreeTax'] = $sku->isFreeTax;
                $spuarr['thirdCategoryId'] = $sku->thirdCategoryId;
                $skuspec=$this->handleSpecInfo($skuProperty);
                if(!empty($skuspec)) {
                    $skuspect = $skuspec['specvalue'];//sizeof($skuspec['specvalue']) == 1 ? $skuspec['specvalue'][0] : $skuspec['specvalue'];
                    $spuspecnt = sizeof($skuspec['specname']) == 1 ? $skuspec['specname'][0] : $skuspec['specname'];

                    if ($skuspec['isComp'] == 0) {
                        unset($spuspecx);
                        foreach ($skuspect as $key => $value) {
                            $spuspecx[$key] = $value;
                        }
                        $spukey = '';
                        foreach ($spuspecnt as $key => $value) {
                            $spuspecName[$key] = $value;
                            $spukey = $key;
                        }
                        $spuspec = $spuspecx;
                    } else {
                        $skuspec4sku = array();
                        foreach ($skuspect as $key => $value) {
                            $spuspec[$key] = $value;
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

                    $spuspecnt= $spuspecName;
                    foreach ($spuspec as $k => $v) {
                        foreach ($v as $kk => $vv) {
                            $skuspecs[$k][$kk] = $vv;
                        }
                    }

                }else{
                    $skuspect='';
                    $spuspecnt='';
                }
                $i++;

            }else{
                $skuProperty=unserialize($sku->skuProperty);
                $skuspec=$this->handleSpecInfo($skuProperty);
                if(!empty($skuspec)) {
                    $skuspect = $skuspec['specvalue'];//sizeof($skuspec['specvalue']) == 1 ? $skuspec['specvalue'][0] : $skuspec['specvalue'];
                    $spuspecnt = sizeof($skuspec['specname']) == 1 ? $skuspec['specname'][0] : $skuspec['specname'];

                    if ($skuspec['isComp'] == 0) {
                        unset($spuspecx);
                        foreach ($skuspect as $key => $value) {
                            $spuspecx[$key] = $value;
                        }
                        $spukey = '';
                        foreach ($spuspecnt as $key => $value) {
                            $spuspecName[$key] = $value;
                            $spukey = $key;
                        }
                        $spuspec = $spuspecx;
                    } else {
                        $skuspec4sku = array();
                        foreach ($skuspect as $key => $value) {
                            $spuspec[$key] = $value;
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
                    $spuspecnt= $spuspecName;
                    foreach ($spuspec as $k => $v) {
                        foreach ($v as $kk => $vv) {
                            $skuspecs[$k][$kk] = $vv;
                        }
                    }
                }else{
                    $skuspect='';
                    $spuspecnt='';
                }
                $i++;
            }
        }
        //增加spu信息 ，最后一条还没有保存，这里处理——start。
        if($skuspect!=''){
            $spuspec=$skuspecs;
            $spuarr['spuspec'] = serialize($spuspec);
            $spuarr['spuspecname'] = serialize($spuspecnt);
        }else{
            $spuarr['spuspec'] = '';
            $spuarr['spuspecname'] = '';
        }
        $spuimgarr = [
            'spu_id' => $spuarr['goodsId'],
            'image_url' => $spuarr['imageUrl'],
            'sort' => 0,
            'is_default' => 1,
        ];

        DB::table('wykl_spuimgs_two')->insert($spuimgarr);
        DB::table('wykl_goodsInfoSpu_two')->insert($spuarr);
        //增加spu信息 ，最后一条还没有保存，这里处理——end。
    }

    /**
     * 2018年7月24日09:21:51
     * 订单确认
     * @author yu
     * @param $order
     * @return mixed
     */
    public function orderConfirm($order)
    {
        $user = DB::table('member_idcard')->where('member_id',$order['member_id'])->first();
        //获得收货地址信息
        $receiverProvinceName = DctArea::find($order['sendee_province_id'])->name;            // 省级名称
        $receiverCityName = DctArea::find($order['sendee_area_id'])->name;                    // 地区名称
        $receiverDistrictName = DctArea::find($order['sendee_city_id'])->name;

        //获得收货人信息
        $receiver = preg_split('/ /', $order['sendee_address_info'], -1, PREG_SPLIT_NO_EMPTY);
        $receiverName = $user->realname;
        $receiverPhone = explode(':', $receiver[1])[1];
        $receiverAddressDetail = $receiver[3];

        //订单商品信息
        $orderSkus = [];
        foreach ($order['orderSkus'] as $orderSku) {
            $sku = [
                'goodsId'=>$orderSku->from_plat_spuid,
                'skuId' => $orderSku->from_plat_skuid,
                'buyAmount' => $orderSku->number,
                'channelSalePrice' => $orderSku->goods_price,
            ];
            $orderSkus[] = $sku;
        }

        //$orderItemList['orderItemList']=$orderSkus;
        $thirdPartOrderId =$order['plat_order_sn'];
        $userInfo['accountId']='lxhtest100@163.com';
        $userInfo['name']=$receiverName;
        $userInfo['mobile']=$receiverPhone;
        //$userInfo['email']=;

        $userInfo['provinceName']=$receiverProvinceName;
        //$userInfo['provinceCode']=;
        $userInfo['cityName']=$receiverCityName;
        //$userInfo['cityCode']=;
        $userInfo['districtName']=$receiverDistrictName;
        //$userInfo['districtCode']=;
        $userInfo['address']=$receiverAddressDetail;
        /*$userInfo['postCode']=;
        $userInfo['phoneNum']=;
        $userInfo['phoneAreaNum']=;
        $userInfo['phoneExtNum']=;*/

        $userInfo['identityId']=$user->idcard;
        $userInfo['identityPicFront']=$user->front_img;
        $userInfo['identityPicBack']=$user->back_img;

        //订单信息
        $data = [
            'thirdPartOrderId' => $thirdPartOrderId,
            'userInfo' =>$userInfo,
            'orderItemList' =>$orderSkus,

        ];
        return WyKlThirdApiTwo::orderConfirm($data);

    }

    /**
     * 2018年7月23日20:54:23
     * 手动派单到第三方
     * author yu
     * @param $plat_order_id
     */
    function sendManual($plat_order_id){
        $db_prefix = config('database')['connections']['mysql']['prefix'];
        if($plat_order_id) {
            $order = Order::where('plat_order_id',$plat_order_id)->first();
            //dd($order);
            if ($order->is_share_gifts == 0) {//如果订单是普通订单（is_share_gifts==0）则进行处理，否则（值为1）为微信送礼单，不做第三方派单
                $checkSql = "SELECT t1.from_plat_code,t1.cnt = t2.cnt AS ready FROM ( SELECT sku.from_plat_code, count(1) AS cnt FROM " . $db_prefix . "store_deliver_goods d,  " . $db_prefix . "goods_sku sku WHERE d.sku_id = sku.sku_id AND d.plat_order_id =?  GROUP BY sku.from_plat_code ) t1 LEFT JOIN ( SELECT sku.from_plat_code, count(1) AS cnt FROM  " . $db_prefix . "order_goods og,  " . $db_prefix . "goods_sku sku WHERE og.sku_id = sku.sku_id AND og.plat_order_id =?  GROUP BY sku.from_plat_code ) t2 ON t2.from_plat_code = t1.from_plat_code";
                //$plat_order_id = $item->plat_order_id;//1120;//
                $result = DB::select($checkSql, [$plat_order_id, $plat_order_id]);

                foreach($result as $rst){
                    if ($rst->ready == 1) {//所有商品都已派到仓点
                        $soc = new SupplierOrderController();
                        $isSend = $soc->thirdCreateOrder($plat_order_id, $rst->from_plat_code);
                        if ($isSend) {
                            if ($isSend['httpcode'] == 200) {//成功发送订单到第三方
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
                    }
                }
            } else {
                Log::notice('weixin gift main order, not send to third ,id ' . $plat_order_id);
            }
        }
    }

    /**
     * 查询订单
     * @param $plat_order_id
     */
    function queryOrder($plat_order_id){
        if($plat_order_id) {
            $order = Order::where('plat_order_id',$plat_order_id)->first();
            $result=$this->getOrder($order->plat_order_sn);
            dd($result);
        }
    }

    /**
     * 2018年7月23日21:23:36
     * 查询第三方订单
     * author yu
     * 传入参数：$orderId(平台订单编号)
     */
    public function getOrder($orderId)
    {
        return WyKlThirdApiTwo::queryOrderStatus($orderId);
    }

    /**
     * 查询要取消的订单
     * @param $plat_order_id
     */
    function cancelThirdOrder($plat_order_id){
        if($plat_order_id) {
            $order = Order::where('plat_order_id',$plat_order_id)->first();
            $result=$this->cancelOrder($order->plat_order_sn);
            dd($result);
        }
    }

    /**
     * 2018年7月23日21:23:36
     * 取消第三方订单
     * author yu
     * 传入参数：$orderId(平台订单编号)
     */
    public function cancelOrder($orderId)
    {
        return WyKlThirdApiTwo::cancelOrder($orderId);
    }

    /**
     * 2018年7月23日18:53:57
     * 规格处理
     * author yu
     * @param $speclist
     * @return array
     */
    public function handleSpecInfo($speclist){
        $r_data[]=[];
        $n_data[]=[];
        $rt_data[]=[];
        unset($r_data);
        unset($n_data);
        unset($rt_data);
        if($speclist) {
            $i = 0;
            foreach ($speclist as $spec) {
                $i++;
                $def = null;
                $dict = null;
                $goodsSpecDefine = GoodsSpecDefine::where('name', $spec['propertyName'])->where('is_use', '1')->first();
                if (!$goodsSpecDefine) {//不存在新增规格和规格值
                    $res = GoodsSpecDefine::create([
                        'caption' => $spec['propertyName'],  //后期启动：简称和全名  name为简称  caption为全名
                        'name' => $spec['propertyName'],     // 默认规格名称个简称都是一样的
                        // 'py_code' => ApiWrapperFacade::getPinyinCode($resp['name']),
                        'description' => '',

                    ]);
                    $def = $res;
                    $data = [                                     //参数没有去判断，，catch统一处理
                        'name' => $spec['propertyValue'],
                        'sp_id' => $res->id,
                        'sp_color' => '',
                        'sort' => '999',
                    ];
                    $spec_dct = GoodsSpecValueDct::create($data);
                    $dict = $spec_dct;
                } else {
                    $def = $goodsSpecDefine;
                    $id = $goodsSpecDefine->id;
                    $resd = GoodsSpecValueDct::where("name", $spec['propertyValue'])->where('sp_id', $id)->first();//存在规格值
                    if ($resd) {//存在
                        if ($resd->is_use == 0) {//如果曾经被禁用，则修改为启用
                            $resd->update(['is_use' => 1, 'sort' => '999']);
                        }
                    } else {
                        $data = [                                     //参数没有去判断，，catch统一处理
                            'name' => $spec['propertyValue'],
                            'sp_id' => $id,
                            'sp_color' => '',
                            'sort' => '999',
                        ];
                        $resd = GoodsSpecValueDct::create($data);
                    }
                    $dict = $resd;
                }
                Log::notice('def----' . $def);
                Log::notice('dict-----' . $dict);
                $r_data[] = [$dict->id => $dict->name];
                $rt_data[$def->id] = [$dict->id => $dict->name];
                $n_data[] = [$def->id => $def->name];
            }
            if ($i > 1) {
                $t_data = [
                    'specname' => $n_data,
                    'specvalue' => $rt_data,
                    'isComp' => 1
                ];
            } else {
                $t_data = [
                    'specname' => $n_data,
                    'specvalue' => $rt_data,
                    'isComp' => 0
                ];
            }
        }else{
            $t_data=[];
        }
        return $t_data;
    }
}