```php

namespace App\Http\Business\Dao;

use App\Exceptions\JsonException;
use App\Http\Common\Helper;

class UserWarningDao
{
    // 模型名称
    private $model_name = 'UserWarningModel';
    
    /**
     * 添加
     *
     * @author jilin
     * @param array $data
     */
    public function store(array $data = [])
    {
        //code 类型
        $allow_type_arr = array_column(config('stock.choice_type'), 'code');
        $allow_type_str = implode(',', $allow_type_arr);
        
        // 验证数据
        $rule = [
            'user_id' => ['required', 'numeric', 'min:1'],
            'code' => ['required', 'string', 'min:1'],
            'type' => ['required', 'numeric', 'in:'.$allow_type_str],
            'buy_one' => ['numeric', 'min:0'],
            'sell_one' => ['numeric', 'min:0'],
        ];
        $validator = app('validator')->make($condition, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }

        // 模型
        $model = app($this->model_name);
        // 赋值
        $allow_key = array_keys($rule);
        foreach ($condition as $key=>$value) {
            if (in_array($key, $allow_key)) {
                $model->$key = $value;
            }
        }
        $model->addtime = Helper::getNow(true);
        $model->last_update_time = Helper::getNow(true);

        $response = $model->save();
        
        // 添加失败
        if (true !== $response) {
            throw new JsonException(60010);
        }

        return $model;
    }
    
    /**
     * 批量添加
     *
     * @author jilin
     * @return boolean
     */
    public function batchStore(array $data = [])
    {
        if (empty($data)) {
            throw new JsonException(10000);
        }

        // 验证数据
        foreach ($data as $condition) {
            $rule = [
                'user_id' => ['required', 'numeric', 'min:1'],
                'assemble_id' => ['required', 'numeric', 'min:1'],
            ];
            $validator = app('validator')->make($condition, $rule);
            if ($validator->fails()) {
                throw new JsonException(10000, $validator->messages());
            }
        }

        // 执行添加
        $model = app($this->model_name);
        $boolean = $model->insert($data);
        if (true !== $boolean) {
            throw new JsonException(110211);
        }

        return $boolean;
    }

    /**
     * 修改
     *
     * @author jilin
     * @param array $condition
     * @return $model
     */
    public function update(array $condition = [], \App\Model\AssembleChoice $assemble_choice = null)
    {
        // 验证数据
        $rule = [
            'shares' => ['numeric', 'min:0'],
            'cost_price' => ['string', 'min:0'],
            'sort' => ['numeric', 'min:1'],
        ];
        if (is_null($assemble_choice)) {
            $rule['id'] = ['required', 'numeric', 'min:1'];
        }
        $validator = app('validator')->make($condition, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }

        // 获取详情
        $model = !is_null($assemble_choice) ? $assemble_choice : $this->detail(['id'=>$condition['id']]);

        // 构造要修改的字段
        $allow_key = array_keys(array_except($rule,'id'));
        foreach ($condition as $key=>$value) {
            if (in_array($key, $allow_key)) {
                $model->$key = $value;
            }
        }
        $model->last_update_time = Helper::getNow(true);

        $response = $model->save();
        
        // 修改失败
        if (true !== $response) {
            throw new JsonException(110202);
        }

        return $model;
    }
    
    /**
     * 批量修改
     *
     * @author jilin
     * @param array $update_list
     * @return int $affected 影响行数
     */
    public function batchUpdate(array $update_list = [])
    {
        if (empty($update_list)) {
            throw new JsonException(10000);
        }

        $model = app($this->model_name);

        $table = $model->getTable();

        // 组装sql
        $update_sql = Helper::updateBatch($table, $update_list);

        // 批量更新排序
        $db = app('db');
        try {
            $affected = $db->update($db->raw($update_sql));
        } catch (\Exception $e) {
            throw new JsonException(110206);
        }

        return $affected;
    }

    /**
     * 删除
     *
     * @author jilin
     * @param array $condition
     * @return $model
     */
    public function destroy(array $condition = [], \App\Model\AssembleChoice $assemble_choice = null)
    {
        // 验证数据
        if (is_null($assemble_choice)) {
            $rule = [
                'id' => ['required', 'numeric', 'min:1'],
            ];
            $validator = app('validator')->make($condition, $rule);
            if ($validator->fails()) {
                throw new JsonException(10000, $validator->messages());
            }
        }

        // 获取详情
        $model = !is_null($assemble_choice) ? $assemble_choice : $this->detail(['id' => $condition['id']]);

        // 判断用户详情是否存在
        if (empty($assemble_choice) || !isset($assemble_choice->id)) {
            throw new JsonException(60040);
        }
        
        // 执行删除
        $response = $model->delete();

        if (true !== $response) {
            throw new JsonException(110203);
        }

        return $model;
    }

    /**
     * 批量删除
     *
     * @author jilin
     * @param array $data
     * @return int $affected 影响行数
     */
    public function batchDestroy(array $data = [])
    {
        // 验证数据
        $rule = [
            'id_arr' => ['array', 'min:1'],
            'assemble_id' => ['numeric', 'min:1'],
        ];
        $validator = app('validator')->make($data, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }
        
        $model = app($this->model_name);
        $builder = $model->select(['id']);
        
        // 包含ID查询
        if (isset($condition['id_arr'])) {
            $builder->IdIncludeQuery($condition['id_arr']);
        }
        // 组合ID查询
        if (isset($data['assemble_id'])) {
            $builder->AssembleIdQuery($data['assemble_id']);
        }

        // 执行删除
        $affected = $builder->delete();

        if (empty($affected)) {
            throw new JsonException(110205);
        }

        return $affected;
    }
    
    /**
     * 详情
     * 
     * @author jilin
     * @param array $condition 条件
     * @param array $columns 字段
     * @param array $relation 关联关系
     */
    public function detail(array $condition = [], array $columns = ['*'], array $relation = [])
    {
        $condition['first'] = 'true';
        $list = $this->getData($condition, $columns, $relation);
        return $list;
    }
    
    /**
     * 全部数据
     * @author jilin
     * @param array $condition 条件
     * @param array $columns 字段
     * @param array $relation 关联关系
     */
    public function allData(array $condition = [], array $columns = ['*'], array $relation = [])
    {
        $condition['all'] = 'true';
        $list = $this->getData($condition, $columns, $relation);
        return $list;
    }
    
    /**
     * 获取数据
     *
     * @author  jilin
     * @param $condition    array   查询条件
     *         $condition['first'] = 'true'             单个数据
     *         $condition['sort'] = ['id'=>'desc']      排序
     *         $condition['sort'] = 'rand'
     *         $condition['group'] = 'id,name'          分组
     *         $condition['limit'] = 10
     *         $condition['offset'] = 100
     *         $condition['all'] = 'true'
     *         $condition['total'] = 'true'
     * @param $columns      array   查询字段
     * @param $relation     array   关联关系
     * @return $collection
     */
    public function getData(array $condition = [], array $columns = ['*'], array $relation = []) {

        // 验证数据
        $rule = array(
            'page_size' => ['integer', 'min:1'],
            'id' => ['integer', 'min:1'],
            'id_include' => ['array', 'min:1'],
        );
        $validator = app('validator')->make($condition, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }

        $model = app($this->model_name);
        $builder = $model->select($columns);

        // ID查询
        if (isset($condition['id'])) {
            $builder->IdQuery($condition['id']);
        }
        // 包含ID查询
        if (isset($condition['id_include'])) {
            $builder->IdIncludeQuery($condition['id_include']);
        }

        // 每页个数
        $page_size = isset($condition['page_size']) && is_numeric($condition['page_size']) && $condition['page_size'] > 0
            ? abs($condition['page_size'])
            : config('site.page_size.default');

        // 排序
        if (isset($condition['sort']) && $condition['sort'] == 'rand') {

            $builder->orderBy(app('db')->raw('RAND()'));
        } else {

            // 默认排序
            if (!isset($condition['sort'])) {
                $condition['sort'] = [
                    'addtime' => 'desc',
                    'id' => 'desc',
                ];
            }

            foreach ($condition['sort'] as $column => $rule) {
                $builder->orderBy($column, $rule);
            }

        }

        // 分组
        if (isset($condition['group'])) {
            $builder->groupBy($condition['group']);
        }

        // 调试
//        echo $builder->toSql();var_dump($condition);exit;

        // 获取数据
        if (isset($condition['limit']) && is_numeric($condition['limit']) && $condition['limit'] > 0) {

            if (isset($data['offset'])) {
                $builder->skip((int)$condition['offset']);
            }
            $collection = $builder->take($condition['limit'])->get();

        } elseif (isset($condition['first']) && $condition['first'] == 'true') {

            $collection = $builder->first();

        } elseif (isset($condition['all']) && $condition['all'] == 'true') {

            $collection = $builder->get();

        } else if (isset($condition['total']) && $condition['total'] == 'true') {

            $collection = $builder->count('id');

        } else {

            $collection = $builder->paginate($page_size);
        }

        // 加载关系
        if (!empty($relation) && !empty($collection)) {
            $collection->load($relation);
        }

        return $collection;
    }

    /**
     * 字段值递增
     *
     * @author jilin
     * @param $id int ID
     * @param $column string 字段名
     * @return mixed
     * @throws JsonException
     */
    public function increment($id, $column, $amount = 1)
    {
        // 验证数据
        $rule = array(
            'id' => ['required', 'integer', 'min:1'],
            'column' => ['required', 'string', 'min:1'],
        );
        $condition = [
            'id' => $id,
            'column' => $column,
        ];
        $validator = app('validator')->make($condition, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }

        $model = $this->detail(['id'=>$id]);

        return $model->increment($column, $amount);
    }
}
```
