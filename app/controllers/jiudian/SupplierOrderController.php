<?php
/**
 * 供应商订单管理类
 * @auth 杨瑞
 * Class SupplierOrderController
 * @package App\controllers
 */
namespace App\controllers\jiudian;

use App\lib\ApiResponseByHttp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\facades\Api;
use App\facades\LogInfoFacade;

use App\models\dct\DctArea;
use App\models\goods\GoodsSpu;

use App\models\order\Order;
use App\models\order\OrderGoods;

use App\models\supplier\OrderSupplier;
use App\models\supplier\OrderExtendSupplier;
use App\models\supplier\OrderGoodsSupplier;

use App\models\supplier\SupplierBaseinfo;
use App\models\supplier\SupplierGoodsSku;

use App\models\supplier\StoreBaseinfo;
use App\models\supplier\StoreGoodsSku;
use App\controllers\thirdapi\WyYxThirdApiController;

class SupplierOrderController extends BaseController
{
    protected $api;

    /**
     * 实例化的时候指定要使用的api类
     * 不指定则使用ApiResponseByHttp类
     * SupplierOrderController constructor.
     * @param string $api
     */
    public function __construct($api = '')
    {
        $this->api = empty($api) ? new ApiResponseByHttp() : $api;
    }


    /**
     * 调用支付接口 成功支付后回调url执行该方法
     * 根据平台订单(派单)
     * 该方法认为平台sku和供应商是一一对应的(以此完成自动派单)
     *
     * @param Request $request
     * @return mixed
     */
    public function orderSplit($plat_order_id)
    {
        // 平台订单拆分到供应商，操作员为系统管理员
        $member_id = 1;
        $member_name = 'admin';

        // 根据平台订单ID拆单
        $plat_order_id = (int)$plat_order_id;
        if ($plat_order_id == 0) {
            Log::alert('供应商拆单失败, 需要拆分的平台订单id为空 控制器:SupplierOrderController@orderSplit');
            return $this->api->responseMessage(60101, null, '传入的订单ID数据格式非法！');
        }

        // 根据订单状态验证订单是否完成付款
        // 订单状态（1:（已下单）待付款; 2:（已付款）待发货; 3:（已发货）待收货;
        $plat_order_sn = '';
        $sendee_area_id = 0;
        $sendee_city_id = 0;
        $plat_order = Order::select('plat_order_id', 'plat_order_sn', 'sendee_area_id','sendee_city_id')
            ->where('plat_order_id', $plat_order_id)
            ->where('plat_order_state', 2)
            ->first();
        if ($plat_order) {
            $plat_order_sn = $plat_order->plat_order_sn;
            $sendee_area_id = $plat_order->sendee_area_id;
            $sendee_city_id = $plat_order->sendee_city_id;
        } else {
            return $this->api->responseMessage(60101, null, '订单不存在或订单状态不匹配');
        }

        // 验证该订单是否已拆单
        $supplier_order_id = OrderSupplier::select('supplier_order_id')
            ->where('plat_order_id', $plat_order_id)
            ->value('supplier_order_id');
        if ($supplier_order_id) {
            // 60104 => '订单已拆单到供应商'
            return $this->api->responseMessage(60104);
        }

        // 获取平台订单商品SKU情况
        $plat_order_goods_lst = OrderGoods::select('plat_order_id',
            'order_detail_id as plat_order_detail_id',
            'sku_id', 'sku_name', 'sku_image',
            'sku_spec', 'spu_id', 'gc_id', 'gc_name',
            'commis_rate', 'transport_cost',
            'settlement_price as plat_settlement_price',
            'number as plat_settlement_number')
            ->where('plat_order_id', $plat_order_id)
            ->orderBy('order_detail_index')
            ->get()
            ->toArray();

        if (!$plat_order_goods_lst || count($plat_order_goods_lst) == 0) {
            // 60105 => '订单商品不存在'
            return $this->api->responseMessage(60105);
        }

        // -----------------------1、创建预发货二维数组($pre_deliver_goods_lst)--------
        $pre_deliver_goods_lst = array();
        foreach ($plat_order_goods_lst as $plat_record) {

            // 在平台订单商品SKU数组中，增加平台订单编号
            $plat_record['plat_order_sn'] = $plat_order_sn;

            $plat_sku_id = $plat_record['sku_id'];
            $plat_goods_number = $plat_record['plat_settlement_number'];

            // 当前平台 SKU 拟拆单到各仓点数组
            $store_deliver_goods_lst = $this->preDeliverGoods($plat_sku_id, $plat_goods_number,$sendee_area_id);
            foreach ($store_deliver_goods_lst as $store_record) {
                $pre_deliver_goods_lst[] = array_merge($plat_record, $store_record);
            }
        }

        //  -----------------------2、保存供应商订单（根据预发货二维数组将平台订单拆分为供应商订单）------
        // 对数组 $store_deliver_goods_lst 进行格式化处理，形成供应商订单拟用数据
        /*
            1、第一层，二维数组，格式如下：
            $supplier_data['supplier_140'] = array(supplier_order_info=>[],supplier_sku_info=>[]);

            2、第二层，第一个元素为一维数组，第二个元素为二维数组,
            第一个元素格式如下：
            $supplier_order_info = array(supplier_id=>910,supplier_name=>'***',
                                        plat_order_id=>142,plat_order_sn=>20021025001);

            第二个元素格式如下：
            $supplier_sku_info['supplier_sku_910'] = array(supplier_sku_id=>910,...,supplier_settlement_number=>3)
            信息项有：
                supplier_sku_id,plat_order_id,plat_order_detail_id,plat_settlement_price,
                sku_id,sku_name,sku_image,sku_spec,spu_id,gc_id,gc_name,
                commis_rate,transport_cost,
                supplier_goods_price,supplier_settlement_price,supplier_settlement_number
        */
        $supplier_order_id_lst = '';
        $pre_supplier_data = $this->formatSupplierData($pre_deliver_goods_lst);

        foreach ($pre_supplier_data as $supplier_data) {

            // 2.1、首先统计每个供应商订单的商品金额、运费等
            $supplier_order_info = $supplier_data['supplier_order_info'];   // 一维数组
            $supplier_sku_info = $supplier_data['supplier_sku_info'];       // 二维数组
            $this->StatOrderGoodsInfo($supplier_order_info, $supplier_sku_info, $sendee_city_id);

            // 2.2、保存每一个供应商订单并回填仓点预发货数组【主要是供应商发货单ID、发货单明细ID】
            // 生成该供应商订单
            $supplier_id = $supplier_order_info['supplier_id'];
            $supplier_order = $this->addSupplierOrder($supplier_order_info,
                $supplier_sku_info,
                $pre_deliver_goods_lst);
            $supplier_order_id = $supplier_order->supplier_order_id;

            // 2.3、供应商订单扩展表
            OrderExtendSupplier::create(
                array('supplier_order_id' => $supplier_order_id,
                    'plat_order_id' => $plat_order_id,
                    'deliver_goods_info' => '')
            );

            // 2.4、其它
            if ($supplier_order_id_lst == '') {
                $supplier_order_id_lst = (string)$supplier_order_id;
            } else {
                $supplier_order_id_lst .= ',' . (string)$supplier_order_id;
            }

            // 供应商订单状态：20:无效订单（再次派单）; 21:待分配到仓点;
            //  22:供应商拒单;23:仓点待备货; 24:仓点拒单;25:（已备货）仓点待发货;
            //  3:（已发货）待收货;4:（已收货）待评价; 9:已完成;-5:已退货;
            $supplier_order_state = 23;
            $content = '该订单来自于平台拆单，平台订单ID为“' . $plat_order_id . '”';
            LogInfoFacade::logOrderSupplier($supplier_id, $supplier_order_id, $content,
                $supplier_order_state, $member_id, $member_name);
        }

        // -----------------------3、保存仓点发货信息-----
        $obj_storeDeliver = new StoreDeliverController();
        foreach ($pre_deliver_goods_lst as $store_deliver_goods) {
            $obj_storeDeliver->addStoreDeliverGoods($store_deliver_goods);
        }

        // -----------------------4、保存平台订单拆单日志---------------------------
        if ($supplier_order_id_lst != '') {
            // 自动拆单操作员为平台管理员(admin)
            // 订单状态：1:（已下单）待付款; 2:（已付款）待发货; 3:（已发货）待收货; 4:（已收货）待评价; 9:已完成;
            //  -1:已取消; -2:已退单; -5:已退货; -9:已删除; ）'
            $plat_order_state = 2;
            $supplier_order_count = count($pre_supplier_data);
            $content = '自动拆单成功，平台订单“' . $plat_order_id . '”共拆分成' .
                $supplier_order_count . '个供应商订单，其单号分别为：' .
                $supplier_order_id_lst;
            LogInfoFacade::logOrderPlat($plat_order_id, $content, $plat_order_state, $member_id, $member_name);
        }

        // 拆单成功
        return $this->api->responseMessage(0, null, '自动拆单成功');
    }

