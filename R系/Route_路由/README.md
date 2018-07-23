- 别名 
```php

$app->post('verify', ['uses'=>'ArticlesMustReadController@verify','as'=>'must_read_verify']);

Route::resource('api/article', 'Api\ArticleController',['as'=>'api']);

Route::group(['as' => 'admin::'], function () {
    Route::get('dashboard', ['as' => 'dashboard', function () {
        // 路由被命名为 "admin::dashboard"
    }]);
});

Route::get('user/profile', 'UserController@showProfile')->name('profile');
```
