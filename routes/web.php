<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'GoodsController@index')->middleware('check.login')->middleware('share');
Route::get('index', 'GoodsController@index')->middleware('check.login')->middleware('share');
//Route::get('ys', 'ys\GoodsController@index')->middleware('check.login')->middleware('share');
Route::get('ys', 'ys\GoodsController@spuList')->middleware('check.login')->middleware('share');
/*
|--------------------------------------------------------------------------
| 商城路由, 需要登录的页面在指定路由文件添加指定的中间件
|--------------------------------------------------------------------------
|
*/

Route::group(['middleware' => ['share']], function () {//170817去掉昨天添加的 ,'check.login'

    require(__DIR__ . '/mall.php');                     // 商城

    require(__DIR__ . '/sd_mall.php');                  // 新增商城

    require(__DIR__ . '/personal.php');                 // 个人中心

    require(__DIR__ . '/cart.php');                     // 购物车

    require(__DIR__ . '/order.php');                    // 订单

    require(__DIR__ . '/marketing.php');                // 营销活动

    require(__DIR__ . '/share_gift.php');               // 微信礼品分享

    require(__DIR__ . '/thirdapi.php');                 // 第三方api

    require(__DIR__ . '/member.php');                   // 会员

    require(__DIR__ . '/wx.php');                       // 微信

    require(__DIR__ . '/ys.php');                       // 微信

    require(__DIR__ . '/form.php');                     // 报名

    require(__DIR__ . '/jiudian.php');                       // 酒店

    require (__DIR__ . '/orgcards.php');   //机构卡

    require (__DIR__ . '/elife.php');                   //e生活

});

Route::get('document/{doc_id}', 'ArticleController@document')->middleware('share');
//文章详情
Route::get('article/{article_id}', 'ArticleController@articleDetaile')->middleware('share');

// 平台订单自动拆单给供应商，传入参数：平台订单ID【plat_order_id】
Route::get('order/split/{id}', 'SupplierOrderController@orderSplit');

// 商品SPU列表页、详情页，yydad 电商管理平台专用
Route::get('spu/list', 'GoodsController@spuList');
Route::get('spu/detaile/{id}', 'GoodsController@spuDetail');

// 当前登录用户信息、退出登录
Route::group(['prefix' => 'user'], function () {
    Route::get('login/info', 'UserController@getLoginUserInfo');
    Route::get('logout', 'UserController@logOut');
});

// 接口测试路由
Route::group(['prefix' => 'test'], function () {
    Route::get('index', 'TestController@index');
    Route::post('login', 'TestController@login');
    Route::get('show/loginUser', 'TestController@ShowLoginUser');
    Route::any('test', 'TestController@test');
    Route::any('wxSendMsg', 'TestController@wxSendMsg');
    Route::post('wxDeliverMsg','TestController@wxDeliverMsg');
});
