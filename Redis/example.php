<?php 
$redis = app('redis');


$name = md5('meinvbingyue');

foreach($user_info as $key=>$value){
    $redis->hset($name, $key, $value);
}

$redis->expire($name, $expire_time);

////////////////////////////////////////////////

$redis->set($key,$value);
$redis->expire($key, $expires_time);

$redis->get($key);

$redis->del($key);

////////////////////////////////////////////////

// 判断是否存在
return $redis->sinter($key);
?>