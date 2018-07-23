```php
别名 
$app->post('verify', ['uses'=>'ArticlesMustReadController@verify','as'=>'must_read_verify']);

Route::resource('api/article', 'Api\ArticleController',['as'=>'api']);
```
