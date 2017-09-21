<?php
namespace Admin;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;


class ProductTest extends \TestCase
{
    use WithoutMiddleware;

    /**
     * 获取产品分类列表
     * @test
     * Author weixinhua
     */
    public function getProductList()
    {
        // 认证
        $user = factory('App\Model\AdminUsers')->create();
        $this->actingAs($user, 'admin');
        $faker = app('Faker\Generator');

        $url = action('Admin\ProductController@getProductList');

        $pcname = $faker->name();

        // 处理json分页
        $paginator = new LengthAwarePaginator([
            [
                "id"           => 197,
                "pcname"       => $pcname,
                "summary"      => $faker->name(),
                "addtime"      => $faker->date('YmdHis'),
                "addpercent"   => $faker->numberBetween(1, 10),
                "status"       => null,
                "imgurl"       => $faker->imageUrl(),
                "term"         => $faker->numberBetween(1, 10),
                "imgurl2"      => $faker->imageUrl(),
                "cntpc"        => $faker->name(),
                "gsjs"         => $faker->url(),
                "deleted_at"   => null,
                "created_at"   => $faker->date('Y-m-d H:i:s'),
                "updated_at"   => $faker->date('Y-m-d H:i:s'),
                "platform"     => "CPZX",
                "buycount"     => 0,
                "idea"         => "",
                "catalogue_id" => $faker->numberBetween(1, 10),
                'area'         => $faker->randomElement(['GZ', 'CS']),
                'serve_rule'   => [
                    'type' => $faker->randomElement(['count', 'time']),
                    'time_type'    => $faker->randomElement(['month', 'day','week']),
                ],
                "serve_cycle"  => [
                    [
                        "id"         => $faker->numberBetween(1, 1000),
                        "serve_id"   => $faker->numberBetween(1, 1000),
                        "name"       => $faker->name(),
                        "price"      => "6666.00",
                        "deleted_at" => null,
                        'is_main' => 'no',
                        'count' => 1,
                        "created_at" => $faker->date('Y-m-d H:i:s'),
                        "updated_at" => $faker->date('Y-m-d H:i:s'),
                    ]
                ]
            ]
        ], 1, 1, 1);
        $this->successMock([$paginator->toArray()]);

        $this->visit($url)->see($pcname);
    }


