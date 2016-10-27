<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>电子资料二维码下载 | 有方科技</title>
        <link rel="shortcut icon" href="__PUBLIC__/Images/favicon.ico" />
        
        <!-- Bootstrap -->
        <link href="__PUBLIC__/plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="__PUBLIC__/plugins/bootstrap-3.3.5-dist/css/bootstrap-theme.css" rel="stylesheet">

        <!-- style -->
        <link href="__PUBLIC__/Css/style.css" rel="stylesheet">

        <!-- HTML5 shiV and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

    </head>
    <body>
         Fixed navbar 
                <div class="navbar navbar-inverse navbar-fixed-top">
                    <div class="container">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <?php if(isset($_SESSION['username']) && $_SESSION['username'] != ''): ?><a class="navbar-brand" href="__APP__/Index/home">电子资料二维码下载</a>
                                <?php else: ?>
                                <a class="navbar-brand" href="__APP__/Index/index">电子资料二维码下载</a><?php endif; ?>
                        </div>
        
                        <div class="navbar-collapse collapse">
                            <?php if(isset($_SESSION['username']) && $_SESSION['username'] != ''): ?><ul class="nav navbar-nav navbar-right">
                                    <li><a>admin</a></li>
                                    <li><a href="__APP__/Index/logoff">注销</a></li>
                                </ul>
                                <form class="navbar-form navbar-right form-group" id="search_btn" method="post" action="__APP__/Index/search">
                                    <div class="input-group">
                                        <?php if($searchname != ""): ?><input class="form-control" type="text" name="file_name" id="s" value="<?php echo ($searchname); ?>" onclick="this.value=''"> 
                                            <?php else: ?>
                                            <input class="form-control" type="text" name="file_name" id="s" placeholder="搜索..." onclick="this.value=''"><?php endif; ?>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" onclick="$('#search_btn').submit()">
                                                <span class="glyphicon glyphicon-search"></span>
                                            </button>
                                        </span>
                                    </div>
                                </form>
                                <?php elseif($current_html == 'index'): ?>
                                <ul class="nav navbar-nav navbar-right">
                                    <li><a href="http://www.neoway.com/cn/index.aspx">关于有方</a></li>
                                </ul>
                                <?php elseif($current_html != 'index'): ?>
                                <script language="javascript" type="text/javascript">
                                    //<![CDATA[ 		
                                    window.location.href = "__APP__/Index/index";
                                    //]]> 
                                </script><?php endif; ?>
                        </div>
                    </div>
                </div>
<div id="header">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h1>电子资料二维码下载</h1>
                <h2 class="subtitle">电子文档资料，减少纸质文档，彩页的发放，促进绿色环保</h2>
                <form class="form-inline signup"  id="login" method="post" name="login" onsubmit="return false" role="form">
                    <div class="form-group">
                        <input class="form-control" id="user"  name="user" type="text" placeholder="输入用户名" 
                               data-toggle="popover" data-placement="bottom" data-content='<span style="color: rgb(245, 0, 0);" class="glyphicon glyphicon-remove">用户名错误！</span>'>
                        <input class="form-control" id="password" name="password" type="password" placeholder="输入密码"
                               data-toggle="popover" data-placement="bottom" data-content='<span style="color: rgb(245, 0, 0);" class="glyphicon glyphicon-remove">密码错误！</span>'>
                    </div>
                    <button  class="btn btn-theme" id="login_button" style="width:100px;">登录</button>
                </form>				
            </div>
            <div class="col-lg-4 col-lg-offset-2">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
                    </ol>

                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="__PUBLIC__/Images/slide1.png" alt="QQ二维码">
                        </div>
                        <div class="item">
                            <img src="__PUBLIC__/Images/slide2.png" alt="微信二维码">
                        </div>
                    </div>
                </div>		
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="__PUBLIC__/Js/Base.js"></script> 
<!--<script type="text/javascript" src="__PUBLIC__/Js/prototype.js"></script>--> 
<script type="text/javascript" src="__PUBLIC__/Js/mootools.js"></script> 
<script type="text/javascript" src="__PUBLIC__/Js/ThinkAjax.js"></script> 
<script language="javascript"  type="text/javascript"  src="__PUBLIC__/Js/jquery-1.9.1.js"></script>

<script language="javascript"  type="text/javascript"  src="__PUBLIC__/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script language="javascript"  type="text/javascript"  src="__PUBLIC__/plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $("#user").focus(function() {
        $('#user').popover('hide');
    });
    $("#user").blur(function() {
        if ($('#user').val() != "admin") {
            $('#user').popover({html: true});
            $('#user').popover('show');
        }
    });
    $('#login_button').click(function() {
        $.ajax({
            type: "POST",
            url: "__URL__/login",
            data: {user: $('#user').val(), password: $('#password').val()},
            success: function(data) {
                if (data.status == "1") {
//                    alert(data.status);
                    window.location.href = "__URL__/home";
                } else {
//                    alert(data.status);
                    $('#password').popover({html: true});
                    $('#password').popover('show');
//                    $('#login').onsubmit="return false";
                }
            }
        }, "json");
    });
</script>
<div id="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <p class="copyright">Copyright © 2016 - 有方科技股份有限公司</p>
            </div>
        </div>		
    </div>	
</div>

</body>
</html>