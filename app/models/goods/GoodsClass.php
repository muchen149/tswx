<?php

namespace App\models\goods;

use Illuminate\Database\Eloquent\Model;


/**
 * 商品分类表
 * Class GoodsClass
 * @package App\ynplat\models\goods
 */
class GoodsClass extends Model
{
    /**
     * 对应的表名称
     * @var string
     */
    protected $table = 'goods_class';


    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'id';


    /**
     * laravel自动生成的开始时间和更新时间禁止掉
     * @var bool
     */
    public $timestamps = false;


    /**
     * 可填充的数据
     * @var array
     */
    protected $fillable = [
        'name','title','keywords','description','py_code','pid','sort','state'
    ];


    /**
     * 莫个分类下的所有品牌
     */
    public function goodsbrand()
    {
        return $this->belongsToMany(GoodsBrand::class,"goods_class_brand_r","gc_id","gb_id");
    }


}
