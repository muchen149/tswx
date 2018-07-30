<?php
/* 购物车
 *
*/
Route::group(['prefix' => 'cart', 'middleware' => 'check.login'], function () {

    Route::get('index/{num?}', 'CartController@index');                         // 购物车列表分页数据

    Route::get('updateNum/{cartId}/{num}', 'CartController@updateNumBySkuId');  // 更新商品数量

    Route::get('clean', 'CartController@cleanNullGoods');                       // 清除过期商品

    Route::post('add', 'CartController@add');                                   // 增加商品到购物车

    Route::post('delete', 'CartController@delete');                             // 批量删除商品

});
