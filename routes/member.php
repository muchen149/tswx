<?php

// 会员个人信息
Route::group(['prefix' => 'member'], function () {

    //分享卡活动路由--start

    //领卡
    Route::post('getshareCard', 'MemberController@getshareCard')->middleware('check.login');

    //分享后进入的领卡页面
    Route::get('shareCardInfo/{id}', 'MemberController@shareCardInfo')->middleware('check.login');

    //领取卡
    Route::get('getShareCards', 'MemberController@getShareCards')->middleware('check.login');

    //分享活动首页
    Route::get('shareIndex', 'MemberController@shareIndex')->middleware('check.login');
    //分享卡活动路由--end

    // 扫码绑定仓点管理员
    Route::get('bindCdAdmin/{store_id?}', 'MemberController@bindCdAdmin')->middleware('check.login');

    // tongjiNeida
    Route::get('stat', 'MemberController@tongjiNeida');


    // 管家中心
    Route::get('center', 'MemberController@center')->middleware('check.login');

    // 会员详细信息
    Route::get('detail', 'MemberController@detail');

    // 续费会员(传入要购买的会员等级grade), 不传入则相当于提升会员等级(选择购买)
    Route::get('buy/{grade?}', 'MemberController@buy');

    // 会员详情下的续费
    Route::get('pay_privilege', function () {
        return view('member.my_member_pay_privilege');
    });

    // 会员详情中的提升级别
    Route::get('buy_privilege', function () {
        return view('member.my_member_buy_privilege');
    });

    // 尊享优惠
    Route::get('privilege_detail', function () {
        return view('member.my_member_privilege_detail');
    });

    // 邀请好友
    Route::get('inviteFriend', 'MemberJoinInController@invite_friend');//->middleware('share');;

    // 用户点击分享的邀请链接,member_id为分享者的id，跳到展示礼品页面
    Route::get('toGetReward/{member_id}', 'MemberJoinInController@toGetReward');

    // 用户点击链接,直接领取会员卡，跳到卡包页面
    Route::get('toGetMemberCard/{activity_id}', 'MemberJoinInController@toGetMemberCard')->middleware('check.login');;


    Route::group(['prefix' => 'callFriend', 'namespace' => 'member'], function () {

        // 呼朋唤友
        Route::get('index', 'CallFriendController@index');

    });

    Route::get('callFriend', function () {
        return view('member.my_member_callFriend');
    });

    // 一起买
    Route::get('callFriendBuy', function () {
        return view('member.my_member_callFriendBuy');
    });

    // 申请采购 (again 代表是否是从审核失败中点击重新申请过来的：1是)
    Route::get('manage_procurement/{again?}', 'MemberJoinInController@manage_procurement')->middleware('check.login');

    // 申请采购详情
    Route::get('manage_procurement_detail', 'MemberJoinInController@manage_procurement_detail');

    // 提交申请材料
    Route::post('submitAdd', 'MemberJoinInController@submitAdd');

    // 我要供货
    Route::get('manage_supply', 'MemberJoinInController@manage_supply')->middleware('check.login');

    // 供货详细信息
    Route::get('manage_supply_detail', 'MemberJoinInController@manage_supply_detail');

    // 提交供货申请材料
    Route::post('supplyAdd', 'MemberJoinInController@manage_supply_add');

    // 管家
    Route::get('call', function () {
        return view('member.my_member_call');
    });

    // 团购去支付
    Route::get('group_to_pay', function () {
        return view('member.my_member_call_friend_Paysucceed');
    });

    // 去参加团购
    Route::get('join_group', function () {
        return view('member.my_member_call_friend_join');
    });

    // 参团我去购买
    Route::get('join_buy', function () {
        return view('member.my_member_join_buy');
    });

    // 扫码得会员
    Route::get('scan', function () {
        return view('member.my_member_scan');
    });

});

// 会员卡
Route::group(['prefix' => 'membership'], function () {

    // 个人中心 => 我的会员卡购买记录
    Route::get('getCardList/{use_state}', 'MemberShipController@enableCardList')->middleware('check.login');

    // 会员卡立即使用
    Route::get('use/{membership_id}', 'MemberShipController@useMemberShip')->middleware('check.login');

    // 立即领取
    Route::post('getCardToPackage', 'MemberShipController@fastGetByScan');

    // 会员卡线上支付(通过ajax获取微信支付json)
    Route::post('getPayJosnForMemberShip', 'MemberShipController@getPayJosnForMemberShipByajax');

    //免费体验
    Route::post('getExpJosnForMemberShip', 'MemberShipController@getExpJosnForMemberShip')->middleware('check.login');

});