<?php

namespace App\models\goods;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * 商品SKU表
 * Class GoodsClassExtend
 * @package App\ynplat\models\goods
 */
class GoodsSku extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'goods_sku';

    /**
     * 对应的主键
     * @var string
     */
    protected $primaryKey = 'sku_id';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     *
     * @var array
     */
    protected $fillable = [
        'sku_id', 'sku_name','sku_title', 'spu_id', 'color_id', 'sku_spec'
        , 'price', 'market_price', 'cost_price', 'groupbuy_price', 'groupbuy_lower_limit'
        , 'trade_price', 'trade_lower_limit', 'partner_price','points_limit'
        , 'sku_code', 'sku_storage_num', 'sku_storage_alarm'
        , 'click_count', 'collect_count', 'salenum_count', 'evaluation_count', 'created_at'
        , 'updated_at'
    ];

    /**
     * 购物车展示字段
     * @杨瑞
     * @param $query
     * @param $sku_id
     * @return mixed
     */
    public function scopeSelectZdBySkuId($query, $sku_id)
    {
        // sku价格体系
        // 市场价(国内市场价格) 	market_price(spu_market_price)
        // 零售价(平台价格、官价)	price(spu_plat_price)
        // 团购价			        groupbuy_price(spu_groupbuy_price)
        // 批发价			        trade_price(spu_trade_price)
        // (分销伙伴)抄底价		    partner_price(spu_partner_price)
        // 成本价(进货价)		    cost_price(spu_cost_price)
        return $query->select('sku_id', 'spu_id', 'sku_name','sku_title',
                                'market_price', 'price', 'groupbuy_price','trade_price',
                                'partner_price','points_limit','sku_spec')
                        ->where('sku_id', $sku_id);
    }


    /**
     * 根据会员种类获取SKU的价格
     * @杨瑞
     * @param $query
     * @param $grade
     * @param $sku_id
     * @return mixed
     */
    public function scopePrice($query, $grade, $sku_id)
    {
        /* 会员类别(grade)【10:普通会员;20:黄金会员;30:钻石会员;40:黑卡VIP】
        SKU 价格体系：
            market_price		市场价【通过模型计算的国内市场均价，指导价】
        	price			    商品价格【官网价、官网零售价】
        	groupbuy_price		团购价
          	trade_price		    批发价
          	partner_price		(分销伙伴)抄底价【成本价 + 管理费】
        	cost_price		    成本价【平台进价 + 运费】
        */
        $price = 'price';
        switch ($grade) {
            case 10 :
                break;
            case 20 :
                $price = 'groupbuy_price';
                break;
            case 30 :
                $price = 'trade_price';
                break;
            case 40 :
                $price = 'partner_price';
                break;
        }

        return $query->where('sku_id', $sku_id)->value($price);
    }
}
