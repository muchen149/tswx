<?php

namespace App\models\member;

use Illuminate\Database\Eloquent\Model;

class MemberShipActivity extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'membership_activity';

    /**
     * 模型主键
     *
     * @var string
     */
    public $primaryKey = 'activity_id';

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

    public function scopeGetByGradeWithLineOn($query, $grade)
    {
        return $query->where('use_type', 2)
            ->where('supplier_id', 0)
            ->where('activity_state', 1)
            ->where('grade', $grade)
            ->whereNotIn('activity_id', [11,14,15])//线上会员卡活动170907
            ->select(
                'activity_id', 'activity_name', 'grade',
                'exp_date', 'exp_date_code', 'exp_date_name', 'price'
            );
    }
}
