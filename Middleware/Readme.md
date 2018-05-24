- 在控制器中使用
```php
    public function __construct()
    {
        $this->middleware('encrypt')->only(['postPush']);  // postPush是方法名
    }
```
