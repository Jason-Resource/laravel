<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试队列推送&消费';

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
        $type = $this->argument('type');
        switch ($type) {
            case 'produce' :
                $this->produce();
                break;
            case 'consume' :
                $this->consume();
                break;
        }
    }

    // 生产
    protected function produce()
    {
        $push_data = [
            'data' => 'test',
            'time' => time(),
        ];
        $test_queue = \App\Http\Common\TestBusinessAmqp::getInstance();
        $flag = $test_queue->basic_publish($push_data);
        dd($flag);
    }

    // 消费
    protected function consume()
    {
        $test_queue = \App\Http\Common\TestBusinessAmqp::getInstance();
        $task_channel = $test_queue->getOneChannel();

        // 获取配置
        $config = $test_queue->getConfig();
        // 队列名称
        $queue_name = $config['queue_name'];
        // 消费者tag
        $consumerTag = 'consumer-'.posix_getpid();

        $task_channel->basic_consume($queue_name, $consumerTag, false, false, false, false, [$this, 'handleData']);

        while (count($task_channel->callbacks)) {
            $task_channel->wait();
        }
    }

    // 处理数据
    public function handleData(\PhpAmqpLib\Message\AMQPMessage $message)
    {
        $msg = $message->getBody();
        $msg_arr = json_decode($msg, true);
        var_dump($msg_arr);

        return $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    }
}

