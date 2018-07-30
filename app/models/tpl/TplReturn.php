<?php

namespace App\models\tpl;

use Illuminate\Database\Eloquent\Model;

class TplReturn extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'tpl_return';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'title', 'py_code', 'sort', "state"
    ];


    /**
     * 获取退费模板详细表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany('App\ynplat\models\tpl\TplReturnDetail', 'tpl_id', 'id');
    }

    /**
     * 模板空数组
     * @return array
     */
    public function getEmptyArray()
    {
        return [
            'tpl_id' => null,
            'tpl_name' => null,
            'tpl_title' => null,
            'tpl_detail' => [],
        ];
    }

    


}
