- 服务提供者
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Common\OverwritePaginator;

/**
 * Class PaginatorProvider
 * @author chentengfeng @create_at 2017-08-31  09:22:36
 */
class PaginatorProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() 
    {
        $this->app->bind(LengthAwarePaginator::class, OverwritePaginator::class);
    }

    /**
     * undocumented function
     *
     * @return array
     * @author chentengfeng @create_at 2017-08-30  00:41:16
     */
    public function provides()
    {
        return [
            LengthAwarePaginator::class
        ];
    }
}

```

----

- 覆盖类的方法
 
```php
<?php

namespace App\Http\Common;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class OverwritePaginator
 * @author chentengfeng @create_at 2017-08-31  09:22:36
 */
class OverwritePaginator extends LengthAwarePaginator
{
    /**
     * Get the instance as an array.   <---- 覆盖LengthAwarePaginator的toArray方法
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'list' => $this->items->toArray(),
            'count' => $this->total(),
            'total_page' => $this->lastPage(),
            'page' => $this->currentPage(),
            'page_size' => $this->perPage(),
        ];
    }
}

```
