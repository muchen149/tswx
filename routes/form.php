<?php
/**
 * Created by PhpStorm.
 * 报名入口
 * Date: 2017/10/9 0009
 * Time: 下午 5:29
 */
Route::group(['prefix' => 'form'], function () {

    // 报名页
    Route::get('index/{id}', 'FormController@index')->middleware('check.login');


    // 报名页
    Route::post('add', 'FormController@add');

    //表单
    Route::get('ajax_form', 'FormController@ajax_form');


});