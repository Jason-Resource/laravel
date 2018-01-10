```
netstat -tunpl | grep 27017	#查看mongo是否启动
pstree -p | grep mongod

cd /usr/local/mongodb/bin

./mongo				#进入mongo客户端

>show dbs;			#查看所有数据库
>db;				#查看当前数据库
>use test;			#使用test数据库
>show collections;		#查看所有集合
>db.test.find().pretty();	#查询test集合的数据
```

----

- 1、composer require jenssegers/mongodb

- 2、编辑 config/app.php
    ```
    'providers' => [
    	
    	//使用模型提供者来绑定所有的模型 -> php artisan make:provider ModelServiceProvider
            App\Providers\ModelServiceProvider::class,

            /*
             * Mongodb Service Providers
             */
            \Jenssegers\Mongodb\MongodbServiceProvider::class,
        ],

    'aliases' => [
            'Moloquent' => Jenssegers\Mongodb\Eloquent\Model::class,
        ],
    ```

- 3、编辑 app/Providers/ModelServiceProvider.php

    ```
    public function register()
    {
    	$this->app->bind('AdminUser',AdminUser::class);
    }
    ```

- 4、编辑 config/database.php

    ```
    'mongodb' => [
                'driver'   => 'mongodb',
                'host'     => env('MONGO_DB_HOST', 'localhost'),
                'port'     => env('MONGO_DB_PORT', 27017),
                'database' => env('MONGO_DB_DATABASE', 'mydb'),
                'username' => env('MONGO_DB_USERNAME', ''),
                'password' => env('MONGO_DB_PASSWORD', ''),
                'options'  => [
                    //'replicaSet' => 'replicaSetName'
                ]
            ],
    ```

- 5、编辑 .env

    ```
    MONGO_DB_HOST=192.168.11.133
    MONGO_DB_PORT=27017
    MONGO_DB_DATABASE=pph
    MONGO_DB_USERNAME=
    MONGO_DB_PASSWORD=
    ```

- 6、新增模型类
    * php artisan make:model Models/Admin/AdminUser
```php

<?php namespace App\Models\Admin;

use Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class AdminUser extends Moloquent
{
    use SoftDeletes;

    /**
     * 连接mongodb数据库
     */
    protected $connection = 'mongodb';

    /**
     * 关联表
     */
    protected $collection = 'admin_user';

    /**
     * 根据 id 查询
     */
    public function scopeIdQuery($query,$id)
    {
        return $query->where('_id',$id);
    }
}
```

- 7、测试

```php
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