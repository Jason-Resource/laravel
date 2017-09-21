<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\AmqpHandler;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;

class LogProvider extends ServiceProvider
{
    // 延迟加载
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Logger', function ($app) {
            // @TODO 这样使用时，一定要是延迟加载
            $logger = new Logger($app['request']->getHttpHost());
            //$logger->pushHandler(new StreamHandler(storage_path('logs/test.log'), Logger::DEBUG));

            $logger->pushProcessor(new \Monolog\Processor\WebProcessor);
            $logger->pushProcessor(new \Monolog\Processor\PsrLogMessageProcessor);

            // 记录当前 session 
            $logger->pushProcessor(function ($record) use ($app) {
                //dd($app['session']->all());
                $record['extra']['session'] = $app['session']->all();
                $record['current_time'] = date('YmdHis');

                return $record;
            });
            
            //加入对应的队列
            $connection = new AMQPStreamConnection(
                config('rabbitmq.host'), 
                config('rabbitmq.port'), 
                config('rabbitmq.username'), 
                config('rabbitmq.password'),
                config('rabbitmq.vhost')
            );
            $logger->pushHandler(new AmqpHandler($connection->channel(), $app['request']->getHttpHost(), Logger::DEBUG));

            return $logger;
        });

    }

    /**
     * 获取由提供者提供的服务.
     *
     * @return void
     * @author chentengfeng @create_at 2017-01-09  09:07:25
     */
    public function provides()
    {
        return [
            'Logger'
        ];
    }
}
