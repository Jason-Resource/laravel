<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\Model::class, function (Faker\Generator $faker) {
    return [
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

