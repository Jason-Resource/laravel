<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => env('REDIS_CLUSTER', false),

        'default' => [
            'host'     => env('REDIS_HOST', '127.0.0.1'),
            'port'     => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DATABASE', 1),
            'password' => env('REDIS_PASSWORD', null),
            //不超时
            'read_write_timeout' => '-1'
        ],

        'stock_redis' => [
            'host'     => env('STOCK_REDIS_HOST', '127.0.0.1'),
            'port'     => env('STOCK_REDIS_PORT', 6379),
            'database' => env('STOCK_REDIS_DATABASE', 5),
            'password' => env('STOCK_REDIS_PASSWORD', null),
        ],

    ],

];
