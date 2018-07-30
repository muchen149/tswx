<?php

namespace App\models\member;

use Illuminate\Database\Eloquent\Model;

class MemberElifeExtend extends Model
{

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'elife_member_extend';

    /**
     * 对应的主键(会员用户id)
     *
     * @var string
     */
    protected $primaryKey = 'elife_id';

    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [];

}
