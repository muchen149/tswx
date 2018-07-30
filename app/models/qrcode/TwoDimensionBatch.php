<?php

namespace App\models\qrcode;

use Illuminate\Database\Eloquent\Model;

class TwoDimensionBatch extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'two_dimension_batch';

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = [
        'batch_name', 'batch_code', 'store_id', 'store_name',
        'batch_amount', 'batch_state', 'operator', 'created_at', 'updated_at'
    ];

    /**
     * 模型主键
     *
     * @var string
     */
    public $primaryKey = 'batch_id';

    public function scopeSelectInfo($query)
    {
        return $query->select(
            'batch_id', 'batch_name', 'batch_code', 'store_id', 'store_name',
            'batch_amount', 'batch_state', 'operator', 'created_at', 'updated_at'
        );
    }
    

}
