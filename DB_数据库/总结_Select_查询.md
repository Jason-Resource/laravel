```php

# 原生查询
$users = DB::select('select * from users where id = :id', ['id' => 1]);

$builder = DB::table('articles');

app('db')->connection('mysql_user');

$builder = app('db')->table('users');

$model = app('PermissionsModel');	//PermissionsModel是在ServiceProvider中定义的

/**************************************************
	字段
**************************************************/
$model->select();
$model->select(['*']);
$model->select(['id', 'name']);
$model->select('name', 'email as user_email');
$model->addSelect('age');							#追加查询字段

$raw = DB::raw('id,user_id,manage_id,message,start, min(created_at) start_time, max(created_at) as end_time');
$model->select($raw);

/**************************************************
	条件
**************************************************/

$model->IdQuery(1);					#对应Model里的scope方法

$model->whereIn('pid', [1,2,3]);

$model->where('role_id', 1);
$model->where('role_id', '=', 1);
$model->where('role_id', '!=', 1);
$model->where('votes', '>', 100);
$model->where('votes', '>=', 100);
$model->where('votes', '<>', 100);
$model->where('name', 'like', 'T%');
$model->where(
			[
			    ['status', '=', '1'],
			    ['subscribed', '<>', '1'],
			]);

$model->where(function ($query) {
	$query->where('id',1);
});

$model->where(function ($query)  use ($condition){
    $query->orWhere('user_name', 'like', '%' . $condition['name'] . '%')
          ->orWhere('nick_name', 'like', '%' . $condition['name'] . '%');
});


$model->whereRaw(" (`created_at` > '".Carbon::today()->subMonths(3)->toDateString()."' or (`order` > 0) ) ");
$model->whereRaw('FIND_IN_SET(?, area)', [$condition['area']]);

$model->whereBetween('votes', [1, 100]);

$model->whereDate('created_at', '2016-12-31');
$model->whereYear('created_at', '2016');
$model->whereMonth('created_at', '12');
$model->whereDay('created_at', '31');

$model->whereColumn('first_name', 'last_name');		#用于验证两个字段是否相等
$model->whereColumn('updated_at', '>', 'created_at');
$model->whereColumn([
                ['first_name', '=', 'last_name'],
                ['updated_at', '>', 'created_at']
            ]);

/**************************************************
	排序
**************************************************/
$model->orderBy('id');
$model->orderBy('id', 'desc');
$model->orderBy(DB::raw('RAND()'));
$model->orderByRaw("RAND()");

$model->orderBy('sort','desc')->orderBy('id','desc');

/**************************************************
	分组
**************************************************/
$model->groupBy('id');
$model->groupBy('user_id', 'manage_id');

/**************************************************
	去重
**************************************************/
$model->distinct()->get();
$model->distinct()->get(['name']);


/**************************************************
	统计
**************************************************/
$model->count();

$model->max('price');

$model->avg('price');


/**************************************************
	获取执行的SQL语句，必须在获取数据之前调用
**************************************************/
$model->toSql();


/**************************************************
	获取数据
**************************************************/

$model->get();							#获取所有

$model->take(5)->get();					#获取若干
$model->limit(5)->get();					

$model->simplePaginate(15);				#分页
$model->paginate(15);

$model->first();						#获取第一个数据
$model->find(1);						#查询单个数据
$model->find([1, 2, 3]);				#查询若干数据

$model->value('name');					#提取某列的单个数据
$model->pluck('name');					#提取某列的若干数据
foreach ($names as $name) {
}

$roles = $model->pluck('title', 'name');			#可以理解为 以name为键，以title为值
foreach ($roles as $name => $title) {
    echo $title;
}

$model->chunk(100, function($users) {				#将数据进行分块，每次处理 100 条记录
    foreach ($users as $user) {
        //
    }
});


$model->created_at->getTimestamp();				##获取时间戳



/*******************************************************栗子************************************************************************************/

$builder = DB::table('articles');
$result = $builder->select(DB::raw('count(id) as total,category_id'))
    ->whereIn('category_id', $category_id_arr)
    ->groupBy('category_id')
    ->get();


$db = app('db');
$sql = $db->raw('SELECT count(id) as total,id,title,version FROM `stk_information` group by version having total>1');
$result = $db->select($sql);
$id_arr = collect($result)->pluck('id');    


// 左联
$builder = app('InvoiceModel')
    ->select([
	'invoice.id',
	'invoice.*',
	'user_weixin_assessment_vip.id as vip_id',
	'user_weixin_assessment_vip.serve_id as vip_serve_id',
	'user_weixin_assessment_vip.sign_contract_date as vip_date',
    ])
    ->leftJoin('user_weixin_assessment_vip', function($join) {
	$join->on('invoice.product_id', '=', 'user_weixin_assessment_vip.serve_id')
	    ->on('invoice.user_id', '=', 'user_weixin_assessment_vip.weixin_id')
	    ->where('user_weixin_assessment_vip.sign_contract_date', '!=', '0')
	    ->whereNull('user_weixin_assessment_vip.deleted_at');
    })
;
$builder->where('invoice.user_id', $condition['user_id']);
$builder->groupBy('invoice.id');
$list = $builder->get();
```
