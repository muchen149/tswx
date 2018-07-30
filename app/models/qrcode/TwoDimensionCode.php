<?php

namespace App\models\qrcode;

use Illuminate\Database\Eloquent\Model;

class TwoDimensionCode extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'two_dimension_code';

    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 模型主键
     *
     * @var string
     */
    public $primaryKey = 'code_id';

}