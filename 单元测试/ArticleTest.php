<?php

class ArticleTest extends TestCase
{
    //接口地址
    protected $baseUrl = 'http://consults.sscf.net';
    
    /**
     * 测试新闻
     * @author  jianwei
     * @created_at  2016-11-16 10:57
     */
    public function testNewArticles()
    {
        //$this->visit('/api/article/news-list?sscf-terminal=ios')->see('Laravel 5');
        
        //$this->post('/api/article/news-list',['sscf-terminal'=>'ios'])->seeJson(['code' => 30007,]);
        //$this->post('/api/article/news-list',['sscf-terminal'=>'ios'])->assertResponseOk();
        $this->post('/api/article/news-list',['sscf-terminal'=>'ios'])->seeJsonEquals(['code' => 30007,'msg'=>"没找到任何文章!",'data'=>[]]);
    }

    

    /**
     * 获取App 要闻-文章列表
     * @author jingchang
     */
    public function testAppNewsArticlesList()
    {
        //调试使用
        $this->dump();

        //调用接口
        $this->get('/api/article/app-news-articles-list')
            ->seeStatusCode(200)
            ->seeJson([
                'code' => 0,
                'msg' => '成功',
            ]);

        //返回的数据
        $responseData = json_decode($this->response->getContent(), true);

        //data 不为空才判断
        if(!empty($responseData['data'])) {
            $this->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'yw_cover_image_id', 'yw_title', 'source_name', 'addtime', 'slide_img'
                    ]
                ]
            ]);
        }
    }

    /**
     * 获取App 要闻-文章详情
     * @author jingchang
     */
    public function testAppNewsArticleInfo()
    {
        //文章id
        $article_id = 38;

        //调用接口
        $this->get("/api/article/app-news-article-info?article_id={$article_id}")
            ->seeStatusCode(200)
            ->seeJson([
                'code' => 0,
                'msg' => '成功',
            ])
            ->seeJsonStructure([
                'data' => [
                    'id', 'is_show', 'verified', 'source', 'source_link', 'yw_subtitle', 'yw_title', 'yw_content', 'source_name', 'addtime'
                ]
            ]);
    }


    
}
