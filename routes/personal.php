<?php

// 个人中心
Route::group(['prefix' => 'personal'], function () {

    Route::get('index', 'MemberController@index')->middleware('check.login');       // 首页

    Route::get('becomeCjk', 'MemberController@becomeCjk');                          // 成为股东

    Route::get('shareholder', 'MemberController@Shareholder');                      // 股东

    Route::get('coin', 'MemberController@coin');                                    // 积分(依你币)

    Route::get('contacts', 'MemberController@contacts');                            // 人脉

    // 关于水丁网
    Route::get('about', function () {
        return view('user.about_SD');
    });

    // ==================================== 用户注册、登录、密码变更、个人中心设置——开始 ====================================

    //  1.1、用户注册账号初始化页面
    Route::get('userRegister', function () {
        return view('user.wx_user_register');
    });

    // 1.2、获取手机验证码【用于会员注册、重置登录密码、重置支付密码等业务】
    Route::post('getYZM', 'GetPhoneMsgCodeController@getMsgCode');

    // 1.3、注册保存用户信息
    Route::post('userRegisterSave', 'MemberController@saveRegisterSave');

    //  2.1、登录初始化页面【以用户名及密码登录】
    Route::get('userLoginView', function () {
        return view('user.wx_user_login');
    });

    // 2.2、登录提交【以用户名及密码登录】
    Route::post('userLoginSubmit', 'MemberController@userLoginSubmit');

    // 2.3、退出登录
    Route::get('userLogout', function () {
        Auth::logout();
        return redirect('/');
    });

    // 2.4已绑定的显示已绑定页面
    Route::get('userBindedView/{mobile}', function ($mobile) {
        return view('user.wx_user_mobileBinded', compact('mobile'));
    });

    // 3.1、会员中心账户信息【头像、昵称、绑定手机号】，如果没有绑定手机，提示用户绑定手机
    Route::get('userInfo', function () {
        return view('user.wx_user_info');
    });

    // 3.2、个人中心账号设置
    Route::get('accountSet', function () {
        return view('user.wx_user_setting');
    });

    // 3.3、用户登录密码重置初始化页面
    Route::get('userPasswordView', function () {
        return view('user.wx_user_password');
    });

    // 3.4、用户登录密码重置提交保存
    Route::post('userPasswordUpdate', 'MemberController@userPasswordUpdate');

    // 4.1、（当前用户）绑定手机初始化页面
    Route::get('userMobileBindView', function () {
        return view('user.wx_user_mobileBind');
    });

    // 4.2、（当前用户）绑定手机提交页面【输入手机号、验证码后提交】
    Route::post('userMobileBindUpdate', 'MemberController@mobileBind');

    // 5.1、用户虚拟币支付密码重置初始化页面
    Route::get('userSetPayPwdView', function () {
        return view('user.wx_user_setPayPwd');
    });

    // 5.2、用户虚拟币支付密码重置提交保存
    Route::post('userPayPwdUpdate', 'MemberController@userPayPwdUpdate')->middleware('check.login');

    // 5.3、用户支付密码验证初始化页面
    Route::get('userCheckPayPwdView', function () {
        return view('user.wx_user_checkPayPwd');
    });

    // 5.4、用户支付密码验证提交
    Route::post('userCheckPayPwd', 'MemberController@checkPayPwd')->middleware('check.login');

    // 充值
    Route::get('payRecharge', 'MemberRechargeController@payRecharge');
    // 充值
    Route::post('getPayJosnForRechargeByajax', 'MemberRechargeController@getPayJosnForRechargeByajax');


    // ==================================== 用户注册、登录、密码变更、个人中心设置——结束 ====================================

    Route::group(['prefix' => 'address'], function () {

        Route::group(['middleware' => 'check.login'], function () {

            Route::get('setDefault/{id}', 'MemberAddressController@setDefault');    // 设置默认收货地址

            Route::get('delete/{id}', 'MemberAddressController@delete');            // 删除收货地址

            Route::get('child/{id}', 'MemberAddressController@getChild');           // 根据父id获取子地址信息

            Route::get('addressList', 'MemberAddressController@getAddressList');    // 地址管理中用户的地址列表

            Route::get('addressAdd', 'MemberAddressController@addressAdd');         // 地址管理中添加地址

            Route::get('addressEdit/{id}', 'MemberAddressController@addressEdit');  // 地址管理中编辑地址
        });

        Route::post('save', 'MemberAddressController@save');                        // 保存收货地址(新增或编辑)

        Route::post('addressSave', 'MemberAddressController@addressSave');          // 地址管理中保存新添加或者编辑的地址

    });

    // 我的收藏【分页列表、收藏、取消收藏】,被收藏主题类型(subjectType)【1品牌;2商品SPU;3商品SKU;4分销商;】
    Route::group(['prefix' => 'collect'], function () {

        Route::get('list/{subjectType?}/{pageNumber?}/{pageSize?}/{subjectName?}', 'MemberCollectController@index')->middleware('check.login');

        Route::post('add', 'MemberCollectController@add');

        Route::post('cancel', 'MemberCollectController@cancel');

    });

    // 我的足迹【分页列表、浏览】
    Route::group(['prefix' => 'browse', 'middleware' => 'check.login'], function () {

        Route::get('list/{subjectType?}/{pageNumber?}/{pageSize?}/{subjectName?}', 'MemberBrowseController@index')->middleware('check.login');

        Route::post('add', 'MemberBrowseController@add');

    });

    // 卡余额明细
    Route::get('balanceLog', 'MemberController@balanceLog')->middleware('check.login');

    // 零钱明细
    Route::get('walletLog', 'MemberController@walletLog')->middleware('check.login');

    // 虚拟币明细
    Route::get('vrcoinLog', 'MemberController@vrcoinLog')->middleware('check.login');

    // 更多卡券
    Route::get('moreCard', 'MemberController@moreCard')->middleware('check.login');

    // 中奖记录
    Route::get('awardsRecord', 'MemberController@awardsRecord')->middleware('check.login');

    // 充值卡
    Route::group(['prefix' => 'wallet/rechargeCards'], function () {

        // 个人中心-》我的充值卡
        Route::get('myRechargeCard', 'MemberRechargeController@myRechargeCard');

        // 充值卡列表(用于充值)
        Route::get('rechargeCardList', 'MemberRechargeController@rechargeCardList');


        // 充值账户详情
        Route::get('rechargeAccountDetail', 'MemberRechargeController@rechargeAccountDetail');

        // 充值卡详情
        Route::get('rechargeCardDetail/{cardId}', 'MemberRechargeController@rechargeCardDetail');

        // 余额提现
        Route::post('cash', 'MemberRechargeController@cash');

        // 扫码充值
        Route::post('bind', 'MemberRechargeController@bind');

    });

    // 我的礼券
    Route::group(['prefix' => 'wallet/giftCoupons'], function () {

        // 个人中心-》我的礼券
        Route::get('myGiftCoupon', 'MemberCouponController@myGiftCoupon')->middleware('check.login');

        // 礼券详情(选择礼品包)
        Route::get('choseGiftCouponGoods/{giftCouponId}', 'MemberCouponController@choseGiftCouponGoods')->middleware('check.login');

        // 保存礼券商品
        Route::post('saveCouponGoods', 'MemberCouponController@saveCouponGoods');

    });

    // 用户申请
    Route::group([], function () {
        // 进度列表
        Route::get('joininList', 'MemberJoinInController@myJoininList')->middleware('check.login');

        // 进度详情
        Route::get('joininDetail/{id}', 'MemberJoinInController@joininDetail')->middleware('check.login');

        // 完善申请页面
        Route::get('perfectApply/{applyType}', function ($applyType) {
            return view('user.prefect_apply', compact('applyType'));
        })->middleware('check.login');

        // 上传图片
        Route::post('uploadImg', 'MemberJoinInController@uploadImg');

        // 提交申请
        Route::post('delImg', 'MemberJoinInController@delImg');

        // 提交申请
        Route::post('submitApply', 'MemberJoinInController@submitApply');

        // 取消申请
        Route::post('cancelApply', 'MemberJoinInController@cancelApply');

    });

    // 用户身份证绑定
    Route::group([],function () {

        // 上传身份证照片
        Route::post('uploadIdImg','MemberController@uploadIdImg');

        //删除照片
        Route::post('delIdImg', 'MemberController@delIdImg');

        // 绑定身份证号
        Route::any('bingIdCard','MemberController@bingIdCard');

        // 绑定身份证照片
        Route::any('bingIdCardImg','MemberController@bingIdCardImg');

    });

});