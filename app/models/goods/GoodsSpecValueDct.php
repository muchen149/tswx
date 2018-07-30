<?php

namespace App\models\goods;

use Illuminate\Database\Eloquent\Model;


/**
 * 分类规格字典表
 * Class GoodsSpecValueDct
 * @package App\ynplat\models\goods
 */
class GoodsSpecValueDct extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'goods_spec_value_dct';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 可以被批量赋值的属性
     * 规格值名称    所属规格ID   规格颜色    规格值排序
     * @var array
     */
    protected $fillable = [
        'name', 'sp_id', 'sp_color', 'sort','is_use'
    ];
    

}
