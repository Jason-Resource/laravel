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
Route::group(['prefix' => 'api', 'middleware' => 'jsonp'], function () {
    //客服聊天
    Route::group(['namespace' => 'ChatRoom'], function () {
        Route::controller('/chat-room', 'JudgeController');
    });

    Route::group(['namespace' => 'Api'], function () {
        //分类
        Route::controller('/category', 'CategoryController');
        //文章
        Route::controller('/article', 'ArticleController');
    });

});


/**
 * 后台
 */
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {

    //登录
    Route::get('/login', 'SystemController@anyLogin');
    //检查登录
    Route::any('/system/check-login', 'SystemController@anyCheckLogin');

    Route::group(['middleware' => 'checkPermission'], function () {
        //首页
        Route::get('/', 'IndexController@index');
        //分类管理
        Route::controller('/category', 'CategoryController');
        //文章管理
        Route::controller('/article', 'ArticleController');
        //系统管理
        Route::controller('/system', 'SystemController');
    });
});


/**
 * 前台
 */
Route::group(['namespace' => 'Client'], function () {

    //首页
    Route::get('/', 'IndexController@index');
    Route::get('/index.html', 'IndexController@index');

    //内容页
    Route::get('/news/{id}/', 'ArticleController@getContent')->where(['id' => '[0-9]+']);
    Route::get('/news/{id}/{page}.html', 'ArticleController@getContent')
        ->where(['id' => '[0-9]+', 'page' => '[0-9]+']);

    //搜索页
    Route::get('/{category_url}/s_{word}/', 'CategoryController@anyGetSearch')
        ->where(['category_url' => '[A-Za-z0-9]+']);
    Route::get('/{category_url}/s_{word}/{page}.html', 'CategoryController@anyGetSearch')
        ->where(['category_url' => '[A-Za-z0-9]+', 'page'=>'[0-9]+']);

    //分类页
    Route::get('/{category_url}/{page}.html', 'CategoryController@anyGetList')
        ->where(['category_url' => '[A-Za-z0-9/]+', 'page' => '[0-9]+']);

    Route::get('/{category_url}', 'CategoryController@anyGetList')
        ->where(['category_url' => '[A-Za-z0-9/(index.html)]+']);
});