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
    
        'client' => 'predis',
    
        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DATABASE', 0),
            //不超时
            'read_write_timeout' => '-1',
            'options' => [
                'prefix' => env('REDIS_PREFIX', ''),
            ]
        ],
    
        'options' => [
            'cluster' => 'redis',
            'persistent'    =>  false,
        ],
        //集群
        'clusters'    =>  [
            //股票预警数据库链接   $redis = app('redis')->connection('stock_warning');
            'stock_warning' => [
                [
                    'host' => env('STOCK_WARNING_REDIS_HOST', 'localhost'),
                    'password' => env('STOCK_WARNING_REDIS_PASSWORD', null),
                    'port' => env('STOCK_WARNING_REDIS_PORT', 6379),
                    'database' => 8,
                ],
            ],
        ],


    ],

];
