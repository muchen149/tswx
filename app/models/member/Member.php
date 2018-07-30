<?php

namespace App\models\member;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use Notifiable;

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'member';

    /**
     * 对应的主键(会员用户id)
     *
     * @var string
     */
    protected $primaryKey = 'member_id';

    /**
     * 不能被批量赋值的属性
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


    /**
     * 记住我字段
     *
     * @var bool
     */
    protected $remember_token = false;


    /**
     * 验证某用户是否绑定手机，如果已经绑定，传出手机号；如果没有绑定，传出空手机号
     * 传入参数
     * @param   $query          object  本查询
     * @param   $member_id      int     被检测的用户ID，必传参数
     * 传出参数
     * @param $bind_mobile_num  string  手机号
     */
    public function scopeCheckIsNotBindMobile($query, $member_id)
    {
        /*
        member_id	int(10) 	    会员id
        mobile		varchar(20) 	手机号
        mobile_bind	tinyint(4)	    手机是否绑定(0:未绑定;1:已绑定)
        */
        $bind_mobile_num = -1;
        $member_id = (int)$member_id;
        $obj_member = $query->select('member_id', 'mobile', 'mobile_bind')
            ->where('member_id', $member_id)->first();
        if ($obj_member) {
            if ($obj_member->mobile_bind) {
                $bind_mobile_num = trim($obj_member->mobile . '');
            }
        }

        return $bind_mobile_num;
    }

    /**
     * 验证某手机是否已经绑定，如果已经绑定，传出绑定的用户ID
     * 传入参数
     * @param   $query          object  本查询
     * @param   $mobile_num     string  手机号
     * 传出参数
     * @return $bind_member_id      int      0没绑定；非零为绑定的手机号
     */
    public function scopeCheckMobileIsNotBind($query, $mobile_num)
    {
        /*
        member_id	int(10) 	    会员id
        mobile		varchar(20) 	手机号
        mobile_bind	tinyint(4)	    手机是否绑定(0:未绑定;1:已绑定)
        */
        $bind_member_id = -1;
        $mobile_num = trim($mobile_num . '');
        $obj_member = $query->select('member_id', 'mobile', 'mobile_bind')
            ->where('mobile', $mobile_num)->first();
        if ($obj_member) {
            if ($obj_member->mobile_bind) {
                $bind_member_id = (int)($obj_member->member_id);
            }
        }

        return $bind_member_id;
    }

    /**
     * 验证某手机是否已经绑定，如果已经绑定，传出绑定的用户ID
     * 传入参数
     * @param   $query          object  本查询
     * @param   $mobile_num     string  手机号
     * 传出参数
     * @return $bind_member_id      int      0没绑定；非零为绑定的手机号
     */
    public function scopeElifeMobileIsNotBind($query, $mobile_num)
    {
        /*
        member_id	int(10) 	    会员id
        mobile		varchar(20) 	手机号
        mobile_bind	tinyint(4)	    手机是否绑定(0:未绑定;1:已绑定)
        */
        $cust_id = 0;
        $mobile_num = trim($mobile_num . '');
        $obj_member = $query->select('member_id', 'mobile', 'mobile_bind','cust_id')
            ->where('mobile', $mobile_num)->first();
        if ($obj_member) {
            if ($obj_member->mobile_bind) {
                $cust_id = (int)($obj_member->cust_id);
            }
        }

        return $cust_id;
    }
}
