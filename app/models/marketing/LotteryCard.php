<?php

namespace App\models\marketing;

use Illuminate\Database\Eloquent\Model;

/**
 * 抽奖活动制卡
 * Class LotteryCard
 * @package App\models\marketing;
 */
class LotteryCard extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'lottery_card';

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

    /**
     * 模型主键
     *
     * @var string
     */
    public $primaryKey = 'card_id';

}
