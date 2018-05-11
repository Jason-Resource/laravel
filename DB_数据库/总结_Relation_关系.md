### 关系图 <http://pan.baidu.com/s/1kVSkMZ9/>

```php
// 后台列表搜索使用
        if (isset($condition['search_name'])) {
            $model->whereHas('relationUser',function($query) use($condition){
                $query->where('real_name', 'like', '%'.$condition['search_name'].'%');
            });
        }
```

----

```php
// 关联里面再关联
$relation = [
    'orderMealRuleRelation' => function($query){
	$query->select(['id','order_meal_rule_id','category_id']);

	$query->with(['relationMealRule'=>function($query){
	    $query->select(['id','type','number','price']);
	}]);
    }
];
$info = $this->pool_category_dao->detail($id, $columns, $relation);
```

```php

$model->select($columns)
->with([
    'question' => function ($query){
        $query->select(['id','paper_id','question_name','question_type','question_sort']);
    },
    'question.question_option' => function ($query) {
        $query->select(['id','question_id','option_name','score','sortrand']);
    }
])
->find($id);

/******************************************************************************************/
#子查询

class ConsultsArticles extends Model
{

  /**
   * 通过一组平台id查询
   */
  public function scopePlatformArrQuery($query,$platform_arr)
  {
      $query->whereHas('ArticlePlatformRelation',function($query) use($platform_arr){
          $query->whereIn('platform_id',$platform_arr);
      });
      return $query;
  }

  /**
   * 关联到平台关系表
   * 一篇文章属于多个平台
   */
  public function ArticlePlatformRelation()
  {
      return $this->hasMany(RelationArticlePlatform::class,'article_id','id');
  }  

}


/******************************************************************************************/

#渴求加载（预加载） -> 减少查询次数

# 下列操作则只会执行两条 SQL 语句
# select * from books
# select * from authors where id in (1, 2, 3, 4, 5, ...)
$books = App\Book::with('author')->get();
foreach ($books as $book) {
    echo $book->author->name;
}

$model->with('permissionRole', 'permissionRole.permission')->first()->permissionRole->pluck('permission.target');
↑↑↑↑↑
第一个参数permissionRole--->  这个是在RoleModel里定义的关系
第二个参数permission--->	    这个是在PermissionRoleModel里定义的关系

$model->with([
    'posts' => function($query)
    {
        $query->where('title', 'like', '%first%');
        $query->orderBy('created_at', 'desc');
    }
])->get();

#延迟加载（延迟预载入 --> 这对于需要根据情况决定是否载入关联对象时，或是跟缓存一起使用时很有用。）
$collection = $model->get();
$collection->load($relatives);


$collection = $model->get();
$related['RelationArticleTag'] = function($query){
    $query->whereNull('consult_tags.deleted_at');
    $query->whereNull('consult_tags_relation.deleted_at');
    $query->select(['*']);
};
if(!empty($related) && count($collection)>0) {
    $collection->load($related);
}

// 上层模型被获取后才预加载关联
$books = App\Book::all();
if ($someCondition) {
    $books->load('author', 'publisher');
}

// 设置预加载查询的额外条件
$books->load(['author' => function ($query) {
    $query->orderBy('published_date', 'asc');
}]);

/******************************************************************************************
用户 - 角色 - 角色权限 - 权限
******************************************************************************************/

$account_model = app('AccountModel');
$collection = $account_model->select(['*'])->with('role', 'role.rolePermission', 'role.rolePermission.permission')->find(5);

dd($collection);
dd($collection->name);	#获取用户名
dd($collection->role->role_name);	#获取用户的角色名
foreach ($collection->role->rolePermission as $role_permission) #循环用户的角色权限列表
{
    dd($role_permission->permission->name);	#获取权限名
}


######反查：先查出角色、然后查看这个角色下的用户数据
$role_model = app('RoleModel');
$collection = $role_model->select()->with('account')->find(1);
dd($collection->role_name);	#获取角色名
dd($collection->account->name);	#获取该角色下用户名

$collection = $role_model->find(1);
dd($collection->load('account')->account->name);


######反查：先查出权限、然后查看这个权限下的哪些角色在用
$permission_model = app('PermissionModel');
$collection = $permission_model->select()->with('permissionRole')->find(1);
foreach ($collection->permissionRole as $role)
{
    echo $role->role_name.' ';
}


class Account extends Model
{
    protected $table = 'account';

	/**
     * 一个用户对应一个角色
     * 
     * where `role`.`id`= `account`.`role_id` 
     */
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id'); //hasOne(string $related, string $foreignKey = null, string $localKey = null)
    }

}


class Role extends Model
{
    protected $table = 'role';
	
	/**
     * 一个角色对应多个权限（用中间表关联）
     * 
     * where `role_permission`.`role_id` in (?) 
     */
    public function rolePermission()
    {
        return $this->hasMany(RolePermission::class, 'role_id', 'id');  //hasMany(string $related, string $foreignKey = null, string $localKey = null)
    }

    /**
     * 这是hasOne的逆向关联
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'id', 'role_id');
    }
}


class RolePermission extends Model
{
    protected $table = 'role_permission';

    public function permission()
    {
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }
}

class Permission extends Model
{
    protected $table = 'permission';

    /**
     * 一个权限对应多个角色
     *
     * 可以记住这个口诀： 权限     -   角色     -   中间表     -   权限     -   角色
     *                    当前模型     关联模型     关联表         当前模型ID   关联模型ID
     */
    public function permissionRole()
    {
        return $this->belongsToMany(Role::class,'role_permission','permission_id','role_id');
    }
}


/******************************************************************************************
试卷 - 试卷评估 - 试题 - 试题选项
******************************************************************************************/
/*CREATE TABLE `nms_test_paper` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paper_name` varchar(255) NOT NULL COMMENT '试卷名称',
  `paper_desc` text COMMENT '试卷描述',
  `created_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='试卷表';*/

class TestPaper extends Model
{
    use SoftDeletes;
    protected $table = 'test_paper';

    /**
     * 获取所有评估结果。
     */
    public function evaluation()
    {
        return $this->hasMany(TestPaperEvaluation::class,'paper_id','id');
    }

    /**
     * 获取所有试题。
     */
    public function question()
    {
        return $this->hasMany(TestQuestion::class,'paper_id','id');
    }
}

/*
CREATE TABLE `nms_test_paper_evaluation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paper_id` int(10) unsigned NOT NULL COMMENT '试卷ID',
  `min_score` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '最新分数',
  `max_score` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '最大分数',
  `evaluation` text NOT NULL COMMENT '评估结果',
  `created_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idnex1` (`paper_id`,`min_score`,`max_score`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='试卷评估表';
*/

/*
CREATE TABLE `nms_test_question` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paper_id` int(10) unsigned NOT NULL COMMENT '试卷ID',
  `question_name` varchar(200) NOT NULL COMMENT '试题名',
  `question_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '试题类型',
  `question_sort` smallint(6) NOT NULL DEFAULT '1' COMMENT '排序',
  `created_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_1` (`paper_id`,`question_sort`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='试题表';
*/
class TestQuestion extends Model
{
    use SoftDeletes;
    protected $table = 'test_question';

    /**
     * 获取所有试题选项。
     */
    public function question_option()
    {
        return $this->hasMany(TestQuestionOption::class,'question_id','id');
    }
}

/*
CREATE TABLE `nms_test_question_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL COMMENT '试题ID',
  `option_name` varchar(200) NOT NULL DEFAULT '' COMMENT '选项名',
  `score` smallint(6) NOT NULL DEFAULT '0' COMMENT '选项分数',
  `sortrand` smallint(6) NOT NULL DEFAULT '1' COMMENT '排序',
  `created_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_1` (`question_id`,`sortrand`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COMMENT='试题选项表';
*/


/******************************************************************************************
5.4文档中的案例集合
******************************************************************************************/
class User extends Model
{
    /**
     * 获取与用户关联的电话号码
     * 一对一
     */
    public function phone()
    {
        return $this->hasOne('App\Phone');
    }
}

$phone = User::find(1)->phone;

------------------------------------------------------
class Phone extends Model
{
    /**
     * 获取拥有该电话的用户模型。
     * 一对一（反向关联）
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
------------------------------------------------------
class Post extends Model
{
    /**
     * 获取这篇博文下的所有评论。
     * 一对多
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}

$comments = App\Post::find(1)->comments;
$comments = App\Post::find(1)->comments()->where('title', 'foo')->first();

// 获取那些至少拥有一条评论的文章...
$posts = App\Post::has('comments')->get();

// 获取所有至少有三条评论的文章...
$posts = Post::has('comments', '>=', 3)->get();

// 获取所有至少有一条评论被评分的文章...   votes应该是comment模型里的votes关联--未验证
$posts = Post::has('comments.votes')->get();

//  获取那些至少有一条评论包含 foo 的文章
$posts = Post::whereHas('comments', function ($query) {
    $query->where('content', 'like', 'foo%');
})->get();

// 如果你想对关联数据进行计数但又不想再发起单独的 SQL 请求，你可以使用 withCount 方法，此方法会在你的结果集中增加一个 {relation}_count 字段
$posts = App\Post::withCount('comments')->get();
foreach ($posts as $post) {
    echo $post->comments_count;
}

// 获取多重关联的「计数」
$posts = Post::withCount(['votes', 'comments' => function ($query) {
    $query->where('content', 'like', 'foo%');
}])->get();

echo $posts[0]->votes_count;
echo $posts[0]->comments_count;

------------------------------------------------------
class Comment extends Model
{
    /**
     * 获取该评论所属的文章模型。
     * 一对多（反向关联）
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
$comment = App\Comment::find(1);
echo $comment->post->title;

------------------------------------------------------
class User extends Model
{
    /**
     * 属于该用户的身份。
     * （多对多）
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');

        //return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id')->withTimestamps(); #->中间表自动维护 created_at 和 updated_at 时间戳

        //return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id')->wherePivot('approved', 1);          #->使用中间表来过滤关联数据
        //return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id')->wherePivotIn('priority', [1, 2]);
    }
}

//该用户下的所有角色
$user = App\User::find(1);
foreach ($user->roles as $role) {
    //
}

//该用户下的所有角色（根据name排序后）
$roles = App\User::find(1)->roles()->orderBy('name')->get();

//获取中间表字段
$user = App\User::find(1);
foreach ($user->roles as $role) {
    echo $role->pivot->created_at;
}

------------------------------------------------------

class Role extends Model
{
    /**
     * 属于该身份的用户。
     * 多对多（反向关联）
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}

------------------------------------------------------

# 远层一对多

表结构：
countries
    id - integer
    name - string

users
    id - integer
    country_id - integer
    name - string

posts
    id - integer
    user_id - integer
    title - string


class Country extends Model
{
    /**
     * 获取该国家的所有文章。
     */
    public function posts()
    {
        /*
        * 第一个参数为我们希望最终访问的模型名称
        * 第二个参数为中间模型的名称
        * 第三个参数为中间模型的外键名称
        * 第四个参数为最终模型的外键名称
        * 第五个参数则为本地键。
        */
        return $this->hasManyThrough(
            'App\Post', 'App\User',
            'country_id', 'user_id', 'id'
        );
    }
}

------------------------------------------------------

# 多态关联

表结构：

posts
    id - integer
    title - string
    body - text

videos
    id - integer
    title - string
    url - string

comments
    id - integer
    body - text
    commentable_id - integer           # 存放文章或者视频的 id
    commentable_type - string          # 存放所属模型的类名，commentable_type 是当我们访问 commentable 关联时， ORM 用于判断所属的模型是哪个「类型」 
                                       # commentable_type 的默认值可以分别是 App\Post 或者 App\Video
                                       # 也可以自定义:在 AppServiceProvider 中的 boot 函数注册这个 morphMap ，或者创建一个独立且满足你要求的服务提供者。   
                                          use Illuminate\Database\Eloquent\Relations\Relation;
                                          Relation::morphMap([
                                              'posts' => App\Post::class,
                                              'videos' => App\Video::class,
                                          ]);
                                          
class Comment extends Model
{
    /**
     * 获取所有拥有的 commentable 模型。
     */
    public function commentable()
    {
        return $this->morphTo();
    }
}

class Post extends Model
{
    /**
     * 获取所有文章的评论。
     */
    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }
}

class Video extends Model
{
    /**
     * 获取所有视频的评论。
     */
    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }
}

//获取谋篇文章的所有评论
$post = App\Post::find(1);
foreach ($post->comments as $comment) {
}

$comment = App\Comment::find(1);
$commentable = $comment->commentable; # Comment 模型的 commentable 关联会返回 Post 或 Video 实例，这取决于评论所属模型的类型。


------------------------------------------------------

# 写入关联模型

# 插入单条
$comment = new App\Comment(['message' => 'A new comment.']);
$post = App\Post::find(1);
$post->comments()->save($comment);


$post = App\Post::find(1);
$comment = $post->comments()->create([
    'message' => 'A new comment.',
]);

# 插入多条
$post = App\Post::find(1);
$post->comments()->saveMany([
    new App\Comment(['message' => 'A new comment.']),
    new App\Comment(['message' => 'Another comment.']),
]);


------------------------------------------------------

# 连动父级时间戳

class Comment extends Model
{
    /**
     * 所有的关联将会被连动。
     *
     * @var array
     */
    protected $touches = ['post'];      // <----------------

    /**
     * 获取拥有此评论的文章。
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}


$comment = App\Comment::find(1);
$comment->text = 'Edit to this comment!';
$comment->save();
```
