<?php

namespace App\models\supplier;

use Illuminate\Database\Eloquent\Model;

class StoreBaseinfo extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'store_baseinfo';
    
    /**
     * 对应的主键
     * @var string
     */
    protected $primaryKey = 'store_id';

    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [ ];

    
}
