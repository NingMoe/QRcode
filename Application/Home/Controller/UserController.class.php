<?php

namespace Home\Controller;
use User\Api\UserApi;
class UserController extends HomeController {
    /* 空操作，用于输出404页面 */

    public function _empty() {
        $this->redirect('Index/index');
    }

    //网站初始化，读取配置信息
    protected function _initialize() {
        /* 读取站点配置 */
//        $config = api('Config/lists');
//        C($config); //添加配置
//        if (!C('WEB_SITE_CLOSE')) {
//            $this->error('站点已经关闭，请稍后访问~');
//        }
    }

    public function index() {

        $this->display();
    }

//登录验证
    Public function login2() {
        $this->assign('current_html', "login");
        $where['username'] = $_POST['user'];
        $where['password'] = md5($_POST['password']);
//        var_dump(M('user')->find());
        if (M('user')->where($where)->find()) {
            $_SESSION['username'] = $_POST['user'];
            $data['status'] = 1;                        //status=1表示登录成功
            $this->ajaxReturn($data);
        } else {
            $data['status'] = 2;                        //status=2表示登录失败
            $this->ajaxReturn($data);
        }
    }

    public function login() {
        $this->assign('current_html', "login");
        if (IS_POST) {
            $username = I("user");
            $password = I("password");
            
            if (cookie('user_auth') && cookie('user_auth_sign')) {
                $username = cookie('user_auth');
                $password = cookie('user_auth_sign');
//                echo $username."<br>".$password;
                $this->cookieLogin($username, $password);
            } else {
                $this->IDLogin($username, $password);
            }
        }
    }

    //普通用户名+密码登录
    public function IDLogin($username, $password) {
        /* 调用UC登录接口登录 */
        $user = new UserApi;
        $uinfo = $user->login($username, $password);
        if (0 < $uinfo['uid']) {
            if (I('remember')) {
                //记住登录状态
                $key = C('ENCKEY');
                $pasVerify = C("PASSVERIFY");
                $cookieExp = C("COOKIE_EXPIRES");

                $loginUser['uid'] = $uinfo['uid'];
                $loginUser['uname'] = $uinfo['username'];
                $loginUser['expires'] = time() + $cookieExp;
                $str = http_build_query($loginUser);

                $uCookieInfo = encrypt($key, $str);  //cookie保存用户信息
                $uCookieSign = md5(sha1(sha1(md5($uCookieInfo . $pasVerify))));  //cookie保存用户识别信息

                cookie('user_auth', $uCookieInfo, $cookieExp);
                cookie('user_auth_sign', $uCookieSign, $cookieExp);
            }
            session('userid', $uinfo['uid']);
            session('username', $uinfo['username']);
            
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else { //登录失败
            switch ($uinfo['uid']) {
                case -1: 
                    $data['error'] = "*用户不存在或被禁用！";
                    $data['status'] = -1;
                    $this->ajaxReturn($data);
                    break; //系统级别禁用
                case -2: 
                    $data['error'] = "*密码错误！";
                    $data['status'] = -2;
                    $this->ajaxReturn($data);
                    break;
                default: 
                    $error = "未知错误！";
                    $this->error($error);
                    break; 
            }
        }
    }

    //cookie登录
    public function cookieLogin($userAuth, $userAutnSign) {
        $key = C('ENCKEY');
        $pasVerify = C("PASSVERIFY");

        $pas = md5(sha1(sha1(md5($userAuth . $pasVerify))));
        if ($userAutnSign == $pas) {  //验证cookie是否正确
            $user = decrypt($key, $userAuth);
            parse_str($user);   //获取cookie保留信息

            if (time() <= $expires) {
                session('userid', $uid);   //session保存登录状态
                session('username', $uname);
                $this->redirect(U('index/home'));
//                $this->success("登录成功", U('user/index'));
            } else {
                cookie('user_auth', null);
                cookie('user_auth_sign', null);
                $this->error("cookie有效期已过",U('index/index'));
            }
        } else {
            cookie('user_auth', null);
            cookie('user_auth_sign', null);
            $this->error("cookie错误",U('index/index'));
        }
    }

    //注销
    public function logoff() {
        cookie('user_auth', null);
        cookie('user_auth_sign', null);

        session('userid', null);
        session('username', null);
        $this->success("注销成功！", U('index/index'));
    }

    //发送激活邮件
    public function sendEmail() {
        $key = C('ENCKEY');
        $pasVerify = C("PASSVERIFY");
        $exipres = C("ACTIVE_EXPIRES");

        $user['uid'] = 001;
        $user['uname'] = "lpy";
        $user['expires'] = time() + $exipres;
        $str = http_build_query($user);

        $uInfo = encrypt($key, $str);
        $uPas = md5(sha1(sha1(md5($uInfo . $pasVerify))));
        $URLuInfo = urlencode($uInfo);
        $add = "http://localhost/thinkphp3.2/index.php/home/index/active?uInfo=" . $URLuInfo . "&uPass=" . $uPas;
        $content = '<a href="' . $add . '">点击激活</a>';
//        sendMail('919950974@qq.com', 'lpyi', $content);
        echo $content;

//        $this->display();
    }

    //激活邮箱
    public function mailActive() {
        $key = C('ENCKEY');
        $pasVerify = C("PASSVERIFY");

        $uInfo = I('uInfo');
        $uPass = I("uPass");

        $pas = md5(sha1(sha1(md5($uInfo . $pasVerify))));
        if ($uPass == $pas) {
            $user = urldecode(decrypt($key, $uInfo));
            parse_str($user);
            if (time() <= $expires) {
                echo $uid . $uname . $expires;
                echo "激活成功";
            } else {
                echo "有效期已过";
            }
        } else {
            echo "激活失败";
        }
    }

}
