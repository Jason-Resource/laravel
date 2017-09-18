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
     * 修改用户预警
     *
     * @author jilin
     * @param array $data
     */
    public function updateUserWarning(array $data = [], \App\Model\UserWarning $userWarning = null)
    {
        // 验证数据
        $rule = [
            'user_id' => ['sometimes', 'numeric', 'min:1'],
            'code' => ['sometimes', 'string', 'min:1'],
            'type' => ['sometimes', 'numeric', 'min:0'],
        ];
        if (is_null($userWarning)) {
            $rule['id'] = ['required', 'numeric', 'min:1'];
        }
        $validator = validator($data, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }

        $model = !is_null($userWarning) ? $userWarning : app('UserWarningModel')->select(['*'])->find($data['id']);
        if (isset($data['user_id'])) {
            $model->user_id = $data['user_id'];
        }
        if (isset($data['code'])) {
            $model->code = $data['code'];
        }
        if (isset($data['type'])) {
            $model->type = $data['type'];
        }
        $model->last_update_time = Helper::getNow();

        $flag = $model->save();

        if (!$flag) {
            throw new JsonException(60020);
        }

        return $model;
    }

    /**
     * 删除用户预警
     *
     * @author jilin
     * @param array $data
     * @param UserWarning|null $userWarning
     */
    public function destroyUserWarning(array $data = [], UserWarning $userWarning = null)
    {
        // 验证数据
        if (!($userWarning instanceof UserWarning)) {
            $rule = [
                'id' => ['required', 'numeric', 'min:1'],
            ];
            $validator = validator($data, $rule);
            if ($validator->fails()) {
                throw new JsonException(10000, $validator->messages());
            }
        }

        // 获取用户预警详情
        if (!($userWarning instanceof UserWarning)) {
            $dataTemp = [
                'id' => $data['id']
            ];
            $userWarning = $this->getUserWarningInfo($dataTemp);
        }

        // 判断用户详情是否存在
        if (empty($userWarning) || !isset($userWarning->id)) {
            throw new JsonException(60040);
        }

        $response = $userWarning->delete();

        return $response;
    }

    /**
     * 批量删除用户预警（根据ID）
     *
     * @author jilin
     * @param array $data
     * @return int $affected 影响行数
     */
    public function destroyBatchUserWarningById(array $data = [])
    {
        // 验证数据
        $rule = [
            'id_arr' => ['required', 'array', 'min:1'],
        ];
        $validator = validator($data, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }

        // 验证所有的ID是否都存在
        $id_arr = array_unique(array_filter($data['id_arr']));
        $dataTemp = [
            'id_arr' => $id_arr,
            'all' => true,
        ];
        $column = ['id'];
        $collection = $this->getUserWarningList($dataTemp, $column);
        if (count($collection) != count($id_arr)) {
            throw new JsonException(60050);
        }

        // 执行删除
        $model = app('UserWarningModel');
        $affected = $model->select(['id'])
            ->IdsQuery($data['id_arr'])
            ->delete();

        return $affected;
    }
    
    /**
     * 检查数据是否存在
     *
     * @author jilin
     * @param array $data
     * @param array $columns
     * @return mixed false(不存在) | 模型(存在)
     * @throws JsonException
     */
    public function checkDataExist(array $data = [], array $columns = ['*'])
    {
        // 验证数据
        $rule = [
            'code' => ['sometimes', 'string', 'min:0'],
            'type' => ['sometimes', 'numeric', 'min:0'],
        ];
        $validator = validator($data, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }

        // 初始化模型
        $model = app('UserWarningModel');
        $builder = $model->select($columns);

        // 构建条件
        if (isset($data['code'])) {
            $builder->CodeQuery($data['code']);
        }

        if (isset($data['type'])) {
            $builder->TypeQuery($data['type']);
        }

        // 调试
        //echo $builder->toSql();print_r($data);exit;

        // 获取数据
        $collection = $builder->first();

        if (empty($collection)) {
            return false;
        } else {
            return $collection;
        }
    }

    /**
     * 检查数据是否存在-或查询
     *
     * @author jilin
     * @param array $data
     * @param array $columns
     * @return mixed false(不存在) | 模型(存在)
     * @throws JsonException
     */
    public function checkDataExistOrQuery(array $data = [], array $columns = ['*'])
    {
        // 验证数据
        $rule = [
            'code' => ['sometimes', 'string', 'min:0'],
            'type' => ['sometimes', 'numeric', 'min:0'],
        ];
        $validator = validator($data, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }

        // 初始化模型
        $model = app('UserWarningModel');
        $builder = $model->select($columns);

        // 构建条件
        if (isset($data['code'])) {
            $builder->CodeOrQuery($data['code']);
        }

        if (isset($data['type'])) {
            $builder->TypeOrQuery($data['type']);
        }

        // 调试
        //echo $builder->toSql();print_r($data);exit;

        // 获取数据
        $collection = $builder->first();

        if (empty($collection)) {
            return false;
        } else {
            return $collection;
        }
    }
    
    /**
     * 获取预警详情
     *
     * @author jilin
     * @param array $data
     * @param array $columns
     * @param array $relation
     */
    public function getUserWarningInfo(array $data = [], array $columns = ['*'], array $relation = [])
    {
        // 验证数据
        if (empty($data)) {
            throw new JsonException(10000);
        }

        $rule = array(
            'id' => ['sometimes', 'integer', 'min:1'],
        );
        $validator = validator($data, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }

        // 初始化模型
        $model = app('UserWarningModel');
        $builder = $model->select($columns);

        // 构建条件
        if (isset($data['id'])) {
            $builder->IdQuery($data['id']);
        }

        // 获取数据
        $collection = $builder->first();

        // 加载关系
        if (!empty($relation) && !empty($collection)) {
            $collection->load($relation);
        }

        return $collection;
    }

    /**
     * 获取预警列表
     *
     * @author  jilin
     * @param $data    array   查询条件
     * @param $select_columns   array   查询字段
     * @param $relatives    array   关联关系
     */
    public function getUserWarningList(array $data = [], array $columns = ['*'], array $relation = []) {

        // 验证数据
        $rule = array(
            'limit' => ['integer', 'min:1'],
            'offset' => ['integer', 'min:0'],
            'page_size' => ['integer', 'min:1'],
            'page' => ['integer', 'min:1'],
        );
        $validator = validator($data, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }

        $model = app('UserWarningModel');
        $builder = $model->select($columns);

        // 按照ID查询
        if (!empty($data['id'])) {
            $builder->IdQuery($data['id']);
        }

        // 每页个数
        $page_size = isset($data['page_size']) && is_numeric($data['page_size']) && $data['page_size'] > 0 ? abs($data['page_size']) : 15;

        // 当前页码
        $page = isset($data['page']) && is_numeric($data['page']) && $data['page'] > 0 ? abs($data['page']) : 1;

        // 排序
        if (isset($data['sort']) && isset($data['sort_column']) && is_array($data['sort']) && is_array($data['sort_column']) && count($data['sort_column']) == count($data['sort'])) {

            foreach ($data['sort'] as $sortKey=>$sortValue) {
                if (isset($data['sort_column'][$sortKey]) && !empty($data['sort_column'][$sortKey]) && !empty($sortValue)) {

                }
                $builder->orderBy($data['sort_column'][$sortKey], $sortValue);
            }
        } elseif (isset($data['sort']) && $data['sort'] == 'rand') {

            $builder->orderBy(DB::raw('RAND()'));
        } else {

            $sort_column = isset($data['sort_column']) && is_string($data['sort_column']) ? $data['sort_column'] : 'addtime';
            $sort = isset($data['sort']) && is_string($data['sort']) ? $data['sort'] : 'desc';
            $builder->orderBy($sort_column, $sort);
            if ($sort_column == 'addtime') {
                $builder->orderBy('id', $sort);
            }
        }

        /**
        * 分组
        * $data['group'] = 'code';
        * $data['group'] = ['code', 'id'];
        */
        if (!empty($data['group'])) {
            $builder->groupBy($data['group']);
        }

        // 调试
        //echo $builder->toSql();print_r($data);exit;

        // 获取数据
        if (isset($data['limit'])) {

            if (isset($data['offset'])) {
                $builder->skip((int)$data['offset']);
            }

            $collection = $builder->take((int)$data['limit'])->get();

        } elseif (isset($data['all']) && $data['all'] == 'true') {

            $collection = $builder->get();

        } else if (isset($data['total']) && $data['total'] == 'true') {

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
}
```
