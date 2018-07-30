   <?php
/**
 *微信礼品分享路由
*/
Route::group(['prefix'=>'gift'], function(){

    Route::group(['middleware' => 'check.login'], function () {  //'middleware' => 'check.login'
        //微信送礼首页
        Route::get('index', 'WxShareGiftsController@index');
//        //订单确认
//        Route::post('orderConfirm', 'WxShareGiftsController@order_confirm');

       //取消订单
       Route::get('cancelShareGiftOrder/{plat_order_id}', 'WxShareGiftsController@cancelShareGiftOrder')->middleware('check.login');;

      //用户点击分享的微信礼品链接展示的礼品信息
      Route::get('shareGiftInfo/{share_gifts_info_id}', 'WxShareGiftsController@shareGiftInfo');

      //用户领取微信礼品时判断该礼品是否可以领取
      Route::get('checkToGetGift/{share_gifts_info_id}', 'WxShareGiftsController@checkToGetGift');

      //用户领取微信礼品时，有关礼品详情
      Route::get('getGiftDetailInfo/{share_gifts_info_id}', 'WxShareGiftsController@getGiftDetailInfo');

      //用户生成微信送礼订单后，跳到分享页面进行分享
      Route::get('giftToShare/{share_gifts_info_id}', 'WxShareGiftsController@giftToShare')->middleware('share');;

     //用户领取微信礼品成功页面
     Route::get('getGiftSuccess/{share_gifts_info_id}', 'WxShareGiftsController@getGiftSuccess');


    });

     //领微信礼品者生成订单
     Route::post('get_gift_order_add', 'OrderController@get_gift_order_add');

    //生成订单
    Route::post('gift_order_add', 'OrderController@gift_order_add');


});
