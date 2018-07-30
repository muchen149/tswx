<?php

namespace App\models\wyyx;

use Illuminate\Database\Eloquent\Model;

class WyyxReopen extends Model
{
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'wyyx_reopen';
    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [ ];
}
