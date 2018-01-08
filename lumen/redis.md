## 安装扩展

- composer require illuminate/redis (可能要注意版本问题)

- composer require predis/predis
 
## 加载服务

```
\bootstrap\app.php

$app->register(\Illuminate\Redis\RedisServiceProvider::class);
```