    /**
     * 将平台订单的某个SKU及数量，拆分到若干个仓点
     * @param $plat_sku_id          int     平台SkuId
     * @param $plat_goods_number    int     平台订单数量
     * @param $sendee_area_id       int     收货人所在地区ID，例如：商丘市、海淀区
     * @return $store_deliver_goods_lst  array  当前商品拟拆分到发货站信息数组
     */
    public function preDeliverGoods($plat_sku_id, $plat_goods_number,$sendee_area_id = 0)
    {
        /* 预发货二维数组每一行的信息项如下：
            'supplier_id` int(10) '供应商id',
            `supplier_name` varchar(60) '供应商名称',
            `supplier_sku_id` bigint(20)  '供应商SKUid',
            `supplier_goods_price` decimal(10,2)  '供应商商品定价【商品供应价】',
            `supplier_settlement_price` decimal(10,2)  '供应价【供应商与平台间结算价】',
            `supplier_settlement_number` decimal(10,2)  '供应量【供应商与平台间数量】',

            'supplier_order_id` bigint(20) '供货商订单id',
            `supplier_order_detail_id` tinyint(3) '供货商订单明细记录id',

            `store_id` int(10)  '仓点ID',
            `store_name` varchar(60) '仓点名称',
            `store_address` varchar(200) '仓点地址'
            `deliver_number` smallint(5) '数量【仓点发货量】',
        */
        $store_deliver_goods_lst = array();
        if ($plat_sku_id <= 0 || $plat_goods_number <= 0) {
            return $store_deliver_goods_lst;
        }

        /*
            拆单满足的条件
                1、当前仓点发货区域覆盖买家收货人地点
                2、当前仓点库存可发货【有库存】,暂时不考虑库存条件 and a.storage_num > 0，不考虑超级供应商（supplier_id=1）
                3、仓点发货权重优先【请注意：yes_supplier_store_r.weight 值越小权重越大】、库存量优先、就近优先
        */
        $db_prefix = DB::connection()->getConfig('prefix');
        $sql = 'select
                  a.store_id,a.supplier_id,a.supplier_name,a.supplier_sku_id,a.storage_num,
                  b.store_name,b.province_id,b.area_id,b.city_id,b.area_info,b.address,
                  c.cost_price as supplier_goods_price,
                  d.weight,e.area_id_lst
                from ' . $db_prefix . 'store_goods_sku as a
                  inner join ' . $db_prefix . 'store_baseinfo as b on a.store_id=b.store_id
                  inner join ' . $db_prefix . 'goods_sku as c on a.sku_id = c.sku_id
                  inner join ' . $db_prefix . 'supplier_store_r as d on a.supplier_id = d.supplier_id
                    and a.store_id = d.store_id
                    and d.state = 1
                  left join ' . $db_prefix . 'store_send_area as e on a.store_id = e.store_id
                where a.sku_id = ?
                    and c.sku_id = ?
                    and a.supplier_id <> 1
                order by d.weight,a.storage_num desc ';
        $param = array($plat_sku_id, $plat_sku_id);
        $store_goods_info = DB::select($sql, $param);
        if (count($store_goods_info) > 0)
        {
            foreach ($store_goods_info as $row)
            {
                // 默认仓点的发货区域，覆盖不到收货人地址所在地区
                $is_can_deliver = 0;

                // 如果当前仓点没设置发货区域，默认全球任何地方都可配货
                $area_id_lst = trim($row->area_id_lst . '');
                if ($area_id_lst == '')
                {
                    $is_can_deliver = 1;
                }

                // 如果当前仓点设置了发货区域，要验证收货地址所在地区是否在仓点发货地区范围内
                $str_area_id = ',' . (string)$sendee_area_id . ',';
                $int_pos = strpos($area_id_lst,$str_area_id);
                if ($int_pos === 0)
                {
                    // 首字符匹配（0）处理为 1
                    $int_pos = 1;
                }

                if ($int_pos)
                {
                    $is_can_deliver = 1;
                }

                if ($is_can_deliver)
                {
                    // 有仓点可发货，并且数量足以发货【暂时不考虑库存管理】
                    $pre_deliver_info = array();

                    // 供应商涉及到的信息
                    $pre_deliver_info['supplier_id'] = $row->supplier_id;
                    $pre_deliver_info['supplier_name'] = $row->supplier_name;
                    $pre_deliver_info['supplier_sku_id'] = $row->supplier_sku_id;
                    $pre_deliver_info['supplier_goods_price'] = $row->supplier_goods_price;
                    $pre_deliver_info['supplier_settlement_price'] = $row->supplier_goods_price;
                    $pre_deliver_info['supplier_settlement_number'] = $plat_goods_number;

                    // 供应商订单及明细ID，初始化为 0，待保存供应商订单后，回填
                    $pre_deliver_info['supplier_order_id'] = 0;
                    $pre_deliver_info['supplier_order_detail_id'] = 0;

                    //  发货站信息
                    $pre_deliver_info['store_id'] = $row->store_id;
                    $pre_deliver_info['store_name'] = $row->store_name;
                    $pre_deliver_info['store_address'] = $row->area_info . $row->address;
                    $pre_deliver_info['deliver_number'] = $plat_goods_number;

                    // 不考虑库存管理，也不考虑下单后冻结库存，只要有一个仓点能发货，即退出
                    $store_deliver_goods_lst[] = $pre_deliver_info;
                    break;
                }
            }
        }

        // 如果没有合适的仓点发货，启用超级仓点发货
        if (count($store_deliver_goods_lst) == 0)
        {
            $pre_deliver_info = array();
            // 如果库存不存在，直接拆分给超级供应商及超级仓点
            /* 平台拆单能保证运行的基本条件是：
                1、超级供应商（supplier_id=1，supplier_name='超级供应商（自营）'）存在;
                2、超级仓点（store_id=1,store_name='超级仓点（自营）'）存在;
                3、超级供应商SKU存在（没有添加之）
            */
            // 获取超级供应商（supplier_id=1）的商品 SKU
            $supplier_id = 1;
            $obj_supplier_sku = SupplierGoodsSku::where('supplier_id', $supplier_id)
                ->where('sku_id', $plat_sku_id)
                ->first();

            // 获取超级仓点（store_id=1）的商品 SKU
            $store_id = 1;
            $obj_super_store = StoreBaseinfo::select('store_id', 'store_name', 'area_info', 'address')
                ->where('store_id', $store_id)
                ->first();

            if (!$obj_supplier_sku) {
                $obj_supplier_sku = $this->addSupplierSKU($supplier_id, $plat_sku_id);
            }

            // 将订单拆分给超级供应商及超级仓点
            if ($obj_supplier_sku && $obj_super_store) {
                $pre_deliver_info['supplier_id'] = $obj_supplier_sku->supplier_id;
                $pre_deliver_info['supplier_name'] = $obj_supplier_sku->supplier_name;
                $pre_deliver_info['supplier_sku_id'] = $obj_supplier_sku->supplier_sku_id;
                $pre_deliver_info['supplier_goods_price'] = $obj_supplier_sku->supply_price;
                $pre_deliver_info['supplier_settlement_price'] = $obj_supplier_sku->supply_price;
                $pre_deliver_info['supplier_settlement_number'] = $plat_goods_number;

                // 供应商订单及明细ID，初始化为 0，待保存供应商订单后，回填
                $pre_deliver_info['supplier_order_id'] = 0;
                $pre_deliver_info['supplier_order_detail_id'] = 0;

                //  发货站信息
                $pre_deliver_info['store_id'] = $obj_super_store->store_id;
                $pre_deliver_info['store_name'] = $obj_super_store->store_name;
                $pre_deliver_info['store_address'] = $obj_super_store->area_info . $obj_super_store->address;
                $pre_deliver_info['deliver_number'] = $plat_goods_number;
            }

            $store_deliver_goods_lst[] = $pre_deliver_info;
        }

        return $store_deliver_goods_lst;
    }

