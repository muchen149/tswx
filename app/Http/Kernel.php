<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     * These middleware are run during every request to your application.
     * @var array
     */
    protected $middleware = [
        /*\App\Http\Middleware\EncryptCookies::class,
        \App\Http\Middleware\VerifyCsrfToken::class,

        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,

        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,*/

        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,


    ];

    /** 路由中间件组
     * The application's route middleware groups.
     * @var array
    */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            // \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /** 路由中间件，在定义路由组中用，或单独使用
     * The application's route middleware.
     * These middleware may be assigned to groups or used individually.
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        //'check.login'  => \App\Http\Middleware\LoginMiddleware::class,
        'check.login' =>  \App\Http\Middleware\WechatOauthMiddleware::class,

        //微信分享
        'wechat.oauth' => \Overtrue\LaravelWechat\Middleware\OAuthAuthenticate::class,
        'is.weixin' => \App\Http\Middleware\IsWexin::class,
        'share' =>  \App\Http\Middleware\Share::class,
    ];
}
