<?php
/**
 * 检测是否含in.between
 */
//防注入，字符串处理，禁止构造数组提交
//字符过滤
function safe_replace($string) {
    if (is_array($string)) {
        $string = implode('，', $string);
        $string = htmlspecialchars(str_shuffle($string));
    } else {
        $string = htmlspecialchars($string);
    }
    $string = str_replace('%20', '', $string);
    $string = str_replace('%27', '', $string);
    $string = str_replace('%2527', '', $string);
    $string = str_replace('*', '', $string);
    $string = str_replace("select", "", $string);
    $string = str_replace("join", "", $string);
    $string = str_replace("union", "", $string);
    $string = str_replace("where", "", $string);
    $string = str_replace("insert", "", $string);
    $string = str_replace("delete", "", $string);
    $string = str_replace("update", "", $string);
    $string = str_replace("like", "", $string);
    $string = str_replace("drop", "", $string);
    $string = str_replace("create", "", $string);
    $string = str_replace("modify", "", $string);
    $string = str_replace("rename", "", $string);
    $string = str_replace("alter", "", $string);
    $string = str_replace("cas", "", $string);
    $string = str_replace('"', '&quot;', $string);
    $string = str_replace("'", '', $string);
    $string = str_replace('"', '', $string);
    $string = str_replace(';', '', $string);
    $string = str_replace('<', '&lt;', $string);
    $string = str_replace('>', '&gt;', $string);
    $string = str_replace("{", '', $string);
    $string = str_replace('}', '', $string);

    return $string;
}
//判断是否已经登录
//user_auth      用户信息
//user_auth_sign 用户信息加密后的字符串
function is_login() {
    $user = session('user_auth');
    if (empty($user)) {
        return 0;
    } else {
        return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
    }
}
/**
 * 数据签名认证
 * @param  array  $data 被认证的数据
 * @return string       签名
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function data_auth_sign($data) {
    //数据类型检测
    if (!is_array($data)) {
        $data = (array) $data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1(md5($code)); //生成签名
    return $sign;
}
/**
 * 调用系统的API接口方法（静态方法）
 * api('User/getName','id=5'); 调用公共模块的User接口的getName方法
 * api('Admin/User/getName','id=5');  调用Admin模块的User接口
 * @param  string  $name 格式 [模块名]/接口名/方法名
 * @param  array|string  $vars 参数
 */
function api($name, $vars = array()) {
    $array = explode('/', $name);
    $method = array_pop($array);
    $classname = array_pop($array);
    $module = $array ? array_pop($array) : 'Common';
    $callback = $module . '\\Api\\' . $classname . 'Api::' . $method;
    if (is_string($vars)) {
        parse_str($vars, $vars);
    }
    return call_user_func_array($callback, $vars);
}
/**
 * 邮件发送函数
 */
function sendMail($to, $title, $content) {

    Vendor('PHPMailer.PHPMailer');
    $mail = new \vendor\PHPMailer\PHPMailer(); //实例化
    $mail->IsSMTP(); // 启用SMTP
//    $mail->Host = C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
//    $mail->Username = C('MAIL_USERNAME'); //你的邮箱名
//    $mail->Password = C('MAIL_PASSWORD'); //邮箱密码    

    $mail->SMTPAuth = true; //启用smtp认证
//    $mail->Host = "smtp.qq.com";
//    $mail->Port = '465'; //qq邮箱
//    $mail->SMTPSecure = 'ssl'; //qq邮箱
//    $mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
//    $mail->Username = "919950974@qq.com"; //你的邮箱名
//    $mail->Password = "bmfytrydmojqbfca"; //邮箱密码
//    $mail->From = "919950974@qq.com"; //发件人地址（也就是你的邮箱地址）

//    $mail->Host = "smtp.163.com"; //smtp服务器的名称（这里以QQ邮箱为例）smtp.exmail.qq.com
//    $mail->Username = "lipeiyililin@163.com"; //你的邮箱名
//    $mail->Password = "li885577510510"; //邮箱密码
//    $mail->From = "lipeiyililin@163.com"; //发件人地址（也就是你的邮箱地址）
//    $mail->FromName = "lpy"; //发件人姓名
    
    $mail->Host = "mail.neoway.com.cn"; //smtp服务器的名称（这里以QQ邮箱为例）smtp.exmail.qq.com
    $mail->Username = "li.peiyi@innos.com"; //你的邮箱名
    $mail->Password = "0301001"; //邮箱密码
    $mail->From = "li.peiyi@innos.com"; //发件人地址（也就是你的邮箱地址）
    $mail->FromName = "lpy"; //发件人姓名

    $mail->AddAddress($to, "尊敬的客户");
    $mail->WordWrap = 50; //设置每行字符长度
    $mail->IsHTML(TRUE); // 是否HTML格式邮件
    $mail->CharSet = 'utf-8'; //设置邮件编码
    $mail->Subject = $title; //邮件主题
    $mail->Body = $content; //邮件内容
    $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
    return($mail->Send());
}

/**
 * 加密
 */
function encrypt($key,$data) {
    $key = md5($key);
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) {
            $x = 0;
        }
        $char .= $key{$x};
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
    }
    return base64_encode($str);
}

/**
 * 解密
 */
function decrypt($key,$data) {
    $key = md5($key);
    $x = 0;
    $data = base64_decode($data);
    $len = strlen($data);
    $l = strlen($key);
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) {
            $x = 0;
        }
        $char .= substr($key, $x, 1);
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        } else {
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return $str;
}

//传递数据以易于阅读的样式格式化后输出
function p($data) {
    // 定义样式
    $str = '<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;'
            . 'color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;'
            . 'border: 1px solid #CCC;border-radius: 4px;">';
    // 如果是boolean或者null直接显示文字；否则print
    if (is_bool($data)) {
        $show_data = $data ? 'true' : 'false';
    } elseif (is_null($data)) {
        $show_data = 'null';
    } else {
        $show_data = print_r($data, true);
    }
    $str.=$show_data;
    $str.='</pre>';
    echo $str;
}
