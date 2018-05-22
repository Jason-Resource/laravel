```php

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

```

- 输出一个远程图片
```php
http://admin.shushan.cc/admin/show-remote-img?image_url=https://img1.doubanio.com/view/subject/m/public/s29740437.jpg

<img src="{{route('show.remote.image')}}?image_url={{$info->small_image}}" alt="" style="height: 150px">

/**
     * 显示远程图片
     *
     * @param Request $request
     * @return mixed
     * @throws JsonException
     */
    public function showRemoteImage(Request $request)
    {
        $image_url = $request->get('image_url');
        if (empty($image_url)) {
            throw new JsonException(10000);
        }

        // 获取图片文件相关信息
        $info = getimagesize($image_url);
        //获取文件后缀
        $imgExt = image_type_to_extension($info[2], false);
        //获取图片的 MIME 类型
        if (isset($info['mime'])) {
            $mime = $info['mime'];
        } else {
            $mime = image_type_to_mime_type(exif_imagetype($image_url));
        }

        $file = file_get_contents($image_url);
        return response($file, 200)
            ->header('Content-Type', $mime);
    }
```
