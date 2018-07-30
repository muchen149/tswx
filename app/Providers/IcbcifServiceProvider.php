<?php

namespace App\Yini\Jdif\Providers;

use Illuminate\Support\ServiceProvider;


class JdifServiceProvider extends ServiceProvider
{
    /**
     * 延迟加载
     *
     * @var boolean
     */
    protected $defer = true;

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
        $this->app->singleton(['App\\Yini\\Jdif\\Jdif' => 'jdif'], function($app){
            $jdif = new JdifManage();
            return $jdif;
        });
    }

    /**
     * 提供的服务
     *
     * @return array
     */
    public function provides()
    {
        return ['jdif', 'App\\Yini\\Jdif\\Jdif'];
    }

}
