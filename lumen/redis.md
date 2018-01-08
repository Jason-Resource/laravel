## 安装扩展

- composer require illuminate/redis (可能要注意版本问题)

- composer require predis/predis
 
## 加载服务

```
\bootstrap\app.php

$app->register(\Illuminate\Redis\RedisServiceProvider::class);
```

## 配置

```
根据 \config\database.php

.env 增加：
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_DATABASE=0
REDIS_PASSWORD=123456

```

##  测试
```
$router->get('/', function () use ($router) {
    $redis = app('redis');
    dd($redis->get('name'));
});

```
