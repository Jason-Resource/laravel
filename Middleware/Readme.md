- 在控制器中使用
```php
    public function __construct()
    {
        $this->middleware('encrypt')->only(['postPush']);  // postPush是方法名
        
        $this->middleware('guest:admin', ['except' => 'logout']);
        
        $this->middleware('assessment-path-record')->except('getIndex');
        
        $this->middleware('guest');
        
        
    }
```
