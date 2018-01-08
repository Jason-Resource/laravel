## 安装扩展

- composer require jenssegers/mongodb
 
## 加载服务
- \bootstrap\app.php
```
$app->register(\Jenssegers\Mongodb\MongodbServiceProvider::class);
$app->withEloquent();   // 一定要在这之前加载
```

## 配置
- \config\database.php
```
'mongodb' => [
    'driver'   => 'mongodb',
    'host'     => env('MONGO_DB_HOST', 'localhost'),
    'port'     => env('MONGO_DB_PORT', 27017),
    'database' => env('MONGO_DB_DATABASE'),
    'username' => env('MONGO_DB_USERNAME'),
    'password' => env('MONGO_DB_PASSWORD'),
    'options'  => [
        //'database' => 'admin' // sets the authentication database required by mongo 3
    ]
],

```

- .env
```
MONGO_DB_HOST=127.0.0.1
MONGO_DB_PORT=27017
MONGO_DB_DATABASE=test
MONGO_DB_USERNAME=
MONGO_DB_PASSWORD=
```
##  添加模型
- \app\UserModel.php
```php
<?php
namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class UserModel extends Eloquent
{
    protected $connection = 'mongodb';

    protected $collection = 'users';
}

```

##  添加模型服务
- app\Providers\ModelProvider.php
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
$app->register(App\Providers\AppServiceProvider::class);
// ....
$app->register(\App\Providers\ModelProvider::class); // <------
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
