<?php

namespace App\models\goods;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * 商品SKU表
 * Class GoodsClassExtend
 * @package App\ynplat\models\goods
 */
class GoodsSpu extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'goods_spu';

    /**
     * 对应的主键
     * @var string
     */
    protected $primaryKey = 'spu_id';

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
        'spu_id', 'spu_name', 'ad_info', 'ad_link_title', 'ad_link_url'
        , 'gc_id_1', 'gc_id_2', 'gc_id_3', 'gc_id', 'gc_name'
        , 'gb_id', 'gb_name', 'spu_attr', 'spec_name', 'spec_value'
        , 'main_image', 'web_content', 'mobile_content', 'keywords'
        , 'producter', 'spu_code'
        , 'spu_plat_price', 'spu_market_price', 'spu_cost_price', 'spu_groupbuy_price', 'spu_trade_price'
        , 'spu_partner_price','spu_points_limit','spu_storage_num', 'spu_storage_alarm', 'areaid_1', 'areaid_2'
        , 'freight', 'tpl_transport_id', 'tpl_transport_name', 'tpl_return_id', 'tpl_return_name'
        , 'plateid_top', 'plateid_bottom', 'is_commend', 'invoice_type', 'offline_time'
        , 'offline_time', 'state', 'state_remark', 'py_code', 'operator'
        , 'created_at', 'updated_at'
    ];


    /**
     * 根据会员种类获取 SPU 的价格
     * 传入参数：
     *      $query  查询结果
     *      $grade  会员类别
     *      $spu_id SPU ID
     *  传出参数：价格
     */
    public function scopePrice($query, $grade, $spu_id)
    {
        /* 会员类别(grade)【10:普通会员;20:黄金会员;30:钻石会员;40:黑卡VIP】
        spu 价格体系：
            spu_market_price	市场价【通过模型计算的国内市场均价，指导价】
        	spu_plat_price		平台价格【官网价、官网零售价】
        	spu_groupbuy_price	团购价
          	spu_trade_price		批发价
          	spu_partner_price	(分销伙伴)抄底价【成本价 + 管理费】
        	spu_cost_price		成本价【平台进价 + 运费】
        */
        $price = 'spu_plat_price';
        switch ($grade) {
            case 10 :
                break;
            case 20 :
                $price = 'spu_groupbuy_price';
                break;
            case 30 :
                $price = 'spu_trade_price';
                break;
            case 40 :
                $price = 'spu_partner_price';
                break;
        }

        return $query->where('spu_id', $spu_id)->value($price);
    }
}