    /** 管理供应商SKU，如果不存在，增加之
     * @param $supplier_id int  供应商ID
     * @param $sku_id int  平台SKU，合法性由外部调用者验证
     * @return SupplierGoodsSku object
     */
    public function addSupplierSKU($supplier_id = 1, $plat_sku_id)
    {
        $obj_supplier = SupplierBaseinfo::select('supplier_id', 'supplier_name')
            ->where('supplier_id', $supplier_id)
            ->first();

        if ($obj_supplier) {
            $supplier_name = $obj_supplier->supplier_name;
        } else {
            // 供应商不存在，直接退出
            return null;
        }

        $db_prefix = DB::connection()->getConfig('prefix');
        $sql = 'select sku_id,sku_name,market_price,
                  cost_price as supply_price,b.gc_id,b.gc_name
                from ' . $db_prefix . 'goods_sku as a
                    inner join ' . $db_prefix . 'goods_spu as b on a.spu_id = b.spu_id
                where sku_id = :sku_id
                limit 0,1';
        $param = array('sku_id' => $plat_sku_id);
        $plat_sku_info = DB::select($sql, $param)[0];
        if ($plat_sku_info) {
            $data = array();
            $data['sku_id'] = $plat_sku_info->sku_id;
            $data['sku_name'] = $plat_sku_info->sku_name;
            $data['gc_id'] = $plat_sku_info->gc_id;
            $data['gc_name'] = $plat_sku_info->gc_name;

            $data['supplier_id'] = $supplier_id;
            $data['supplier_name'] = $supplier_name;

            $data['market_price'] = $plat_sku_info->market_price;
            $data['supply_price'] = $plat_sku_info->supply_price;
            $data['sku_code'] = '';
            $data['storage_num'] = 100000;

            // 供应商SKU增加一条记录
            $obj_supplierSku = SupplierGoodsSku::create($data);

            // 检查当前供应商在超级仓点（store_id=1）的SKU情况，如果不存在，增加之
            $obj_store_Sku = $this->addStoreSKU(1, $obj_supplierSku);
            return $obj_supplierSku;
        }

        return null;
    }

