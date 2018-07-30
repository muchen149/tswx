<?php
/**
 * Created by PhpStorm.
 * User: yiyuanda
 * Date: 2017/9/1
 * Time: 17:01
 */

namespace App\models\member;

use Illuminate\Database\Eloquent\Model;

class MemberYsxx  extends Model
{/**
 * 关联到模型的数据表
 *
 * @var string
 */
    protected $table = 'member_ysxx';

    /**
     * 对应的主键(会员用户id)
     * @var string
     */
    protected $primaryKey = 'ysxx_id';

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $guarded = [];



}