- 创建表
  


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
           // 创建五条记录
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

   $factory->define(Model::class, function (Faker $faker) {
       return [
           //
           'name'          => $faker->name,
           'email'         => $faker->safeEmail,
           'password'      => bcrypt(str_random(10)),
           'password'      => bcrypt('123456'),
           'stock_name'    => $faker->numberBetween(9999, 99999),
           'buy_price'     => $faker->randomFloat(2, 10, 100),
           'service_id'    => $faker->randomNumber(),
           'title'         => $faker->title,
           'content'       => $faker->paragraph,
           'keywords'      => $faker->word,
           'mstar'         => $faker->randomElement([0, 1]),
           'platform'      => $faker->randomElement(['ZB', 'ZGWY','VIP']),
           'linkurl'       => $faker->url,
           'rank'          => str_random(10),
           'addtime'       => $faker->date('YmdHis'),
           'memo'          => $faker->realText(50),
           'zsno'          => $faker->creditCardNumber(),
           'zsurl'         => $faker->imageUrl(),
           'nengli'        => implode(range(1, 9, 3), ','),
       ];
   });

   ```
   
- 执行命令
```
php artisan migrate --seed 						#运行所有 seeder 类
php artisan db:seed 							#运行所有 seeder 类
php artisan db:seed --class=TestSeeder		#单独运行一个特定的 seeder 类
```
