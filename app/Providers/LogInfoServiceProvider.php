<?php

namespace App\Providers;

use App\lib\LogInfo;
use App\lib\UploadManage;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\ServiceProvider;

class LogInfoServiceProvider extends ServiceProvider
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
        $this->app->singleton('loginfo', function () {
            return new LogInfo();
        });
    }

}
