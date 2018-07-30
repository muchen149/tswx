<?php

namespace App\models\tpl;

use Illuminate\Database\Eloquent\Model;

class TplTransportDetail extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'tpl_transport_detail';

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
    protected $fillable=[
        'tpl_id', 'tpl_name', 'top_area_id', 'area_id',
        'area_name', 'first_number', 'first_price', 'next_number',
        'next_price','sort','is_default'
    ];

}
