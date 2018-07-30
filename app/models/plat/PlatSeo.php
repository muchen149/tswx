<?php

namespace App\models\plat;

use Illuminate\Database\Eloquent\Model;


/**
 * 平台SEO优化表
 * Class PlatAdmin
 * @package App\ynplat\models\user
 */
class PlatSeo extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'plat_seo';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id', 'title', 'keywords', 'description', 'type'
    ];
}