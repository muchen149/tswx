<?php

namespace App\models\marketing;

use Illuminate\Database\Eloquent\Model;

/**
 * 抽奖活动奖项设置
 * Class LotteryInfo
 * @package App\models\marketing;
 */
class LotteryInfo extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'lottery_info';

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
