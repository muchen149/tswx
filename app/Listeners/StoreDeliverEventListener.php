<?php

namespace App\Listeners;

use App\Events\StoreDeliverEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\models\order\Order;
use App\models\supplier\StoreDeliverGoods;
use Illuminate\Support\Facades\DB;
use App\controllers\SupplierOrderController;


class StoreDeliverEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  StoreDeliverEvent  $event
     * @return void
     */
    public function handle(StoreDeliverEvent $event)
    {
        //
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
        $checkSql="SELECT t1.from_plat_code,t1.cnt = t2.cnt AS ready FROM ( SELECT sku.from_plat_code, count(1) AS cnt FROM ". $db_prefix ."store_deliver_goods d,  ". $db_prefix ."goods_sku sku WHERE d.sku_id = sku.sku_id AND d.plat_order_id =?  GROUP BY sku.from_plat_code ) t1 LEFT JOIN ( SELECT sku.from_plat_code, count(1) AS cnt FROM  ". $db_prefix ."order_goods og,  ". $db_prefix ."goods_sku sku WHERE og.sku_id = sku.sku_id AND og.plat_order_id =?  GROUP BY sku.from_plat_code ) t2 ON t2.from_plat_code = t1.from_plat_code";
        $plat_order_id = $event->getPlatOrderID();//1120;//
        $result=DB::select($checkSql,[$plat_order_id,$plat_order_id]);
        if($result[0]->ready==1){//所有商品都已派到仓点
            $soc=new SupplierOrderController();
            $isSend=$soc->thirdCreateOrder($plat_order_id,$result[0]->from_plat_code);
            if ($isSend['code'] == 200) {//成功发送订单到第三方
                $storeDgoods=StoreDeliverGoods::where('plat_order_id',$plat_order_id)->get();
                $to_plat_code=$result[0]->from_plat_code;
                foreach($storeDgoods as $dgood){
                    $dgood->to_plat_code=$to_plat_code;
                    $dgood->is_send=1;
                    $dgood->save();
                }
            }
        }
        Log::alert($result);
        Log::notice("this is an event, handled by listener.....". $plat_order_id. ".........");
    }
}

