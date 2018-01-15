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

- 4、配置
   * \config\queue.php



 ```
   'stock_real' => [

       'driver' => 'rabbitmq',

       'dsn' => env('RABBITMQ_DSN', null),

       /*
        * Could be one a class that implements \Interop\Amqp\AmqpConnectionFactory for example:
        *  - \EnqueueAmqpExt\AmqpConnectionFactory if you install enqueue/amqp-ext
        *  - \EnqueueAmqpLib\AmqpConnectionFactory if you install enqueue/amqp-lib
        *  - \EnqueueAmqpBunny\AmqpConnectionFactory if you install enqueue/amqp-bunny
        */
       'factory_class' => Enqueue\AmqpLib\AmqpConnectionFactory::class,

       'host' => env('RABBITMQ_HOST', '127.0.0.1'),
       'port' => env('RABBITMQ_PORT', 5672),

       'vhost' => env('RABBITMQ_VHOST', '/'),
       'login' => env('RABBITMQ_LOGIN', 'guest'),
       'password' => env('RABBITMQ_PASSWORD', 'guest'),

       'options' => [

           'exchange' => [

               'name' => env('RABBITMQ_EXCHANGE_NAME'),

               /*
               * Determine if exchange should be created if it does not exist.
               */
               'declare' => env('RABBITMQ_EXCHANGE_DECLARE', true),

               /*
               * Read more about possible values at https://www.rabbitmq.com/tutorials/amqp-concepts.html
               */
               'type' => env('RABBITMQ_EXCHANGE_TYPE', \Interop\Amqp\AmqpTopic::TYPE_DIRECT),
               'passive' => env('RABBITMQ_EXCHANGE_PASSIVE', false),
               'durable' => env('RABBITMQ_EXCHANGE_DURABLE', true),
               'auto_delete' => env('RABBITMQ_EXCHANGE_AUTODELETE', false),
               'arguments' => env('RABBITMQ_EXCHANGE_ARGUMENTS'),
           ],

           'queue' => [

               /*
               * The name of default queue.
               */
               'name' => env('RABBITMQ_QUEUE', 'default'),

               /*
               * Determine if queue should be created if it does not exist.
               */
               'declare' => env('RABBITMQ_QUEUE_DECLARE', true),

               /*
               * Determine if queue should be binded to the exchange created.
               */
               'bind' => env('RABBITMQ_QUEUE_DECLARE_BIND', true),

               /*
               * Read more about possible values at https://www.rabbitmq.com/tutorials/amqp-concepts.html
               */
               'passive' => env('RABBITMQ_QUEUE_PASSIVE', false),
               'durable' => env('RABBITMQ_QUEUE_DURABLE', true),
               'exclusive' => env('RABBITMQ_QUEUE_EXCLUSIVE', false),
               'auto_delete' => env('RABBITMQ_QUEUE_AUTODELETE', false),
               'arguments' => env('RABBITMQ_QUEUE_ARGUMENTS'),
           ],
       ],

       /*
        * Determine the number of seconds to sleep if there's an error communicating with rabbitmq
        * If set to false, it'll throw an exception rather than doing the sleep for X seconds.
        */
       'sleep_on_error' => env('RABBITMQ_ERROR_SLEEP', 5),

       /*
        * Optional SSL params if an SSL connection is used
        */
       'ssl_params' => [
           'ssl_on' => env('RABBITMQ_SSL', false),
           'cafile' => env('RABBITMQ_SSL_CAFILE', null),
           'local_cert' => env('RABBITMQ_SSL_LOCALCERT', null),
           'local_key' => env('RABBITMQ_SSL_LOCALKEY', null),
           'verify_peer' => env('RABBITMQ_SSL_VERIFY_PEER', true),
           'passphrase' => env('RABBITMQ_SSL_PASSPHRASE', null),
       ],

   ],
 ```

- 创建job
  * \app\Jobs\ConsumeStockRealData.php
```php
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ConsumeStockRealData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 这里是消费
        print_r($this->data).PHP_EOL;
//        Log::info($this->data['name']);
    }
}


```

- 创建命令
   * app\Console\Commands\Testqueue.php
```php
<?php

namespace App\Console\Commands;

use App\Jobs\ConsumeStockRealData;
use Illuminate\Console\Command;

class Testqueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '推送数据到队列';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        for ($i=0;$i<1000;$i++) {
            $arr=[
                'name'=> 'jason'.rand(1000,9999).'--------',
            ];
            print_r($arr);
            \App\Jobs\ConsumeStockRealData::dispatch($arr)->onConnection('stock_real');
//            \Illuminate\Support\Facades\Queue::push(new ConsumeStockRealData($arr));
        }

    }
}

```

- 或者采用路由推送消息
 ```php
 use App\Jobs\ConsumeStockRealData;

 Route::get('/push', function(){

     //向队列推送数据
     for ($i=0;$i<100000;$i++) {
         $data["header"] = ["time"=>time()];
         $job = (new ConsumeStockRealData($data))->onConnection('stock_real');
         dispatch($job);
     }

 });
 
 ```
 
---- 
```
生产 
php artisan test:queue
 
消费 
php artisan queue:work stock_real --queue=test  // 可以指定用哪个链接、队列

 
更新了代码记得使用 queue:restart 来重启
php artisan queue:restart

```
