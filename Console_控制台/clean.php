<?php

require __DIR__.'/bootstrap/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$input = new Symfony\Component\Console\Input\ArgvInput;
$first_argument = $input->getFirstArgument();

$kernel->call('config:clear');
$kernel->call('config:cache');

$kernel->call('view:clear');

$kernel->call('route:clear');
$kernel->call('route:cache');

$kernel->call('cache:clear');

//删除已编译的类文件
$kernel->call('clear-compiled');

if ($first_argument == 'all') {
    $kernel->call('optimize');
}
