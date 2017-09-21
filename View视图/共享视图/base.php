<?php

##服务提供者
namespace App\Providers;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        view()->share('key', 'value');
    }
}

?>


<?php

##中间件
namespace App\Http\Middleware;

class Menus
{

    public function handle($request, Closure $next)
    {
    	$menus_list = [];

        view()->share('menus', $menus_list);

        return $next($request);
    }

}
