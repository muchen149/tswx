<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4 0004
 * Time: 下午 4:05
 */

return [

    //聚合数据(身份证号码验证)
    'IdCard' => [
        'idKey'     => 'd2d45c0275cbb0dfd8f0af13bc9b7148',  // 身份证实名认证(key)
        'imgKey'    => '608587901af444a25f925e9502602a2e',  // 身份证OCR识别(key)
        'url'       => 'http://op.juhe.cn/idcard/query',
        'img_url'   => 'http://apis.juhe.cn/idimage/verify',
    ],
];