    /** 管理仓点SKU，如果不存在，增加之
     * @param $supplier_id int  供应商ID
     * @param $sku_id int  平台SKU
     * @return StoreGoodsSku object
     */
    public function addStoreSKU($store_id = 1, $obj_supplierSku)
    {
        $supplier_sku_id = $obj_supplierSku->supplier_sku_id;
        $obj_store_Sku = StoreGoodsSku::where('store_id', $store_id)
            ->where('supplier_sku_id', $supplier_sku_id)
            ->first();

        if (!$obj_store_Sku) {
            $data = array();
            $data['store_id'] = $store_id;
            $data['supplier_id'] = $obj_supplierSku->supplier_id;
            $data['supplier_name'] = $obj_supplierSku->supplier_name;
            $data['supplier_sku_id'] = $supplier_sku_id;

            $data['sku_id'] = $obj_supplierSku->sku_id;
            $data['sku_name'] = $obj_supplierSku->sku_name;
            $data['gc_id'] = $obj_supplierSku->gc_id;
            $data['gc_name'] = $obj_supplierSku->gc_name;

            // 仓点的SKU价格，暂时用该商品的进货价
            $data['sku_code'] = '';
            $data['price'] = $obj_supplierSku->supply_price;
            $data['storage_num'] = 100000;
            $data['storage_alarm'] = 100;

            // 仓点SKU增加一条记录
            $obj_store_Sku = StoreGoodsSku::create($data);
        }

        return $obj_store_Sku;
    }

