<?php

namespace App\models\member;


use Illuminate\Database\Eloquent\Model;

class MemberAddress extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
    */
    protected $table = 'member_address';
    
    /**
     * 对应的主键(会员用户id)
     * @var string
     */
    protected $primaryKey = 'address_id';

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * 选择获取字段
     * @杨瑞
     * @param $query
     * @return mixed
     */
    public function scopeSelectZd($query)
    {
        return $query->select(
            'address_id', 'member_id', 'recipient_name',
            'province_id', 'area_id', 'city_id', 'area_info',
            'address', 'email', 'mobile', 'is_default', 'use_state'
        );
    }
}
