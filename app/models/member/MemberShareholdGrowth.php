<?php

namespace App\models\member;


use Illuminate\Database\Eloquent\Model;


/**
 * 股东成长体系
 * @author      :lishuo
 * Class        :MemberShareholdGrowth
 * @package     :App\models\member
 */
class MemberShareholdGrowth extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
    */
    protected $table = 'member_sharehold_growth';
    
    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $guarded = [];
  
}
