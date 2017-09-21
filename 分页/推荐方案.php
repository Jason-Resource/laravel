php artisan vendor:publish --tag=laravel-pagination

所有默认模板文件将导出到 resources/views/vendor/pagination 下

default.blade.php 文件对应于默认分页视图


/***********************************************************************/

<?php
$admin_user = app('AdminUser');
$page_size = 5;//每页显示个数
$cur_page = request()->page;//当前页
$list =  $admin_user->paginate($page_size, ['*'], 'page', $cur_page);

return view('admin.login',compact('list'));
?>

/***********************************************************************/

@foreach($list as $item)
    <p>{{$item->username}}</p>
    @endforeach
{{$list->links('vendor.pagination.default',['categoryInfo' => $categoryInfo])}}