<?php

namespace App\models\member;


use Illuminate\Database\Eloquent\Model;


/**
 * 会员成长体系
 * @author      :lishuo
 * Class        :MemberGrowthSystem
 * @package     :App\models\member
 */
class MemberGrowthSystem extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
    */
    protected $table = 'member_growth_system';
    
    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $guarded = [];
  
}
