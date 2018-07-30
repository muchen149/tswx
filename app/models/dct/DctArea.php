<?php

namespace App\models\dct;

use Illuminate\Database\Eloquent\Model;

/**
 * 区域字典表
 * Class GoodsAttrDefine
 * @package App\ynplat\models\goods
 */
class DctArea extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'dct_area';

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
        'name', 'pid', 'sort', 'deep', 'region'
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeSelectZd($query)
    {
        return $query->select('id', 'name', 'pid');
    }
}
