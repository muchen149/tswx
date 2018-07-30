<?php

return [

    /**
     * 网易严选接口
     */
    'wyyx' => [
        'appKey' => '3652377ade884836ae5c7cd8ca78be52',         //应用Key测试
        'appSecret' => 'd17e65b4c7154f52bd029935ca8ea359',      //应用Secret测试
        //'appKey' => '1db2b0ffd3674451a4e774f54b3a4742',         //应用Key
        //'appSecret' => '7bfc8b122f274e85b56f553b1bf1ca7c',      //应用Secret
        //'testUrl' => 'http://openapi.you.163.com/',             //沙盒测试环境
        'testUrl' => 'http://openapi-test.you.163.com/channel/api.json',             //沙盒测试环境
        'apiUrls' => 'http://openapi.you.163.com/channel/api.json',                //线上生产环境
        'apiUrl' => 'http://api.you.163.com/',                  //线上生产环境
        'method' => [
            'skuIds' => 'yanxuan.item.id.batch.get',         //商品列表查询接口
            'skuItems' => 'yanxuan.item.batch.get',     //商品信息查询接口
            'createOrder' => 'yanxuan.order.paid.create',         //下单
            'cancelOrder' => 'yanxuan.order.paid.cancel',        //订单取消申请
            'confirmOrder' => 'yanxuan.order.received.confirm',    //订单确认收货
            'getOrder' => 'yanxuan.order.paid.get',              //订单查询
            'stockInfo' => 'yanxuan.inventory.count.get',         //渠道库存查询
            'register' =>'yanxuan.callback.method.register', //渠道自助注册回调
            'list' =>'yanxuan.callback.method.list',  //获取渠道已注册的回调方法名
        ]
    ],


    /**
     * 网考拉接口(一般贸易商品)
     */
    'wykl' => [
        'appKey' => 'bb0b3ad64c9e5eb06c2fb6f163bf179e79051bd5c9b652fc45dc68a2b5dd23c7',         //应用Key测试
        'appSecret' => '4ed8b056c32939b9fd66987470b3e9fb720bcsqd02197e678e516bdcdf810833',      //应用Secret测试
        //'appKey' => '1db2b0ffd3674451a4e774f54b3a4742',         //应用Key
        //'appSecret' => '7bfc8b122f274e85b56f553b1bf1ca7c',      //应用Secret
        //'testUrl' => 'http://openapi.you.163.com/',             //沙盒测试环境
        'channelId' => 1200,
        'testUrl' => 'http://test1.thirdpart.kaolatest.netease.com/api/',             //沙盒测试环境
        'v'=>1.0, //api 版本
       /* 'apiUrls' => 'http://openapi.you.163.com/channel/api.json',                //线上生产环境
        'apiUrl' => 'http://api.you.163.com/',                  //线上生产环境*/
        'method' => [
            'spuSkuIds' => 'queryAllGoodsIdAndSkuId',         //商品列表查询接口
            'querySkuIdsByGoodsIds' => 'querySkuIdsByGoodsIds', //根据goodsId(前台)查询出下面所有的skuId
            'skuIds' => 'queryAllGoodsId',            //商品列表查询接口
            'skuItems' => 'queryGoodsInfoByIds ',     //商品信息查询接口
            'skuItem' => 'queryGoodsInfoById ',     //单个商品信息查询接口
            'queryChangedGoodsInfo'=>'queryChangedGoodsInfo', //商品信息增量更新接口
            'createOrder' => 'bookorder',               //下单
            'cancelOrder' => 'cancelOrder',             //订单取消
            'orderConfirm' => 'orderConfirm',           //确认订单接口
            'queryOrderStatus' => 'queryOrderStatus',   //订单状态查询
            'payOrder' => 'payOrder',                   //订单支付
            'bookpayorder'=>'bookpayorder',             //下单且支付
            'closeOrder' => 'closeOrder',                //订单关闭
        ]
    ],

    /**
     * 网考拉接口(非一般贸易商品)
     */
    'wykl_two' => [
        'appKey' => 'bb0b3ad64c9e5eb06c2fb6f163bf179e79051bd5c9b652fc45dc68a2b5dd23c7',         //应用Key测试
        'appSecret' => '4ed8b056c32939b9fd66987470b3e9fb720bcsqd02197e678e516bdcdf810833',      //应用Secret测试
        'channelId' => 1200,
        'testUrl' => 'http://test1.thirdpart.kaolatest.netease.com/api/',                       //沙盒测试环境
        'v'=>1.0,                                                                               //api 版本
        'method' => [
            'querySkuIdsByGoodsIds' => 'querySkuIdsByGoodsIds', //根据goodsId(前台)查询出下面所有的skuId
            'skuIds' => 'queryAllGoodsId',              //商品列表查询接口
            'skuItems' => 'queryGoodsInfoByIds ',       //商品信息查询接口
            'skuItem' => 'queryGoodsInfoById ',         //单个商品信息查询接口
            'cancelOrder' => 'cancelOrder',             //订单取消
            'orderConfirm' => 'orderConfirm',           //确认订单接口
            'queryOrderStatus' => 'queryOrderStatus',   //订单状态查询
            'bookpayorder'=>'bookpayorder',             //下单且支付
        ]
    ],


    'icbc' => [
        'appid' => '10000000000000059530',         //appid
        'appSecret' => '',      //应用Secret
        'testUrl' => 'https://apisandbox.dccnet.com.cn/',             //沙盒测试环境
        'apiUrl' => 'https://apisandbox.dccnet.com.cn/',                  //线上生产环境
        'pri' =>'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCgWmecIHX36/ahbFBWxRDwpMLju5BEJlVy97q+RkOaZbYdHZN1ijhZfNiGuZp2DzchZiFA7JZqWLoXKly/15hhSUvatN5zrmGu7+QeoYUBLws+DXa6WefMuYwAzB9s093Ej96/BAcUr1OQkHYGE6631ER9NRGvg+zkX+ZPpCUf7s1LC7HxqGKvWh1maNSjU26uCze6jEWcK2x6Ny4NOVp6+zlDmrual6S4UdyiVV6EH9eRh5wRtdPfw4O9rAnA2vP0eu0aG4gBzrU/T2fePtGXBW9r57DRIwgIvMvkvwTK+KGKcGY+f/sqkaJAdxEo7rMy0xoPcChbpewcKDjHiUmHAgMBAAECggEBAJfeJ2TJpZChzVqCz+/uAiY3lVDEElVJDQKutxGAUISJMhqPKVpYBxhR0mx+mliX/nnGVVY8/BRKZiyMdX1H/kydc5b2V/ytulxJXP7ZsLM3T+l8LOc/QPc2/+69ZEHYwp9oNukoMmCX0IgJGY6V05LNGfSPb2mQg6qjXOguqO585+ciOGGpbRZbD12BXwWiSR8CZzJKE8vAMAVJu+L+ncTSoi2RpDQkJ+C7fgXdRTIPu3+5dDAanmd5Zn4c83C76BSFxOh83ql1nGCjmj36fhyA5Z288aZZbe7w7nclg4Jt1q63Z372E6h5YqRqItSCzF9mKEi6AdQMbgALiX9hkXECgYEAzVXZEKROYRBHw5utM3kYpVZ1uP9z/n9RPsjjSgcqOqYBUMy7aH5odLmw446xPiH5cnIbC13DU8Tol+9G8gnZ4xoAn1mtuw7WJ/exP7Q/pE6v83QGZ3PaX0AQEYHqWHJKFzLh6lOh8Y3mEdRMmyVAHSNz3gq0WFHOFNjnUo7P0e8CgYEAx+s5b/p63vTShaQCdvSU7VwSxR6tqR1Bq4Cxe1m+/dUU737lKFUQImz6CpI19l/k6sxUQqEJmWyqDguURAZpHjcAgdWFsFDc2lCnj6TXG4j8CXeO3cK3RYT360oMDKXzqEdwGj/Ief4Vzy5M1lNgQEmOtUTyJVCwBwWUGXSrOekCgYBPuBjCIUhc3tk91F72MPmkl2C1Jlh+YifE3HGB+C4o/vJb0GCiPRGI398ROgEOQlp6WFqvmwOOrlAvTLKancB+L0Y2l7affS8f7UZfmTdsLzCYsF8cIxqRCGo0od+93wFs6FBVjYq+IX1FRstHILs3lOATQMyrzXbZGS0WHGQK+QKBgFlqW8Y5wbr2xTIAqRmLSxDenYaMsh9xdm2+oaMKAOKG61Yy60uewBilpTAVNQ181mYt/YHPhPuaHnUpuKa0N0/MSe3IEoNJp339lPQqRguKuS+CyeNls5LkZf5WoA0ILHKXgQw8eu4VNqvziWpS4DngrHNm4ubNr+10EUlRZUQBAoGAM+I7uTREEvl1ngMzPaFL/c50RzwU0Dig78HIH2ebGFOGHJJKH6lnzA4zmRm2Ic58z6E27NQcv/J2P/9gA0I6hiMycEXcClaDsBFpR6wGA1oiBVnvcTYnpNUhed34RtxothWG4s8vwbaJYAo4JKcHidopiBvBAbis4ziNsZr7GyU=',  //私钥
        'aespri'=>'InAop3i4KTAXjsSKI1CTsg==',
        'mer_id'=>'020004042266',
        'store_code'=>'02000319741',

        'method' => [
            'pay' => 'api/qrcode/V2/pay',                    //支付
            'query' => 'api/qrcode/V2/query',     //查询
            'reject' => 'api/qrcode/V2/reject',           //退款
            'reverse' => 'api/qrcode/V2/reverse',         //冲正
            'rejectQuery' => 'api/qrcode/V2/reject/query',  //退货查询
        ]
    ],
];
