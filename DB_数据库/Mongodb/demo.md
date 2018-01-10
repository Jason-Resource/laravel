## 检查mongodb
```
netstat -tunpl | grep 27017 #查看mongo是否启动
pstree -p | grep mongod

cd /usr/local/mongodb/bin

./mongo             #进入mongo客户端

>show dbs;          #查看所有数据库
>db;                #查看当前数据库
>use test;          #使用test数据库
>show collections;      #查看所有集合
>db.test.find().pretty();   #查询test集合的数据
```

----

## 安装扩展

- composer require jenssegers/mongodb
 
##  添加模型
- \app\UserModel.php
    * php artisan make:model UserModel
```php
<?php
namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class UserModel extends Eloquent
{
    use SoftDeletes;

    protected $connection = 'mongodb';

    protected $collection = 'users';

    /**
     * 根据 id 查询
     */
    public function scopeIdQuery($query,$id)
    {
        return $query->where('_id',$id);
    }
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



## 加载服务

- /config/app.php
    ```
    'providers' => [
        
            App\Providers\ModelProvider::class,
            \Jenssegers\Mongodb\MongodbServiceProvider::class,
        ],

    'aliases' => [
            'Moloquent' => Jenssegers\Mongodb\Eloquent\Model::class,
        ],
    ```

## 配置
- \config\database.php
```
    'connections' => [

        // ...

        'mongodb' => [
            'driver'   => 'mongodb',
            'host'     => env('MONGO_DB_HOST', 'localhost'),
            'port'     => env('MONGO_DB_PORT', 27017),
            'database' => env('MONGO_DB_DATABASE'),
            'username' => env('MONGO_DB_USERNAME'),
            'password' => env('MONGO_DB_PASSWORD'),
            'options'  => [
                //'database' => 'admin' // sets the authentication database required by mongo 3
                //'replicaSet' => 'replicaSetName'
            ]
        ],
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



##  测试
```
Route::get('/', function () {
    $user = app('UserModel');
    $user->name = 'meinvbingyue';
    $flag = $user->save();

    echo $flag.PHP_EOL;

    $list = $user->get()->toArray();
    dd($list);
});



```

```
/*$admin_user = app('AdminUser');
$admin_user->username = 'meinvbingyue';
$admin_user->salt = 'i76D@a';
$admin_user->password = md5('7862102'.$admin_user->salt);
$flag = $admin_user->save();
dd($flag);*/

/*$admin_user = app('AdminUser');
$flag = $admin_user->where('username','=','meinvbingyue')->first()->delete();
dd($flag);*/

/*$flag = 0;
for ($i=1;$i<=20;$i++){
    $admin_user = app('AdminUser');
    $admin_user->username = 'meinvbingyue'.$i;
    $flag += $admin_user->save();
}
dd($flag);*/

$admin_user = app('AdminUser');
$page_size = 5;//每页显示个数
$cur_page = request()->page;//当前页
$list =  $admin_user->paginate($page_size, ['*'], 'page', $cur_page);
dd($list);

$admin_user = app('AdminUser');
dd($admin_user->all());
```
