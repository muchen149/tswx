<?php

namespace App\models\member;

use Illuminate\Database\Eloquent\Model;

class MemberRechargeCard extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'member_rechargecard';

    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [ ];

    /**
     * 模型主键
     *
     * @var string
     */
    public $primaryKey = 'rechargecard_id';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;
    
}