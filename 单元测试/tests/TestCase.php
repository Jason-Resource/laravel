<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * 测试api返回的数据结构与结果
     *
     * @return void
     * @author chentengfeng @create_at 2016-10-25  18:58:52
     */
    public function apiStructure($response, $code = 0)
    {
        $response->seeJson([
            'code' => $code,
        ])->seeJsonStructure([
            'code',
            'msg',
            'data'
        ]);
    }

    /**
     * 模拟响应
     * $contents 与 heads key相同
     *
     * @return void
     * @author chentengfeng @create_at 2017-01-05  20:20:33
     */
    public function mockResponse(array $contents, $code = 200, array $heads=[])
    {
        $response_queue = [];
        foreach ($contents as $index => $content) {
            $response_queue[] = new Response(
                200, 
                isset($heads[$index]) ? $heads[$index] : [],
                $content
            );
        }

        $handler = HandlerStack::create(new MockHandler($response_queue));
        $this->app->instance(
            'GuzzleHttp\Client', 
            new Client(['handler' => $handler])
        );
    }

    /**
     * 成功模拟
     *
     * @return void
     * @author chentengfeng @create_at 2017-01-05  20:20:33
     */
    public function successMock($data = [])
    {
        $mock_response = [];

        if (empty($data)){
            $mock_response[] =  json_encode(['code' => 0, 'msg' => 'success', 'data' => $data]);
        }else{
            foreach ($data as $item){
                $mock_response[] =  json_encode(['code' => 0, 'msg' => 'success', 'data' => $item]);
            }
        }
        
        $this->mockResponse($mock_response);
    }
}
