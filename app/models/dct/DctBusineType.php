<?php

namespace App\models\dct;

use Illuminate\Database\Eloquent\Model;

/**
 * 商城业务字典表
 * @author      :lishuo
 * Class        :DctBusineType
 * @package     :App\models\dct
 */
class DctBusineType extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'dct_busine_type';

    /**
     * 对应的主键 
     * @var string
     */
    protected $primaryKey = 'code_id';
    
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
