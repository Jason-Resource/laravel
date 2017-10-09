<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Predis\Client;

class AppServiceProvider extends ServiceProvider
{
    //延时
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //Redis
        $this->app->singleton('assistant_redis', function ($app) {
            $redis = app('redis')->connection('assistant');
            return $redis;
        });
    }
    
    
    /**
     * 延时
     */
    public function provides()
    {
        return array(
            'assistant_redis'
        );
    }
}
