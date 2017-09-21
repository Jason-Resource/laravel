<?php

namespace App\Exceptions;

use Exception;

class ScriptException extends JsonException
{
    /**
     * undocumented function
     *
     * @return void
     * @author chentengfeng @create_at 2016-09-19  08:14:42
     */
    public function __construct($code, $url, $data=[])
    {
        parent::__construct($code, $data);

        $this->url = $url;
    }
    /**
     * 获取错误信息
     *
     * @return void
     * @author chentengfeng @create_at 2016-09-19  08:14:42
     */
    public function getErrorMsg()
    {
        if (empty($this->url)) {
            parent::getErrorMsg();
        }
        //@TODO 应该没人用http作中间的路由名称吧!!
        if (strpos($this->url, 'http') === false) {
            $this->url = 'http://' . $this->url;
        }

        $return = [
            'code' => 10000,
            'msg'  => '参数错误',
            'url'  => $this->url,
        ];

        if (empty($this->code_list[$this->code])) {
            return response()->view('admin.errors.redirect', $return);
        }

        $return['code'] = $this->code;
        $return['msg'] = $this->code_list[$this->code]['msg'];

        return response()->view('admin.errors.redirect', $return);
    }

}