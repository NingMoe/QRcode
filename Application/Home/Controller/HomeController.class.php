<?php

namespace Home\Controller;

use Think\Controller;

/**
 * Description of HomeController
 *
 * @author lpy
 */
class HomeController extends Controller {

    protected function _empty() {
        $this->redirect('Index/index');
    }

    protected function _initialize() {
        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置
        if (!C('WEB_SITE_CLOSE')) {
            $this->error('站点已经关闭，请稍后访问~');
        }
    }
    
    /* 用户登录检测 */
    protected function login() {
        /* 用户登录检测 */
        is_login() || $this->error('您还没有登录，请先登录！', U('User/login'));
    }

}
