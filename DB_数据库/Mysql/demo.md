## 创建数据库和表
```mysql
DROP TABLE IF EXISTS `sys_users`;
CREATE TABLE `sys_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
```

## 配置
- .env
  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=lumen
  DB_USERNAME=root
  DB_PASSWORD=
  DB_PREFIX=sys_
  ```
- \config\database.php

  ```
  'mysql' => [
      'driver' => 'mysql',
      'host' => env('DB_HOST', '127.0.0.1'),
      'port' => env('DB_PORT', '3306'),
      'database' => env('DB_DATABASE', 'forge'),
      'username' => env('DB_USERNAME', 'forge'),
      'password' => env('DB_PASSWORD', ''),
      'unix_socket' => env('DB_SOCKET', ''),
      'charset' => 'utf8mb4',
      'collation' => 'utf8mb4_unicode_ci',
      'prefix' => env('DB_PREFIX', ''),
      'strict' => true,
      'engine' => null,
  ],
  ```

##  添加模型
- \app\UserModel.php
  * php artisan make:model UserModel

```php
<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'users';
}

```

##  添加模型服务
- app\Providers\ModelProvider.php
  * php artisan make:provider ModelProvider

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModelProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('UserModel', 'App\UserModel');
    }

    public function provides()
    {
        return [
            "UserModel",
        ];
    }
}

```

- \bootstrap\app.php

```
App\Providers\ModelProvider::class,
```

##  测试
```
$router->get('/', function () use ($router) {
    $user = app('UserModel');
    $user->name = 'meinvbingyue';
    $flag = $user->save();

    echo $flag.PHP_EOL;

    $list = $user->get()->toArray();
    dd($list);
});


```
