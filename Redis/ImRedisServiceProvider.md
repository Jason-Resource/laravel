```php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Redis;

class ImRedisServiceProvider extends ServiceProvider
{
    protected $defer = true;
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
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton("ImRedis", function ($app) {
            $redis = new Redis();
            $redis_config = config("database.redis.im", []);

            $redis->pconnect($redis_config['host'], $redis_config['port'], 5);
            $redis->setOption(Redis::OPT_PREFIX, "block:");

            if (!empty($redis_config['password'])) {
                $redis->auth($redis_config['password']);
            }

            if (!empty($redis_config['database'])) {
                $redis->select($redis_config['database']);
            }

            return $redis;
        });
    }

    /**
     * 获取提供器提供的服务。
     *
     * @return array
     */
    public function provides()
    {
        return ["ImRedis"];
    }
}
```
