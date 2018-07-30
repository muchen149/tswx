<?php

namespace App\models\goods;

use Illuminate\Database\Eloquent\Model;

/**
 * 商品属性表
 * Class GoodsAttrDefine
 * @package App\ynplat\models\goods
 */
class GoodsAttrDefine extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'goods_attr_define';

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
    protected $fillable = [
        'name', 'caption', 'keywords', 'description', 'py_code', 'data_type',
        'input_type', 'value_list', 'default_value', 'is_required', 'is_use'
    ];
}
