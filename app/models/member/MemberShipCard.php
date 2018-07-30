<?php

namespace App\models\member;

use Illuminate\Database\Eloquent\Model;

/**
 * 会员卡
 * Class RechargeCard
 * @package App\ynplat\models\rechargecard
 */
class MemberShipCard extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'membership_card';

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