    /** 根据仓点预发货信息，格式化相关供应商预拆单信息
     * @param $pre_deliver_goods_lst array  仓点预发货信息
     * @return $SupplierData array 供应商SKU信息
     */
    private function formatSupplierData($pre_deliver_goods_lst)
    {
        /*
            1、第一层，二维数组，格式如下：
            $supplier_data['supplier_140'] = array(supplier_order_info=>[],supplier_sku_info=>[]);

            2、第二层，第一个元素为一维数组，第二个元素为二维数组,
            第一个元素格式如下：
            $supplier_order_info = array(supplier_id=>910,supplier_name=>'***',plat_order_id=>142,plat_order_sn=>20021025001);

            第二个元素格式如下：
            $supplier_sku_info['supplier_sku_910'] = array(supplier_sku_id=>910,...,supplier_settlement_number=>3)
            信息项有：
                supplier_sku_id,plat_order_id,plat_order_detail_id,plat_settlement_price,
                sku_id,sku_name,sku_image,sku_spec,spu_id,gc_id,gc_name,
                commis_rate,transport_cost,
                supplier_goods_price,supplier_settlement_price,supplier_settlement_number
        */
        $supplier_data = array();
        foreach ($pre_deliver_goods_lst as $key => $item) {
            $supplier_sku_info = array();
            $supplier_id = $item['supplier_id'];
            $supplier_sku_id = $item['supplier_sku_id'];
            $deliver_number = $item['deliver_number'];

            $supplier_data_key = 'supplier_' . $supplier_id;
            $supplier_sku_info_key = 'supplier_sku_' . $supplier_sku_id;

            if (array_key_exists($supplier_data_key, $supplier_data)) {
                // 非第一次处理某个供应商的信息
                $supplier_sku_info = $supplier_data[$supplier_data_key]['supplier_sku_info'];
                if (array_key_exists($supplier_sku_info_key, $supplier_sku_info)) {
                    // 当前供应商的某个 supplier_sku_id 存在，商品数量累计
                    $supplier_sku_info[$supplier_sku_info_key]['supplier_settlement_number'] += $deliver_number;
                } else {
                    $sku_info = $this->filterDeliverSku($item);
                    if (count($sku_info)) {
                        $supplier_sku_info[$supplier_sku_info_key] = $sku_info;
                    }
                }

                // 更新供应商SKU信息
                $supplier_data[$supplier_data_key]['supplier_sku_info'] = $supplier_sku_info;
            } else {
                // 第一次处理某个供应商的信息
                $supplier_order_info = array('supplier_id' => $supplier_id,
                    'supplier_name' => $item['supplier_name'],
                    'plat_order_id' => $item['plat_order_id'],
                    'plat_order_sn' => $item['plat_order_sn']);
                $sku_info = $this->filterDeliverSku($item);
                if (count($sku_info)) {
                    $supplier_sku_info[$supplier_sku_info_key] = $sku_info;
                    $supplier_data[$supplier_data_key] = array('supplier_order_info' => $supplier_order_info,
                        'supplier_sku_info' => $supplier_sku_info);
                }
            }
        }

        return $supplier_data;
    }

    /** 过滤一行仓点预发货信息
     * @param $sku_info array  仓点预发货信息
     * @return $SupplierData array 供应商SKU信息
     */
    private function filterDeliverSku($deliver_info = array())
    {
        $supplier_sku_info = array();
        try {
            // supplier_sku_id,plat_order_id,plat_order_detail_id,plat_settlement_price
            $supplier_sku_info['supplier_sku_id'] = $deliver_info['supplier_sku_id'];
            $supplier_sku_info['plat_order_id'] = $deliver_info['plat_order_id'];
            $supplier_sku_info['plat_order_detail_id'] = $deliver_info['plat_order_detail_id'];
            $supplier_sku_info['plat_settlement_price'] = $deliver_info['plat_settlement_price'];

            // sku_id,sku_name,sku_image,sku_spec,spu_id,gc_id,gc_name
            $supplier_sku_info['sku_id'] = $deliver_info['sku_id'];
            $supplier_sku_info['sku_name'] = $deliver_info['sku_name'];
            $supplier_sku_info['sku_image'] = $deliver_info['sku_image'];
            $supplier_sku_info['sku_spec'] = $deliver_info['sku_spec'];
            $supplier_sku_info['spu_id'] = $deliver_info['spu_id'];
            $supplier_sku_info['gc_id'] = $deliver_info['gc_id'];
            $supplier_sku_info['gc_name'] = $deliver_info['gc_name'];

            // commis_rate,transport_cost
            $supplier_sku_info['commis_rate'] = $deliver_info['commis_rate'];
            $supplier_sku_info['transport_cost'] = 0;   // $deliver_info['transport_cost'];

            // supplier_goods_price,supplier_settlement_price,supplier_settlement_number
            $supplier_sku_info['supplier_goods_price'] = $deliver_info['supplier_goods_price'];
            $supplier_sku_info['supplier_settlement_price'] = $deliver_info['supplier_settlement_price'];
            $supplier_sku_info['supplier_settlement_number'] = $deliver_info['deliver_number'];
        } catch (Exception $e) {
            return array();
        }

        return $supplier_sku_info;
    }

