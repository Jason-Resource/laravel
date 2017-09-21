```php
<?php
# https://laravel.com/api/5.2/Illuminate/Database/Query/Builder.html
#use Illuminate\Support\Facades\DB;

# 获取表名
$table_name = $model->getTable();


$model->IdQuery(1)->delete();

$model->destroy(1);
$model->destroy([1,2,3]);

$affected = DB::delete("delete from users where id=?", [1]);

//判断是否已经被软删除
if ($model->trashed()) {
    //
}

//恢复被软删除的模型
$model->restore();

//永久地删除模型
$model->forceDelete();



/*********************************栗子*********************************/
$db = app('db');
$db->table('stk_information')
	->whereIn('id', $id_arr)
	->delete();
```
