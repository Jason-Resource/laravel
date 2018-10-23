```php
// 剔除空数据
$collection = collect([1, 2, 3, null, false, '', 0, []]);
$collection->filter()->all();
            
// 判断集合里是否含有某个数据
$collect->pluck('code')->contains($value['code']);

// 将一个集合，用id作key
$collect->keyBy('id')->get(8)->name

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

- handle处理
```
$data->map(function ($value) {
            $value->role;
            $value->short_created_at = date('m-d H:i', strtotime($value->created_at));
            $value->short_updated_at = date('m-d H:i', strtotime($value->updated_at));
});

$data = $data->transform(function ($item) {
    $item->role_info = $item->role;
    return $item;
});
```
