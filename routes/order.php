<?php

/*
|--------------------------------------------------------------------------
| order routes 订单相关路由
|--------------------------------------------------------------------------
|
*/

// 根据商品数量及收货地点区县ID、运费模板ID或商品spu_id(sku_id)，获取运费金额
Route::post('/transport/cost', 'TransportTemplateController@goodsTransportCost');

Route::group(['prefix' => 'order'], function () {

    Route::group(['middleware' => 'check.login'], function () {

        // 订单展示列表
        Route::get('index/{state?}', 'OrderController@index');

        // 单个订单详情
        Route::get('info/{id}', 'OrderController@info');

        // 撤单【未支付的订单，可以撤销】
        Route::get('cancel/{id}', 'OrderController@cancel');

        // 去支付【未支付的订单，在一定期限内（一周内？），可以继续支付】
        Route::get('toPay/{id}', 'OrderController@toPay');

        // 确认收货【订单内每种商品，都发货后，可以确认收货】
        Route::get('confirm/{id}', 'OrderController@confirmReceipt');

        // 删除订单【完成的订单，可以删除（逻辑删除）】
        Route::get('delete/{id}', 'OrderController@delete');

        // 退款退货
        Route::get('afterOrder', 'OrderController@afterOrder');

        // 查看物流
        Route::get('logistics/{id}', 'OrderController@logistics');

    });

    // 生成订单
    Route::post('add', 'OrderController@add');

    // 订单(人民币)预支付
    Route::post('prePay', 'OrderController@prePay');

    // 代理用户或团采用户通过电子支付上传电子支付凭证
    Route::post('uploadImg', 'OrderController@uploadImg');

    // 代理用户或团采用户删除上传的电子支付凭证
    Route::post('delImg', 'OrderController@delImg');

    // 订单购买信息确认页【预结算】
    Route::post('showPay', 'OrderController@showPay');

    // 根据非人民币（例如：虚拟币、零钱、卡余额、优惠劵）拟支付额重新计算人民币支付额
    Route::post('payNum', 'OrderController@getPayNumber');

    // 是否能够配送到
    Route::post('canBuy', 'OrderController@canBuy');

    //申请退单
    /*Route::get('saleOrder/{id}', function () {
        return view('wx_order.sale_order_service');
    });*/
    Route::get('applyRefund/{id}','OrderController@applyRefund');
    //退单状态
    Route::get('saleOrderState','OrderController@saleOrderState');
    // 售后服务
    Route::post('saleOrder', 'OrderController@saleOrder');

    // 验证拟下单商品是否在该商品的销售区域内，是否有发货站配货
    Route::post('goods/canBuy', 'OrderController@canBuy');

});

