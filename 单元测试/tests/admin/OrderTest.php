<?php
namespace Admin;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderTest extends \TestCase
{
    use WithoutMiddleware;

    /**
     * 获取用户订单列表
     * @test
     * Author weixinhua
     */
    public function getOrderList()
    {
        $faker = app('Faker\Generator');

        $user = factory('App\Model\AdminUsers')->create();
        $this->actingAs($user, 'admin');

        $url = action('Admin\OrderController@getOrderList');

        $name = $faker->name();


        $paginator = new LengthAwarePaginator([
            [
                "id"             => 10000,
                "order_sn"       => "13210693011458008446",
                "pay_trade_no"   => "",
                "pay_trade_user" => "",
                "user_id"        => 1000,
                "order_status"   => 1,
                "pay_status"     => 0,
                "tel"            => "",
                "mobile"         => "",
                "email"          => "",
                "postscript"     => "",
                "pay_code"       => "",
                "goods_amount"   => "0.01",
                "order_amount"   => "0.01",
                "add_time"       => "20160315102054",
                "cancel_time"    => 0,
                "pay_time"       => 0,
                "deleted_at"     => null,
                "created_at"     => "2016-03-15 10:20:54",
                "updated_at"     => "2017-01-06 15:10:12",
                "platform"       => "CPZX",
                'order_serve'    => [
                    "id"             => 10000,
                    "num"            => 1,
                    "order_id"       => 10000,
                    "serve_id"       => 25,
                    "serve_cycle_id" => 2,
                    "price"          => "0.01",
                    "name"           => $name,
                    "deleted_at"     => null,
                    "created_at"     => "2016-03-15 10:20:54",
                    "updated_at"     => "2016-11-19 17:56:57"
                ]
            ]
        ], 1, 1, 1);

        $this->successMock([$paginator->toArray(), []]);

        $this->visit($url)->see($name);
    }

    /**
     * 订单详情
     * @test
     * Author weixinhua
     */
    public function getOrderDetails()
    {
        $user = factory('App\Model\AdminUsers')->create();
        $this->actingAs($user, 'admin');

        $faker = app('Faker\Generator');

        $url = action('Admin\OrderController@getOrderDetails');

        $condition = [
            'order_id' => $faker->numberBetween(1, 1000)
        ];
        $url = $url . '?' . http_build_query($condition);

        $order_sn = $faker->numberBetween(1, 1000);

        $this->successMock([[
            'order_sn'       => $order_sn,
            'pay_trade_no'   => $faker->numberBetween(1, 1000),
            'pay_trade_user' => $faker->name(),
            'order_status'   => $faker->randomElement([0, 1]),
            'pay_status'     => $faker->randomElement([0, 1]),
            'tel'            => $faker->numberBetween(1, 1000),
            'mobile'         => $faker->numberBetween(1, 1000),
            'email'          => $faker->email(),
            'postscript'     => $faker->numberBetween(1, 1000),
            'pay_code'       => $faker->numberBetween(1, 1000),
            'goods_amount'   => $faker->numberBetween(1, 1000),
            'order_amount'   => $faker->numberBetween(1, 1000),
            'created_at'     => $faker->date('Y-m-d'),
            'pay_time'       => $faker->date('Y-m-d'),
        ]]);

        $this->visit($url)->see($order_sn);
    }
}
