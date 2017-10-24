```php
// 判断集合里是否含有某个数据
$collect->pluck('code')->contains($value['code']);

/************************************************************************************/
// 循环获取
$collection = collect(1,2,3,4,5);

$collection->map(function ($value) {
    $this->handleAssembleInfo($value); // 这里是定义的函数
});

$list = $list->transform(function ($item) {
    $item->record_at = Carbon::parse($item->record_at)->format('Y/m/d H:i');
    return $item;
});
```
