<?php

Route::group(['prefix' => 'sd_shop'], function () {

    // 商城首页【商品SPU列表页】
    Route::get('index/{pageNumber?}/{pageSize?}/{gcId?}/{goodsName?}/{orderBy?}/{gcType?}', 'GoodsController@index');

    // 新商城首页
    Route::get('shop/{id?}', 'GoodsController@shop');
   /* Route::get('shop',function () {
        return view('sd_mall.shop');
    });*/
    Route::get('toGoodsClass/{id}', 'GoodsController@ajax_toGoodsClass');
    // 搜索页
    /*Route::get('search',function () {
        return view('sd_goods.search');
    });*/
    //搜索页
    Route::get('search','GoodsController@hotSearch');
    // 搜索页搜索
    Route::get('doSearch/{pageNumber?}/{pageSize?}/{gcId?}/{goodsName?}','GoodsController@search');


    // 商品SPU 检索
    // 如果没有搜索词（goodsName），可传入“0”
    // 排序项：0综合、1销量、2价格、3人气
    Route::get('goods/spuList/{pageNumber?}/{pageSize?}/{gcId?}/{goodsName?}/{orderBy?}', 'GoodsController@spuList');

    // 商品分类
    Route::get('goodsClass', 'GoodsController@goodsClassList');

    // 首页懒加载商品列表
    Route::get('goods/ajax/spuList/{pageNumber?}/{pageSize?}/{gcId?}/{goodsName?}/{orderBy?}', 'GoodsController@ajax_spuList');

    // 商品SPU详情页
    Route::get('goods/spuDetail/{spuId}', 'GoodsController@spuDetail')->middleware('check.login');

    // 购买中：动态获取sku信息
    Route::post('getSku', 'GoodsController@getSku');

    // 商品图文详情
    Route::post('goods/mobileContent', 'GoodsController@goodDetail');

    //更多服务
    Route::get('service_more', function () {
        return view('sd_goods.service_more_');
    });

    //每日播报
    Route::get('article_everyday', function () {
        return view('sd_goods.article_everyday');
    });

/*    //文章列表
    Route::get('article_list', function () {
        return view('sd_goods.article-list');
    });*/
    //文章列表
    Route::get('article_list', 'ArticleController@aritcleList');

});