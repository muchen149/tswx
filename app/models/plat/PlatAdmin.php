<?php

namespace App\models\plat;

use Illuminate\Database\Eloquent\Model;



/**
 * plat管理员
 * Class PlatAdmin
 * @package App\ynplat\models\user
 */
class PlatAdmin extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'plat_admin';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 可以被批量赋值的属性.
     *   管理员名称     密码   所属权限组ID   登录时间  登录次数  拼音编码  QQ号   是否超级用户【0:不是;1:是;】
     * 是否可用【0:不可用;1:可用;】
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'gid', 'login_time', 'login_num', 'py_code', 'qq', 'is_super', 'is_use'
    ];

}