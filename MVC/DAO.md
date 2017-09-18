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
        // 验证数据
        $rule = [
            'user_id' => ['required', 'numeric', 'min:1'],
            'code' => ['required', 'string', 'min:1'],
            'type' => ['required', 'numeric', 'min:0'],
            'buy_one' => ['numeric', 'min:0'],
            'sell_one' => ['numeric', 'min:0'],
        ];
        $validator = app('validator')->make($condition, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }

        $model = app($this->model_name);
        $model->user_id = $data['user_id'];
        $model->code = $data['code'];
        $model->type = $data['type'];
        if (isset($data['sell_one'])) {
            $model->sell_one = $data['sell_one'];
        }
        if (isset($data['buy_one'])) {
            $model->buy_one = $data['buy_one'];
        }
        $model->addtime = Helper::getNow();
        $model->last_update_time = Helper::getNow();

        $response = $model->save();
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
            'shares' => ['sometimes', 'numeric', 'min:0'],
            'cost_price' => ['sometimes', 'string', 'min:0'],
            'sort' => ['sometimes', 'numeric', 'min:1'],
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
        if (isset($condition['shares'])) {
            $model->shares = $condition['shares'];
        }
        if (isset($condition['cost_price'])) {
            $model->cost_price = $condition['cost_price'];
        }
        if (isset($condition['sort'])) {
            $model->sort = $condition['sort'];
        }
        if (isset($condition['update_cost_time'])) {
            $model->update_cost_time = $condition['update_cost_time'];
        }
        $model->last_update_time = Helper::getNow(true);

        $response = $model->save();

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
    public function destroyBatch(array $data = [])
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
    
    

    
}
```
