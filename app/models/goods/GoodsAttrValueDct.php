<?php

namespace App\models\goods;

use Illuminate\Database\Eloquent\Model;

/**
 * 商品属性字典表
 * Class GoodsAttrValueDct
 * @package App\ynplat\models\goods
 */
class GoodsAttrValueDct extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'goods_attr_value_dct';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = ['name', 'attr_id', 'sort'];
}
