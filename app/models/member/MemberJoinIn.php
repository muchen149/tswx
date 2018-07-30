<?php

namespace App\models\member;

use Illuminate\Database\Eloquent\Model;

class MemberJoinIn extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'member_joinin';

    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [ ];

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;
    
}