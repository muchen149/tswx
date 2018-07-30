<?php

/**
 * 购物商城
 */
Route::group(['prefix' => 'shop'], function () {

    // 商城首页【商品SPU列表页】
    Route::get('index', 'GoodsController@index');

    // 商品SPU 检索
    // 如果没有搜索词（goodsName），可传入“0”
    // 排序项：0综合、1销量、2价格、3人气
    Route::get('goods/spuList/{pageNumber?}/{pageSize?}/{gcId?}/{goodsName?}/{orderBy?}/{gcType?}', 'GoodsController@spuList')->middleware('check.login');

    // 商品分类
    Route::get('goodsClass', 'GoodsController@goodsClassList');

    // 首页懒加载商品列表
    Route::get('goods/ajax/spuList/{pageNumber?}/{pageSize?}/{gcId?}/{goodsName?}/{orderBy?}/{gcType?}', 'GoodsController@ajax_spuList');

    // 商城懒加载商品列表
    Route::get('goods/ajax/shopSpuList/{pageNumber?}/{pageSize?}/{gcId?}/{goodsName?}/{orderBy?}', 'GoodsController@ajax_shopSpuList');

    // 商城加载推荐商品列表
    Route::get('goods/ajax/rmdSpuList/{gcId?}', 'GoodsController@ajax_rmdSpuList');

    // 商品SPU详情页
    Route::get('goods/spuDetail/{spuId}', 'GoodsController@spuDetail')->middleware('check.login');

    // 购买中：动态获取sku信息
    Route::post('getSku', 'GoodsController@getSku');

    // 商品图文详情
    Route::post('goods/mobileContent', 'GoodsController@goodDetail');

});