## 最好还是不要用，消费速度慢

----

- 1、安装拓展
```
composer require vladimir-yuldashev/laravel-queue-rabbitmq
composer require enqueue/amqp-bunny:^0.8
```
 
- 2、添加服务
  * \config\app.php
```php
'providers' => [

        VladimirYuldashev\LaravelQueueRabbitMQ\LaravelQueueRabbitMQServiceProvider::class,

    ],
```

- 3、环境变量
  * .env
  ```
  QUEUE_DRIVER=stock_real

RABBITMQ_HOST=192.168.11.156
RABBITMQ_PORT=5672
RABBITMQ_VHOST=/
RABBITMQ_LOGIN=admin
RABBITMQ_PASSWORD=admin
RABBITMQ_QUEUE=test
RABBITMQ_EXCHANGE_NAME=amq.direct
RABBITMQ_EXCHANGE_TYPE=direct
  ```
