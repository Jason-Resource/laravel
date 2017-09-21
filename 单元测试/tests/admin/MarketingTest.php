<?php
namespace Admin;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MarketingTest extends \TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;


    /**
     * 添加营销人员
     * @test
     * Author weixinhua
     * @return string
     */
    public function store()
    {
        $faker = app('Faker\Generator');

        $store_data = [
            'type'   => $faker->randomElement([0, 1]),
            'name'   => $faker->name(),
            'mobile' => $faker->phoneNumber()
        ];
        $url = action('Admin\MarketingController@postStore');
        $response = $this->call('POST', $url, $store_data);
        $this->assertResponseOk();
    }

    /**
     * 更新营销人员
     * @test
     * Author weixinhua
     */
    public function update()
    {
        $marketing = factory('App\Model\Marketing')->create();

        $faker = app('Faker\Generator');
        $update_data = [
            'marketing_id' => $marketing->id,
            'type'         => $faker->randomElement([1, 2]),
            'name'         => $faker->name(),
            'mobile'       => $faker->phoneNumber()
        ];

        $url = action('Admin\MarketingController@postUpdate');
        $response = $this->call('POST', $url, $update_data);
        $this->assertResponseOk();
    }

    /**
     * 删除营销人员
     * @test
     * Author weixinhua
     */
    public function destroy()
    {
        $marketing = factory('App\Model\Marketing')->create();

        $url = action('Admin\MarketingController@getDestroy');
        $condition = [
            'marketing_id' => $marketing->id
        ];
        $url = $url . '?' . http_build_query($condition);
        $response = $this->get($url);
        $this->assertResponseOk();
    }

}
