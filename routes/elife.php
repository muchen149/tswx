<?php
/**
 * Created by PhpStorm.
 * 报名入口
 * Date: 2017/10/9 0009
 * Time: 下午 5:29
 */
Route::group(['prefix' => 'elife'], function () {

    // 获取用户信息 分行窗口
    Route::any('user', 'elife\UserController@getLoginUserInfo');
	// 获取用户信息 总行窗口1
    Route::any('user_one', 'elife\UserController@getLoginUserInfoOne');
	// 获取用户信息 总行窗口2
    Route::any('user_two', 'elife\UserController@getLoginUserInfoTwo');

	//默认首页
    Route::any('eLifeIndex', 'elife\IndexController@eLifeIndex');
    //分类页
    Route::any('classGoods/{column_id}', 'elife\IndexController@classGoods');
    //商品SPU详情页
    Route::get('goods/spuDetail/{spuId}', 'elife\GoodsController@spuDetail');
    // 商城懒加载商品列表
    Route::get('goods/ajax/shopSpuList/{pageNumber?}/{pageSize?}/{gcId?}', 'elife\GoodsController@spuShopQuery');

    //优惠劵
    Route::group(['prefix' => 'coupon'], function () {

        //优惠劵列表
        Route::get('list/{id}', 'elife\CouponController@list');

        //优惠劵活动列表
        Route::get('couponActive', 'elife\CouponController@couponActive');

        //优惠劵领取
        Route::post('couponDraw', 'elife\CouponController@ajax_couponDraw');

    });


	//产品
    Route::group(['prefix' => 'goods'], function () {
		//查看商品SPU
		Route::post('getSku', 'elife\GoodsController@getSku');
	});

	//产品 FIXME
    Route::group(['prefix' => 'order'], function () {
		//查看商品SPU
		Route::post('spuDetail', 'elife\GoodsController@spuDetail');
	});

    //订单
    Route::group(['prefix' => 'order'], function () {
		//生成订单
	    Route::post('add', 'elife\OrderController@add');
         //订单详情 E生活跳转分行商户详情
        Route::any('info', 'elife\OrderController@info');
		//订单详情 E生活跳转总行A商户详情
        Route::any('info_one', 'elife\OrderController@info_one');
		//订单详情 E生活跳转总行B商户详情
        Route::any('info_two', 'elife\OrderController@info_two');
        //订单购买信息确认页【预结算】
        Route::post('showPay', 'elife\OrderController@showPay');
		//展示订单列表
		Route::any('index/{state}', 'elife\OrderController@index');
		//单个订单详情
        Route::get('e_info/{id}', 'elife\OrderController@e_info');
		//工银E生活订单列表
		Route::any('deliver_goods', 'elife\OrderController@deliver_goods');
		// 是否能够配送到
		Route::post('canBuy', 'elife\OrderController@canBuy');
        //申请退款
        Route::get('applyRefund/{id}','elife\OrderController@applyRefund');
		//立即申请
		Route::post('saleOrder', 'elife\OrderController@saleOrder');
        //退单状态
        Route::get('saleOrderState/{id}','elife\OrderController@saleOrderState');
		//确认收货【订单内每种商品，都发货后，可以确认收货】
        Route::any('transferConfirmReceipt/{id}', 'elife\OrderController@transferConfirmReceipt');
        //查看物流
        Route::get('logistics/{id}', 'elife\OrderController@logistics');
		//1分钟定时执行检测过期订单
		Route::get('remove_order', 'elife\OrderController@remove_order');
		//每天凌晨1点检测一次超过15天未确认收货订单
		Route::any('automatic_receipt ', 'elife\OrderController@automatic_receipt');
    });
	
    //支付
    Route::group(['prefix' => 'pay'], function () {

		// 平台订单支付
		Route::any('gypay', 'elife\ICBCController@pay');
		//支付回调
		Route::any('notify', 'elife\ICBCController@notify');
		//工银E生活退款
		Route::any('reject', 'elife\ICBCController@reject');

    });

    //购物车
    Route::group(['prefix' => 'cart'], function () {

        Route::get('index/{num?}', 'elife\CartController@index');                         // 购物车列表分页数据

        Route::get('updateNum/{cartId}/{num}', 'elife\CartController@updateNumBySkuId');  // 更新商品数量

        Route::get('clean', 'elife\CartController@cleanNullGoods');                       // 清除过期商品

        Route::post('add', 'elife\CartController@add');                                   // 增加商品到购物车

        Route::post('delete', 'elife\CartController@delete');                             // 批量删除商品

    });

    //个人中心
    Route::group(['prefix' => 'personal'], function () {
        Route::get('index', 'elife\MemberController@index');                               // 个人首页
        // 3.2、个人中心账号设置
        Route::get('accountSet', function () {
            return view('elife.user.wx_user_setting');
        });
        Route::get('addressList', 'elife\MemberController@getAddressList');                 // 地址管理中用户的地址列表
        Route::get('explain', 'elife\MemberController@explain');                           // 帮助中心数据展示
        Route::get('idcardimg', 'elife\MemberController@idcardimg');                           // 帮助中心数据展示
        // 会员中心账户信息【头像、昵称、绑定手机号】，如果没有绑定手机，提示用户绑定手机
        Route::get('userInfo', function () {
            return view('elife.user.wx_user_info');
        });
        // （当前用户）绑定手机初始化页面
        Route::get('userMobileBindView', function () {
            return view('elife.user.wx_user_mobileBind');
        });
        // (当前用户)绑定身份证号
        Route::post('bingIdCard','elife\MemberController@bingIdCard');

    });

    //商城活动
    Route::get('articleDeTaiLe/{id}', 'elife\ArticleController@articleDeTaiLe');

    Route::any('notify', 'elife\ICBCController@notify');//->middleware('check.login');       // 首页

    Route::any('pay', 'elife\ICBCController@pay');//->middleware('check.login');       // 测试支付
});





