```
确保 .env 文件中 CACHE_DRIVER=redis ,更改缓存驱动为Redis

默认的缓存前缀为 laravel ，如果想要修改前缀可以在 config/cache.php 配置文件中 'prefix' => 'laravel' ，修改此项即可。

Laravel默认使用的Redis数据库为 db0 ，你也可以指定其他数据库，在 config/database.php 配置文件中。
```