    /**
     * 统计每个供应商订单的商品金额、运费等
     * @param $supplier_order_info array()  供应商订单信息，一维数组
     * @param $supplier_sku_info array()    供应商订单商品信息，二维数组
     * @param $sendee_city_id (收货城市ID，此地址计算运费用，默认0（不限制）)
     * @return true/false   执行成功是否标识
     */
    private function StatOrderGoodsInfo(& $supplier_order_info, & $supplier_sku_info, $sendee_city_id = 0)
    {
        // $supplier_order_info 格式为
        //  array(supplier_id=>910,supplier_name=>'***',plat_order_id=>142,plat_order_sn=>20021025001);
        $supplier_goods_amount_totals = 0.00;             // 商品结算金额合计
        $supplier_transport_cost_totals = 0.00;           // 运费结算金额合计
        $supplier_goods_preferential = 0.00;              // 商品优惠金额合计
        $supplier_transport_preferential = 0.00;         // 运费优惠金额合计

        $supplier_order_amount_totals = 0.00;            // 订单结算金额合计【商品金额+运费+服务费等其它】
        $supplier_payable_amount = 0.00;                 // 供应商订单应结金额

        // 传入的区县，不存在，默认未知
        if (!DctArea::find($sendee_city_id)) {
            $sendee_city_id = 0;
        }

        /*
            1、第一层，二维数组，格式如下：
            $supplier_data['supplier_140'] = array(supplier_order_info=>[],supplier_sku_info=>[]);

            2、第二层，第一个元素为一维数组，第二个元素为二维数组,
            第一个元素格式如下：
            $supplier_order_info = array(supplier_id=>910,supplier_name=>'***',plat_order_id=>142,plat_order_sn=>20021025001);

            第二个元素格式如下：
            $supplier_sku_info['supplier_sku_910'] = array(supplier_sku_id=>910,...,supplier_settlement_number=>3)
            信息项有：
                supplier_sku_id,plat_order_id,plat_order_detail_id,plat_settlement_price,
                sku_id,sku_name,sku_image,sku_spec,spu_id,gc_id,gc_name,
                commis_rate,transport_cost,
                supplier_goods_price,supplier_settlement_price,supplier_settlement_number
        */

        $spu_id = 0;
        $sku_id = 0;
        $obj_templateController = new TransportTemplateController();
        foreach ($supplier_sku_info as $key => $supplier_sku) {
            // $supplier_sku 信息项有：
            //      supplier_sku_id,plat_order_id,plat_order_detail_id,plat_settlement_price,
            //     sku_id,sku_name,sku_image,sku_spec,
            //     spu_id,gc_id,gc_name,commis_rate,transport_cost,
            //     supplier_goods_price,supplier_settlement_price,supplier_settlement_number
            $spu_id = $supplier_sku['spu_id'];
            $goods_number = $supplier_sku['supplier_settlement_number'];
            $transport_cost = 0.00;

            // ---------------------------1、计算运费---------------------------
            // 单个商品sku和spu
            $obj_spu = GoodsSpu::select('freight', 'tpl_transport_id')
                ->where('spu_id', $spu_id)
                ->first();
            if ($obj_spu) {
                // 计算商品运费
                // 固定运费优先，模板运费次之
                $transport_cost = $obj_spu->freight;
                if ($transport_cost == 0.00) {
                    $tpl_transport_id = $obj_spu->tpl_transport_id;
                    if ($tpl_transport_id) {
                        // 根据商品SpuID、数量(number)、到达区县ID(sendee_city_id)计算运费
                        $transport_cost = $obj_templateController->getTransportCost($tpl_transport_id, $goods_number, $sendee_city_id);
                    }
                }
            }

            // 运费(商品运费金额)
            $supplier_sku[$key]['transport_cost'] = $transport_cost;
            $supplier_transport_cost_totals = bcadd($supplier_transport_cost_totals, $transport_cost, 2);


            // ---------------------------- 2、计算商品 SKU 结算金额、优惠金额 -------------------------
            $goods_price = $supplier_sku['supplier_settlement_price'];
            $goods_amount = 0.00;                           // 商品结算金额
            $goods_preferential = 0.00;                     // 商品优惠金额
            $transport_preferential = 0.00;                 // 运费优惠金额

            // 两个小数相乘，保留 2 位小数
            $goods_amount = bcmul($goods_price, $goods_number, 2);

            // 合计商品结算金额、商品优惠金额、运费优惠金额
            $supplier_goods_amount_totals = bcadd($supplier_goods_amount_totals, $goods_amount, 2);
            // $supplier_goods_preferential = bcadd($supplier_goods_preferential, $goods_preferential, 2) ;
            // $supplier_transport_preferential = bcadd($supplier_transport_preferential,$transport_preferential, 2);
            // 商品佣金比例(供应商支付给平台的服务费率)，将来通过函数获取其值，用于平台与供应商的结算
            $supplier_sku[$key]['commis_rate'] = 0.00;
        }

        // -------------------------------- 3、统计供应商订单金额 ----------------
        // 订单结算金额 = 商品结算金额 + 运费结算金额 + 其它
        $supplier_order_amount_totals = bcadd($supplier_goods_amount_totals, $supplier_transport_cost_totals, 2);

        // 供应商订单应结金额合计 = 订单结算金额 - 商品优惠金额 - 运费优惠金额
        $supplier_payable_amount = bcsub($supplier_order_amount_totals, $supplier_goods_preferential, 2);
        $supplier_payable_amount = bcsub($supplier_payable_amount, $supplier_transport_preferential, 2);

        // 将供应商订单统计信息保存到临时数组中
        $supplier_order_info['supplier_goods_amount_totals'] = $supplier_goods_amount_totals;
        $supplier_order_info['supplier_transport_cost_totals'] = $supplier_transport_cost_totals;
        $supplier_order_info['supplier_goods_preferential'] = $supplier_goods_preferential;
        $supplier_order_info['supplier_transport_preferential'] = $supplier_transport_preferential;

        $supplier_order_info['supplier_order_amount_totals'] = $supplier_order_amount_totals;
        $supplier_order_info['supplier_payable_amount'] = $supplier_payable_amount;

        return true;
    }

