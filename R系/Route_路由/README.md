- 别名 
```php
Route::get('keyword/all','KeywordController@all');     // 放到上面才能覆盖
Route::resource('keyword', 'KeywordController');

Route::resource('api/article', 'Api\ArticleController',['as'=>'api']);


$app->post('verify', ['uses'=>'ArticlesMustReadController@verify','as'=>'must_read_verify']);

Route::group(['prefix'=>'admin', 'as' => 'admin::'], function () {
    Route::get('dashboard', ['as' => 'dashboard', function () {
        // 路由被命名为 "admin::dashboard"
    }]);
});

Route::get('user/profile', 'UserController@showProfile')->name('profile');
```

----

```
class Illuminate\Routing\Route;   #这个可以理解为底层的路由类，所有有关路由的方法都集中在这里

class Illuminate\Routing\Router;  #可以认为这个主要是用来获取上面的Route实例用的，然后提供给RouteServiceProvider注册到系统中使用，同时提供了Facade

/*
路由文件中使用get/post配置，则Controller的method中不需要用前缀any/get/post等
路由文件中使用controller配置，则Controller的method中需要用前缀any/get/post等
*/
use Route;                                  //------------>\Illuminate\Routing\Router；    在/config/app.php中定义了别名，所以可以这样use

$route = Route::current();					//这个获取的是\Illuminate\Routing\Route实例，可调用print_r(get_class_methods($route)); 查看所有方法

$name = Route::currentRouteName();			//这个获取路由别名，需要在定义路由的时候指定，否则返回null。Route::get('/', 'IndexController@anyIndex')->name('index');

$action = Route::currentRouteAction();		//获取路由的控制器+方法的完整信息。App\Http\Controllers\Admin\IndexController@anyIndex


////////////////////////还可以直接make出来使用////////////////////////
$router = app('router');    #Facade中定义的名称

$route = $router->current();

// 获取当前路由别名
$name = $router->currentRouteName();

$action = $router->currentRouteAction();

$pre_url = url()->previous();   // 获取来路链接 -- UrlGenerator
```
