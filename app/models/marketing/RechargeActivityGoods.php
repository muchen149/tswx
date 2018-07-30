<?php

namespace App\models\marketing;

use Illuminate\Database\Eloquent\Model;

/**
 * 礼品卡活动商品表
 * Class RechargeActivityGoods
 * @package App\ynplat\models\marketing
 */
class RechargeActivityGoods extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'recharge_activity_goods';

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