    /**
     * 增加供应商订单
     * @param $supplier_order_info array()  一维数组，供应商订单统计信息
     * @param $supplier_sku_info array()  二维数组，供应商订单商品信息【数量、价格等】
     * @param $pre_deliver_goods_lst array()  二维数组，仓点你发货信息，主要回填供应商订单ID、订单明细ID
     * @param $supplier (供应商)
     */
    public function addSupplierOrder($supplier_order_info, $supplier_sku_info, & $pre_deliver_goods_lst)
    {
        //  1、供应商订单信息（$supplier_order_info）
        // array(supplier_id=>910,supplier_name=>'***',plat_order_id=>142,plat_order_sn=>20021025001
        //  supplier_goods_amount_totals=>0.00,supplier_transport_cost_totals=>0.00,
        //  supplier_goods_preferential=>0.00,supplier_transport_preferential=>0.00,
        //  supplier_order_amount_totals=>0.00,supplier_payable_amount=>0.00
        $supplier_id = $supplier_order_info['supplier_id'];
        $supplier_order_info['supplier_order_sn'] = $this->getSupplierOrderSn($supplier_id);

        // '订单状态（20:无效订单（再次派单）; 21:待分配到仓点; 22:供应商拒单;23:仓点待备货; 24:仓点拒单;25:（已备货）仓点待发货;
        //  3:（已发货）待收货;4:（已收货）待评价; 9:已完成;-5:已退货;',
        $supplier_order_info['supplier_order_state'] = 23;

        // '评价状态(0:未评价; 1:买家已评论; 2:卖家已评论; 3:双方已互评; 9:已过期未评价)',
        $supplier_order_info['evaluation_state'] = 0;

        // 供应商订单生成时间
        $supplier_order_info['create_time'] = time();

        // -------------------------------1、生成供应商订单---------------------
        $supplier_order_id = 0;
        $supplier_order = OrderSupplier::create($supplier_order_info);

        if ($supplier_order) {
            $supplier_order_id = $supplier_order->supplier_order_id;
        } else {
            return Api::responseMessage(50002);
        }

        // -------------------------------2、生成供应商订单商品明细表---------------------
        /* $supplier_sku 信息项有：
            supplier_sku_id,plat_order_id,plat_order_detail_id,plat_settlement_price,
            sku_id,sku_name,sku_image,sku_spec,spu_id,gc_id,gc_name,
            commis_rate,transport_cost,
            supplier_goods_price,supplier_settlement_price,supplier_settlement_number
        */
        $record_index = 1;
        foreach ($supplier_sku_info as $key => $supplier_sku) {
            $supplier_sku['record_index'] = $record_index;
            $supplier_sku['supplier_order_id'] = $supplier_order_id;

            // 移除数组中的元素 supplier_settlement_number
            $supplier_sku['number'] = $supplier_sku['supplier_settlement_number'];
            unset($supplier_sku['supplier_settlement_number']);

            // 生成供应商商品明细表
            $order_goods_supplier = OrderGoodsSupplier::create($supplier_sku);
            if ($order_goods_supplier) {
                $supplier_sku_id = $order_goods_supplier->supplier_sku_id;
                $supplier_order_detail_id = $order_goods_supplier->record_id;

                // 回填仓点你发货信息的项：supplier_order_id、supplier_order_detail_id
                foreach ($pre_deliver_goods_lst as & $row) {
                    if ($row['supplier_id'] == $supplier_id &&
                        $row['supplier_sku_id'] == $supplier_sku_id
                    ) {
                        $row['supplier_order_id'] = $supplier_order_id;
                        $row['supplier_order_detail_id'] = $supplier_order_detail_id;
                    }
                }
            }

            $record_index++;
        }

        // 返回供应商订单
        return $supplier_order;
    }

