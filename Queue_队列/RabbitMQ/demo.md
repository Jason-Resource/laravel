# 自己封装类来调用

- 引入包
   ```
   composer require php-amqplib/php-amqplib
   ```

- 配置环境变量
    * .env
    ```
      APP_ENV=local
      APP_DEBUG=true
      APP_KEY=rW0ZJ0YtBt2bPe9B6jacByG4lIMwVBSt
      APP_TIMEZONE=UTC

      TEST_AMQP_HOST=192.168.11.156
      TEST_AMQP_PORT=5672
      TEST_AMQP_USERNAME=admin
      TEST_AMQP_PASSWORD=admin   
    ```
    
- 配置参数
    * /config/rabbitmq.php
    ```
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
              'vhost'  =>  '/',
              //交换机类型
              'exchange_type' =>  'direct',
              //交换机名称
              'exchange_name' =>  'amq.direct',
              //队列名称
              'queue_name' =>  'test',
              //路由名称
              'route_name'    =>  '',
              //每个客户端最多同时间接收多少消息
              'qos'   =>  300,
              //心跳
              'heartbeat' =>  30,
          ],
      );    
    ```

- 创建类
    * \app\Http\Common\TestBusinessAmqp.php
    ```
    namespace App\Http\Common;

    use PhpAmqpLib\Connection\AMQPStreamConnection;
    use PhpAmqpLib\Message\AMQPMessage;

    class TestBusinessAmqp
    {

        // 实例
        private static $instance = array();

        // 连接对象
        private $connection = null;

        // 频道池
        private $channels = [];

        // 频道
        private $channel = null;

        // 配置
        private $config = [];

        /**
         * 初始化
         */
        private function __construct()
        {
            //判断此配置是否有效
            $config_arr = config('rabbitmq.test');

            if (empty($config_arr)) {
                throw new \Exception("mq配置读取失败");
            }

            $this->setConfig($config_arr);

            // 连接
            $this->connection = $this->connection();
        }

        /**
         * 获取实例
         */
        public static function getInstance()
        {
            if(!isset(self::$instance) || !(self::$instance instanceof self) )
            {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * 获取配置
         */
        public function getConfig()
        {
            return $this->config;
        }

        /**
         * 设置配置
         */
        public function setConfig($config)
        {
            $this->config = $config;
        }

        /**
         * 连接服务器
         */
        private function connection()
        {
            $config = $this->getConfig();

            $read_write_timeout = $config['read_write_timeout'] ?? ($config['heartbeat'] > 0 ? $config['heartbeat'] * 2 : 3.0);

            //加入对应的队列
            $connection = new AMQPStreamConnection(
                $config['host'],
                $config['port'],
                $config['username'],
                $config['password'],
                $config['vhost'],
                false,
                'AMQPLAIN',
                null,
                'en_US',
                5.0,
                $read_write_timeout,
                null,
                false,
                $config['heartbeat']
            );

            return $connection;
        }

        /**
         * 创建一个频道
         *
         * @notice 初始化交换机,队列
         */
        public function getChannel()
        {
            //交换机类型
            $exchange_type = $this->config['exchange_type'];
            //交换机名称
            $exchange_name = $this->config['exchange_name'];
            //队列名称
            $queue_name = $this->config['queue_name'];
            //路由名称
            $route_name = $this->config['route_name'];
            //每个客户端接收多少个消息
            $queue_qos = isset($this->config['qos']) ? $this->config['qos'] : 50;

            //获取一个频道
            $channel = $this->connection->channel();

            //设置 qos 值，每次只取 多少条数据
            $channel->basic_qos(0,$queue_qos,false);

            //创建一个队列
            //第三个参数的意思是把队列持久化
            //第五个参数是自动删除，当没有消费者连接到该队列的时候，队列自动销毁。
            $channel->queue_declare($queue_name,false,true,false,false);

            //创建交换机，当不存在的时候就创建，存在则不管了
            //第二个参数为交换机的类型
            //第四个参数的意思是把队列持久化
            //第五个参数的意思是自动删除，当没有队列或者其他exchange绑定到此exchange的时候，该exchange被销毁。
            $channel->exchange_declare($exchange_name,$exchange_type,false,true,false);

            //把队列与交换机以及路由绑定起来
            $channel->queue_bind($queue_name,$exchange_name,$route_name);

            $this->channels[$channel->getChannelId()] = $channel;

            return $channel;
        }

        /**
         * 获取一个频道
         *
         * @notice 创建或返回一个已有频道
         */
        public function getOneChannel()
        {
            if($this->channel === null){
                $this->channel = $this->getChannel();
            }

            return $this->channel;
        }

        /**
         * 推送消息
         */
        public function basic_publish(array $msg)
        {
            //格式化
            $msg_str = json_encode($msg);

            $property = ['delivery_mode'=>2];
            $amqp_message = new AMQPMessage($msg_str,$property);

            //交换机名称
            $exchange_name = $this->config['exchange_name'];
            //路由名称
            $route_name = $this->config['route_name'];

            $response = $this->getOneChannel()->basic_publish($amqp_message,$exchange_name,$route_name);

            return $response;
        }

        /**
         * 关闭资源
         */
        public function __destruct()
        {
            // 关闭频道
            foreach($this->channels as $sk=>$sv){
                $sv->close();
            }

            // 关闭链接
            $this->connection->close();
        }
    }
    ```

- 测试
      * app\Console\Commands\TestQueueCommand.php
      ```
      namespace App\Console\Commands;

      use Illuminate\Console\Command;

      class TestQueueCommand extends Command
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
              $test_queue = \App\Http\Common\TestBusinessAmqp::getInstance();
              for ($i=0;$i<100000;$i++) {
                  $push_data = [
                      'data' => 'test',
                      'time' => time(),
                  ];
                  $test_queue->basic_publish($push_data);
              }
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
      ```
