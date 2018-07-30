<?php

namespace App\models\goods;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * 商品SKU表
 * Class GoodsClassExtend
 * @package App\ynplat\models\goods
 */
class GoodsSpuAttr extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'goods_spu_attr';

    /**
     * 对应的主键
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     *
     * @var array
     */
    protected $fillable = [
        'id', 'spu_id', 'gc_id', 'attr_id', 'attr_value'
        , 'attr_value_id',
    ];
}
