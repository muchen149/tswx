<?php

return [

    // Debug 模式, 当值为 false 时，所有的日志都不会记录
    'debug' => true,

    // 使用 Laravel 的缓存系统
    'use_laravel_cache' => true,

    // 微信公众号配置
    'app_id' => env('WECHAT_APPID', 'wx22d541111c00fce1'),
    'secret' => env('WECHAT_SECRET', '0668efe462f31147c03b820f91079287'),
    'token' => env('WECHAT_TOKEN', 'yiyuandaKkua1Jj1Ffen'),
    'aes_key' => env('WECHAT_AES_KEY', 'KCtzWZ6TWt15Yupnyah5dweR5Nb4PwCoqpSwaDd7awc'),

    // 开放平台信息
    'open_platform' => [
        'app_id' => env('WECHAT_COMPONENT_APPID', 'wx1d22c08593ae1e72'),
        'secret' => env('WECHAT_COMPONENT_SECRET', 'affd3facbb57c21d2aa1f32f52cc4253'),
        'token' => env('WECHAT_COMPONENT_TOKEN', 'shuitine'),
        'aes_key' => env('WECHAT_COMPONENT_AES_KEY', 'rTQdhJnPTw5j5V6Z5x9ItO161rPUgu7QH8nXkI6o6MT')
    ],

    /*
     * 日志配置
     *
     * level: 日志级别，可选为：
     *                 debug/info/notice/warning/error/critical/alert/emergency
     * file：日志文件位置(绝对路径!!!)，要求可写权限
     */
    'log' => [
        'level' => env('WECHAT_LOG_LEVEL', 'debug'),
        'file' => env('WECHAT_LOG_FILE', storage_path('logs/wechat.log')),
    ],

    /*
     * OAuth 配置
     * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
     * callback：OAuth授权完成后的回调页地址(如果使用中间件，则随便填写。。。)
     */
    'oauth' => [
        'scopes' => ['snsapi_userinfo'],
        'callback' => env('WECHAT_OAUTH_CALLBACK', '/examples/oauth_callback.php'),
    ],

    /*
     * 微信支付
     */
    'payment' => [

        // WECHAT_PAYMENT_MERCHANT_ID=1388054602
        'merchant_id' => env('WECHAT_PAYMENT_MERCHANT_ID', '1361100902'),

        // WECHAT_PAYMENT_KEY=8sU63K7d0n3QM4Deq3sa924bksd7f23U
        'key' => env('WECHAT_PAYMENT_KEY', 'l2i0u0j8t0i6a2n4g0u1i2m6ei656838'),

        // WECHAT_PAYMENT_CERT_PATH='http://ynmo.yininet.com/cert.pem'
        'cert_path' => env('WECHAT_PAYMENT_CERT_PATH', public_path('/wx/cert/apiclient_cert.pem')),

        // WECHAT_PAYMENT_KEY_PATH='http://ynmo.yininet.com/key.pem'
        'key_path' => env('WECHAT_PAYMENT_KEY_PATH', public_path('/wx/cert/apiclient_key.pem')),

        // 你也可以在下单时单独设置来想覆盖它
        'notify_url' => env('MOBILE_MALL_DOMAIN', 'http://sdwx.shuitine.com') . '/wx/pay/callbackWithOrder',

    ],
];
