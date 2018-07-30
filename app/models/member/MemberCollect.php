<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2017/1/16
 * Time: 11:34
 */

namespace App\models\member;
use Illuminate\Database\Eloquent\Model;

class MemberCollect extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
    */
    protected $table = 'member_collect';

    /**
     * 对应的主键
     * @var string
    */
    protected $primaryKey = 'id';

    /**
     * 可以被批量赋值的属性.
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