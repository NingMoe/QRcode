<?php

return array(
    //'配置项'=>'配置值'
    //修改左右定界符
    'TMPL_L_DELIM' => '<{',
    'TMPL_R_DELIM' => '}>',
//    'DB_DSN' => 'mysql://root:neoway@localhost:3309/qrcode', //使用DB_DSN配置数据库
    
	'db_type' => 'mysql',
    'db_user' => 'root',
    'db_pwd' => 'neoway',
    'db_host' => 'localhost',
    'db_port' => '3306',
    'db_name' => 'qrcode',
    'DB_PREFIX' => 'qr_', //设置表前缀 ，不能省略，置空
//    'SHOW_PAGE_TRACE' => true, //开启页面Trace

    'PHONE_NAME_LENGTH' => 100, //电脑显示文件名的最长长度
);
