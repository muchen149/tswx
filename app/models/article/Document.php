<?php

namespace App\models\article;


use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
    */
    protected $table = 'document';
    
    /**
     * 对应的主键
     * @var string
     */
    protected $primaryKey = 'doc_id';

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $guarded = [];
}
