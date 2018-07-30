<?php

Route::group(['prefix' => 'jd'], function () {

    // 集市页面
    Route::get('shop', 'jiudian\GoodsController@shop');

    Route::get('index', 'jiudian\MemberController@index')->middleware('check.login');       // 首页

    // 商品SPU详情页
    Route::get('goods/spuDetail/{spuId}', 'jiudian\GoodsController@spuDetail');

    // 获取用户信息
    Route::get('user/details/{id}', 'wx\UserController@getUserInfoByOpenid');

    // 商品SPU 检索
    // 如果没有搜索词（goodsName），可传入“0”
    // 排序项：0综合、1销量、2价格、3人气
    Route::get('goods/spuList/{pageNumber?}/{pageSize?}/{gcId?}/{goodsName?}/{orderBy?}/{gcType?}', 'jiudian\GoodsController@spuList');

    // 首页懒加载商品列表
    Route::get('goods/ajax/spuList/{pageNumber?}/{pageSize?}/{gcId?}/{goodsName?}/{orderBy?}/{gcType?}', 'jiudian\GoodsController@ajax_spuList');





    Route::group(['prefix' => 'order'], function () {

        // 订单购买信息确认页【预结算】
        Route::post('showPay', 'jiudian\OrderController@showPay');

        // 生成订单
        Route::post('add', 'jiudian\OrderController@add');
        // 订单展示列表
        Route::get('index/{state?}', 'jiudian\OrderController@index');

        // 单个订单详情
        Route::get('info/{id}', 'jiudian\OrderController@info');

    });

    // 购买中：动态获取sku信息
    Route::post('getSkus', 'jiudian\GoodsController@getSkus');
//    Route::get('crowd_funding/{id}', 'CrowdFundingController@details');
//    Route::post('crowd_funding/add', 'CrowdFundingController@add');


    // 购买中：动态获取sku信息
    Route::post('chekcSkus', 'jiudian\GoodsController@chekcSkus');


});



