<?php
/**
 * Created by PhpStorm.
 * User: yiyuanda
 * Date: 2017/5/26
 * Time: 11:37
 */

namespace App\models\company;

use Illuminate\Database\Eloquent\Model;

class CompanyPayAccount extends Model
{

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_pay_account';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [];
}