    /**
     * 生成供应商订单编号
     * @param $supplierId  int  供应商ID
     * @return $supplier_order_sn string
     *      格式：6位供应商编码 + 4位年度 + 2位月份 + 6位序列号，共18位字符长度，形如：000001201609000001
     */
    public function getSupplierOrderSn($supplierId)
    {
        $sql = 'call usp_get_supplier_order_sn(?,@out_orderSn,@returninfo);';
        $result_set = DB::statement($sql, [$supplierId]);
        if ($result_set) {
            $row = DB::select('select @out_orderSn,@returninfo ');
            $temp = (array)$row[0];

            if (!$temp['@out_orderSn']) {
                return 0;
            } else {
                return $temp['@out_orderSn'];
            }
        }

        return 0;
    }

    /**
     * 调用第三方API下单
     *
     */
    public function thirdCreateOrder($plat_order_id, $from_plat_code)//($supplier_order_id, $from_plat_code)
    {
        $db_prefix = DB::connection()->getConfig('prefix');
        /*$order_sql = "SELECT os.supplier_order_id,os.supplier_order_sn,os.supplier_order_amount_totals,os.supplier_transport_cost_totals,
                      os.create_time AS pay_time,o.create_time,o.sendee_province_id,o.sendee_area_id,o.sendee_city_id,o.sendee_address_info
                      FROM " . $db_prefix . "order_supplier AS os
                      INNER JOIN " . $db_prefix . "order AS o ON os.plat_order_id=o.plat_order_id
                      WHERE os.supplier_order_id=" . $supplier_order_id;
        $order = (array)DB::select($order_sql)[0];

        $sku_sql = "SELECT gsk.from_plat_skuid,og.number,og.sku_name,og.plat_settlement_price AS goods_price
                    FROM " . $db_prefix . "order_goods_supplier AS og
                    INNER JOIN " . $db_prefix . "goods_sku AS gsk ON gsk.sku_id=og.sku_id
                    WHERE og.supplier_order_id=" . $supplier_order_id ." and gsk.from_plat_code=" .$from_plat_code;
        */
        //$order = Order::where('plat_order_sn',$plat_order_sn)->first();
        $order = Order::find($plat_order_id);
        $sku_sql = "SELECT gsk.from_plat_skuid,og.number,og.sku_name,og.goods_price FROM " . $db_prefix . "order_goods AS og
                    INNER JOIN " . $db_prefix . "goods_sku AS gsk ON gsk.sku_id=og.sku_id
                    WHERE og.plat_order_id=" . $order->plat_order_id;
        $orderSkus = DB::select($sku_sql);

        $order->orderSkus = $orderSkus;

        $orderSkus = DB::select($sku_sql);
        $order['orderSkus'] = $orderSkus;

        switch ($from_plat_code) {
            case '2002':
                $wyyxThirdApiController = new WyYxThirdApiController();
                $result = $wyyxThirdApiController->createOrder($order);
                break;
            default:
                $result="";
                break;
        }
        return $result;
    }

    /**
     * 调用第三方API确认收货
     *
     */
    public function thirdConfirmOrder($plat_order_id, $from_plat_code)//($supplier_order_id, $from_plat_code)
    {
        $db_prefix = DB::connection()->getConfig('prefix');
        $order = Order::find($plat_order_id);
        $packageIdsql="SELECT distinct d.packageId FROM " . $db_prefix . "store_deliver_goods d WHERE d.plat_order_id=? and d.supplier_id=2";
        $cfmtime=Carbon::now()->format('Y-m-d H:i:s');
        switch ($from_plat_code) {
            case '2002':
                $packageId = DB::select($packageIdsql,[$order->plat_order_id]);
                $wyyxThirdApiController = new WyYxThirdApiController();
                $result = $wyyxThirdApiController->confirmOrder($order->plat_order_sn,$packageId[0]->packageId,$cfmtime);
                break;
            default:
                $result="";
                break;
        }
        return $result;

    }

}
