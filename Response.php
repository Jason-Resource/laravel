<?php

# 查看文档： https://laravel.com/api/master/Illuminate/Http/Response.html
 
return response('Hello World!');

response()->view('client.errors.404', [], 404);

response()->json([
	'code'=>0,
	'msg'=>'success'
]);

$previousUrl = URL::previous();
return response()->view('admin.auth.403', compact('previousUrl'));

// 来源链接
url()->previous();

// 输出一个xml文件
$view = cache()->remember('generated.sitemap', function () {
$posts = Post:all();
return view('generated.sitemap', compact('posts'))->render();
});
return response($view)->header('Content-Type', 'text/xml');
