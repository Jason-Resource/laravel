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
        $messages['ip'] = $app['request']->ip();
        $messages['request'] = $app['request']->all();
        $messages['full_url'] = $app['request']->fullUrl();
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
            //加入对应的队列
            $connection = new AMQPStreamConnection(
                config('monolog.rabbitmq.host'),
                config('monolog.rabbitmq.port'),
                config('monolog.rabbitmq.username'),
                config('monolog.rabbitmq.password'),
                config('monolog.rabbitmq.vhost')
            );
            
            //交换机类型
            $log_exchange_type = config('monolog.rabbitmq.log_exchange_type');
            //交换机名称
            $exchange_name = config('monolog.rabbitmq.log_exchange_name');
            //队列名称
            $queue_name = config('monolog.rabbitmq.log_queue_name');
            //路由名称
            $route_name = config('monolog.rabbitmq.log_route_name');
            
            //获取一个频道
            $channel = $connection->channel();
            
            //创建一个队列
            //第三个参数的意思是把队列持久化
            //第五个参数是自动删除，当没有消费者连接到该队列的时候，队列自动销毁。
//            $channel->queue_declare($queue_name,false,true,false,false);
            
            //创建交换机，当不存在的时候就创建，存在则不管了
            //第二个参数为交换机的类型
            //第四个参数的意思是把队列持久化
            //第五个参数的意思是自动删除，当没有队列或者其他exchange绑定到此exchange的时候，该exchange被销毁。
            $channel->exchange_declare($exchange_name,$log_exchange_type,false,true,false);
            
            //把队列与交换机以及路由绑定起来
            //这个没卵用，因为AmqpHandler会自己创建路由名称，艹
//            $channel->queue_bind($queue_name,$exchange_name,$route_name);
            
            
            //创建 amqphandler
            $amqp_handler = new AmqpHandler($channel, $exchange_name, Logger::DEBUG);
            
            $logger->pushHandler($amqp_handler);
        }
        
        // 文件
        if (in_array('file', $type)) {
            $logger->pushHandler(new StreamHandler(config('monolog.log_file'), Logger::DEBUG));
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
