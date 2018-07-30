<?php


namespace App\models\cjk;

use Illuminate\Database\Eloquent\Model;

class CjkCrowdFundingValue extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'cjk_crowd_funding_value';

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
}
