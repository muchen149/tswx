<?php

namespace App\models\supplier;

use Illuminate\Database\Eloquent\Model;

class SupplierBaseinfo extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'supplier_baseinfo';
    /**
     * 对应的主键
     * @var string
     */
    protected $primaryKey = 'supplier_id';

    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [ ];

    
}
