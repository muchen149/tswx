<?php

namespace App\models\plat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class PlatSetting extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'plat_setting';

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
     * 获取平台设置的一级、二级、三级.....会员等级信息
     * @param $query
     * @return Builder
     */
    public function scopeMemberClassWithNotFree($query)
    {
        return $query->where('name', '<>', 'zero_class_member')
            ->where('name', 'like', '%_class_member')->where('name','<>','third_class_member');
    }
}