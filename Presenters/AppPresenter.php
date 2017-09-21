<?php
namespace App\Presenters;

use Route;

class AppPresenter
{
    /**
     * 获取控制器方法名称
     * author weixinhua
     * @param null $name
     * @return string
     */
    public function activeMenuByAction($name = null)
    {
        $route  =  $route = Route::currentRouteAction();

        list($controller, $action) = explode('@', $route);

        if(isset($action) && $action == $name) {

            return 'active';
        }

        return '';
    }

    /**
     * 获取控制器名称
     * author weixinhua
     * @param null $name
     * @return string
     */
    public function activeMenuByController($name = null)
    {
        $route  =  $route = Route::currentRouteAction();

        list($controller, $action) = explode('@', $route);

        if(isset($controller) && $controller) {
            $controller_array = explode('\\',$controller);
            if ($name == $controller_array[4]){
                return 'active';
            }
        }

        return '';
    }
}