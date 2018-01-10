## 安装扩展

- composer require predis/predis
 
## 配置

- .env
```
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=123456

```

##  测试
```
Route::get('/', function () {
    $redis = app('redis');
    $redis->set('name', 'json');
    dd($redis->get('name'));
});


```
