<?php

namespace App\models\order;

use Illuminate\Database\Eloquent\Model;

class OrderGoods extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'order_goods';

    /**
     * 对应的主键
     *
     * @var string
     */
    protected $primaryKey = 'order_detail_id';
    
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [];
    
    
    public function scopeSelectInfoZd($query)
    {
        /*订单商品信息项说明
            1、佣金比例（commis_rate）：平台收取的服务费。
            2、运费（transport_cost）：订单中该商品的运费
            3、商品定价（goods_price）：买家下订单时的商品价格，不同类别的人，享受的价格不同；
            4、结算价格【优惠后价格，settlement_price】：打折时用到，此价格乘以数量，就是商品的结算金额。
                请注意：满减【购买2000立减100】，结算价格就是商品定价，优惠额存入商品优惠金额中。
            5、促销类型（promotions_type）：买家下订单时，享受的营销类型
            6、促销ID（promotions_id）：营销工具ID
         */
        return $query->select(
            'order_detail_id', 'order_detail_index', 'plat_order_id',
            'sku_id', 'sku_name','sku_title', 'sku_image',
            'sku_spec', 'spu_id', 'gc_id', 'gc_name',
            'commis_rate', 'transport_cost',
            'goods_price', 'settlement_price', 'number',
            'promotions_type', 'promotions_id','goods_state'
        );
    }
}
