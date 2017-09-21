# 定义接口 App\Contracts\HelloInterface.php
<?php
namespace App\Contracts;

interface HelloInterface
{
    public function sayHello($name);
}
?>

# 实现接口 App\Services\HelloCn.php && HelloUs.php
<?php
namespace App\Services;
use App\Contracts\HelloInterface;

class HelloCn implements HelloInterface
{
    public function sayHello($name)
    {
        return "你好：".$name;
    }
}
?>

<?php 
namespace App\Services;
use App\Contracts\HelloInterface;

class HelloUs implements HelloInterface
{
    public function sayHello($name)
    {
        return 'hello:'.$name;
    }
}
?>



/*******************************************************************************************************/
# HelloFactory.php

<?php 
namespace App\Services;
use Illuminate\Support\Facades\App;
use App\Contracts\HelloInterface;

class HelloFactory
{
    public static function bind($lang)
    {
    	#定义绑定
        App::bind(HelloInterface::class,'App\Services\Hello'.$lang);
    }
}
?>

/*******************************************************************************************************/
# IndexController.php

<?php 
namespace App\Http\Controllers;
use App\Services\HelloFactory;

class IndexController extends Controller
{
    public function hello(HelloFactory $helloFactory)
    {
    	#执行绑定
        $lang = request('lang', 'Cn');
        $helloFactory::bind($lang);

        return view('hello');
    }
}
?>

/*******************************************************************************************************/
# hello.blade.php

@inject('helloService','App\Contracts\HelloInterface')
{{ $helloService->sayHello(request('name','laravel')) }}