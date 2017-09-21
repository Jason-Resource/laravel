1、新建命令
php artisan make:command RedisSubscribe			## 在 /app/Console/Commands/RedisSubscribe.php

2、编写签名和描述
<?php 
protected $signature = 'redis:subscribe';
protected $description = 'Subscribe to a Redis channel';
?>

3、编写核心逻辑代码
<?php
public function handle()
{
    Redis::subscribe(['test-channel'], function($message) {
        echo $message;
    });
}
?>



4、编辑 /app/Console/Kernel.php 注册新编写的命令
<?php 
protected $commands = [
	Commands\RedisSubscribe::class
];
?>


5、运行命令
php artisan redis:subscribe