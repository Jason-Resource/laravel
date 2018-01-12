- 查看版本
```
composer -V
```

- 更新到最新版本
```
composer selfupdate
```

- 查看配置
```
composer config -lg
```

- 如果不是用国内镜像，则配置一下
```
composer config -g repo.packagist composer https://packagist.phpcomposer.com
```

- 默认安装，会根据本地PHP版本等环境，自动选择最新稳定版本
```
composer create-project --prefer-dist laravel/laravel blog
```

- 安装指定版本
```
composer create-project --prefer-dist laravel/laravel lar52 "5.2.*"
composer create-project --prefer-dist laravel/laravel lar53 "5.3.*"
composer create-project --prefer-dist laravel/laravel lar54 "5.4.*"
```

- 查看版本
```
php artisan -V
```
