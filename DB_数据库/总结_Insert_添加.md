```php
<?php
# https://laravel.com/api/5.2/Illuminate/Database/Query/Builder.html
#use Illuminate\Support\Facades\DB;

app('db')->table('users')->insert($data);
 
获取 最新新增ID
$db->getPdo()->lastInsertId();

### 单个添加
1.
$model->user_name = $data['user_name'];
$model->addtime = time();
$model->save()

2.
$data = ['username'=>'meinvbingyue'];
$model->create($data);					## 必须定义 fillable 或 guarded 属性 (允许/不允许批量赋值的属性)

3.
$data = ['username'=>'meinvbingyue'];
$model->insert($data);

4.
$data = ['username'=>'meinvbingyue'];
DB::table('users')->insert($data);

5.
$data = ['username'=>'meinvbingyue'];
$new_id = DB::table('users')->insertGetId($data);

6.
DB::insert('insert into users (id, name) values (?, ?)', [1, 'Dayle']);

DB::insert('insert into users (id, name) values (:id, :name)', ['id' => 1, 'name' => 'Dayle']);

### 批量添加
1.
$data = [
	['username'=>'meinvbingyue'],
	['username'=>'jason']
];
DB::table('users')->insert($data);

2.
$data = [
	['username'=>'meinvbingyue'],
	['username'=>'jason']
];
$model->insert($data);

### 关联添加
1.
$post = Post::find(1);
$comment = new Comment(array('message' => 'A new comment.'));
$comment = $post->comments()->save($comment);

2.
$post = Post::find(1);
$comments = array(
    new Comment(array('message' => 'A new comment.')),
    new Comment(array('message' => 'Another comment.')),
    new Comment(array('message' => 'The latest comment.'))
);
$post->comments()->saveMany($comments);


```
