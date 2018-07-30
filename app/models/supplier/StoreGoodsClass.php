<?php

namespace App\models\supplier;

use Illuminate\Database\Eloquent\Model;

class StoreGoodsClass extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'store_goods_class_r';

    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [ ];

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;
}
