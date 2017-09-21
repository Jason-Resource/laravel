```php
<?php

namespace App\Http\Business;

use App\Exceptions\JsonException;
use App\Http\Business\Dao\TestDao;
use App\Http\Common\Helper;
use App\Model\Test as TestModel;

class TestBusiness
{
    private $test_dao = null;

    /**
     * 构造
     */
    public function __construct(TestDao $test_dao)
    {
        $this->test_dao = $test_dao;
    }

    /**
     * 添加
     *
     * @param array $data
     * @return $model
     */
    public function store(array $data = [])
    {
        // 验证数据
        $rule = [
            'first_name' => ['required', 'string', 'min:1'],
            'age' => ['required', 'numeric', 'min:10'],
        ];
        Helper::validateParam($rule, $data);

        $model = $this->test_dao->store($data);

        $model->setVisible(['first_name', 'age']);

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
        // 验证数据
        if (empty($data)) {
            throw new JsonException(10000);
        }

        $db = app('db');
        $db->beginTransaction();
        try {

            $boolean = $this->test_dao->batchStore($data);

        } catch (JsonException $e) {

            $db->rollBack();
            throw new JsonException($e->getCode(), $e->getErrorMsg());
        }
        $db->commit();

        return $boolean;
    }

    /**
     * 修改
     *
     * @param array $data
     * @return model
     */
    public function update(array $data = [])
    {
        // 验证数据
        $rule = [
            'id' => ['required', 'numeric', 'min:1'],
            'first_name' => ['string', 'min:1'],
            'age' => ['numeric', 'min:10'],
        ];
        Helper::validateParam($rule, $data);

        // 详情是否存在
        $detail = $this->test_dao->detail($data['id']);
        if (empty($detail) || !isset($detail->id)) {
            throw new JsonException(88888);
        }

        $model = $this->test_dao->update($data, $detail);

        $model->setVisible(['first_name', 'age']);

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
        // 验证数据
        if (empty($data)) {
            throw new JsonException(10000);
        }

        $affected = $this->test_dao->batchUpdate($data);

        return $affected;
    }

    /**
     * 删除
     *
     * @param array $data
     * @return model
     */
    public function destroy($id)
    {
        // 验证数据
        Helper::validateId($id);

        // 详情是否存在
        $detail = $this->test_dao->detail($id);
        if (empty($detail) || !isset($detail->id)) {
            throw new JsonException(88888);
        }

        // 执行删除
        $model = $this->test_dao->destroy($id, $detail);

        $model->setVisible(['id']);

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
        $rule = [
            'id_include' => ['required', 'array', 'min:1'],
        ];
        Helper::validateParam($rule, $data);

        $affected = $this->test_dao->batchDestroy($data);

        return $affected;
    }

    /**
     * 详情是否存在
     *
     * @param $id
     * @return bool/model
     */
    public function detailExist($id)
    {
        // 验证数据
        Helper::validateId($id);

        $model = $this->test_dao->detail($id);
        if (empty($model) || !isset($model->id)) {
            throw new JsonException(88888);
        }

        $model->setVisible(['first_name']);

        return $model;
    }

    /**
     * 递增年龄
     *
     * @param $id
     * @return int
     */
    public function incrementAge($id)
    {
        // 递增字段
        $column = 'age';
        // 递增数
        $amount = 1;

        // 验证数据
        Helper::validateId($id);

        // 详情是否存在
        $this->detailExist($id);

        $affected = $this->test_dao->recharge($id, $column);

        return $affected;
    }

    /**
     * 获取详情
     *
     * @param $id
     * @return model
     */
    public function detail($id)
    {
        // 验证数据
        Helper::validateId($id);

        $model = $this->test_dao->detail($id);
        if (empty($model) || !isset($model->id)) {
            throw new JsonException(88888);
        }

        $model = $this->handleData($model);

        return $model;
    }

    /**
     * 获取数据
     *
     * @param array $data
     * @return collection
     */
    public function getData(array $data = [], array $columns = ['*'], array $relation = [])
    {
        $collection = $this->test_dao->getData($data, $columns, $relation);

        // 整理数据
        $collection->map(function ($value) {
            $this->handleData($value);
        });

        return $collection;
    }

    /**
     * 处理数据
     *
     * @param $model
     * @return model
     */
    public function handleData(TestModel $model)
    {
        if (empty($model)) {
            return $model;
        }

        $model->time = time();
        $model->addVisible(['time', 'relationTestR']);

        return $model;
    }
}
```
