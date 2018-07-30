<?php

namespace App\models\supplier;

use Illuminate\Database\Eloquent\Model;

class OrderExtendSupplier extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'order_extend_supplier';

    /**
     * 对应的主键
     * @var string
     */
    protected $primaryKey = 'supplier_order_id';
    
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
