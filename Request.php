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

$request->route()->getActionName();
$request->session()->all();
$request->cookie();

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



