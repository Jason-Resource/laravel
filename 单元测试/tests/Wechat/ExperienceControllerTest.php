<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExperienceControllerTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * undocumented function
     *
     * @return void
     * @author chentengfeng @create_at 2017-01-20  11:14:10
     */
    public function additionProvider()
    {
        $free = json_decode('{"id":1023,"person_limit":2,"total_limit":100,"serve_id":1575,"count":2,"total":50,"start_time":20170119162652,"end_time":20170121162652,"deleted_at":null,"created_at":"2017-01-20 16:26:52","updated_at":"2017-01-20 16:26:52","free_serve_history":[],"serve_rule":{"id":150,"type":"time","serve_id":1575,"time_type":"month","deleted_at":null,"created_at":"2017-01-20 16:26:52","updated_at":"2017-01-20 16:26:52"}}', true);
        $store_free = json_decode('{"id":7452,"uid":2,"sid":1575,"num":3,"start_time":0,"end_time":0,"cycle":1,"deleted_at":null,"created_at":"2017-01-20 17:09:41","updated_at":"2017-01-20 17:09:41","platform":"ZB","expend_count":1870,"rule_type":"time"}', true);
        $store_history = json_decode('{"id":714}', true);
        $user_subscribe = json_decode('{"id":10096,"uid":2,"sid":1575,"num":2,"start_time":20170122170756,"end_time":20170123170756,"cycle":1,"deleted_at":null,"created_at":"2017-01-22 17:07:56","updated_at":"2017-01-22 17:07:56","platform":"CPZX","expend_count":0,"rule_type":"time"}', true);

        return [
            [$free, $store_free, $store_history, $user_subscribe],
        ];
    }

    /**
     * 发放过时
     *
     * @return void
     * @author chentengfeng @create_at 2017-01-20  11:14:10
     */
    public function additionProviderError()
    {
        $free = json_decode('{"id":1023,"person_limit":2,"total_limit":100,"serve_id":1575,"count":935,"total":650,"start_time":20170119162652,"end_time":20170121162652,"deleted_at":null,"created_at":"2017-01-20 16:26:52","updated_at":"2017-01-20 17:12:02","free_serve_history":[{"id":714,"service_id":1575,"free_serve_id":1023,"time":20170120171202,"user_id":2,"deleted_at":null,"created_at":"2017-01-20 17:12:02","updated_at":"2017-01-20 17:12:02"}],"serve_rule":{"id":150,"type":"count","serve_id":1575,"time_type":"month","deleted_at":null,"created_at":"2017-01-20 16:26:52","updated_at":"2017-01-20 16:26:52"}}', true);

        return [
            [$free],
        ];
    }


    /**
     * 新增免费体验
     *
     * @test
     * @dataProvider additionProvider
     * @return void
     */
    public function postStore($free, $store_free, $store_history, $user_subscribe)
    {
        $faker = app('Faker\Generator');
        $free_serve_id = $free['id'];
        $url = action('Wechat\ExperienceController@postStore', [$free_serve_id]);

        // 模拟网络请求
        $this->successMock([
            $free,
            $user_subscribe,
            $store_free,
            $store_history,
        ]);

        $this->withSession([
            'user' => [
                'id' => $store_free['uid'],
            ]
        ]);


        $response = $this->post($url);
        //$this->dump();

        $this->assertResponseOk();
    }

    /**
     * 已经发放情况
     *
     * @test
     * @dataProvider additionProviderError
     * @return void
     * @author chentengfeng @create_at 2017-01-20  11:14:10
     */
    public function postStoreError($free)
    {
        $faker = app('Faker\Generator');
        $free_serve_id = $free['id'];
        $url = action('Wechat\ExperienceController@postStore', [$free_serve_id]);

        // 模拟网络请求
        $this->successMock([
            $free,
        ]);

        $this->withSession([
            'user' => [
                'id' => 2,
            ]
        ]);


        $response = $this->post($url);
        //$this->dump();

        $this->apiStructure($response, 70004);// 返回对应错误码,已发放
    }
}
