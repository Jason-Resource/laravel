<?php
/*
|--------------------------------------------------------------------------
| 基于 \Illuminate\Session\Store.php
|--------------------------------------------------------------------------
|
| 参考：https://laravel.com/docs/5.3/session
|
| ****关于设置session值不进去的原因，除了看\storage 目录有没有写权限外，还要检查是否在中间件中加载了：
| \Illuminate\Session\Middleware\StartSession::class,
| 另外，调试session一定要走完全程，不能dd,exit，不然没过中间件，最后是没有设置进值的。
|
*/

#引入下面两个中的任意一个都可以使用 --> Session::
use Illuminate\Support\Facades\Session;
use Session;								//--->这个是因为在config/app.php的aliases里，定义了


/**************************

一共三种方式：
A、request()->session()						//推荐使用这种，代码有提示，方法最全

B、session()								//--->这个是在helpers.php中定义的，主要是使用快捷

C、Session::								//--->门面

**************************/

/**************************/
设：request()->session()->put('key', 'value');			# ->put(['key' => 'value']);
取：request()->session()->get('key', 'default');
删：request()->session()->forget('key');


设：session(['key' => 'value']);
取：session('key');

/**************************/

/*
|--------------------------------------------------------------------------
| 设置
|--------------------------------------------------------------------------
*/

session(['key' => 'value']);

########

request()->session()->put('key', 'value');

request()->session()->push('user.name', 'meinvbingyue');	//将meinvbingyue加入到已有key值(user.name)中


/*
|--------------------------------------------------------------------------
| 取值
|--------------------------------------------------------------------------
*/

Session::get('key');

Session::get('user.name', 0);

########

session('key');

session()->get('key');

########

$values = request()->session()->all();	//获取所有

$value = request()->session()->pull('usre.name', 'default');	//获取值，没有则默认，获取后马上销毁

$value = request()->session()->get('key', 'default');

$value = request()->session()->get('key', function() {
    return 'default';
});

/*
|--------------------------------------------------------------------------
| 删除
|--------------------------------------------------------------------------
*/
Session::forget('key');

request()->session()->forget('key');

request()->session()->flush();

/*
|--------------------------------------------------------------------------
| 获取SESSION_ID
|--------------------------------------------------------------------------
*/
Session::getId();

request()->session()->getId();

/*
|--------------------------------------------------------------------------
| 重新生成会话标识
|--------------------------------------------------------------------------
*/
request()->session()->regenerate();

/*
|--------------------------------------------------------------------------
| 判断
|--------------------------------------------------------------------------
*/
Session::has('users');

request()->session()->has('users');


/*
|--------------------------------------------------------------------------
| session数据暂存
| 数据暂存是把session中的数据保留到下一次请求中，下一次请求结束后则删除数据
|--------------------------------------------------------------------------
*/
Session::flash('session.store', 'Store');		// 把'session.store'数据刷到'_flash.new'，等待下一次请求使用，然后再删除
Session::reflash();								// 把所有本次需要删除的数据全部刷到'_flash.new'中，等待下一次请求使用，然后再删除
Session::keep(['session.store' => 'Store']);	// 把要删除的'session.store'重新激活，刷到'_flash.new'中，等待下一次使用