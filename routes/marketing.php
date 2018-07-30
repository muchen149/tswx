<?php

Route::group(['prefix' => 'marketing'], function () {

    Route::group(['prefix' => 'dispatch'], function () {

        // 数字码验证页面
        Route::get('checkNumberPage', 'MarketingDispatchController@checkNumberPage')->middleware('check.login');

        // 验证码验证
        Route::post('checkCaptcha', 'MarketingDispatchController@checkCaptcha');

        // 获取验证码
        Route::post('captcha', 'MarketingDispatchController@captcha');

        // 提交
        Route::post('submit', 'MarketingDispatchController@submit');

        // 扫码
        Route::get('scan/{code}', 'MarketingDispatchController@scan')->middleware('check.login');

    });

    // 虚拟商品直接充值
    Route::post('recharge', 'MarketingDispatchController@recharge');

    // 抽奖活动
    Route::group(['prefix' => 'lottery'], function () {

        // 玩一玩
        Route::get('play/{activity_id}', 'MemberLotteryController@play')->middleware('check.login');

        // 计算中奖概率同时记录中奖记录
        Route::post('handleLottery', 'MemberLotteryController@handleLottery');

        // 完善领奖信息
        Route::get('perfectAwardInfo/{awardsrecord_id}', 'MemberLotteryController@perfectAwardInfo')->middleware('check.login');

        // 完善用户基本信息
        Route::post('perfectMemberInfo', 'MemberLotteryController@perfectMemberInfo');

        // 现场领取奖品
        Route::post('receiveAward', 'MemberLotteryController@receiveAward');

        // 领取红包
        Route::post('receiveRedpack', 'MemberLotteryController@receiveRedpack');

    });

});
