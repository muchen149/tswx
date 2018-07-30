<?php
/**
 * Created by PhpStorm.
 * User: yiyuanda
 * Date: 2017/12/28
 * Time: 14:12
 * 机构卡路由
 */
Route::group(['prefix'=>'orgcards'], function(){

    Route::group(['middleware' => 'check.login'], function () {
        //机构卡列表
        Route::get('cardList', 'OrgCardsController@cardList');

        //领取特定机构的卡
        Route::get('showOrgcard/{orgid}', 'OrgCardsController@showOrgcard');

        //领取特定机构、特定子分机构的卡
        Route::get('showSubOrgcard/{orgid}/{suborgid}', 'OrgCardsController@showSubOrgcard');

        // 领取机构卡
        Route::post('add', 'OrgCardsController@add');

        //更新机构标识
        Route::post('updateFlag', 'OrgCardsController@updateFlag');

        //机构卡信息
        Route::any('orgcardInfo/{card_id}', 'OrgCardsController@orgcardInfo');

        //专属商品也
        Route::any('orgcardGoods/{card_id}', 'OrgCardsController@orgcardGoods');


        //test
        Route::any('test',function(){
            return view('orgcards.org_card_list');
        });




    });
});
