<?php

namespace App\models\goods;

use Illuminate\Database\Eloquent\Model;


/**
 * 分类规格表
 * Class GoodsSpecDefine
 * @package App\ynplat\models\goods
 */
class GoodsSpecDefine extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'goods_spec_define';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 可以被批量赋值的属性
     * 规格名称    标题,形如“颜色”    关键词    描述   拼音编码    是否可用【0:不可用;1:可用;】   排序',
     * @var array
     */
    protected $fillable = [
        'name', 'caption', 'keywords', 'description', 'py_code', 'is_use', 'sort','data_type'
    ];


    public function empatyArreay()
    {
        return [
            'name' => null,
            'caption' => null,
            'keywords' => null,
            'description' => null,
            'is_use' => null,
            'sort' => null,
            'result' => []
        ];
    }

    /**
     * 规格所属分类
     */
    public function goodsClass()
    {
        return $this->belongsToMany(GoodsClass::class, "goods_class_spec_r", "sp_id", "gc_id");
    }


}
