<?php
/*
|--------------------------------------------------------------------------
| 跳转相关   基于 \Illuminate\Routing\Redirector
|--------------------------------------------------------------------------
|
| 跳转要在Controller层执行，不要在其他层执行
|
| 参考：https://laravel.com/docs/5.3/redirects
|
*/

return redirect('/admin');

return redirect()->to('/admin');

return Redirect::to('admin')->with('success', '登录成功！');

return redirect()->guest('auth/login'); 

return redirect()->route('login');

return redirect()->route('profile', ['id' => 1]);

return redirect()->action('Account\UserController@getLogin');

return redirect()->action('UserController@profile', ['id' => 1]);

return new RedirectResponse(url('/home')); //要引入 use Illuminate\Http\RedirectResponse;

return redirect()->back(); <=> return back();

url()->previous();
