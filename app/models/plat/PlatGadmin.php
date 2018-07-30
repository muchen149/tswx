<?php

namespace App\models\plat;

use Illuminate\Database\Eloquent\Model;



/**
 * plat权限组
 * Class PlatGadmin
 * @package App\ynplat\models\user
 */
class PlatGadmin extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'plat_gadmin';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 可以被批量赋值的属性.
     *   权限组名称     拼音编码   权限   是否可用【0:不可用;1:可用;】  排序
     * 是否可用【0:不可用;1:可用;】
     * @var array
     */
    protected $fillable = [
        'name', 'py_code', 'limits', 'is_use', 'sort'
    ];

}
