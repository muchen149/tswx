<?php

namespace App\models\goods;

use Illuminate\Database\Eloquent\Model;


/**
 * 品牌表
 * Class GoodsBrand
 * @package App\ynplat\models\goods
 */
class GoodsClassBrand extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'goods_class_brand_r';

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
        "id","gc_id","gb_id","sort","state"
    ];
}
