<?php

namespace App\models\dct;

use Illuminate\Database\Eloquent\Model;

/**
 * 快递公司字典表
 * Class GoodsAttrDefine
 * @package App\ynplat\models\goods
 */
class DctExpress extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'dct_express';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * 公司名称,状态,编号,首字母,1常用2不常用,公司网址,是否支持服务站配送0否1是
     * @var array
     */
    protected $fillable = [
        'e_name', 'e_state', 'e_code', 'e_letter', 'e_order', 'e_url', 'e_zt_state'
    ];

}
