<?php
namespace Admin;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;

class SubscriberTest extends \TestCase
{
    use  WithoutMiddleware;

    /**
     * 获取用户订阅列表
     * @test
     * Author weixinhua
     */
    public function index()
    {
        $faker = app('Faker\Generator');

        $user = factory('App\Model\AdminUsers')->create();
        $this->actingAs($user, 'admin');

        $url = action('Admin\SubscribeController@getIndex');

        $id = $faker->numberBetween(1, 1000);

        $paginator = new LengthAwarePaginator([
            [
                "id"         => $id,
                "uid"        => $faker->numberBetween(1, 1000),
                "sid"        => $faker->numberBetween(1, 1000),
                "num"        => $faker->numberBetween(1, 1000),
                "start_time" => $faker->date('YmdHis'),
                "end_time"   => $faker->numberBetween(1, 1000),
                "cycle"      => 1,
                "deleted_at" => null,
                'rule_type' => 'time',
                "created_at" => $faker->date('Y-m-d H:i:s'),
                "updated_at" => $faker->date('Y-m-d H:i:s'),
                "platform"   => "CPZX"
            ]
        ], 1, 1, 1);

        $this->successMock([$paginator->toArray(), [], []]);
        $this->visit($url)->see($id);
    }
}
