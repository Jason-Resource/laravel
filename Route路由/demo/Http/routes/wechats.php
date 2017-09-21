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

// 用以微信支付支付页面的跳转
Route::group(['middleware' => ['column', 'pay-jump','wechat-login']], function () {
    // 订单
    Route::controller('order', 'OrderController');
});

Route::group(['middleware' => ['column','wechat-login']], function () {
    // 投顾咨询
    Route::controller('consultation', 'ConsultationController');
    // 用户中心
    Route::controller('user', 'UserController');
    // 产品
    Route::controller('product', 'ProductController');
    // 免费体验
    Route::controller('experience', 'ExperienceController');
    // 首页
    Route::get('/', 'IndexController@getIndex');
});


Route::any('serve', 'WechatController@serve');
Route::any('notify/{order_sn}', 'WechatController@notify');
Route::get('set-menu', 'WechatController@setMenu'); # 设置菜单栏


