<?php

namespace App\models\goods;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
/**
 * 商品SKU图片表
 * Class GoodsClassExtend
 * @package App\ynplat\models\goods
 */
class GoodsSkuImages extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'goods_sku_images';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     *商品skuID,颜色规格值id,图片地址,同一个SKU内排序,是否SKU主图【1是，0否】，一个SKU只有一个主图
     * @var array
     */
    protected $fillable = ['sku_id', 'color_id', 'image_url', 'sort', 'is_default'];


    /**
     * 获取商品sku展示的主图url
     * @auth 杨瑞
     * @param $query
     * @param $sku_id
     * @return mixed
     */
    public function scopeMainImg($query, $sku_id)
    {
        $img_url = $query->where('sku_id', $sku_id)
            ->where('is_default', 1)->value('image_url');

        $img_url = trim($img_url . '');
        if (!$img_url) {
            // 如果SKU没有设置主图片，启用SPU主图
            $spu_id = GoodsSku::where('sku_id', $sku_id)->value('spu_id');
            if (!$spu_id) {
                Log::error('sku对应的spu不存在,请检查参数是否有误 类:GoodsSkuImages@scopeMainImg');
                exit('sku对应的spu不存在');     // sku_id存在, spu不存在错误
            }
            
            // 如果返回的结果为空或者为false或者为0 则返回的是Builder对象
            return GoodsSpuImages::where('spu_id', $spu_id)
                ->where('is_default', 1)
                ->value('image_url');
        }

        return $img_url;
    }
}
