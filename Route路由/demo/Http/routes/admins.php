<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



Route::auth();

Route::group(['middleware' => ['auth:admin', 'menus', 'auth.admin']], function () {
    // 产品管理
    Route::controller('product', 'ProductController');
    // 权限管理
    Route::controller('auth', 'AuthController');
    // 附加功能
    Route::controller('increment', 'IncrementController');
    // 订单控制器
    Route::controller('order', 'OrderController');
    // 订阅管理
    Route::controller('subscribe', 'SubscribeController');
    // 用户管理
    Route::controller('users', 'UsersController');
    // 营销管理
    Route::controller('marketing', 'MarketingController');
    // 消息管理
    Route::controller('msg', 'MsgController');
    // 首页
    Route::get('/', 'IndexController@getIndex');
});

// demo
Route::get('test', function () {
    app('Logger')->addDebug('add-test', ['context']);
    app('Logger')->addInfo('add-test', ['context']);
});

