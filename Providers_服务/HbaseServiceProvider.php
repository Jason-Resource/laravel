<?php

namespace App\Providers;

use App\Http\Common\SimpleHbase;
use Illuminate\Support\ServiceProvider;

class HbaseServiceProvider extends ServiceProvider
{
    //开启延时加载
    //protected $defer = true;
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*//股票的 hbase 操作
        $this->app->singleton('StockHbase',function(){
            return SimpleHbase::getInstance('stock');
        });*/
        
    }
    
    
    /**
     * 延时加载
     * @author  jianwei
     */
    public function provides()
    {
        /*return array(
            ''
        );*/
    }
    
}
