- 运行流程

# 数据加载
```
中间件（路由中指定）、 ComposerServiceProvider（调用\App\Http\ViewComposers中的文件）
```

# 展现
```
视图展现助手-主要是前端样式辅助，数据已通过其他组件获取-App\Http\Presenters
```
 
----

```html
<html>
<head>
	<meta charset="UTF-8">
	<title>视图</title>
</head>
<body>
	{{-- 注释 --}}
	
	{!! $name !!}  #不转义 保有原來 HTML 格式
	{{ $name }}    #转义 htmlentities 
	{{{ $name }}}  #Echo escaped content

	/*******************************************************************************************************************/

	//获取控制器链接
	{{ action('Account\UserController@postLogin') }}
	{{ action('Jurisdiction\ManageController@getUpdate', $value->id) }}  #还可传递值
	
	//获取Public目录下的资源链接
	{{ asset('css/main.css') }}

	//验证字段
	{{ csrf_field() }}

	//验证token
	{{ csrf_token() }}

	/*******************************************************************************************************************/

	//获取session
	{{Session::get('user.nick_name')}}

	/*******************************************************************************************************************/

	//载入框架
	@extends('layout.main')

	//引入其他模板文件
	@include('article.hot')  //引入 \resources\views\article\hot.blade.php 

	@include('view.name', ['some' => 'data'])
	@include('record.item', $record)

	@include('admin/layouts/ueditor')
	
	//区域加载
	@section('css')
		<link rel="stylesheet" href="{{asset('css/main.css')}}">
	@endsection

	@section('content')
		<div></div>
	@endsection

	//跟上面联合使用，通常位于主框架文件，
	@yield('css')
	@yield('content')


	//A在主框架中定义，B然后在引入模板中赋值
	A、@yield('title')
	B、@section('title', 'Page Title')

	//A在主框架中定义一个区域，先写了一些内容，B然后在引入模板中继承，并加入内容
	A、@section('sidebar')
            This is the master sidebar.
        @show

	B、@section('sidebar')
		    @parent

		    <p>This is appended to the master sidebar.</p>
		@endsection
	
	//A在主框架中定义，B然后在引入模板中多次使用
	A、@stack('me')

	B、@push('me')
		me1<br>
		@endpush


		@push('me')
		me2<br>
		@endpush

	/*******************************************************************************************************************/

	//foreach循环
	@if (count($records))
		@foreach ($list as $value)
			{{$value->name}}
		@endforeach
	@else
	    暂无数据
	@endif

	//调用分页
	{{ $list->links() }}
	{{ $list->render() }}
	{{ $list->append(['name'=>$name])->render() }}
	
	//for循环
	@for ($i = 0; $i < 10; $i++)
	    The current value is {{ $i }}
	@endfor

	//whil循环
	@while (true)
	    <p>I'm looping forever.</p>
	@endwhile

	//加入是否无数据的判断
	@forelse ($users as $user)
	    <li>{{ $user->name }}</li>
	@empty
	    <p>No users</p>
	@endforelse

	//循环继续、跳出
	@foreach ($users as $user)
	    @if ($user->type == 1)
	        @continue
	    @endif

	    <li>{{ $user->name }}</li>

	    @if ($user->number == 5)
	        @break
	    @endif
	@endforeach

	######推荐使用这种更简洁
	@foreach ($users as $user)
	    @continue($user->type == 1)

	    <li>{{ $user->name }}</li>

	    @break($user->number == 5)
	@endforeach
	
	//@each用法
	// record/list.blade.php
	<ul>
	    @each('record.item', $lists, 'item', 'record.no-items')
	</ul>

	// record/item.blade.php
	<li>{{ $item->title }}</li>

	// record/no-items.blade.php
	<li>暂无数据</li>

	/*******************************************************************************************************************/
	//判断
	@if($request->is('chat/*'))
		active 
	@endif


	{{ $name or 'Default' }}


	@if (count($records) === 1)
	    I have one record!
	@elseif (count($records) > 1)
	    I have multiple records!
	@else
	    I don't have any records!
	@endif


	@unless (Auth::check())
	    You are not signed in.
	@endunless

	/*******************************************************************************************************************/

	//执行PHP函数
	{{ count($list) }}

	{{ App\Http\Common\Helper::getNow() }}
	
	//app实例化Request，当然也能实例化其他类
	{{ app('Illuminate\Http\Request')->is('chat/visit/dialogue-list') ? 1 : 0 }}
	
	//$request可直接使用
	{{ $request->path() }}

	能这样用是因为在 App\Providers\AppServiceProvider 中的  boot 方法中 书写了下面的代码
	public function boot(Request $request)
    {
        view()->share('request', $request);
    }
	
	/*******************************************************************************************************************/

	//注入服务，调用其方法
	@inject('metrics', 'App\Presenters\MetricsService')

	<div>
	    Monthly Revenue: {{ $metrics->monthlyRevenue() }}.
	</div>


	/*******************************************************************************************************************/

	//扩展标签

	在 App\Providers\AppServiceProvider 中的  boot 方法中 书写了下面的代码
	
	public function boot()
    {
        Blade::directive('datetime', function($expression) {
            return "<?php echo date('Y-m-d H:i:s', $expression); ?>";
        });
    }

    在前台就可以调用了：
    @datetime( time() )

    注意要先清空缓存：
    php artisan view:clear


	/*******************************************************************************************************************/
</body>
</html>
```
