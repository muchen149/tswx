<?php

namespace App\controllers\thirdapi;

use App\controllers\SupplierOrderController;
use App\facades\Api;
use App\Http\Controllers\Controller;
use App\lib\WyKlThirdApi;
use App\models\dct\DctArea;
use App\models\dct\DctExpress;
use App\models\goods\GoodsSku;
use App\models\order\Order;
use App\models\store\StoreWayBill;
use App\models\supplier\OrderSupplier;
use App\models\supplier\StoreDeliverGoods;
use App\models\supplier\StoreGoodsSku;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\models\goods\GoodsSpecDefine;
use App\models\goods\GoodsSpecValueDct;
use Illuminate\Support\Facades\Event;
use App\Events\StoreDeliverEvent;


class WyKlThirdApiController extends Controller
{
    /**
     * 创建订单
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
            $wareHouse=DB::table('wykl_skustores')->where('skuId',$orderSku->from_plat_skuid)->orderBy('warehouseStore','desc')->first();
            $sku = [
                'goodsId'=>$orderSku->from_plat_spuid,
                'skuId' => $orderSku->from_plat_skuid,
                'buyAmount' => $orderSku->number,
                'channelSalePrice' => $orderSku->goods_price,
                'warehouseId' => $wareHouse->warehouseId,
            ];
            $orderSkus[] = $sku;
        }

        $orderItemList['orderItemList']=$orderSkus;
        $thirdPartOrderId =$order['plat_order_sn'];

        $userInfo['accountId']='lxhtest10@163.com';
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
        $userInfo['identityId']='430601198503228775';
        /*$userInfo['identityPicFront']=;
        $userInfo['identityPicBack']=;*/



