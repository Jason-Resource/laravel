<?php 

/**************************************************************************************************************************************************************************
* 查询所有路由列表
*/
$route_list = [];
$routes = \Illuminate\Support\Facades\Route::getRoutes(); //----> 这里获取的是\Illuminate\Routing\RouteCollection实例，即是一个Route集合
foreach ($routes as $k=>$v){// 获取单个\Illuminate\Routing\Route实例
    $route = $v->getPath(); 
    if(!empty($route) && strpos($route,'_missing')===false
        && strpos($route,'login')===false
        && strpos($route,'logout')===false
        && strpos($route,'test')===false
        && $route!='admin'
        && $route!='/'
    ){
        $arr = explode('/',$route);
        $short_route = $arr[0].'/'.$arr[1].'/'.$arr[2];
        $route_list[$k]['route'] = $short_route;
        $route_list[$k]['display_name'] = $arr[2];
        $route_list[$k]['created_at'] = Helper::getNow();
        $route_list[$k]['updated_at'] = Helper::getNow();
    }
}

echo DB::table('permissions')->insert($route_list);
?>