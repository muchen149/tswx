<?php
/**
 * Created by PhpStorm.
 * User: yiyuanda
 * Date: 2017/9/27
 * Time: 10:58
 */

namespace App\models\store;
use Illuminate\Database\Eloquent\Model;

class StoreNoticeMsg extends Model
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
    protected $table = 'store_noticemsg';
    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [ ];
}
