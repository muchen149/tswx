<?php

namespace App\models\goods;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 * 商品分类-运费模板 表
 * Class GoodsClassExtend
 * @package App\ynplat\models\goods
 */
class GoodsClassExtend extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'goods_class_extend';

    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'gc_id';
    
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
    protected $fillable=[
        'gc_id', 'tpl_transport_id', 'tpl_transport_name', 'freight',
        'tpl_return_id', 'tpl_return_name'
    ];
}
