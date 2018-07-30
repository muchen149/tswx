<?php

namespace App\models\form;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/30 0030
 * Time: 上午 10:58
 * 创建表单
 */


class FormData extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'form_data';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 可以被批量赋值的属性.
     *   表单id     表单名称   表单说明   添加时间  是否可用【0:不可用;1:可用;】
     * @var array
     */
    /*protected $fillable = [
        'fid', 'fname', 'fmsg', 'addtime', 'display'
    ];*/
    protected $guarded = [];

}