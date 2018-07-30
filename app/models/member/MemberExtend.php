<?php

namespace App\models\member;

use Illuminate\Database\Eloquent\Model;

class MemberExtend extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
    */
    protected $table = 'member_extend';
    
    /**
     * 对应的主键(会员用户id)
     * @var string
     */
    protected $primaryKey = 'member_id';   

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    //protected $fillable = ['member_id','province_id','area_id','city_id','area_info','true_name','pid'];

    protected $guarded = [];
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;
    
}
