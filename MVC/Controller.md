```php
<?php

namespace App\Http\Controllers;

use App\Exceptions\JsonException;
use App\Http\Business\TestBusiness;
use Illuminate\Http\Request;

class TestController extends Controller
{

    /**
     * 添加
     *
     * /store?first_name=meinvbingyue&age=20
     */
    public function store(Request $request, TestBusiness $test_business)
    {
        $data = $request->all();

        $response = $test_business->store($data);

        return $this->jsonFormat($response);
    }

    /**
     * 批量添加
     *
     * /batch-store?data=[{"first_name":"jason","age":"11"},{"first_name":"meinvbingyue","age":"12"}]
     */
    public function batchStore(Request $request, TestBusiness $test_business)
    {
        $data = $request->get('data');
        $data = json_decode($data, true);

        $response = $test_business->batchStore($data);

        return $this->jsonFormat($response);
    }

    /**
     * 修改
     *
     * /update?id=588&age=30&first_name=meinvbingyue
     */
    public function update(Request $request, TestBusiness $test_business)
    {
        $data = $request->all();

        $response = $test_business->update($data);

        return $this->jsonFormat($response);
    }

    /**
     * 批量修改
     *
     * /batch-update?data=[{"id":"588","age":"66"},{"id":"589","age":"77"}]
     */
    public function batchUpdate(Request $request, TestBusiness $test_business)
    {
        $data = $request->get('data');
        $data = json_decode($data, true);

        $response = $test_business->batchUpdate($data);

        return $this->jsonFormat($response);
    }

    /**
     * 删除
     *
     * /destroy?id=588
     */
    public function destroy(Request $request, TestBusiness $test_business)
    {
        $id = $request->get('id');

        $response = $test_business->destroy($id);

        return $this->jsonFormat($response);
    }

    /**
     * 批量删除
     *
     * /batch-destroy?ids=588,589
     */
    public function batchDestroy(Request $request, TestBusiness $test_business)
    {
        $ids = $request->get('ids');
        $data['id_include'] = explode(',', $ids);

        $response = $test_business->batchDestroy($data);

        return $this->jsonFormat($response);
    }

    /**
     * 递增年龄
     *
     * /increment-age?id=588
     */
    public function incrementAge(Request $request, TestBusiness $test_business)
    {
        $id = $request->get('id');

        $response = $test_business->incrementAge($id);

        return $this->jsonFormat($response);
    }

    /**
     * 详情是否存在
     *
     * /detail-exist?id=588
     * @return boolean
     */
    public function detailExist(Request $request, TestBusiness $test_business)
    {
        $id = $request->get('id');

        try {
            $test_business->detailExist($id);
            $response = true;
        } catch (JsonException $e) {
            $response = false;
        }

        return $this->jsonFormat($response);
    }

    /**
     * 获取详情
     *
     * /detail?id=588
     */
    public function detail(Request $request, TestBusiness $test_business)
    {
        $id = $request->get('id');

        $response = $test_business->detail($id);

        return $this->jsonFormat($response);
    }

    /**
     * 获取列表
     *
     *  /get-list
     */
    public function getList(Request $request, TestBusiness $test_business)
    {
        $data = $request->all();

        $response = $test_business->getData($data);

        return $this->jsonFormat($response);
    }

    /**
     * 获取列表
     *
     *  /get-list-by-rela
     *  /get-list-by-rela?edu_include=本科
     */
    public function getListByRela(Request $request, TestBusiness $test_business)
    {
        $data = $request->all();

        $rela['relationTestR'] = function ($query) {
        };
//        $data['with'] = 'true';
        $response = $test_business->getData($data, ['*'], $rela);

        return $this->jsonFormat($response);
    }
}
```
