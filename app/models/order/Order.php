<?php

namespace App\models\order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'order';

    /**
     * 对应的主键
     *
     * @var string
     */
    protected $primaryKey = 'plat_order_id';

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


    /**
     * 订单详细信息页所需字段
     * @param $query
     * @return mixed
     */
    public function scopeSelectInfoZd($query)
    {
        /*
            订单金额计算公式
            1、订单结算金额 = （商品结算金额 + 运费结算金额 + 其它费用）
                order_amount_totals >= (goods_amount_totals + transport_cost_totals)
                order_amount_totals = (goods_amount_totals + transport_cost_totals + 其它服务费)
                商品结算金额 = 单价 * 数量，买家身份不同，购物价格不一样，具体为：
                    普通会员(10)        price	    	平台价格【官网价、官网零售价】
                    黄金会员(20)        groupbuy_price	团购价
                    钻石会员(30)        trade_price		批发价
                    黑卡VIP(40)         partner_price	(分销伙伴)抄底价【成本价 + 管理费】

                运费结算金额：根据运费计算规则，计算出的本订单的运费
                其它费用：平台收取的服务费等
            2、订单应付金额 = （订单结算金额 - 商品优惠金额 - 运费优惠金额）
                payable_amount = (order_amount_totals - goods_preferential - transport_preferential)

                商品优惠金额，即：订单结算时，买家享受的购物优惠政策，折算成金额，例如：购X员满减100元、购Y件8折优惠等
                运费优惠金额，即：订单结算时，买家享受的运费优惠政策，折算成金额，例如：购1000元免运费。
            3、人民币应付金额 = （订单应付金额 - 虚拟币支付金额 - 其它支付金额）
                pay_rmb_amount = (payable_amount - pay_points_amount - pay_wallet_amount)
                金币积分支付金额，即：订单结算时，使用的积分、酒币、代金券
        */
        return $query->select(
            'plat_order_id', 'plat_order_sn',
            'goods_amount_totals', 'goods_preferential',
            'transport_cost_totals', 'transport_preferential',
            'order_amount_totals',
            'payable_amount',
            'pay_rmb_amount',
            'pay_points_amount','pay_wallet_amount',
            'payment',
            'pay_rmb_time','pay_rmb_sn',
            'sendee_address_id', 'sendee_address_info',
            'plat_order_state', 'evaluation_state',
            'create_time', 'transport_time',
            'arrival_time','finnshed_time','pay_mode_id','pay_mode_code','pay_mode_name','pay_cert','expire_time',
            'is_share_gifts','group_is_send','is_get_gift'
        );
    }

    /**
     * 查询当前用户在前台需要展示的订单
     * @param $query
     * @return mixed
     */
    public function scopeListsOrder($query)
    {
        /*  订单重要信息项
            buyer_type  tinyint(1) NOT NULL DEFAULT '0' COMMENT '买家类型【0:注册会员;1:分销商（大客户）】',
            member_id   int(10) unsigned NOT NULL COMMENT '买家id',
            member_name varchar(60) DEFAULT '' COMMENT '买家姓名',

            1、订单应付金额，例如：77.00
            payable_amount` decimal(10,2) 订单应付金额

            2、订单支付情况
            人民币支付                           pay_rmb_amount      decimal(10,2)   例如：12.55 元
            虚拟币支付                           pay_points_amount   decimal(10,2)   例如：4     个
            其它（卡余额、零钱、代金劵等）支付   pay_wallet_amount   decimal(10,2)   例如：60.45 元

            3、支付明细（payment text），序列化数组字符，每笔支付的明细，格式为：
            [
                ['pay_type'=>'rmb','pay_id'=>326,'pay_amount'=>12.55],
                ['pay_type'=>'vrb','pay_id'=>14,'pay_amount'=>4],
                ['pay_type'=>'wallet','pay_id'=>26,'pay_amount'=>0.45],
                ['pay_type'=>'card_balance','pay_id'=>456,'pay_amount'=>10],
                ['pay_type'=>'voucher','pay_id'=>51,'pay_amount'=>50]
            ]
            注释：rmb——人民币；vrb——虚拟币；wallet——零钱【钱包】；
                  card_balance——卡余额；voucher——代金劵
            plat_order_state` tinyint(4) 订单状态（1:（已下单）待付款; 2:（已付款）待发货; 3:（已发货）待收货;
                4:（已收货）待评价;9:已完成; -1:已取消; -2:已退单; -9:已删除; ）',
            delete_state` tinyint(4) 删除状态(0:未删除;1:放入回收站; 2:逻辑删除);
        */
        // 请注意：buyer_type=0 为注册会员订单；buyer_type=1为分销商订单
        return $query->select(
            'plat_order_id', 'plat_order_sn',
            'goods_amount_totals', 'goods_preferential',
            'transport_cost_totals', 'transport_preferential',
            'order_amount_totals',
            'payable_amount',
            'pay_rmb_amount',
            'pay_points_amount','pay_wallet_amount',
            'payment',
            'pay_rmb_time','pay_rmb_sn',
            'plat_order_state', 'evaluation_state',
            'create_time', 'transport_time',
            'arrival_time','finnshed_time','pay_mode_id','pay_mode_code','pay_mode_name','pay_cert','expire_time',
            'is_share_gifts','group_is_send','is_get_gift'
        )->where('buyer_type', 0)
            ->where('member_id', Auth::user()->member_id)
            ->where('delete_state', 0)
            ->orderBy('plat_order_id', 'desc');
    }
}