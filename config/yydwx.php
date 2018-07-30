<?php

return [

    /*
    |--------------------------------------------------------------------------
    | sdwx配置文件
    |--------------------------------------------------------------------------
    |
    */
    /**
     * 加密算法
     */
    'cipher' => 'DES-CBC',

    /**
     * 加密密钥
     */
    'key' => 'yesQg7cpa3bF0mjGJIjN+j/H7OElV+fmGyEZdGNHwEE=',

    /**
     * 加密盐值
     */
    'iv' => 'sdwxfx_c',
    
    /**
     * 物流配置
     */
   /* 'eBusinessID' => '1269248', //电商ID
    'appKey' => 'e9efed8c-f0dd-4678-b3d9-6ed1fe6eb701',   //电商加密私钥，快递鸟提供，注意保管，不要泄漏
    'reqUR' => 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx', //请求url*/
    'eBusinessID' => '1296779', //电商ID
    'appKey' => '6a86c987-000a-44c1-804c-1d121f5f9977',   //电商加密私钥，快递鸟提供，注意保管，不要泄漏
    'reqUR' => 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx', //请求url

    /**
     * 验证码
     */
    'sdwx_captcha_cache_tags' => ['sdwx.captcha'],
    'captcha_cache_expire' => 10, //ten minutes
    'captcha_width' => 168,
    'captcha_height' => 72,
    'captcha_font' => null,

    /**
     * 微信jsapi
     */
    'sdwx_jsapi_ticket_cache_tags' => ['sdwx.jsapi_ticket'],
];
