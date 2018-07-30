<?php

namespace App\models\goods;

use Illuminate\Database\Eloquent\Model;


/**
 * 品牌表
 * Class GoodsBrand
 * @package App\ynplat\models\goods
 */
class GoodsBrand extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'goods_brand';

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
        'name', 'english_name', 'first_letter', 'pic_url',
        'sort', 'recommend', 'show_type', 'state'
    ];
}
