<?php
/**
 * Created by PhpStorm.
 * User: hengdegang
 * Date: 2016/10/27
 * Time: 9:45
 */
namespace App\controllers\ys;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\facades\Api;
use App\facades\LogInfoFacade;
use App\Http\Controllers\Controller;
use App\models\supplier\StoreDeliverGoods;

class StoreDeliverController extends BaseController
{
    /**
     * 仓点发货表增加一条记录
     * @param $store_deliver_goods array  仓点你发货信息
     * @return $deliverRecordId int 发货记录ID
     */
    public function addStoreDeliverGoods($store_deliver_goods)
    {
        /*$store_deliver_goods 仓点预发货信息有下列信息项，暂时不启用
            sku_image,sku_spec,spu_id,gc_id,gc_name,supplier_goods_price
        */
        $data = array();

        // 平台订单id、平台订单明细记录id、平台SKUid、平台SKU名称【含规格】
        $data['plat_order_id'] = $store_deliver_goods['plat_order_id'];
        $data['plat_order_detail_id'] = $store_deliver_goods['plat_order_detail_id'];
        $data['sku_id'] = $store_deliver_goods['sku_id'];
        $data['sku_name'] = $store_deliver_goods['sku_name'];
        $data['sku_image'] = $store_deliver_goods['sku_image'];
        $data['sku_spec'] = $store_deliver_goods['sku_spec'];
        $data['spu_id'] = $store_deliver_goods['spu_id'];
        $data['gc_id'] = $store_deliver_goods['gc_id'];
        $data['gc_name'] = $store_deliver_goods['gc_name'];

        // 供应商ID、供应商名称
        $data['supplier_id'] = $store_deliver_goods['supplier_id'];
        $data['supplier_name'] = $store_deliver_goods['supplier_name'];

        // 供应商订单ID、供应商订单明细ID
        $data['supplier_order_id'] = $store_deliver_goods['supplier_order_id'];
        $data['supplier_order_detail_id'] = $store_deliver_goods['supplier_order_detail_id'];

        // 供应商sku_id、供应价【供应商与平台间结算价】、零售价【平台和买家间结算价】
        $data['supplier_sku_id'] = $store_deliver_goods['supplier_sku_id'];
        $data['supplier_settlement_price'] = $store_deliver_goods['supplier_settlement_price'];
        $data['plat_settlement_price'] = $store_deliver_goods['plat_settlement_price'];

        // 仓点信息及发货量
        $data['store_id'] = $store_deliver_goods['store_id'];
        $data['store_name'] = $store_deliver_goods['store_name'];
        $data['store_address'] = $store_deliver_goods['store_address'];
        $data['number'] = $store_deliver_goods['deliver_number'];

        // 发货状态【23:待备货; 24:仓点拒单; 25:（已备货）待发货; 3:（已发货）待收货; 4:已收货】
        // $orderSupplier->update('supplier_order_state', 25);
        $data['deliver_state'] = 23;
        $data['create_time'] = time();         //创建时间

        $deliverRecordId = 0;
        $obj_storeDeliverGoods = StoreDeliverGoods::create($data);
        if ($obj_storeDeliverGoods) {
            // 自动拆单操作员为平台管理员(admin)
            $member_id = 1;
            $member_name = 'admin';

            $deliverRecordId = $obj_storeDeliverGoods->id;
            $content = '创建“' . $data['store_name'] .
                '”的发货记录，记录ID为：' . $deliverRecordId .
                ',记录状态为“待备货（23）”';
            LogInfoFacade::logOrderStore($obj_storeDeliverGoods, $content, $member_id, $member_name);
        }

        return $deliverRecordId;
    }
}