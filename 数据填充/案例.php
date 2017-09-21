<?php
/*
fake   伪造;篡改      [feɪk]
faker  骗子;伪造者;   
Seed   种子
Seeder 播种机，播种人 ['si:də]
migration  迁移，移居 [maɪˈgreɪʃn] 
*/

//-----------------------------------------------------------------------------------------------//

/**********************************************************************************************/
//创建模型

php artisan make:model Models/Admin

protected $table = 'admin';

//创建播种者

php artisan make:seeder AdminsTableSeeder

public function run()
{
	factory(\App\Models\Admin::class,3)->create([
	]);
}

/**********************************************************************************************/

//修改 /database/seeds/DatabaseSeeder.php

public function run()
{
	......
	$this->call(AdminsTableSeeder::class);
}

/**********************************************************************************************/

//修改 /database/factories/ModelFactory.php

//(添加)
$factory->define(App\Models\Admins::class, function (Faker\Generator $faker) {
    return [
        'name'          => $faker->name,
	......
    ];
});

/**********************************************************************************************/

//执行迁移命令

php artisan migrate --seed 						#运行所有 seeder 类

php artisan db:seed 							#运行所有 seeder 类

php artisan db:seed --class=UserTableSeeder		#单独运行一个特定的 seeder 类

