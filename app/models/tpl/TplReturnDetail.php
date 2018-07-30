<?php

namespace App\models\tpl;

use Illuminate\Database\Eloquent\Model;

class TplReturnDetail extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'tpl_return_detail';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = [
        'tpl_id', 'tpl_name', 'number_percent', 'part_amount_percent',
        'all_amount_percent', 'is_default','sort'
    ];

    /**
     * 获取退费详细表对应的退费模板
     */
    public function return_tpl()
    {
        return $this->belongsTo('App\ynplat\models\tpl\TplReturn', 'tpl_id', 'id');
    }


}
