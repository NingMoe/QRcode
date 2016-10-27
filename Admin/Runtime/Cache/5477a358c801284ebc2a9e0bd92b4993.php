<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>Ajax无刷新分页</title>
        <script type="text/javascript" src="../Public/jquery-1.7.2.min.js"></script>
        <script type="text/javascript">
            function user(id){    //user函数名 一定要和action中的第三个参数一致上面有
                 var id = id;
                    $.get('Index/user', {'p':id}, function(data){  //用get方法发送信息到UserAction中的user方法
                        alert("123");
                        $("#user").replaceWith("<div  id='user'>"+data+"</div>"); //user一定要和tpl中的一致
                });
             }
            
        </script>
    </head>

    <body>
            <div id='user'>   <!--这里的user和下面js中的test要一致-->
                    <?php if(is_array($lists)): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><!--内容输出-->
                    <?php echo ($list["fileid"]); ?>&nbsp;&nbsp;<?php echo ($list["filename"]); ?><br/><?php endforeach; endif; else: echo "" ;endif; ?>
            <?php echo ($page); ?>  <!--分页输出-->
        </div>
        
    </body>
</html>