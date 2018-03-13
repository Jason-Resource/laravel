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
   * 测试上传
   * Class SwaggerController
   * @SWG\Info(
   *      version="1.0",
   *      title="问股后台接口",
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
  ```
  
- 构建 swagger-ui
  * 在public目录下，创建swagger文件夹
  * git clone git@github.com:swagger-api/swagger-ui.git
  * 将dist下的所有文件，拷贝到swagger文件夹下

- 修改swagger-ui访问地址
  * index.html
  
  ```js

  ```
 
