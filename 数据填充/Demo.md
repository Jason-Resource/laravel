### [Facker库](https://github.com/fzaninotto/Faker)

- <a href="#SQL语句">创建表</a>

- 创建模型
  * php artisan make:model Test
  
  ```php
  namespace App;

  use Illuminate\Database\Eloquent\Model;

  class Test extends Model
  {
      protected $table = 'test';
  }
  ```

- 创建播种者
  * php artisan make:seeder TestSeeder
 
   ```php
   // \database\seeds\TestSeeder.php


  use Illuminate\Database\Seeder;

  class TestSeeder extends Seeder
  {
      /**
       * Run the database seeds.
       *
       * @return void
       */
      public function run()
      {
          // 创建一条记录
          factory(\App\Test::class)->create();

          // 创建五条记录，并覆盖字段值
          factory(\App\Test::class, 5)->create([
              'name' => 'jason'.rand(),   // 覆盖name字段的值
          ]);
      }
  }

   ```
 
- 修改 /database/seeds/DatabaseSeeder.php
  ```php
   use Illuminate\Database\Seeder;

   class DatabaseSeeder extends Seeder
   {
       /**
        * Run the database seeds.
        *
        * @return void
        */
       public function run()
       {
           // $this->call(UsersTableSeeder::class);

           $this->call(TestSeeder::class); // <----添加刚刚创建的播种者
       }
   }

  ```
 
- 创建模型工厂
  * php artisan make:factory TestFactory
  
   ```php

  use Faker\Generator as Faker;

  $factory->define(\App\Test::class, function (Faker $faker) {
      return [
          'name'          => $faker->name,
          'email'         => $faker->safeEmail,
          'password'      => bcrypt(str_random(10)),
      ];
  });

   ```
   
- 执行命令
```
php artisan migrate --seed 						#运行所有 seeder 类
php artisan db:seed 							#运行所有 seeder 类
php artisan db:seed --class=TestSeeder		#单独运行一个特定的 seeder 类
```
----
<a name="SQL语句"></a>
```
  DROP TABLE IF EXISTS `sys_test`;
  CREATE TABLE `sys_test` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) DEFAULT NULL,
    `email` varchar(255) DEFAULT NULL,
    `password` varchar(255) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
```
