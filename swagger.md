- 引入组件
 ```
 composer require zircote/swagger-php
 ```
 
- 创建控制器
  * php artisan make:controller SwaggerController
```php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * 文档
 * Class SwaggerController
 * @SWG\Info(
 *      version="1.0",
 *      title="后台接口",
 *      description="按功能模块区分,测试执行可以使用对应的 try it out"
 * )
 */
class SwaggerController extends Controller
{
    public function api()
    {
        /*if ( in_array(gethostname(), ["ask_stock_control"]) ) {
            return "";
        }*/
        $swagger = \Swagger\scan(app('path') . '/Http/Controllers');
        // 在没写 200 响应时添加对应的 200 回应
        $methods = ['get', 'put', 'post', 'delete', 'options', 'head', 'patch'];
        foreach ($swagger->paths as &$paths) {
            foreach ($methods as $method) {
                if (!isset($paths->{$method})) {
                    continue;
                }

                foreach ($paths->{$method}->{'responses'} as &$response) {
                    if ($response->response == 200) {
                        $response->description = '当code不为0时，抛出msg的错误信息';
                    }
                }
            }
        }
        return response()->json($swagger);
    }
}

```

- 创建路由
  ```php
  $router->get('swagger-api', 'SwaggerController@api');
  
  // 返回文档接口 （只在本地开发时候才显示）
    if (config('app.env') == 'local' && config('app.debug') == true) {
        Route::get('swagger-api', 'SwaggerController@api');
    }
  ```
  
- 构建 swagger-ui
  * 在public目录下，创建swagger文件夹
  * git clone git@github.com:swagger-api/swagger-ui.git
  * 将dist下的所有文件，拷贝到swagger文件夹下

- 修改swagger-ui访问地址
  * index.html
  
  ```js
  //url: "http://petstore.swagger.io/v2/swagger.json",
  url: "/swagger-api",
  ```
 
- 接口编写

```php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    /**
     * 添加
     * @SWG\Post(
     *     path="/backend/admin/add",
     *     description="添加管理员",
     *     operationId="backend.admin.add",
     *     produces={"application/json"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     tags={"权限模块"},
     *     @SWG\Parameter(name="name",description="登录名",type="string",in="formData",required=true),
     *     @SWG\Parameter(name="realname",description="真实姓名",type="string",in="formData",required=true),
     *     @SWG\Parameter(name="nickname",description="昵称",type="string",in="formData",required=false),
     *     @SWG\Parameter(name="password",description="密码",type="string",in="formData",required=true),
     *     @SWG\Parameter(name="role_id",description="角色ID",type="integer",in="formData",required=true),
     *     @SWG\Response(
     *         response=200,
     *         description="",
     *     )
     * )
     * @author jason
     * @date 2017-09-22
     * @param Request         $request
     * @param AdminBusiness $business
     * @return array
     */
    public function index(Request $request)
    {
        return view('admin.index.index');
    }

}

```

- 接口文档访问

```
http://test.cc/swagger/
```

----

```
/**
     * 广告列表
     * @SWG\Get(
     *     path="/backend/ad/list",
     *     description="返回广告列表",
     *     operationId="backend.ad.list",
     *     produces={"application/json"},
     *     tags={"广告"},
     *     @SWG\Parameter(name="is_all",description="是否查询全部(默认0),1-全部查询,0-分页查询",type="integer",in="query",required=false),
     *     @SWG\Parameter( name="page",description="页码",type="integer",in="query",required=false),
     *     @SWG\Parameter(name="page_size",description="每页数量",type="integer",in="query",required=false),
     *     @SWG\Parameter(name="keyword",description="关键词",type="string",in="query",required=false),
     *     @SWG\Parameter(name="position",description="广告位置 app:app首页, pc:pc首页",type="string",in="query",required=false),
     *     @SWG\Parameter(name="is_show",description="是否显示 yes:显示, no:不显示",type="string",in="query",required=false),
     *     @SWG\Parameter(name="title",description="标题",type="string",in="query",required=false),
     *     @SWG\Parameter(name="release_time_start",description="开始日期:YYY-mm-dd HH:ii:ss",type="string",in="query",required=false),
     *     @SWG\Parameter(name="release_time_end",description="结束日期:YYY-mm-dd HH:ii:ss",type="string",in="query",required=false),
     *     @SWG\Response(
     *         response=200,
     *         description="",
     *     )
     * )
     * @author lhl
     * @Date 2017-9-18 14:04:28
     * @param Request $request
     * @param AdBusiness $ad_business
     * @return mixed
     */
```
