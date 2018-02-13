## redis模式

- 环境变量
  * .env
```
QUEUE_DRIVER=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=123456
REDIS_PORT=6379
```

- 创建job
  * php artisan make:job QueueJob
```php
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class QueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $data)
    {
        // 这里获取到数据
        $this->id = $id;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 这里消费数据
        print_r($this->id);
        echo PHP_EOL;
        print_r($this->data);
    }
}

```

- 创建命令
  * php artisan make:command TestCommand 
```php
<?php

namespace App\Console\Commands;

use App\Jobs\QueueJob;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test command';

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
        // 构造数据
        $id = 888;
        $data = [
            'name'=>'meinvbingyue',
            'age'=>30,
        ];

        // 将数据分发到队列
        dispatch((new QueueJob($id, $data))->onQueue('jason')); // jason 是指定的队列名
    }
}

```
 
- 生产数据
    * php artisan test:command
```
在redis数据库0中，会生成一个LIST数据，
名为 queues:jason
```

- 消费数据
    * php artisan queue:work --queue=jason --tries=3
```
[2018-02-13 08:40:50] Processing: App\Jobs\QueueJob
888
Array
(
    [name] => meinvbingyue
    [age] => 30
)
[2018-02-13 08:40:50] Processed:  App\Jobs\QueueJob

```
