<?php

// 依你币（积分）服务
namespace App\Providers;

use App\lib\CoinRule;
use Illuminate\Support\ServiceProvider;

class CoinRuleServiceProvider extends ServiceProvider
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
        $this->app->singleton('coin_rule', function () {
            return new CoinRule();
        });
    }

}