    /**
     * 添加产品
     * @test
     * Author weixinhua
     */
    public function postStoreProduct()
    {
        $faker = app('Faker\Generator');
        $store_data = [
            'pcname'       => $faker->name(),
            'catalogue_id' => $faker->numberBetween(1, 1000),
            'term'         => $faker->numberBetween(1, 1000),
            'addpercent'   => $faker->numberBetween(1, 10),
            'gsjs'         => $faker->url(),
            'rule_type'    => $faker->randomElement(['time', 'count']),
            'time_type'    => $faker->randomElement(['month', 'day', 'week']),
            'tag_ids'      => $faker->numberBetween(1, 1000),
            'imgurl'       => $faker->imageUrl(),
            'imgurl2'      => $faker->imageUrl(),
            'cntpc'        => $faker->name(),
            'summary'      => $faker->name(),
        ];
        $url = action('Admin\ProductController@postStoreProduct');
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
     * 更新产品
     * @test
     * Author weixinhua
     */
    public function postUpdateProduct()
    {
        $faker = app('Faker\Generator');
        $update_data = [
            'product_id'   => $faker->numberBetween(1, 1000),
            'pcname'       => $faker->name(),
            'catalogue_id' => $faker->numberBetween(1, 1000),
            'term'         => $faker->numberBetween(1, 1000),
            'addpercent'   => $faker->numberBetween(1, 10),
            'gsjs'         => $faker->url(),
            'rule_type'    => $faker->randomElement(['time', 'count']),
            'time_type'    => $faker->randomElement(['month', 'day', 'week']),
            'tag_ids'      => $faker->numberBetween(1, 1000),
            'imgurl'       => $faker->imageUrl(),
            'imgurl2'      => $faker->imageUrl(),
            'cntpc'        => $faker->name(),
            'summary'      => $faker->name(),
        ];

        $url = action('Admin\ProductController@postUpdateProduct');
        $this->successMock([1]);

        $response = $this->call('POST', $url, $update_data);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue(1 == $response_data['data']);
    }

    /**
     * 添加产品服务周期
     * @test
     * Author weixinhua
     */
    public function postStoreServeCycle()
    {
        $faker = app('Faker\Generator');
        $store_data = [
            'serve_id' => $faker->numberBetween(1, 1000),
            'name'     => $faker->name(),
            'price'    => $faker->numberBetween(1, 1000),
        ];
        $url = action('Admin\ProductController@postStoreServeCycle');
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
     * 更新产品服务周期
     * @test
     * Author weixinhua
     */
    public function postUpdateServeCycle()
    {
        $faker = app('Faker\Generator');
        $update_data = [
            'serve_cycle_id' => $faker->numberBetween(1, 1000),
            'serve_id'       => $faker->numberBetween(1, 1000),
            'name'           => $faker->name(),
            'price'          => $faker->numberBetween(1, 1000),
        ];

        $url = action('Admin\ProductController@postUpdateServeCycle');
        $this->successMock([1]);

        $response = $this->call('POST', $url, $update_data);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue(1 == $response_data['data']);
    }

    /**
     * 删除产品服务周期
     * @test
     * Author weixinhua
     */
    public function getDestroyServeCycle()
    {
        $faker = app('Faker\Generator');

        $url = action('Admin\ProductController@getDestroyServeCycle');
        $condition = [
            'serve_cycle_id' => $faker->numberBetween(1, 1000),
        ];
        $url = $url . '?' . http_build_query($condition);
        $this->successMock([1]);

        $response = $this->call('GET', $url);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue(1 == $response_data['data']);
    }


    /**
     * 添加产品标签
     * @test
     * Author weixinhua
     */
    public function storeTag()
    {
        $faker = app('Faker\Generator');
        $store_data = [
            'name' => $faker->name()
        ];
        $url = action('Admin\ProductController@postStoreTag');
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
     * 更新产品标签
     * @test
     * Author weixinhua
     */
    public function updateTag()
    {
        $faker = app('Faker\Generator');

        $update_data = [
            'tag_id' => $faker->numberBetween(1, 1000),
            'name'   => $faker->name()
        ];

        $url = action('Admin\ProductController@postUpdateTag');
        $this->successMock([1]);

        $response = $this->call('POST', $url, $update_data);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue(1 == $response_data['data']);
    }

    /**
     * 删除产品标签
     * @test
     * Author weixinhua
     */
    public function destroyTag()
    {
        $faker = app('Faker\Generator');

        $url = action('Admin\ProductController@getDestroyTag');
        $condition = [
            'tag_id' => $faker->numberBetween(1, 1000),
        ];
        $url = $url . '?' . http_build_query($condition);
        $this->successMock([1]);

        $response = $this->call('GET', $url);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue(1 == $response_data['data']);
    }

    /**
     * 添加产品分类目录
     * @test
     * Author weixinhua
     */
    public function storeCatalogue()
    {
        $faker = app('Faker\Generator');
        $store_data = [
            'name'  => $faker->name(),
            'color' => $faker->name()
        ];
        $url = action('Admin\ProductController@postStoreCatalogue');
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
     * 更新产品分类目录
     * @test
     * Author weixinhua
     */
    public function updateCatalogue()
    {
        $faker = app('Faker\Generator');

        $update_data = [
            'catalogue_id' => $faker->numberBetween(1, 1000),
            'name'         => $faker->name(),
            'color'        => $faker->name()
        ];

        $url = action('Admin\ProductController@postUpdateCatalogue');
        $this->successMock([1]);

        $response = $this->call('POST', $url, $update_data);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue(1 == $response_data['data']);
    }

    /**
     * 删除产品分类目录
     * @test
     * Author weixinhua
     */
    public function destroyCatalogue()
    {
        $faker = app('Faker\Generator');

        $url = action('Admin\ProductController@getDestroyCatalogue');
        $condition = [
            'catalogue_id' => $faker->numberBetween(1, 1000),
        ];
        $url = $url . '?' . http_build_query($condition);
        $this->successMock([1]);

        $response = $this->call('GET', $url);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue(1 == $response_data['data']);
    }

    /**
     * 添加消息模版
     * @test
     * Author weixinhua
     */
    public function postStoreTemplate()
    {
        $faker = app('Faker\Generator');
        $store_data = [
            'serve_id' => $faker->numberBetween(1, 1000),
            'view_path' => $faker->name(),
            'param_rule[name]' => [
                $faker->name(),
            ],
            'param_rule[type]' => [
                $faker->name(),
            ],
            'param_rule[is_must]' => [
                $faker->randomElement([0, 1]),
            ],
            'param_rule[explain]' => [
                $faker->name(),
            ]
        ];
        $url = action('Admin\ProductController@postStoreTemplate');

        // 设定添加时返回自增ID
        $increment = $faker->numberBetween(1, 1000);
        $this->successMock([['id' => $increment]]);

        $response = $this->call('POST', $url, $store_data);
        $this->assertResponseOk();
        $response_data = json_decode($response->getContent(), true);
        // 校验数据
        $this->assertTrue($increment == $response_data['data']['id']);

    }

}
