<?php

return array(
    //'配置项'=>'配置值'
    //修改左右定界符
    'TMPL_L_DELIM' => '<{',
    'TMPL_R_DELIM' => '}>',
    'DB_DSN' => 'mysql://root:neoway@localhost:3306/qrcode', //使用DB_DSN配置数据库
//    'DB_DSN' => 'mysql://root:@localhost:3306/qrcode', //使用DB_DSN配置数据库
    'DB_PREFIX' => 'qr_', //设置表前缀 ，不能省略，置空
        'SHOW_PAGE_TRACE'=>true,//开启页面Trace
        //修改URL的分隔符
        //'URL_PATHINFO_DEPR'=>'-',
);
?>