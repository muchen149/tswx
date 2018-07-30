<?php

namespace App\Providers;

use App\lib\ApiResponseByHttp;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * 服务提供者加是否延迟加载
     * @var bool
     */
    public function boot()
    {
        // 设置Carbon日期显示为中文(是为了让 diffForHumans() 方法返回的 "1 hours after" 显示为 "一小时后"
        \Carbon\Carbon::setLocale('zh');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('api', function () {
            return new ApiResponseByHttp();
        });
    }

}
