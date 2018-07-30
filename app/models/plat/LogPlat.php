<?php

namespace App\models\plat;

use Illuminate\Database\Eloquent\Model;

/**
 * 日志model
 * Class LogPlate
 * @package App\ynplat\models
 */
class LogPlat extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'log_plat';

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
    protected $fillable = [
        'id', 'content', 'createtime', 'admin_name',
        'admin_id', 'ip', 'url'
    ];

}
