<?php
#https://laravel.com/api/master/Illuminate/Http/Response.html
#https://laravel.com/api/5.3/Illuminate/Cookie/CookieJar.html

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

//$response = new \Illuminate\Http\Response();

/*
|--------------------------------------------------------------------------
| 设置   		return 和 hello 不可或缺
|--------------------------------------------------------------------------
*/
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
$cookie = cookie('name', 'value', $minutes); //先创建一个cookie的实例
return response('hello')->cookie($cookie);	 //推送到客户端

return response('hello')->cookie(
    'name', 'value', $minutes, $path, $domain, $secure, $httpOnly
);

return response('hello')->withCookie(
	'car','audi','60'
);

return response('hello')->withCookie(
    cookie()->make('fruit','apple','60')
);

return response('hello')->withCookie(
	cookie()->forever('name', 'value', $path, $domain, $secure, $httpOnly)	//永不过期
);

// 在回应之前先积累 cookie，回应时统一返回
Cookie::queue('key', 'value', 'minutes');

/*
|--------------------------------------------------------------------------
| 取值
|--------------------------------------------------------------------------
*/

echo request()->cookie('name');

echo $value = Cookie::get('name', 'default');

/*
|--------------------------------------------------------------------------
| 删除
|--------------------------------------------------------------------------
*/

return response('hello')->withCookie('name', '', -1);

return response('hello')->withCookie(
	cookie()->forget('name', $path, $domain)
);

Cookie::queue('name', '');

/*
|--------------------------------------------------------------------------
| 判断
|--------------------------------------------------------------------------
*/
if (Cookie::has('name')) { }