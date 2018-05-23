<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapWebRoutes($router);
        $this->mapAdminRoutes($router);
        $this->mapWechatRoutes($router);

        //
    }

    /**
     * 遍历后台所有路由
     *
     * @return void
     * @author chentengfeng @create_at 2017-01-18  09:57:15
     */
    protected function mapAdminRoutes($router)
    {
        $router->group([
            'namespace' => $this->namespace . '\Admin', 'middleware' => 'web', 'prefix' => 'admin'
        ], function ($router) {
            require app_path('Http/routes/admins.php');
        });
    }

    /**
     * undocumented function
     *
     * @return void
     * @author chentengfeng @create_at 2017-01-18  09:57:15
     */
    protected function mapWechatRoutes($router)
    {
        $router->group([
            'namespace' => $this->namespace . '\Wechat', 'middleware' => ['area', 'web'], 'prefix' => 'wechat'
        ], function ($router) {
            require app_path('Http/routes/wechats.php');
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'web',
        ], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
