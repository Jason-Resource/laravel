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

- 关系

![图片说明](https://thumbnail0.baidupcs.com/thumbnail/0bb1a61351a36783f94281d964a550d4?fid=1593996327-250528-1001200771457497&time=1517194800&rt=sh&sign=FDTAER-DCb740ccc5511e5e8fedcff06b081203-f%2BA7FlbmakMK4x1ZOQYke%2F7iq98%3D&expires=8h&chkv=0&chkbd=0&chkpc=&dp-logid=660036032751605951&dp-callid=0&size=c710_u400&quality=100&vuk=-&ft=video)
