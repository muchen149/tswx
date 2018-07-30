<?php

namespace App\models\elife;

use Illuminate\Database\Eloquent\Model;

/**
 * 会员卡活动表
 * Class RechargeActivity
 * @package App\ynplat\models\rechargecard
 */
class CouponShip extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'coupon_ship';

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
    public $primaryKey = 'id';

}
