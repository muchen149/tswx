<?php

namespace App\models\member;

use Illuminate\Database\Eloquent\Model;

class MemberCart extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'member_cart';

    /**
     * 对应的主键(购物车id)
     * @var string
     */
    protected $primaryKey = 'cart_id';

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = [
        'member_id', 'member_name', 'seller_id', 'seller_name', 'sku_id', 'number', 'bl_id'
    ];

    /**
     * 从购物车获取的字段信息
     * @param $query
     * @return mixed
     */
    public function scopeSelectZd($query)
    {
        return $query->select('sku_id', 'number');
    }

    /**
     * 当前登录用户的指定的sku(购物车内)
     * @param $query
     * @param $member_id
     * @param $sku_id
     * @return mixed
     */
    public function scopeSkuByMember($query, $member_id, $sku_id)
    {
        return $query->where('member_id', $member_id)->where('sku_id', $sku_id);
    }


    /**
     * 当前登录用户的指定的sku(购物车内)
     * @param $query
     * @param $member_id
     * @param $sku_id
     * @return mixed
     */
    public function scopeSkuByCartId($query, $member_id, $cart_id)
    {
        return $query->where('member_id', $member_id)->where('cart_id', $cart_id);
    }
}
