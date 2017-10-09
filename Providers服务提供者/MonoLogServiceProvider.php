<?php

namespace App\Providers;

use App\Http\Common\Helper;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\AmqpHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\WebProcessor;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class MonoLogServiceProvider extends ServiceProvider
{
    
    //延时加载
    public $defer = true;
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Logger', function ($app) {
            return $this->createLogger($app);
        });
    }
    
    /**
     * Create the logger.
     *
     * @return \Illuminate\Log\Writer
     */
    public function createLogger($app)
    {
        //以域名为为channel_name
        $channel_name = Helper::logChannel();
        if(empty($channel_name)) {
            $channel_name = Helper::logChannel($app['request']->getHttpHost());
        }

        $logger = new Logger($channel_name);
        
        //添加process，为日志添加额外的数据或格式化数据
        $logger->pushProcessor(new WebProcessor());
        $logger->pushProcessor(new PsrLogMessageProcessor());
        
        //当为测试环境，把内容使用什么的都记录下来
        if(config('app.debug') === true){
            $logger->pushProcessor(new MemoryUsageProcessor());
        }
        
        //生成一个版本号
        Helper::logVersion(true);
        
        //额外记录的消息
        $messages = array();
//        $messages['session'] = $app['session']->all();
//        $messages['ip'] = $app['request']->ip();
//        $messages['request'] = $app['request']->all();
//        $messages['full_url'] = $app['request']->fullUrl();
//        $messages['request_time'] = Helper::getNow();
        
        //自定义的processer,记录一些需要的信息
        $logger->pushProcessor(function ($record) use ($app,$messages) {
            //$record['version'] = $version;
            //$record['messages'] = $messages;
            $record['extra']['version'] = Helper::logVersion();
            $record['extra']['messages'] = $messages;
            $record['extra']['request_time'] = Helper::getNow(true);
            
            foreach($messages as $sk=>$sv){
                $record['extra'][$sk] = $sv;
            }
            return $record;
        });
        
        $type = array_map('trim', explode(',', config('monolog.log_handle_type', 'file')));
        
        // 队列
        if (in_array('amqp', $type)) {
            
            $conf_rabbitmq = config('monolog.rabbitmq');

            // 主机
            $conf_rabbitmq_host = $conf_rabbitmq['host'];
            // 端口
            $conf_rabbitmq_port = $conf_rabbitmq['port'];
            // 用户名
            $conf_rabbitmq_username = $conf_rabbitmq['username'];
            // 密码
            $conf_rabbitmq_password = $conf_rabbitmq['password'];
            // 虚拟机
            $conf_rabbitmq_vhost = isset($conf_rabbitmq['vhost']) ? $conf_rabbitmq['vhost'] : '';
            // 交换机类型
            $conf_rabbitmq_exchange_type = $conf_rabbitmq['log_exchange_type'];
            // 交换机名称
            $conf_rabbitmq_exchange_name = $conf_rabbitmq['log_exchange_name'];
            // 队列名称
            $conf_rabbitmq_queue_name = $conf_rabbitmq['log_queue_name'];
            // 路由名称
            $conf_rabbitmq_route_name = $conf_rabbitmq['log_route_name'];
            // 心跳
            $conf_rabbitmq_heartbeat = $conf_rabbitmq['heartbeat'];
            // 读写超时
            $conf_rabbitmq_read_write_timeout = isset($conf_rabbitmq['read_write_timeout']) ? $conf_rabbitmq['read_write_timeout'] : 0;
            $read_write_timeout = is_numeric($conf_rabbitmq_read_write_timeout) && $conf_rabbitmq_read_write_timeout> 0 ? $conf_rabbitmq_read_write_timeout : ($conf_rabbitmq_heartbeat > 0 ? $conf_rabbitmq_heartbeat * 2 : 3.0);

            //加入对应的队列
            $connection = new AMQPStreamConnection(
                $conf_rabbitmq_host,
                $conf_rabbitmq_port,
                $conf_rabbitmq_username,
                $conf_rabbitmq_password,
                $conf_rabbitmq_vhost,
                false,
                'AMQPLAIN',
                null,
                'en_US',
                5.0,
                $read_write_timeout,
                null,
                false,
                $conf_rabbitmq_heartbeat
            );

            //获取一个频道
            $channel = $connection->channel();
            
            //创建一个队列
            //第三个参数的意思是把队列持久化
            //第五个参数是自动删除，当没有消费者连接到该队列的时候，队列自动销毁。
//            $channel->queue_declare($conf_rabbitmq_queue_name,false,true,false,false);
            
            //创建交换机，当不存在的时候就创建，存在则不管了
            //第二个参数为交换机的类型
            //第四个参数的意思是把队列持久化
            //第五个参数的意思是自动删除，当没有队列或者其他exchange绑定到此exchange的时候，该exchange被销毁。
            $channel->exchange_declare($conf_rabbitmq_exchange_name,$conf_rabbitmq_exchange_type,false,true,false);
            
            //把队列与交换机以及路由绑定起来
            //AmqpHandler会自己创建路由名称
//            $channel->queue_bind($conf_rabbitmq_queue_name,$conf_rabbitmq_exchange_name,$conf_rabbitmq_route_name);
            
            
            //创建 amqp handler
            $amqp_handler = new AmqpHandler($channel, $conf_rabbitmq_exchange_name, Logger::DEBUG);
            
            $logger->pushHandler($amqp_handler);
        }
        
        // 文件
        if (in_array('file', $type)) {
            
            //创建 file handler
            $file_handler = new StreamHandler(config('monolog.log_file'), Logger::DEBUG);

            $logger->pushHandler($file_handler);
        }
        
        return $logger;
    }
    
    
    /**
     * Get the maximum number of log files for the application.
     *
     * @return int
     */
    protected function maxFiles()
    {
        if ($this->app->bound('config')) {
            return $this->app->make('config')->get('app.log_max_files', 5);
        }
        
        return 0;
    }
    
    public function provides()
    {
        return [
            'Logger',
        ];
    }
}
