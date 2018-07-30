<?php

return [

    /* -------------------------------------------------------------------------------------------------------------
     *          会员类别(grade) => 10:普通会员; 20:黄金会员(一级会员); 30:钻石会员(二级会员); 40:黑卡VIP(三级会员)
     * -------------------------------------------------------------------------------------------------------------
     *
     * 价格字段名                对应会员等级                          中文名
     *
     * SKU 价格体系：
     * market_price             暂未指定                    市场价【通过模型计算的国内市场均价，指导价】
     * price			        10(普通会员)                商品价格【官网价、官网零售价】
     * groupbuy_price		    20(一级会员)                团购价
     * trade_price		        30(二级会员)                批发价
     * partner_price		    40(三级会员)                (分销伙伴)抄底价【成本价 + 管理费】
     * cost_price		        暂未指定                    成本价【平台进价 + 运费】
     *
     * SPU 价格体系：
     * spu_market_price	        暂未指定                    市场价【通过模型计算的国内市场均价，指导价】
     * spu_plat_price		    10(普通会员)                平台价格【官网价、官网零售价】
     * spu_groupbuy_price	    20(一级会员)                团购价
     * spu_trade_price		    30(二级会员)                批发价
     * spu_partner_price	    40(三级会员)                (分销伙伴)抄底价【成本价 + 管理费】
     * spu_cost_price		    暂未指定                    成本价【平台进价 + 运费】
     *
     */

    'system_price' => [

        10 => [
            'grade' => 10,
            'spu_price_name' => 'spu_plat_price',
            'sku_price_name' => 'price',
        ],

        20 => [
            'grade' => 20,
            'spu_price_name' => 'spu_groupbuy_price',
            'sku_price_name' => 'groupbuy_price',
        ],

        30 => [
            'grade' => 30,
            'spu_price_name' => 'spu_trade_price',
            'sku_price_name' => 'trade_price',
        ],

        40 => [
            'grade' => 40,
            'spu_price_name' => 'spu_partner_price',
            'sku_price_name' => 'partner_price',
        ]

    ]

];