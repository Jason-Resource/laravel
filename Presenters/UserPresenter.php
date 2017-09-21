<?php
namespace App\Presenters;

use Route;

class UserPresenter
{
    protected $cur_route = null;
    protected $cur_controller = null;
    protected $cur_action = null;

    public function __construct()
    {
        $this->cur_route = Route::currentRouteAction();
        list($this->cur_controller, $this->cur_action) = explode('@', $this->cur_route);
    }

    /**
     * 高亮左侧导航-我的文章
     *
     * @author jilin
     * @return string
     */
    public function activeMenuByArticle()
    {
        $arr = [
            'collect',
            'praise',
            'comment',
        ];
        if(in_array($this->cur_action, $arr)) {
            return 'class="active"';
        }

        return '';
    }

    /**
     * 高亮左侧导航-我的消息
     *
     * @author jilin
     * @return string
     */
    public function activeMenuByMsg()
    {
        $arr = [
            'message',
        ];
        if(in_array($this->cur_action, $arr)) {
            return 'class="active"';
        }

        return '';
    }

    /**
     * 高亮左侧导航-帐号设置
     *
     * @author jilin
     * @return string
     */
    public function activeMenuByAcount()
    {
        $arr = [
            'setBaseinfo',
            'setAvatar',
            'modifyPassword',
            'accountSecurity',
        ];
        if(in_array($this->cur_action, $arr)) {
            return 'class="active"';
        }

        return '';
    }

}