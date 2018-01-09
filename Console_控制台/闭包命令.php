<?php 

####################################################################
# 直接编辑 /routes/console.php   
#
# 然后在控制台输入 php artisan list 就能看到创建的命令。
#
# 原理：在 /app/Console/Kernel.php 文件中，引入了该文件
#	protected function commands()
#    {
#        require base_path('routes/console.php');
#    }
#
#
#
####################################################################

## php artisan test
Artisan::command('test', function () {
    $this->info("test");
})->describe('Display an test info');

## php artisan show:user 1
Artisan::command('show:user {id}', function ($id) {
    //$this->comment("user {$id}!");
    $this->info("user: {$id}!");
})->describe('Display an user info');


?>