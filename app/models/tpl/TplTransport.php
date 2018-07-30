<?php

namespace App\models\tpl;

use Illuminate\Database\Eloquent\Model;

class TplTransport extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'tpl_transport';

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
    protected $fillable = ['name', 'title', 'py_code', 'sort','state','isFreeDelivery','limitNum'];

}
