```php
{{ $list_obj->appends(request()->all())->render() }}
```
 
----
# 手动创建分页器
```php

$options = ['path'=>action('Web\UserController@message'), ['page' => $page]];
        $message_obj = Helper::createPaginator($message_list, $options);
        
$options = ['path'=>action('Web\MarketController@exponentNews', ['page' => $page, 'code' => $code])];
        $list_obj = Helper::createPaginator($list, $options);
        
    /**
     * 分页列表数组转对象
     *
     * @param $list_array 分页列表数据
     * @param $options 其他设置项
     */
    public static function createPaginator($list_array, array $options = [])
    {
        $items = $list_array['data'] ?? [];
        $total = $list_array['total'] ?? 0;
        $perPage = $list_array['per_page'] ?? 15;
        $currentPage = $list_array['current_page'] ?? 1;
        $paginator = new LengthAwarePaginator($items, $total, $perPage, $currentPage, $options);

        return $paginator;
    }

```
