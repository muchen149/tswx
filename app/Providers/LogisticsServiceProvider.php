<?php

// 运单物流服务
namespace App\Providers;

use App\lib\Logistics;
use Illuminate\Support\ServiceProvider;

class LogisticsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('logistics', function () {
            return new Logistics();
        });
    }

}
