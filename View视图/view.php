<?php 

//视图文件是否存在
if (view()->exists('emails.customer')) {
    //
}


//加载视图并赋值
return view('greetings', ['name' => 'Victoria']);

return view('greetings', compact('list'));

return view('greeting')->with('name', 'Victoria');

return view('greeting')->with(compact('list'));
?>


<?php

//所有视图中共享数据
namespace App\Providers;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        view()->share('key', 'value');
    }
}

?>