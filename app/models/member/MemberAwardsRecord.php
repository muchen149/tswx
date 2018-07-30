<?php

namespace App\models\member;

use Illuminate\Database\Eloquent\Model;

class MemberAwardsRecord extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'member_awardsrecord';

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
    public $primaryKey = 'awardsrecord_id';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;
    
}