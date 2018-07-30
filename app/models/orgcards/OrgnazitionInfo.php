<?php

namespace App\models\orgcards;

use Illuminate\Database\Eloquent\Model;

class OrgnazitionInfo extends Model
{
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'orgnazition_info';
    /**
     * 对应的主键
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [ ];

    
}
