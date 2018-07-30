<?php
/* 第三方API
 *
*/
Route::group(['prefix' => 'thirdapi'], function () {

    Route::group(['prefix' => 'wyyx'], function () {

        //订单取消回调
        Route::any('cancelOrderCallback', 'thirdapi\WyYxThirdApiController@cancelOrderCallback');

        //订单包裹物流绑单回调
        Route::any('bindOrderExpressCallback', 'thirdapi\WyYxThirdApiController@bindOrderExpressCallback');

        //商品更新
        Route::any('updateThirdGoodsSku', 'thirdapi\WyYxThirdApiController@updateThirdGoodsSku');
        Route::any('updateThirdGoodsImage', 'thirdapi\WyYxThirdApiController@updateThirdGoodsImage');

        //网易严选统一回调
        Route::any('wyyxCallback', 'thirdapi\WyYxThirdApiController@wyyxCallback');

        //申请网易取消订单
        Route::any('cancelOrder/{id}','thirdapi\WyYxThirdApiController@cancelOrder');

        //测试
        Route::any('test', 'thirdapi\WyYxThirdApiController@test');


        //手动派单网易
        Route::any('sendManual/{id}', 'thirdapi\WyYxThirdApiController@sendManual');

        //手动取消订单网易
        Route::any('cancelManual/{id}', 'thirdapi\WyYxThirdApiController@cancelManual');

        //手动查单网易
        Route::any('queryOrder/{id}', 'thirdapi\WyYxThirdApiController@queryOrder');
    });

    /**
     * wykl
     * 2018年7月23日14:53:40
     * 考拉一般贸易商品
     */
    Route::group(['prefix' => 'wykl'], function () {

        //商品更新
        Route::any('updateThirdGoodsSku', 'thirdapi\WyKlThirdApiController@updateThirdGoodsSku');
        Route::any('updateThirdGoodsImage', 'thirdapi\WyKlThirdApiController@updateThirdGoodsImage');

        //更新商品spu (通过sku更新sku)
        Route::any('test', 'thirdapi\WyKlThirdApiController@test');

        //根据goodsId(前台)查询出下面所有的skuId
        Route::any('querySkuIdsBySpuIds/{spuId}','thirdapi\WyKlThirdApiController@querySkuIdsBySpuIds');


        //手动派单网易
        Route::any('sendManual/{id}', 'thirdapi\WyKlThirdApiController@sendManual');

        //手动查单网易
        Route::any('queryOrder/{id}', 'thirdapi\WyKlThirdApiController@queryOrder');

        //支付订单
        Route::any('payOrder/{id}', 'thirdapi\WyKlThirdApiController@payThirdOrder');

        //关闭订单
        Route::any('closeOrder/{id}', 'thirdapi\WyKlThirdApiController@closeThirdOrder');

        //取消订单
        Route::any('cancelOrder/{id}', 'thirdapi\WyKlThirdApiController@cancelThirdOrder');

        //增量查询
        Route::any('queryChangedGoodsInfo', 'thirdapi\WyKlThirdApiController@queryChangedGoodsInfo');
    });

    /**
     * wykl_two
     * 2018年7月23日14:53:40
     * 考拉非一般贸易商品
     */
    Route::group(['prefix' => 'wykl_two'], function () {

        //商品更新
        Route::any('updateThirdGoodsSku', 'thirdapi\WyKlThirdApiControllerTwo@updateThirdGoodsSku');

        //更新商品spu (通过sku更新sku)
        Route::any('updateThirdGoodsSpu', 'thirdapi\WyKlThirdApiControllerTwo@updateThirdGoodsSpu');

        //根据goodsId(前台)查询出下面所有的skuId
        Route::any('querySkuIdsBySpuIds/{spuId}','thirdapi\WyKlThirdApiControllerTwo@querySkuIdsBySpuIds');


        //手动派单网易
        Route::any('sendManual/{id}', 'thirdapi\WyKlThirdApiControllerTwo@sendManual');

        //手动查单网易
        Route::any('queryOrder/{id}', 'thirdapi\WyKlThirdApiControllerTwo@queryOrder');

        //取消订单
        Route::any('cancelOrder/{id}', 'thirdapi\WyKlThirdApiControllerTwo@cancelThirdOrder');

    });
});
