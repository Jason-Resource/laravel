<?php
use Illuminate\Support\Facades\Request;
#直接在任意位置即可调用：Request::all()

use Illuminate\Http\Request;
#需要在方法中先声明 test(Request $request) ； 然后在方法里调用 $request->all()

$request = app('request');
#也可以直接make出来

/******************* 压入/出参数 *******************/

$request->offsetSet('alias_name', config('site.push_channel_name'));
$request->offsetUnSet('alias_name');

/******************* 获取传入参数 *******************/
$data = $request->all();//获取所有输入参数
$data = $request->input();
$data = $request->query();

echo $request->input('id');//获取单个参数值，下面也一样的效果
echo $request->get('id');
echo $request->query('id');

echo $request->id;//动态属性--->这样获取貌似更快

/******************* 客户端信息 *******************/

// 返回当前页面的地址:http://a.com/platforms
URL::full();
url()->full();
// 返回当前页面的完整路径:http://a.com/platforms
URL::current();
url()->current();
// 返回前一个页面的地址:http://a.com
URL::previous();
url()->previous();
// https://jiahe.com/css/foo.css
URL::secureAsset('css/foo.css');

$request->server('HTTP_REFERER');//上一个页面，可能为空           $pre_url = url()->previous();
$request->server('HTTP_USER_AGENT');
$request->server('REDIRECT_STATUS');
$request->server('REQUEST_TIME');

$request->path();
$request->url();
$request->fullUrl();
$request->getHttpHost();
$request->method();
$request->ip();

$request->route()->getAction();
$request->route()->getActionName();
$request->session()->all();
$request->cookie();

// 获取请求 Uri: /aa/bb/?c=d
$request->getRequestUri();
// 获取 Uri: http://xx.com/aa/bb/?c=d
$request->getUri();

/******************* 判断 *******************/

if($request->ajax()){
	//
}

if ($request->is('admin/*')) {
    //
}

if ($request->isMethod('post')) {
    //
}

if ($request->has('username')) {
    //
}

/******************* Input *******************/

use Illuminate\Support\Facades\Input;

Input::get();
Input::get('name');

/*用户提交信息持久化
有时可能需要在用户的多个请求之间持久化用户提交的信息。 比如，当用户提交的信息验证失败重新返回提交信息页面时还原用户的输入。
将用户提交的信息存入Session*/


Input::flash();
Input::flashOnly('username', 'email');	//把指定的用户提交的信息存入Session
Input::flashExcept('password');

 




//如果你需要关联持久用户提交的信息的操作和重定向操作，可以使用如下的链式调用的方法：


return Redirect::to('form')->withInput();
 

return Redirect::to('form')->withInput(Input::except('password'));

 

//注意： 如果你想持久化其它的信息，请参考 Session 类.
//获取已持久化的用户提交的信息


Input::old('username');

// 也可以使用全局函数获取
old('username');

