<?php

namespace App\models\member;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ShareCardGetRecords extends Authenticatable
{
    use Notifiable;

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
    protected $table = 'share_cardgetrecords';
    /**
     * 对应的主键(id)
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [];


}
