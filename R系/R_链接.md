```php

$url = url("/posts/{$id}"); // http://example.com/posts/1


route('route_name', ['id' => $id]);

$url = action('HomeController@index');
$url = action('UserController@profile', ['id' => 1]);

action('Wechat\InvoiceController@getShow').'?id='.$invoice_id;

// 用它提供的方法检测 URL 是否有效
if (app('url')->isValidUrl($rootUrl)) {
    app('url')->forceRootUrl($rootUrl);
}

// 强制生成使用 HTTPS 协议的 URL
app('url')->forceSchema('https');
```