        //订单信息
        $data = [
            'thirdPartOrderId' => $thirdPartOrderId,
            'userInfo' =>$userInfo,
            'orderItemList' =>$orderSkus,

        ];
        return WyKlThirdApi::createOrder($data);
    }

    public function orderConfirm($order)
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
            //$wareHouse=DB::table('wykl_skustores')->where('skuId',$orderSku->from_plat_skuid)->orderBy('warehouseStore','desc')->first();
            $sku = [
                'goodsId'=>$orderSku->from_plat_spuid,
                'skuId' => $orderSku->from_plat_skuid,
                'buyAmount' => $orderSku->number,
                'channelSalePrice' => $orderSku->goods_price,
            ];
            $orderSkus[] = $sku;
        }

        $orderItemList['orderItemList']=$orderSkus;
        $thirdPartOrderId =$order['plat_order_sn'];
        /*
                $orderItemList=[];
                $orderItem['goodsId']=;
                $orderItem['skuId']=;
                $orderItem['buyAmount']=;
                $orderItem['channelSalePrice']=;
                $orderItem['warehouseId']=; */

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
        $userInfo['identityId']=$user->ID_card;//'430601198503228775';
        /*$userInfo['identityPicFront']=;
        $userInfo['identityPicBack']=;*/


        //订单信息
        $data = [
            'thirdPartOrderId' => $thirdPartOrderId,
            'userInfo' =>$userInfo,
            'orderItemList' =>$orderSkus,

        ];
        return WyKlThirdApi::orderConfirm($data);


    }

    /**
     * 更新网易考拉商品SKU信息（主图、商品详情）
     */
    public function updateThirdGoodsSku()
    {
        //set_time_limit(0);
        $db_prefix = config('database')['connections']['mysql']['prefix'];
        $rskuIds = WyKlThirdApi::skuIds();
        //dd($rskuIds);
        if ($rskuIds['httpcode'] == 200) {
            $nump = DB::table("wykl_goodsInfo")->truncate();//删除临时表数据
            $nums = DB::table("wykl_skustores")->truncate();//删除临时表数据


            if($rskuIds['content']['recCode']== 200) {
                $skuIdsT = array_chunk($rskuIds['content']['goodsInfo'], 20);
                foreach ($skuIdsT as $key => $idsV) {
                    //$skuIds = join(',', $idsV);
                    $skuIds = json_encode($idsV);
                    $rskuItems = WyKlThirdApi::skuItems($skuIds);
                    //dd($rskuItems);
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
                                    /*foreach($skuspec['specvalue'] as $k=>$v){
                                        foreach($v as $kk=>$vv){
                                            $skuspect[$kk]=$vv;
                                        }
                                        //$skuspect = $skuspec['specvalue'];//sizeof($skuspec['specvalue']) == 1 ? $skuspec['specvalue'][0] : $skuspec['specvalue'];
                                    }*/
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


                                DB::table('wykl_goodsInfo')->insert($goodInfo);
                                DB::table('wykl_skustores')->insert($skuStores);

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

    /**
     * 订单确认收货
     * 传入参数：$orderId $packageId $confirmTime
     */
    /*public function confirmOrder($orderId, $packageId, $confirmTime)
    {
        return WyKlThirdApi::confirmOrder($orderId, $packageId, $confirmTime);
    }*/

    /**
     * 更新网易考拉库存信息到sku信息表
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
        return WyKlThirdApi::stockInfo($skuIds);
    }

    /**
     * 更新网易考拉商品图片表
     */
    public function updateThirdGoodsImage()
    {
        $spusql='INSERT INTO `yyd_goods_spu_images` (`spu_id`,`image_url`,`sort`,`is_default`) VALUES (?,?,?,?)'.
            'ON DUPLICATE KEY UPDATE `spu_id` = values(`spu_id`), `image_url` = values(`image_url`), `sort` = values(`sort`), `is_default` = values(`is_default`);';

        $skusql='INSERT INTO `yyd_goods_sku_images` (`sku_id`,`color_id`,`image_url`,`sort`,`is_default`) VALUES(?,?,?,?,?)'.
            'ON DUPLICATE KEY UPDATE `sku_id` = values(`sku_id`), `color_id` = values(`color_id`), `image_url` = values(`image_url`), `sort` = values(`sort`), `is_default` = values(`is_default`);';

        $rskuIds = WyKlThirdApi::skuIds();
        if ($rskuIds['code'] == 200) {
            $skuIds = join(',', $rskuIds['result']);
            $rskuItems = WyKlThirdApi::skuItems($skuIds);
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
            if($order){
                switch ($orderCancelResult['cancelStatus']) {
                    case 1://允许取消
                        //撤单逻辑
                        /*...*/
                        Log::notice('---撤单---------');
                        $order->supplier_order_state = -1;
                        $order->save();
                        break;
                    case 0:
                        //不允许取消 rejectReason 原因描述

                        break;
                    case 2://待审核

                        break;

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
        $insert_attr=[];
        $countChage=[];
        $time = Carbon::now()->format('Y-m-d H:i:s');

        foreach ($transfers['skuTransfers'] as $skuTransfer) {
            $countChage=[
                'skuId'=>$skuTransfer['skuId'],
                'transferCount'=>$skuTransfer['transferCount'],
                'update_at'=>$time,
                ];
            $insert_attr[]=$countChage;
        }
        DB::table('wyyx_countchange')->insert($insert_attr);
        //$ChannelInventoryTransfer = $data['ChannelInventoryTransfer'];
        //$skuTransfers=$ChannelInventoryTransfer->skuTransfers;
        return $this->resp(200,'');


    }

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
        return WyKlThirdApi::queryOrderStatus($orderId);
    }

    function payThirdOrder($plat_order_id){
        $db_prefix = config('database')['connections']['mysql']['prefix'];
        if($plat_order_id) {
            $order = Order::where('plat_order_id',$plat_order_id)->first();
            //dd($order);
            //查询订单
            $result=$this->payOrder($order->plat_order_sn);
            dd($result);

        }
    }

    /**
     * 订单支付
     * 传入参数：$orderId
     */
    public function payOrder($orderId)
    {
        return WyKlThirdApi::payOrder($orderId);
    }

    function cancelThirdOrder($plat_order_id){
        $db_prefix = config('database')['connections']['mysql']['prefix'];
        if($plat_order_id) {
            $order = Order::where('plat_order_id',$plat_order_id)->first();
            //dd($order);
            //查询订单
            $result=$this->cancelOrder($order->plat_order_sn);
            dd($result);

        }
    }

    /**
     * 订单取消
     * 传入参数：$orderId
     */
    public function cancelOrder($orderId)
    {
        return WyKlThirdApi::cancelOrder($orderId);
    }

    function queryChangedGoodsInfo(){

        return WyKlThirdApi::queryChangedGoodsInfo(1,2);
    }

    function closeThirdOrder($plat_order_id){
        $db_prefix = config('database')['connections']['mysql']['prefix'];
        if($plat_order_id) {
            $order = Order::where('plat_order_id',$plat_order_id)->first();
            //dd($order);
            //查询订单
            $result=$this->closeOrder($order->plat_order_sn);
            dd($result);

        }
    }

    /**
     * 订单关闭
     * 传入参数：$orderId
     */
    public function closeOrder($orderId)
    {
        return WyKlThirdApi::closeOrder($orderId);
    }

    /**
     * 测试
     */
    public function test()
    {
        /*set_time_limit(0);
        $this->updateThirdGoodsSku();
        dd("ok");*/


        set_time_limit(0);
        $this->formThirdGoodsSpu();
        dd("ok");



        //$skuList = DB::table("wykl_goodsInfo")->where('id','>=',496)->where('id','<=',499)->orderBy('skuId')->get();
        //$skuList = DB::table("wykl_goodsInfo")->where('onlineStatus',1)->orderBy('skuId')->get();

        $db_prefix = config('database')['connections']['mysql']['prefix'];
        $sql='select * from '.$db_prefix.'wykl_goodsInfo t where t.goodsId = :id1 order by skuID';
        $data=array(
            'id1' =>58737128,
           // 'id1' =>493,
           // 'id2' =>495,
        );


        /*$sql='select * from '.$db_prefix.'wykl_goodsInfo t where t.id >= :id1 and t.id <= :id2 order by skuID';
        $data=array(
            'id1' =>114,
            'id2' =>119,
            // 'id1' =>493,
            // 'id2' =>495,
        );*/

        $skuList = DB::select($sql,$data);


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

       /* $nump = DB::table("wykl_goodsInfoSpu")->truncate();//删除临时表数据
        $numi = DB::table("wykl_spuimgs")->truncate();//删除临时表数据*/


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

                   /* DB::table('wykl_spuimgs')->insert($spuimgarr);
                    DB::table('wykl_goodsInfoSpu')->insert($spuarr);*/
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

                    /*foreach($skuspect as $key=>$spec){
                        $spuspeckey=$key;
                        foreach($spec as $k=>$v){
                            $skuspecs[$k]=$v;
                        }
                    }*/

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

                    /*foreach($skuspect as $key=>$spec){
                        $spuspeckey=$key;
                        foreach($spec as $k=>$v){
                            $skuspecs[$k]=$v;
                        }
                    }*/

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
        dd($spuarr);
    }

    public function formThirdGoodsSpu()
    {

        //$skuList = DB::table("wykl_goodsInfo")->where('id','>=',496)->where('id','<=',499)->orderBy('skuId')->get();
        $skuList = DB::table("wykl_goodsInfo")->where('onlineStatus',1)->orderBy('skuId')->get();
        /*
        $db_prefix = config('database')['connections']['mysql']['prefix'];
        $sql='select * from '.$db_prefix.'wykl_goodsInfo t where t.id >= :id1 and t.id <= :id2 order by skuID';
        $data=array(
            'id1' =>496,
            'id2' =>499,
           // 'id1' =>493,
           // 'id2' =>495,
        );
        $skuList = DB::select($sql,$data);*/


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

        $nump = DB::table("wykl_goodsInfoSpu")->truncate();//删除临时表数据
        $numi = DB::table("wykl_spuimgs")->truncate();//删除临时表数据


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

                     DB::table('wykl_spuimgs')->insert($spuimgarr);
                     DB::table('wykl_goodsInfoSpu')->insert($spuarr);
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

                    /*foreach($skuspect as $key=>$spec){
                        $spuspeckey=$key;
                        foreach($spec as $k=>$v){
                            $skuspecs[$k]=$v;
                        }
                    }*/

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

                    /*foreach($skuspect as $key=>$spec){
                        $spuspeckey=$key;
                        foreach($spec as $k=>$v){
                            $skuspecs[$k]=$v;
                        }
                    }*/

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

        DB::table('wykl_spuimgs')->insert($spuimgarr);
        DB::table('wykl_goodsInfoSpu')->insert($spuarr);
        //增加spu信息 ，最后一条还没有保存，这里处理——end。
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
            'from_plat_name' => '网易考拉',
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
            'from_plat_name' => '网易考拉',
            'from_plat_skuid' => $item['primarySkuId'],
            'created_at' => $time,
            'updated_at' => $time,
        ];
    }

    public function querySkuIdsBySpuIds($spuIds=0)
    {
        $spuIds = json_encode([$spuIds]);
        $goodsIds = WyKlThirdApi::querySkuIdsByGoodsIds($spuIds);
        log::alert($goodsIds);
        dd($goodsIds);
    }
}
