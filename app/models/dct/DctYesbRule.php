<?php

namespace App\models\dct;

use Illuminate\Database\Eloquent\Model;

/**
 * 商城酒币获取规则表
 * @author      :lishuo
 * Class        :DctBusineType
 * @package     :App\models\dct
 */
class DctYesbRule extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'dct_yesb_rule';

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
    protected $guarded = [];

}
