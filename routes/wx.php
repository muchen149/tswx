<?php

Route::group(['prefix' => 'wx'], function () {

    // 公众号事件接收URL
    Route::any('serve', 'wx\WxController@serve');

    // 微信菜单生成
    Route::get('menu', 'wx\WxController@menu');

    // 获取用户列表
    Route::get('user/list', 'wx\UserController@users');

    // 获取用户信息
    Route::get('user/details/{id}', 'wx\UserController@getUserInfoByOpenid');

    // 获取用户列表中每个用户的详细信息
    Route::get('users', 'wx\UserController@getUsersInfo');

    // 微信菜单   众筹
    Route::get('crowd_funding', 'CrowdFundingController@index');
    Route::get('crowd_funding/{id}', 'CrowdFundingController@details');
    Route::post('crowd_funding/add', 'CrowdFundingController@add');

    // 抽奖活动关注后领取红包处理
    Route::post('sendRedPack', 'wx\RedPackController@sendRedPack4Subscribe');

    /*
    |--------------------------------------------------------------------------
    | wx支付(支付授权目录 wx/pay)
    |--------------------------------------------------------------------------
    | 所有使用公众号支付方式发起支付请求的链接地址, (该发起支付请求的文件)都必须在支付授权目录之下,
    | 且访问支付授权目录的域名必须通过ICP备案
    |
    */

    Route::group(['prefix' => 'pay'], function () {

        // 平台订单支付
        Route::get('wxPay', 'wx\WxPayController@pay')->name('wxPay');

        // 会员购买或续费(使用ajax来获取微信的json支付数据, 本页面是使用js调起的支付, 所以该文件一定要在授权目录下)
        Route::get('payMemberShip', 'MemberShipController@buyMember');

        // 卡余额充值(使用ajax来获取微信的json支付数据, 本页面是使用js调起的支付, 所以该文件一定要在授权目录下)
        //Route::get('payRecharge', 'MemberRechargeController@payRecharge');
        Route::get('rechargeCardList', 'MemberRechargeController@rechargeCardList');

        // 支付订单退款(微信支付退款)
        Route::get('wxRefund/{id}', 'wx\WxPayController@wxRefund');

        Route::post('orderConfirm', 'WxShareGiftsController@order_confirm');

        /*
        |--------------------------------------------------------------------------
        | 支付完成后的回调通知 callback
        |--------------------------------------------------------------------------
        |
        */

        // 订单支付回调
        Route::post('callbackWithOrder', 'wx\WxPayController@wxCallback');

        // 会员卡支付成功回调
        Route::post('callbackWithMembership', 'MemberShipController@wxPayCallback');

        // 充值支付成功回调
        Route::post('callbackWithRecharge', 'MemberRechargeController@wxPayCallback');

    });

});

// =================================================== 以下方法待修改 ====================================================

/*
|--------------------------------------------------------------------------
| 用户授权信息存入数据库
|--------------------------------------------------------------------------
|
*/
//Route::get('/oauth', 'wx\LoginController@createOrUpdate')->middleware('wechat.oauth');  //授权登录
Route::get('/oauth', 'wx\LoginController@wxLoginCreate')->middleware('check.login');  //授权登录

/*
| 请用微信打开页面
*/
Route::get('/notWeixin', function () {
    return view('errors.notWeixin');
});


