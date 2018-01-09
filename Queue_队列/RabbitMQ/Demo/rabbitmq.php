<?php

return array(
    // 测试队列
    'test' =>  [
        //ip
        'host'  =>  env('TEST_AMQP_HOST'),
        //端口
        'port'  =>  env('TEST_AMQP_PORT'),
        //用户名称
        'username'  =>  env('TEST_AMQP_USERNAME'),
        //密码
        'password'  =>  env('TEST_AMQP_PASSWORD'),
        //虚拟机
        'vhost'  =>  '/stock_business',
        //交换机类型
        'exchange_type' =>  'fanout',
        //交换机名称
        'exchange_name' =>  'business_stock_notice_exchange',
        //队列名称
        'queue_name' =>  'business_stock_notice_queue',
        //路由名称
        'route_name'    =>  'business.stock.notice.route',
        //每个客户端最多同时间接收多少消息
        'qos'   =>  200,
        //心跳
        'heartbeat' =>  30,
    ],
);
