<?php
/**
 * Created by PhpStorm.
 * User: shuo
 * Date: 16-9-8
 * Time: 下午3:05
 */

namespace App\lib;

use App\models\order\LogOrderPlat;
use App\models\supplier\LogOrderStore;
use App\models\supplier\LogOrderSupplier;
use Illuminate\Support\Facades\Auth;

/**
 * 所有日志类
 * @author      :lishuo
 * Class        :LogInfo
 * @package     :App\lib
 */
class LogInfo
{


    /**
     * 仓点发货日志表
     * @param $storeDeliverGoods //仓点发货（对象）
     * @param $content //文字描述
     */
    public function logOrderStore($storeDeliverGoods, $content, $member_id = 0, $member_name = '')
    {
        if ($member_id == 0 || $member_name == '') {
            $member_id = Auth::user()->member_id;
            $member_name = Auth::user()->nick_name;
        }

        $data = [
            'store_id' => $storeDeliverGoods->store_id,                                         //仓点ID
            'supplier_id' => $storeDeliverGoods->supplier_id,                                   //供应商id
            'supplier_name' => $storeDeliverGoods->supplier_name,                               //供应商名称
            'supplier_order_id' => $storeDeliverGoods->supplier_order_id,                   //供应商订单id
            'supplier_order_detail_id' => $storeDeliverGoods->supplier_order_detail_id,   //供货商订单明细记录id
            'goods_name' => $storeDeliverGoods->sku_name,                                   //商品名称
            'user_id' => $member_id,
            'user_name' => $member_name,
            'create_time' => time(),
            'content' => $content,
            'order_state' => $storeDeliverGoods->deliver_state,
        ];

        LogOrderStore::create($data);
    }

    /**
     * 供应商订单处理日志表
     * @param $supplier_id // 供应商id
     * @param $order_id  // 供应商订单id
     * @param $content // 文字描述
     * @param $state // 操作后订单状态
     */
    public function logOrderSupplier($supplier_id, $order_id, $content, $state, $member_id = 0, $member_name = '')
    {
        if ($member_id == 0 || $member_name == '') {
            $member_id = Auth::user()->member_id;
            $member_name = Auth::user()->nick_name;
        }

        $data = [
            'supplier_id' => $supplier_id,
            'order_id' => $order_id,
            'content' => $content,
            'create_time' => time(),

            'user_id' => $member_id,
            'user_name' => $member_name,
            'order_state' => $state,
        ];

        LogOrderSupplier::create($data);
    }


    /**
     * 创建一条平台订单操作日志
     * @param $order_id  int 订单ID
     * @param $member int    当前操作员
     * @param $content string   操作内容
     * @param $order_state [订单状态（1:（已下单）待付款; 2:（已付款）待发货; 3:（已发货）待收货;
     *                              4:（已收货）待评价; 9:已完成;
     *                              -1:已取消; -2:已退单; -5:已退货; -9:已删除; ]
     * @param $member_id int        如果没传入 $member ，启用该操作者ID
     * @param $member_name string  如果没传入 $member ，启用该操作者名称
     * @return bool
     */
    public function logOrderPlat($order_id, $content, $order_state, $member_id = 0, $member_name = '')
    {
        if ($member_id == 0 || $member_name == '') {
            $member_id = Auth::user()->member_id;
            $member_name = Auth::user()->nick_name;
        }

        // 平台订单操作日志
        $log = LogOrderPlat::create([
            'order_id' => $order_id,
            'content' => $content,
            'create_time' => time(),
            'user_id' => $member_id,
            'user_name' => $member_name,
            'order_state' => $order_state
        ]);

        if ($log->exists) {
            return true;
        }

        return false;
    }
}