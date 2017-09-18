```php
// 判断缓存是否存在
app('cache')->has($cache_key);

Cache::has($cache_key)

//删除缓存
Cache::store('file')->forget('menus_1');

//读取缓存
app('cache')->get($cache_key);

/*
* 设置一个有过期时间的缓存
*/
$expire_seconds = config('sms.expire_minute');//过期时间，单位分钟
$cache_key = sprintf(config('sms.cache_key'), $mobile, $code);//缓存的key
$value = Helper::getNow();
$expires_at = Carbon::now()->addMinutes($ex_seconds);
Cache::put($cache_key, $value, $expires_at);


//当存在则删除
if(cache()->has($cache_key)){
    
    cache()->forget($cache_key);
}

cache()->get($cache_key);



$expiresAt = Carbon::now()->addSecond($cache_expire);
cache()->put($cache_key,$user_data,$expiresAt);


//缓存页面
$cache_time = config('viewcache.f10.cache_time');
$expires_time = Carbon::now()->addSecond($cache_time);
app('cache')->remember($cache_key, $expires_time, function() use($response) {
    return app('view')->make('web.f10.finance', $response)
        ->render();
});
```
