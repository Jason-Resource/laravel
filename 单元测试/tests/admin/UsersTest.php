<?php
namespace Admin;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;

class UsersTest extends \TestCase
{
    use  WithoutMiddleware;

    /**
     * 获取用户列表
     * @test
     * Author weixinhua
     */
    public function index()
    {
        $faker = app('Faker\Generator');

        $user = factory('App\Model\AdminUsers')->create();
        $this->actingAs($user, 'admin');

        $url = action('Admin\UsersController@getIndex');

        $nickname = $faker->name();


        $paginator = new LengthAwarePaginator([
            [
                "id"                  => $faker->numberBetween(1, 1000),
                "platform"            => "CPZX",
                "classify"            => $faker->randomElement([0, 1, 2, 3, 4]),
                "subscribe"           => $faker->randomElement([0, 1]),
                "sex"                 => $faker->randomElement([1, 2]),
                "subscribe_time"      => $faker->date('YmdHis'),
                "unsubscribed_time"   => 0,
                "mobile_phone"        => $faker->phoneNumber(),
                "openid"              => $faker->name(),
                "unionid"             => "",
                "source"              => "common",
                "nickname"            => $nickname,
                "real_name"           => $faker->name(),
                "headimgurl"          => $faker->imageUrl(),
                "deleted_at"          => null,
                "created_at"          => $faker->date('Y-m-d H:i:s'),
                "updated_at"          => $faker->date('Y-m-d H:i:s'),
                "custom_service_id"   => $faker->numberBetween(1, 1000),
                "marketing_personnel" => $faker->numberBetween(1, 1000),
                "marketing_manager"   => $faker->numberBetween(1, 1000),
                "user_weixin_info"    => null,
                'area' => $faker->randomElement(['GZ', 'CS']),
            ]
        ], 1, 1, 1);

        $this->successMock([$paginator->toArray(), [], []]);

        $this->visit($url)->see($nickname);
    }

    /**
     * 他的订阅
     * @test
     * Author weixinhua
     */
    public function getHisSubscribe()
    {
        $faker = app('Faker\Generator');

        $user = factory('App\Model\AdminUsers')->create();
        $this->actingAs($user, 'admin');

        $url = action('Admin\UsersController@getHisSubscribe');
        $condition = [
            'user_id' => $faker->numberBetween(1, 1000)
        ];
        $url = $url . '?' . http_build_query($condition);

        $this->successMock([]);
        $this->call('GET', $url);
        $this->assertResponseOk();
    }

    /**
     * 添加订阅
     * @test
     * Author weixinhua
     */
    public function postStoreSubscribe()
    {
        $faker = app('Faker\Generator');

        $store_data = [
            'user_id'             => $faker->numberBetween(1, 1000),
            'serve_id'            => $faker->numberBetween(1, 1000),
            'real_name'           => $faker->name(),
            'classify'            => $faker->numberBetween(1, 4),
            'marketing_personnel' => $faker->numberBetween(1, 1000),
            'marketing_manager'   => $faker->numberBetween(1, 1000),
            'custom_service_id'   => $faker->numberBetween(1, 1000),
            'end_or_count'        => $faker->date('Y-m-d'),
        ];

        $url = action('Admin\UsersController@postStoreSubscribe');
        // 设定添加时返回自增ID
        $increment = $faker->numberBetween(1, 1000);
        $this->successMock([['id' => $increment], []]);

        $response = $this->call('POST', $url, $store_data);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue($increment == $response_data['data']['id']);
    }

    /**
     * 更新用户订阅
     * @test
     * Author weixinhua
     */
    public function postUpdateSubscribe()
    {
        $faker = app('Faker\Generator');

        $update_data = [
            'subscribe_id'        => $faker->numberBetween(1, 1000),
            'user_id'             => $faker->numberBetween(1, 1000),
            'real_name'           => $faker->name(),
            'classify'            => $faker->numberBetween(1, 4),
            'marketing_personnel' => $faker->numberBetween(1, 1000),
            'marketing_manager'   => $faker->numberBetween(1, 1000),
            'custom_service_id'   => $faker->numberBetween(1, 1000),
            'end_or_count'        => $faker->date('Y-m-d'),
        ];

        $url = action('Admin\UsersController@postUpdateSubscribe');
        $this->successMock([1, []]);

        $response = $this->call('POST', $url, $update_data);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue(1 == $response_data['data']);
    }

    /**
     * 更新用户数据
     * @test
     * Author weixinhua
     */
    public function postUpdateUsers()
    {
        $faker = app('Faker\Generator');

        $update_data = [
            'user_id'             => $faker->numberBetween(1, 1000),
            'real_name'           => $faker->name(),
            'mobile_phone'        => $faker->phoneNumber(),
            'classify'            => $faker->numberBetween(1, 4),
            'marketing_personnel' => $faker->numberBetween(1, 1000),
            'marketing_manager'   => $faker->numberBetween(1, 1000),
            'custom_service_id'   => $faker->numberBetween(1, 1000),
        ];
        $url = action('Admin\UsersController@postUpdateUsers');
        $this->successMock([1]);

        $response = $this->call('POST', $url, $update_data);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue(1 == $response_data['data']);
    }

}
