<?php

namespace App\models\order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ServiceOrder extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'service_order';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [];

}
