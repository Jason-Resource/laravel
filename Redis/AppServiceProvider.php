<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    //开启延时加载
    protected $defer = true;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        //分时数据的 redis 链接
        $this->app->singleton('stock_redis',function(){
            $redis = app('redis')->connection('stock_redis');

            return $redis;
        });

    }

    /**
     * 延时加载
     */
    public function provides() {
        return array(
          'stock_redis',
        );
    }
}
