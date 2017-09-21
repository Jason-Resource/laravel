<?php
namespace App\Presenters;


use App\Http\Common\Helper;
use Illuminate\Support\Facades\Input;

class PaginatePresenter
{

    /**
     * 获取分类分页路径
     * @param string $category_url 分类路径
     * @param string $word 搜索词
     * @param int $page 分页数
     * @return string 返回分页路径
     */
    public function getCategoryPageUrl($category_url = '', $page = 1)
    {
        $url = '#';
        
        if (!empty($category_url)) {
            $param = Input::get();
            $param['page'] = $page;
            $param_str = http_build_query($param);
            //替换分页参数
            $url= Helper::buildFullUrl($category_url).'?'.$param_str;
        }

        return $url;
    }
}