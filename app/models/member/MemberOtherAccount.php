<?php
namespace App\models\member;

use Illuminate\Database\Eloquent\Model;

class MemberOtherAccount extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'member_other_account';
    

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = [
       'member_id','account_id','account_name','account_type','check_flag','access_token'
    ];

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

}

