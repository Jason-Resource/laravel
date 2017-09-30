```php
<?php

namespace App\Http\Business\Dao;

use App\Exceptions\JsonException;
use App\Http\Common\Helper;
use App\Model\Test as Model;
use Illuminate\Database\QueryException;

class TestDao
{
    // 模型名称
    private $model_name = 'TestModel';

    // 每页个数
    private $page_size = 15;

    // id(兼容mongodb)
    private $id = 'id';

    // 错误码
    private $err_code = [
        // 参数错误
        'invalid_param' => 10000,
        // 保存失败
        'store_fail' => 88888,
        // 批量保存失败
        'batch_store_fail' => 88888,
        // 更新失败
        'update_fail' => 88888,
        // 批量更新失败
        'batch_update_fail' => 88888,
        // 详情不存在
        'no_detail' => 88888,
        // 删除失败
        'destroy_fail' => 88888,
        // 批量删除失败
        'batch_destroy_fail' => 88888,
    ];

    public function __construct()
    {
        $this->page_size = config('site.page_size.default');
    }

    /**
     * 验证数据
     *
     * @param $type 类型
     * @param array $data 数据
     * @param array $extra_rule 扩展规则
     */
    public function validateData($type, array $data, array $extra_rule = [])
    {
        switch ($type)
        {
            case 'store':
                $rule = [
                    'first_name' => ['required', 'string', 'min:1'],
                    'age' => ['required', 'numeric', 'min:10'],
                ];
                break;
            case 'batch_store':
                $rule = [
                    'first_name' => ['required', 'string', 'min:1'],
                    'age' => ['required', 'numeric', 'min:10'],
                ];
                break;
            case 'update':
                $rule = [
                    'first_name' => ['string', 'min:1'],
                    'age' => ['numeric', 'min:10'],
                ];
                break;
            case 'batch_update':
                $rule = [
                    $this->id => ['numeric', 'min:1'],
                    'age' => ['numeric', 'min:10'],
                ];
                break;
            case 'destroy':
                $rule = [
                    $this->id => ['required', 'numeric', 'min:1'],
                ];
                break;
            case 'batch_destroy':
                $rule = [
                    'id_include' => ['array', 'min:1'],
                ];
                break;
            case 'recharge':
                $rule = array(
                    $this->id => ['required', 'integer', 'min:1'],
                    'column' => ['required', 'string', 'min:1'],
                    'type' => ['required', 'string', 'in:increment,decrement'],
                );
                break;
            case 'get_data':
                $rule = array(
                    'page_size' => ['integer', 'min:1'],
                    $this->id => ['integer', 'min:1'],
                    'id_include' => ['array', 'min:1'],
                );
                break;
        }

        if (!empty($extra_rule)) {
            $rule = array($rule, $extra_rule);
        }
        Helper::validateParam($rule, $data);

        return $rule;
    }

    /**
     * 获取查询器
     *
     * @param $builder
     * @param $data
     * @return mixed
     */
    public function getQuery($builder, $data)
    {
        // ID查询
        if (isset($data[$this->id])) {
            $builder->IdQuery($data[$this->id]);
        }
        // 包含ID查询
        if (isset($data['id_include'])) {
            $arr_temp = is_array($data['id_include']) ? $data['id_include'] : explode(',', $data['id_include']);
            $builder->IncludeIdQuery($arr_temp);
        }
        // 包含关系查询
        if (isset($data['edu_include'])) {
            $arr_temp = is_array($data['edu_include']) ? $data['edu_include'] : explode(',', $data['edu_include']);
            $builder->EducationIncludeQuery($arr_temp);
        }

        return $builder;
    }

    /******************************************以下代码不用修改****************************************************/
    /**
     * 添加
     *
     * @param array $data
     * @return $model
     */
    public function store(array $data = [])
    {
        // 验证数据
        $rule = $this->validateData('store', $data);

        // 获取模型
        $model = $this->getModel();

        // 赋值
        $allow_key = array_keys($rule);
        foreach ($data as $key=>$value) {
            if (in_array($key, $allow_key)) {
                $model->$key = $value;
            }
        }
        $cur_time = Helper::getNow(true);
        $model->addtime = $cur_time;
        $model->last_update_time = $cur_time;

        // 执行保存
        $response = $model->save();

        // 添加失败
        if (true !== $response) {
            throw new JsonException($this->err_code['store_fail']);
        }

        return $model;
    }

    /**
     * 批量添加
     *
     * @param array $data
     * @return boolean
     */
    public function batchStore(array $data = [])
    {
        if (empty($data)) {
            throw new JsonException($this->err_code['invalid_param']);
        }

        $insert_data = [];
        foreach ($data as $key=>$item) {

            // 验证数据
            $rule = $this->validateData('batch_store', $item);

            $cur_time = Helper::getNow(true);
            $cur_format_time = Helper::getFormatTime();

            // 赋值
            $allow_key = array_keys($rule);
            foreach ($item as $k=>$v) {
                if (in_array($k, $allow_key)) {
                    $insert_data[$key][$k] = $v;
                }
            }
            $insert_data[$key]['addtime'] = $cur_time;
            $insert_data[$key]['last_update_time'] = $cur_time;
            $insert_data[$key]['created_at'] = $cur_format_time;
            $insert_data[$key]['updated_at'] = $cur_format_time;
        }

        // 执行添加
        $boolean = $this->getModel()->insert($insert_data);

        // 添加失败
        if (true !== $boolean) {
            throw new JsonException($this->err_code['batch_store_fail']);
        }

        return $boolean;
    }

    /**
     * 修改
     *
     * @param array $data
     * @return $model
     */
    public function update(array $data = [], Model $model = null)
    {
        // 验证数据
        $rule = [];
        if (is_null($model)) {
            $rule[$this->id] = ['required', 'numeric', 'min:1'];
        }
        $rule = $this->validateData('update', $data, $rule);

        // 获取模型
        $model = !is_null($model) ? $model : $this->detail($data[$this->id]);

        // 构造要修改的字段
        $allow_key = array_keys(array_except($rule,$this->id));
        foreach ($data as $key=>$value) {
            if (in_array($key, $allow_key)) {
                $model->$key = $value;
            }
        }
        $model->last_update_time = Helper::getNow(true);

        // 执行修改
        $response = $model->save();

        // 修改失败
        if (true !== $response) {
            throw new JsonException($this->err_code['update_fail']);
        }

        return $model;
    }

    /**
     * 批量修改
     *
     * @param array $data
     * @return int $affected 影响行数
     */
    public function batchUpdate(array $data = [])
    {
        if (empty($data)) {
            throw new JsonException($this->err_code['invalid_param']);
        }

        $update_data = [];
        foreach ($data as $key=>$item) {
            // 验证数据
            $rule = $this->validateData('batch_update', $data);

            // 赋值
            $allow_key = array_keys($rule);
            foreach ($item as $k=>$v) {
                if (in_array($k, $allow_key)) {
                    $update_data[$key][$k] = $v;
                }
            }
            $update_data[$key]['last_update_time'] = Helper::getNow(true);
            $update_data[$key]['updated_at'] = Helper::getFormatTime();
        }

        $table = $this->getModel()->getTable();

        // 组装sql
        $update_sql = Helper::updateBatch($table, $update_data);

        // 执行修改
        $db = app('db');
        try {
            $affected = $db->update($db->raw($update_sql));
        } catch (QueryException $e) {
            throw new JsonException($this->err_code['batch_update_fail']);
        }

        return $affected;
    }

    /**
     * 删除
     *
     * @param $id
     * @param Model|null $model
     * @return Model
     */
    public function destroy($id, Model $model = null)
    {
        // 验证数据
        if (is_null($model)) {
            $data = [
                $this->id => $id,
            ];
            $this->validateData('destroy', $data);
        }

        // 获取模型
        $model = !is_null($model) ? $model : $this->detail($id);

        // 判断详情是否存在
        if (empty($model) || !isset($model->id)) {
            throw new JsonException($this->err_code['no_detail']);
        }

        // 执行删除
        $response = $model->delete();

        if (true !== $response) {
            throw new JsonException($this->err_code['destroy_fail']);
        }

        return $model;
    }

    /**
     * 批量删除
     *
     * @param array $data
     * @return int $affected 影响行数
     */
    public function batchDestroy(array $data = [])
    {
        // 验证数据
        $this->validateData('batch_destroy', $data);

        // 获取构造器
        $builder = $this->getBuilder($data, [$this->id]);

        // 执行删除
        $affected = $builder->delete();

        // 批量删除失败
        if (empty($affected)) {
            throw new JsonException($this->err_code['batch_destroy_fail']);
        }

        return $affected;
    }

    /**
     * 字段值递增/递减
     *
     * @param $id int ID
     * @param $column string 字段名
     * @param $type string 类型
     * @param $amount int 数量
     * @return int 影响行数
     * @throws JsonException
     */
    public function recharge($id, $column, $type = 'increment', $amount = 1)
    {
        // 验证数据
        $data = [
            $this->id => $id,
            'column' => $column,
            'type' => $type,
        ];
        $this->validateData('recharge', $data);

        $model = $this->detail($id);

        if ($type == 'increment') {
            $affected = $model->increment($column, $amount);
        } else {
            $affected = $model->decrement($column, $amount);
        }

        return $affected;
    }

    /**
     * 详情
     *
     * @param int $id
     * @param array $data
     * @param array $columns
     * @param array $relation
     * @return $model
     */
    public function detail($id = 0, array $data = [], array $columns = ['*'], array $relation = [])
    {
        if (!empty($id) && $id > 0) {
            $data[$this->id] = $id;
        }
        $data['first'] = 'true';

        $model = $this->getData($data, $columns, $relation);

        return $model;
    }

    /**
     * 全部数据

     * @param array $data 数据
     * @param array $columns 字段
     * @param array $relation 关联关系
     * @return $collection
     */
    public function allData(array $data = [], array $columns = ['*'], array $relation = [])
    {
        $data['all'] = 'true';

        $list = $this->getData($data, $columns, $relation);
        
        return $list;
    }

    /**
     * 获取数据
     *
     * @param $data    array   数据
     *         $data['first'] = 'true'              单个数据
     *         $data['sort'] = ['id'=>'desc']       排序
     *         $data['sort'] = 'rand'               随机
     *         $data['group'] = 'id,name'           分组
     *         $data['limit'] = 10                  限制条数
     *         $data['offset'] = 100                跳过条数
     *         $data['all'] = 'true'                获取所有
     *         $data['total'] = 'true'              统计
     * @param $columns      array   查询字段
     * @param $relation     array   关联关系
     * @return $collection
     */
    public function getData(array $data = [], array $columns = ['*'], array $relation = [])
    {
        // 验证数据
        $this->validateData('get_data', $data);

        // 获取构造器
        $builder = $this->getBuilder($data, $columns, $relation);

        // 获取数据
        if (isset($data['limit']) && is_numeric($data['limit']) && $data['limit'] > 0) {

            if (isset($data['offset'])) {
                $builder->skip((int)$data['offset']);
            }
            $collection = $builder->take($data['limit'])->get();

        } elseif (isset($data['all']) && $data['all'] == 'true') {

            $collection = $builder->all();

        } elseif (isset($data['first']) && $data['first'] == 'true') {

            $collection = $builder->first();

        } else if (isset($data['total']) && $data['total'] == 'true') {

            $collection = $builder->count($this->id);

        } else {

            // 每页个数
            $page_size = array_get($data, 'page_size', $this->page_size);

            $collection = $builder->paginate($page_size);
        }

        // 延迟加载关系
        if (!empty($relation) && !empty($collection)) {
            $collection->load($relation);
        }

        return $collection;
    }

    /**
     * 获取模型
     *
     * @return $model
     */
    public function getModel()
    {
        return app($this->model_name);
    }

    /**
     * 获取构造器
     *
     * @param array $data
     * @param array $columns
     * @param array $relation
     * @return $builder
     */
    public function getBuilder(array $data = [], array $columns = ['*'], array $relation = [])
    {
        $builder = $this->getModel()->select($columns);

        $builder = $this->getQuery($builder, $data);

        // 排序
        if (isset($data['sort']) && $data['sort'] == 'rand') {

            $builder->orderBy(app('db')->raw('RAND()'));
        } else {

            // 默认排序
            if (!isset($data['sort'])) {
                $data['sort'] = [
                    'addtime' => 'desc',
                    $this->id => 'desc',
                ];
            }

            foreach ($data['sort'] as $column => $rule) {
                $builder->orderBy($column, $rule);
            }

        }

        // 分组
        if (isset($data['group'])) {
            $builder->groupBy($data['group']);
        }

        // 预加载关系
        if (!empty($relation) && isset($data['with']) && $data['with'] == 'true') {
            $builder->with($relation);
        }

        // 调试
//        echo $builder->toSql();var_dump($data);exit;

        return $builder;
    }

}
```
