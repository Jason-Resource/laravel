<?php

/*
放在 /routes/console.php
使用于 Laravel Framework 5.4.24
*/

Artisan::command('make:route', function () {

    // 先清除路由缓存
    \Illuminate\Support\Facades\Artisan::call('route:clear');

    // 获取所有路由
    $routes = \Illuminate\Support\Facades\Route::getRoutes();

    $route_list = [];
    foreach ($routes as $k=>$v){

        // 请求路径
        $uri = $v->uri();
        // 获取前缀
        $prefix = $v->getPrefix();
        // 支持的请求方法
        $allow_method = $v->methods();

        $arr = explode('/',$uri);

        if(!empty($uri) && strlen($prefix)>1 && count($arr)==2){

            // 是否接口
            $is_api = 0;
            if (in_array('POST', $allow_method)) {
                $is_api = 1;
            }

            $display_name = $arr[1];

            $route_list[$k]['route'] = $uri;
            $route_list[$k]['display_name'] = $display_name;
            $route_list[$k]['is_api'] = $is_api;
            $route_list[$k]['created_at'] = \App\Http\Common\Helper::getNow();
            $route_list[$k]['updated_at'] = \App\Http\Common\Helper::getNow();
        }
    }

    // 加入表
    foreach ($route_list as $item) {

        // 判断路由是否已经存在
        $info = \Illuminate\Support\Facades\DB::select('SELECT `id` FROM cms_permissions WHERE `route` = :route', ['route'=>$item['route']]);
        if (is_array($info) && isset($info[0])) {
            continue;
        }

        // 插入数据
        $flag = \Illuminate\Support\Facades\DB::table('permissions')->insert($item);
        var_dump($flag);
    }

})->describe('get all route');