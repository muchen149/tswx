<?php

namespace App\Providers;

use App\controllers\StoreDeliverController;
use App\controllers\SupplierOrderController;
use App\models\goods\GoodsSku;
use App\models\order\Order;
use App\models\supplier\StoreGoodsSku;
use App\models\wyyx\WyyxClose;
use App\models\wyyx\WyyxCountChange;
use App\models\wyyx\WyyxCountCheck;
use App\models\wyyx\WyyxReopen;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use App\models\supplier\StoreDeliverGoods;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // blade扩展指令(展示图片指令)
        Blade::directive('showImg', function ($data) {
            return '<?php echo "' . env('VISIT_IMAGE_DOMAIN', 'http://images.shuitine.com') . '/' . $data . '" ?>';
        });
        //模型事件处理
        //仓点派单后发送第三方
        StoreDeliverGoods::created(function($item){
            $db_prefix = config('database')['connections']['mysql']['prefix'];

			//判断是否是考拉商品 直接跳转 B计划(考拉拆单派单)
			/*$getOrderType = "select from_plat_code from yyd_order_goods inner join yyd_goods_spu on yyd_order_goods.plat_order_id = ".$item->plat_order_id." and yyd_order_goods.spu_id = yyd_goods_spu.spu_id";
			$result = DB::select($getOrderType);
			if ($result[0]->from_plat_code == 2003) {
				$this->WyKlsendManual($item->plat_order_id);
				return true;
			}*/
            $store_id=$item->store_id;
            $sc = new StoreDeliverController();
            Log::notice("-------begin handle notice.....".$item->plat_order_id.'====='.$store_id.'=========');
            $sc->wxSendMsg($item->plat_order_id,$store_id);
            //$order=Order::find($item->plat_order_id);
            $order = Order::where('plat_order_id',$item->plat_order_id)->first();
            if($order->is_share_gifts==0) {//如果订单是普通订单（is_share_gifts==0）则进行处理，否则（值为1）为微信送礼单，不做第三方派单
                $checkSql = "SELECT t1.from_plat_code,t1.cnt = t2.cnt AS ready FROM ( SELECT sku.from_plat_code, count(1) AS cnt FROM " . $db_prefix . "store_deliver_goods d,  " . $db_prefix . "goods_sku sku WHERE d.sku_id = sku.sku_id AND d.plat_order_id =?  GROUP BY sku.from_plat_code ) t1 LEFT JOIN ( SELECT sku.from_plat_code, count(1) AS cnt FROM  " . $db_prefix . "order_goods og,  " . $db_prefix . "goods_sku sku WHERE og.sku_id = sku.sku_id AND og.plat_order_id =?  GROUP BY sku.from_plat_code ) t2 ON t2.from_plat_code = t1.from_plat_code";
                $plat_order_id = $item->plat_order_id;//1120;//
                $result = DB::select($checkSql, [$plat_order_id, $plat_order_id]);
                foreach($result as $res){
                    if ($res->ready == 1) {//所有商品都已派到仓点
                        $soc = new SupplierOrderController();
                        $isSend = $soc->thirdCreateOrder($plat_order_id, $res->from_plat_code);
                        //Log::alert('----------1--------'.$isSend.'---------1----------');
                        if ($isSend) {
                            $sku_sql = "SELECT gsk.sku_id FROM " . $db_prefix . "order_goods AS og
                                        INNER JOIN " . $db_prefix . "goods_sku AS gsk ON gsk.sku_id=og.sku_id
                                        WHERE og.plat_order_id=" . $plat_order_id . " and gsk.from_plat_code=" .  $res->from_plat_code ;
                            $sku_ids = DB::select($sku_sql);
                            $sids=[];
                            foreach($sku_ids as $skuid){
                                array_push($sids,$skuid->sku_id);
                            }

                            if($res->from_plat_code == 2002){
                                if ($isSend['code'] == 200) {//成功发送订单到第三方
                                    //$storeDgoods = StoreDeliverGoods::where('plat_order_id', $plat_order_id)->get();
                                    $storeDgoods = StoreDeliverGoods::where('plat_order_id', $plat_order_id)->whereIn('sku_id',$sids)->get();
                                    $to_plat_code = $res->from_plat_code;
                                    foreach ($storeDgoods as $dgood) {
                                        $dgood->to_plat_code = $to_plat_code;
                                        $dgood->is_send = 1;
                                        $dgood->save();
                                    }
                                }
                            }
                            if($res->from_plat_code == 2003){
                                if ($isSend['httpcode'] == 200 && isset($isSend['content']['orderForm'])) {//成功发送订单到第三方
                                    $storeDgoods = StoreDeliverGoods::where('plat_order_id', $plat_order_id)->whereIn('sku_id',$sids)->get();
                                    $to_plat_code = $res->from_plat_code;
                                    foreach ($storeDgoods as $dgood) {
                                        $dgood->to_plat_code = $to_plat_code;
                                        $dgood->is_send = 1;
                                        $dgood->save();
                                    }
                                }
                            }
                            if($res->from_plat_code == 2004){
                                if ($isSend['httpcode'] == 200 && isset($isSend['content']['orderForm'])) {//成功发送订单到第三方
                                    $storeDgoods = StoreDeliverGoods::where('plat_order_id', $plat_order_id)->whereIn('sku_id',$sids)->get();
                                    $to_plat_code = $res->from_plat_code;
                                    foreach ($storeDgoods as $dgood) {
                                        $dgood->to_plat_code = $to_plat_code;
                                        $dgood->is_send = 1;
                                        $dgood->save();
                                    }
                                }
                            }
                        }
                        Log::notice("third return....." . $plat_order_id . ".........");
                        Log::alert($isSend);
                    }
                }
                Log::alert($result);
            }else{
                Log::notice('weixin gift main order, not send to third ,id ' . $item->plat_order_id);
            }
        });


        //严选共享库存回调处理，更新相关的库存信息
        WyyxCountChange::created(function($item){
            $db_prefix = config('database')['connections']['mysql']['prefix'];
            $skuId=$item->skuId;
            $transferCount=$item->transferCount;
            $skuInfo=GoodsSku::where('from_plat_skuid',$skuId)->first();
            if($skuInfo){
                $storeSku=StoreGoodsSku::where('sku_id',$skuInfo->sku_id)->first();
                $num=$storeSku->storage_num;
                $tnum=$num+$transferCount;
                $storeSku->storage_num=$tnum;
                $storeSku->update();
                //更新处理状态为已处理
                $item->is_handled=1;
                $item->update();
            }
        });
        WyyxCountCheck::created(function($item){
            $db_prefix = config('database')['connections']['mysql']['prefix'];
            $skuId=$item->skuId;
            $count=$item->count;
            $skuInfo=GoodsSku::where('from_plat_skuid',$skuId)->first();
            if($skuInfo){
                $storeSku=StoreGoodsSku::where('sku_id',$skuInfo->sku_id)->first();
                //$num=$storeSku->storage_num;
                $storeSku->storage_num=$count;
                $storeSku->update();
                //更新处理状态为已处理
                $item->is_handled=1;
                $item->update();
            }
        });
       WyyxClose::created(function($item){
            $db_prefix = config('database')['connections']['mysql']['prefix'];
        });
        /*
        WyyxReopen::created(function($item){
            $db_prefix = config('database')['connections']['mysql']['prefix'];

        });*/

    }
	
	//B计划(考拉拆单派单)
	/*function WyKlsendManual($plat_order_id){
        $db_prefix = config('database')['connections']['mysql']['prefix'];
        if($plat_order_id) {
            $order = Order::where('plat_order_id',$plat_order_id)->first();
            //dd($order);
            if ($order->is_share_gifts == 0) {//如果订单是普通订单（is_share_gifts==0）则进行处理，否则（值为1）为微信送礼单，不做第三方派单
                $checkSql = "SELECT t1.from_plat_code,t1.cnt = t2.cnt AS ready FROM ( SELECT sku.from_plat_code, count(1) AS cnt FROM " . $db_prefix . "store_deliver_goods d,  " . $db_prefix . "goods_sku sku WHERE d.sku_id = sku.sku_id AND d.plat_order_id =?  GROUP BY sku.from_plat_code ) t1 LEFT JOIN ( SELECT sku.from_plat_code, count(1) AS cnt FROM  " . $db_prefix . "order_goods og,  " . $db_prefix . "goods_sku sku WHERE og.sku_id = sku.sku_id AND og.plat_order_id =?  GROUP BY sku.from_plat_code ) t2 ON t2.from_plat_code = t1.from_plat_code";
                //$plat_order_id = $item->plat_order_id;//1120;//
                $result = DB::select($checkSql, [$plat_order_id, $plat_order_id]);
                if ($result[0]->ready == 1) {//所有商品都已派到仓点
                    $soc = new SupplierOrderController();
                    $isSend = $soc->thirdCreateOrder($plat_order_id, $result[0]->from_plat_code);
                    if ($isSend) {
                        if($result[0]->from_plat_code == 2002){
                            if ($isSend['code'] == 200) {//成功发送订单到第三方
                                $storeDgoods = StoreDeliverGoods::where('plat_order_id', $plat_order_id)->get();
                                $to_plat_code = $result[0]->from_plat_code;
                                foreach ($storeDgoods as $dgood) {
                                    $dgood->to_plat_code = $to_plat_code;
                                    $dgood->is_send = 1;
                                    $dgood->save();
                                }
                            }
                        }
                        if($result[0]->from_plat_code == 2003){
                            if ($isSend['httpcode'] == 200 && isset($isSend['content']['orderForm'])) { //成功发送订单到第三方
                                $storeDgoods = StoreDeliverGoods::where('plat_order_id', $plat_order_id)->get();
                                $to_plat_code = $result[0]->from_plat_code;
                                foreach ($storeDgoods as $dgood) {
                                    $dgood->to_plat_code = $to_plat_code;
                                    $dgood->is_send = 1;
                                    $dgood->save();
                                }
                            }
                        }
                    }
                    Log::notice("third return....." . $plat_order_id . "....".$result[0]->from_plat_code.".....");
                    Log::alert($isSend);

                }
                Log::alert($result);
            } else {
                Log::notice('weixin gift main order, not send to third ,id ' . $plat_order_id);
            }
        }
    }*/
	

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
