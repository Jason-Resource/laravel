```php

namespace App\Http\Business;

use App\Exceptions\JsonException;
use App\Http\Business\Dao\UserWarningDao;
use Illuminate\Support\Facades\DB;

class UserWarningBusiness extends BusinessBase
{
    private $userWarningDao = null;

    /**
     * 构造
     */
    public function __construct(UserWarningDao $userWarningDao)
    {
        $this->userWarningDao = $userWarningDao;
    }

    /**
     * 添加
     *
     * @author jilin
     * @param array $data
     */
    public function storeUserWarning(array $data = [])
    {
        // 验证数据
        $rule = [
            'user_id' => ['required', 'numeric', 'min:1'],
            'code' => ['required', 'string', 'min:1'],
            'type' => ['required', 'numeric', 'min:0'],
        ];
        $validator = app('validator')->make($data, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }

        DB::beginTransaction();
        try {

            $response = $this->userWarningDao->store($data);

        } catch (JsonException $e) {

            DB::rollBack();
            throw new JsonException($e->getCode(), $e->getErrorMsg());
        }
        DB::commit();

        return $response;
    }

    /**
     * 批量新增股票-文章关系
     *
     * @author jilin
     * @param array $stock_list
     */
    public function batchStore(array $data = [])
    {
        if (empty($data)) {
            throw new JsonException(110303);
        }

        $insert_data = [];
        foreach ($data as $item) {
            $insert_data[] = [
                'stock_code' => $item['code'],
                'stock_name' => $item['name'],
                'information_id' => $information_id,
                'addtime' => Helper::getNow(true),
                'last_update_time' => Helper::getNow(true),
                'created_at' => Helper::getFormatTime(),
                'updated_at' => Helper::getFormatTime(),
            ];
        }

        // 调用
    }
}

```
