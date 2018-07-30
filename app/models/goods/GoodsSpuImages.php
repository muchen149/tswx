<?php

namespace App\models\goods;

use Illuminate\Database\Eloquent\Model;

class GoodsSpuImages extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'goods_spu_images';

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
    protected $guarded = [ ];


    /**
     * 获取指定spu的主图
     * @auth 杨瑞
     * @param $querty
     * @param $spu_id
     * @return mixed
     */
    public function scopeMainImg($querty, $spu_id)
    {
        return self::where('spu_id', $spu_id)
                    ->where('is_default', 1)->value('image_url');
    }
        
}
