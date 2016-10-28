<?php

return array(
    //'配置项'=>'配置值'
    //修改左右定界符
    'TMPL_L_DELIM' => '<{',
    'TMPL_R_DELIM' => '}>',
//    'DB_DSN' => 'mysql://root:neoway@localhost:3306/qrcode', //使用DB_DSN配置数据库
    
	'db_type' => 'mysql',
    'db_user' => 'test_user',
    'db_pwd' => '123456',
    'db_host' => 'localhost',
    'db_port' => '3306',
    'db_name' => 'qrcode',
    'DB_PREFIX' => 'qr_', //设置表前缀 ，不能省略，置空
    'SHOW_PAGE_TRACE' => true, //开启页面Trace
    
//系统常量
    'ENCKEY' => 'nwt',   //可逆加密key
    'PASSVERIFY'=>'youfang',  //不可逆加密后缀
    'ACTIVE_EXPIRES'=>60,   //激活有效期
    'COOKIE_EXPIRES'=>259200,   //cookie有效期3天
    
    'LIST_ROWS'=>10, //设置分页每页显示的行数
    'PC_NAME_LENGTH' => 100, //电脑显示文件名的最长长度
);
