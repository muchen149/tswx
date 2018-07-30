<?php

namespace App\models\dct;

use Illuminate\Database\Eloquent\Model;

/**
 * 汉字库字典表
 * Class GoodsAttrDefine
 * @package App\ynplat\models\goods
 */
class DctChineselb extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'dct_chineselb';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'dmid';

    /**
     * 汉字编码,汉字名称,拼音
     *
     * @var array
     */
    protected $fillable = [
        'dmid', 'dmname', 'pinyin'
    ];
}
