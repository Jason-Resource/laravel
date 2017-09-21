<?php
namespace Admin;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;

class IncrementTest extends \TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    /**
     * 战绩回顾列表
     * @test
     * Author weixinhua
     */
    public function stockList()
    {
        // 认证
        $user = factory('App\Model\AdminUsers')->create();
        $this->actingAs($user, 'admin');

        $faker = app('Faker\Generator');

        $url = action('Admin\IncrementController@getStockList');
        $stock_code = $faker->numberBetween(1, 1000);

        $paginator = new LengthAwarePaginator([
            [
                "id"         => $faker->numberBetween(1, 1000),
                "platform"   => "CPZX",
                "buytime"    => $faker->date('YmdHis'),
                "stock_code" => $stock_code,
                "stock_name" => $faker->name(),
                "buy_price"  => $faker->name(),
                "increase"   => $faker->numberBetween(1, 10),
                "addtime"    => $faker->date('YmdHis'),
                "mstar"      => $faker->randomElement([0, 1]),
                "service_id" => $faker->numberBetween(1, 1000),
                "ispush"     => -1,
                "deleted_at" => null,
                "created_at" => $faker->date('Y-m-d H:i:s'),
                "updated_at" => $faker->date('Y-m-d H:i:s')
            ]
        ], 1, 1, 1);

        $this->successMock([$paginator->toArray()]);

        $this->visit($url)->see($stock_code);
    }

    /**
     * 添加战绩回顾
     * @test
     * Author weixinhua
     */
    public function storeStock()
    {
        $faker = app('Faker\Generator');

        $store_data = [
            'platform'   => 'CPZX',
            'service_id' => $faker->numberBetween(1, 1000),
            'stock_name' => $faker->name(),
            'stock_code' => $faker->numberBetween(100000, 999999),
            'buy_price'  => $faker->randomFloat(),
            'increase'   => $faker->numberBetween(1, 10),
            'buytime'    => $faker->date('YmdHis'),
            'mstar'      => $faker->randomElement([0, 1]),
        ];

        $url = action('Admin\IncrementController@postStoreStock');
        // 设定添加时返回自增ID
        $increment = $faker->numberBetween(1, 1000);
        $this->successMock([['id' => $increment]]);

        $response = $this->call('POST', $url, $store_data);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue($increment == $response_data['data']['id']);
    }

    /**
     * 更新战绩回顾
     * @test
     * Author weixinhua
     */
    public function updateStock()
    {
        $faker = app('Faker\Generator');

        $update_data = [
            'stock_id'   => $faker->numberBetween(1, 1000),
            'platform'   => 'CPZX',
            'service_id' => $faker->numberBetween(1, 1000),
            'stock_name' => $faker->name(),
            'stock_code' => $faker->numberBetween(100000, 999999),
            'buy_price'  => $faker->randomFloat(),
            'increase'   => $faker->numberBetween(1, 10),
            'buytime'    => $faker->date('YmdHis'),
            'mstar'      => $faker->randomElement([0, 1]),
        ];

        $url = action('Admin\IncrementController@postUpdateStock');
        $this->successMock([1]);

        $response = $this->call('POST', $url, $update_data);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue(1 == $response_data['data']);
    }

    /**
     * 删除战绩回顾
     * @test
     * Author weixinhua
     */
    public function destroyStock()
    {
        $faker = app('Faker\Generator');

        $url = action('Admin\IncrementController@getDestroyStock');
        $condition = [
            'stock_id' => $faker->numberBetween(1, 1000),
        ];
        $url = $url . '?' . http_build_query($condition);
        $this->successMock([1]);

        $response = $this->call('GET', $url);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue(1 == $response_data['data']);
    }

    // ===== 分析师团队 ===

    /**
     * 分析师团队列表
     * @test
     * Author weixinhua
     */
    public function getAnalystTeamList()
    {
        $faker = app('Faker\Generator');

        // 认证
        $user = factory('App\Model\AdminUsers')->create();
        $this->actingAs($user, 'admin');

        $url = action('Admin\IncrementController@getAnalystTeamList');

        $name = $faker->name();

        $paginator = new LengthAwarePaginator([
            [
                "id"         => $faker->numberBetween(1, 1000),
                "platform"   => "CPZX",
                "name"       => $name,
                "sortid"     => $faker->numberBetween(1, 100),
                "memo"       => $faker->name(),
                "operate"    => $faker->name(),
                "invest"     => $faker->name(),
                "imgurl"     => $faker->imageUrl(),
                "linkurl"    => $faker->url(),
                "deleted_at" => null,
                "created_at" => $faker->date('Y-m-d H:i:s'),
                "updated_at" => $faker->date('Y-m-d H:i:s')
            ]
        ], 1, 1, 1);

        $this->successMock([$paginator->toArray()]);

        $this->visit($url)->see($name);
    }


    /**
     * 添加分析师团队
     * @test
     * Author weixinhua
     */
    public function postStoreAnalystTeam()
    {
        $faker = app('Faker\Generator');

        $store_data = [
            'name'    => $faker->name(),
            'operate' => $faker->name(),
            'invest'  => $faker->name(),
            'linkurl' => $faker->url(),
            'sortid'  => 0,
        ];

        $url = action('Admin\IncrementController@postStoreAnalystTeam');
        // 设定添加时返回自增ID
        $increment = $faker->numberBetween(1, 1000);
        $this->successMock([['id' => $increment]]);

        $response = $this->call('POST', $url, $store_data);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue($increment == $response_data['data']['id']);
    }

    /**
     * 更新分析师团队
     * @test
     * Author weixinhua
     */
    public function updateAnalystTeam()
    {
        $faker = app('Faker\Generator');

        $update_data = [
            'team_id' => $faker->numberBetween(1, 1000),
            'name'    => $faker->name(),
            'operate' => $faker->name(),
            'invest'  => $faker->name(),
            'linkurl' => $faker->url(),
            'sortid'  => 0,
        ];

        $url = action('Admin\IncrementController@postUpdateAnalystTeam');
        $this->successMock([1]);

        $response = $this->call('POST', $url, $update_data);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue(1 == $response_data['data']);
    }

    /**
     * 删除分析师团队
     * @test
     * Author weixinhua
     */
    public function destroyAnalystTeam()
    {
        $faker = app('Faker\Generator');

        $url = action('Admin\IncrementController@getDestroyAnalystTeam');
        $condition = [
            'team_id' => $faker->numberBetween(1, 1000)
        ];
        $url = $url . '?' . http_build_query($condition);

        $this->successMock([1]);

        $response = $this->call('GET', $url);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue(1 == $response_data['data']);
    }

    // ====== 分析师 ======

    /**
     * 分析师列表
     * @test
     * Author weixinhua
     */
    public function getAnalystList()
    {
        $faker = app('Faker\Generator');
        // 认证
        $user = factory('App\Model\AdminUsers')->create();
        $this->actingAs($user, 'admin');

        $url = action('Admin\IncrementController@getAnalystList');

        $name = $faker->name();

        $paginator = new LengthAwarePaginator([
            [
                "id"         => $faker->numberBetween(1, 1000),
                "platform"   => "CPZX",
                "name"       => $name,
                "rank"       => $faker->name(),
                "field1"     => $faker->name(),
                "field2"     => $faker->name(),
                "imgurl_pc"  => $faker->imageUrl(),
                "imgurl"     => $faker->imageUrl(),
                "cnt"        => $faker->name(),
                "summary"    => $faker->name(),
                "addtime"    => $faker->date('YmdHis'),
                "imgmobile"  => $faker->imageUrl(),
                "memo"       => $faker->name(),
                "ishome"     => 0,
                "sortid"     => $faker->numberBetween(1, 1000),
                "zsno"       => $faker->name(),
                "zsurl"      => $faker->url(),
                "service_id" => $faker->numberBetween(1, 1000),
                "classid"    => $faker->numberBetween(1, 1000),
                "nickname"   => $faker->name(),
                "nengli"     => implode(',', [96, 98, 96, 96]),
                "deleted_at" => null,
                "created_at" => $faker->date('Y-m-d H:i:s'),
                "updated_at" => $faker->date('Y-m-d H:i:s')
            ]
        ], 1, 1, 1);

        $this->successMock([$paginator->toArray()]);

        $this->visit($url)->see($name);
    }

    /**
     * 添加分析师
     * @test
     * Author weixinhua
     */
    public function postStoreAnalyst()
    {
        $faker = app('Faker\Generator');

        $store_data = [
            'service_id' => $faker->numberBetween(1, 1000),
            'nengli'     => [
                $faker->numberBetween(90, 99),
                $faker->numberBetween(90, 99),
                $faker->numberBetween(90, 99),
                $faker->numberBetween(90, 99),
                $faker->numberBetween(90, 99),
            ],
            'classid'    => $faker->numberBetween(90, 1000),
            'name'       => $faker->name(),
            'nickname'   => $faker->name(),
            'rank'       => $faker->name(),
            'zsno'       => $faker->name(),
            'zsurl'      => $faker->url(),
            'field1'     => $faker->name(),
            'field2'     => $faker->name(),
            'cnt'        => $faker->name(),
            'sortid'     => $faker->numberBetween(1, 99),
        ];

        $url = action('Admin\IncrementController@postStoreAnalyst');
        // 设定添加时返回自增ID
        $increment = $faker->numberBetween(1, 1000);
        $this->successMock([['id' => $increment]]);

        $response = $this->call('POST', $url, $store_data);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue($increment == $response_data['data']['id']);
    }

    /**
     * 更新分析师
     * @test
     * Author weixinhua
     */
    public function postUpdateAnalyst()
    {
        $faker = app('Faker\Generator');

        $update_data = [
            'analyst_id' => $faker->numberBetween(1, 1000),
            'service_id' => $faker->numberBetween(1, 1000),
            'nengli'     => [
                $faker->numberBetween(90, 99),
                $faker->numberBetween(90, 99),
                $faker->numberBetween(90, 99),
                $faker->numberBetween(90, 99),
                $faker->numberBetween(90, 99),
            ],
            'classid'    => $faker->numberBetween(90, 1000),
            'name'       => $faker->name(),
            'nickname'   => $faker->name(),
            'rank'       => $faker->name(),
            'zsno'       => $faker->name(),
            'zsurl'      => $faker->url(),
            'field1'     => $faker->name(),
            'field2'     => $faker->name(),
            'cnt'        => $faker->name(),
            'sortid'     => $faker->numberBetween(1, 99),
        ];

        $url = action('Admin\IncrementController@postUpdateAnalyst');
        $this->successMock([1]);

        $response = $this->call('POST', $url, $update_data);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue(1 == $response_data['data']);
    }

    /**
     * 删除分析师
     * @test
     * Author weixinhua
     */
    public function getDestroyAnalyst()
    {
        $faker = app('Faker\Generator');

        $url = action('Admin\IncrementController@getDestroyAnalyst');
        $condition = [
            'analyst_id' => $faker->numberBetween(1, 1000)
        ];
        $url = $url . '?' . http_build_query($condition);
        $this->successMock([1]);

        $response = $this->call('GET', $url);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue(1 == $response_data['data']);
    }

    // ====== 找投顾 ======

    /**
     * 找投顾列表
     * @test
     * Author weixinhua
     */
    public function getIndexConsultation()
    {
        $faker = app('Faker\Generator');
        // 认证
        $user = factory('App\Model\AdminUsers')->create();
        $this->actingAs($user, 'admin');

        $url = action('Admin\IncrementController@getIndexConsultation');

        $contents = 'benzun';

        $paginator = new LengthAwarePaginator([
            [
                "id"         => $faker->numberBetween(1, 1000),
                "platform"   => "CPZX",
                "user_id"    => $faker->numberBetween(1, 1000),
                "contents"   => $contents,
                "type"       => $faker->randomElement([1, 2]),
                "status"     => $faker->randomElement([0, 1]),
                "pid"        => $faker->numberBetween(1, 1000),
                "deleted_at" => null,
                "created_at" => "2017-01-06 11:21:46",
                "updated_at" => "2017-01-06 11:21:46"
            ]
        ], 1, 1, 1);

        $this->successMock([$paginator->toArray(), [], [], []]);

        $this->visit($url)->see($contents);
    }

    /**
     * 回复找投顾
     * @test
     * Author weixinhua
     */
    public function postStoreConsultation()
    {
        $faker = app('Faker\Generator');

        $consultation_id = $faker->numberBetween(1, 1000);
        $store_data = [
            'consultation_id' => $consultation_id,
            'user_id'         => $faker->numberBetween(1, 1000),
            'contents'        => $faker->name(),
        ];

        $url = action('Admin\IncrementController@postStoreConsultation');
        // 设定添加时返回自增ID
        $increment = $faker->numberBetween(1, 1000);
        $this->successMock([['id' => $increment], [1]]);

        $response = $this->call('POST', $url, $store_data);
        $this->assertResponseOk();

        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue($increment == $response_data['data']['id']);
    }

}
