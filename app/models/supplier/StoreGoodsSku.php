<?php

namespace App\models\supplier;

use Illuminate\Database\Eloquent\Model;

class StoreGoodsSku extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'store_goods_sku';
    
    /**
     * 对应的主键
     * @var string
     */
    protected $primaryKey = 'store_sku_id';


    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [ ];

   
}
