```php

$url = url("/posts/{$id}"); // http://example.com/posts/1


route('route_name', ['id' => $id]);

$url = action('HomeController@index');
$url = action('UserController@profile', ['id' => 1]);
```
