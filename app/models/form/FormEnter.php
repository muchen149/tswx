<?php

namespace App\models\form;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class FormEnter extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'form_enter';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 可以被批量赋值的属性.
     * @var array
     */
    protected $guarded = [];

}