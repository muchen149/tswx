<?php

namespace App\models\goods;

use Illuminate\Database\Eloquent\Model;

/**
 * 管家服务分类表
 * Serve GoodsServe
 * @package App\ynplat\models\goods
 */
class GoodsServe extends Model
{
    /**
     * 对应的表名称
     * @var string
     */
    protected $table = 'goods_serve';

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
        'name','description','py_code','pid','sort','state','image_url'
    ];
}