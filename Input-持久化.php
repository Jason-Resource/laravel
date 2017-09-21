<?php

/*用户提交信息持久化
有时可能需要在用户的多个请求之间持久化用户提交的信息。 比如，当用户提交的信息验证失败重新返回提交信息页面时还原用户的输入。
将用户提交的信息存入Session*/


Input::flash();
Input::flashOnly('username', 'email');	//把指定的用户提交的信息存入Session
Input::flashExcept('password');

 




//如果你需要关联持久用户提交的信息的操作和重定向操作，可以使用如下的链式调用的方法：


return Redirect::to('form')->withInput();
 

return Redirect::to('form')->withInput(Input::except('password'));

 

//注意： 如果你想持久化其它的信息，请参考 Session 类.
//获取已持久化的用户提交的信息


Input::old('username');

// 也可以使用全局函数获取
old('username');