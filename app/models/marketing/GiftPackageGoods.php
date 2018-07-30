<?php

namespace App\models\marketing;

use Illuminate\Database\Eloquent\Model;

/**
 * 礼品卡活动与礼品包关系表
 * Class GiftPackageGoods
 * @package App\ynplat\models\giftcard
 */
class GiftPackageGoods extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'gift_package_goods';

    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;
}
