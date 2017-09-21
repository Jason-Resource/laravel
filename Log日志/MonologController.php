<?php namespace App\Http\Controllers;

use Monolog\Handler\FirePHPHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\WebProcessor;

class MonologController extends Controller
{

    public function index()
    {
        // 创建handler（处理程序），多个处理程序组成一个栈
        $storage_path = storage_path('logs/my.log');
        $stream_handler = new StreamHandler($storage_path, Logger::DEBUG);
        $firephp_handler = new FirePHPHandler();

        /*
        |--------------------------------------------------------------------------
        | 创建应用的主要logger
        |--------------------------------------------------------------------------
        |
        | 'my_logger'即通道名
        | 将两个处理程序压入栈中，先压入的后处理，所以执行顺序是 firephp->stream
        | 如果处理程序中的$bubble属性设置为false时，则不会将错误继续流到下一个处理程序，即将日志作阻塞处理
        |
        */
        $logger = new Logger('my_logger');
        $logger->pushHandler($stream_handler);
        $logger->pushHandler($firephp_handler);

        // 对日志进行加工处理
        $logger->pushProcessor(new WebProcessor());
        $logger->pushProcessor(new PsrLogMessageProcessor());
        $logger->pushProcessor(new MemoryUsageProcessor());

        // 对日志进行自定义加工处理(添加额外的数据到记录)
        $version = '1.0';

        $messages = [
            'ip' = app('request')->ip();
            'session' = app('session')->all();
            'full_url' = app('request')->fullUrl();
        ];

        $logger->pushProcessor(function ($record) use ($version, $messages) {
            $record['extra']['version'] = $version;
            $record['extra']['http_host'] = request()->getHttpHost();
            $record['current_time'] = date('YmdHis');

            foreach ($messages as $key => $value) {
                $record['extra'][$key] = $value;
            }

            return $record;
        });

        // 开始记录日志
        $logger->addInfo('Info Log', array('username' => 'meinvbingyue'));
        $logger->addInfo('Info Log');
        $logger->addWarning('Warning Log');
        $logger->addError('Error Log');

        var_dump($logger);

        /**
        * 创建一个安全通道日志
        */
        // Create a logger for the security-related stuff with a different channel
        $securityLogger = new Logger('security');
        $securityLogger->pushHandler($stream_handler);
        $securityLogger->pushHandler($firephp_handler);

        // Or clone the first one to only change the channel
        $securityLogger = $logger->withName('security');
    }
}
