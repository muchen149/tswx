<?php

namespace App\models\qrcode;

use Illuminate\Database\Eloquent\Model;

class TwoDimensionScanLog extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'two_dimension_scan_log';

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
    public $primaryKey = 'scan_id';

}