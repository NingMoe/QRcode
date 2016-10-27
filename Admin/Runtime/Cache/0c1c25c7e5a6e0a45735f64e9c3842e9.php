<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>首页 - 有方科技</title>
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

<!--导入css文件-->
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Admin_Home.css" />

<!--导入分类树样式-->
<link href="__PUBLIC__/Css/mtree.css" rel="stylesheet" type="text/css">
<style>
    .mtree-demo .mtree {
        background: #EEE;
        margin: 20px auto;
        max-width: 320px;
        border-radius: 3px;
    }
    .mtree li{
        font-size: 18px;
    }
    
    .dropdown-menu  li  a {
        display: block;
        padding: 3px 20px;
        clear: both;
        font-weight: normal;
        line-height: 1.42857143;
        color: #333;
        white-space: nowrap;
    }
    .dropdown-menu  li  a:hover,
    .dropdown-menu  li  a:focus {
        color: #262626;
        text-decoration: none;
        background-color: #f5f5f5;
    }
    .dropdown-menu  .active  a,
    .dropdown-menu  .active  a:hover,
    .dropdown-menu  .active  a:focus {
        color: #fff;
        text-decoration: none;
        background-color: #337ab7;
        outline: 0;
    }
    .folderF{
        color: #cccccc;
        font-size: 20px;
        margin-right: 10px;
        cursor: not-allowed;
    }
    .folderT{
        color: #00cccc;
        font-size: 20px;
        margin-right: 10px;
        cursor: pointer;
    }
</style>

<div id="pageheader">
    <div class="container">
        <!--上传框提示信息-->
<!--        <div class="modal fade" id="uploadModal" data-backdrop="static">
            <div class="modal-dialog" style="margin: 200px auto;width: 450px;height: 550px;">
                <div class="modal-content">
                    <div class="modal-body" style="margin: 20px;">
                        <span id="modalBodyText">请选择文件后，再上传！</span>
                    </div>
                    <div class="modal-footer">
                        <span id="countDown" style="font-weight: bold;font-size: large;color: #0055FF;">2</span>秒后，
                        <button class="btn btn-sm btn-primary" id="modalButton" data-dismiss="modal" type="button">确认</button>
                        <button class='btn btn-sm btn-default hidden' id='modalFButton' data-dismiss='modal'>取消</button>
                    </div>
                </div>
            </div>
        </div>-->

        <!--导航栏-->
        <div style="float:left;min-width: 20%;margin-right: 30px;margin-top: 10px;">
            <ul id="navUl" class="mtree jet" name="ul" style="background-color: #f5f5f5;">
                <?php $__FOR_START_1701__=1;$__FOR_END_1701__=sizeof($lists);for($i=$__FOR_START_1701__;$i < $__FOR_END_1701__;$i+=1){ if($lists[$i] == 'ul'): ?><ul name="ul"><?php endif; ?>
                    <?php if($lists[$i] == '/ul'): if($lists[$i+1] != ''): ?></ul></li>
                            <?php else: ?> </ul><?php endif; endif; ?>
                    <?php if(is_array($lists[$i])): if($lists[$i+1] == 'ul'): ?><li name="li" value="<?php echo ($lists[$i][0]); ?>"><a id="<?php echo ($lists[$i][2]); ?>" href="__URL__/home?root=<?php echo ($lists[$i][0]); ?>"><?php echo ($lists[$i][1]); ?></a>
                            <?php else: ?>  <li name="li" value="<?php echo ($lists[$i][0]); ?>"><a id="<?php echo ($lists[$i][2]); ?>" href="__URL__/home?root=<?php echo ($lists[$i][0]); ?>"><?php echo ($lists[$i][1]); ?></a></li><?php endif; endif; } ?>
                <!--文件目录操作，添加，修改，删除-->
                <div id="folderIcon" style="text-align: center;">
                    <span class="glyphicon glyphicon-plus folderT" onclick="folderAdd()" ></span>
                    <span class="glyphicon glyphicon-pencil folderT" onclick="folderEdit()" ></span>
                    <span class="glyphicon glyphicon-trash folderT" onclick="folderDel()" ></span>
                </div>
                <div id="folderDiv" class="hidden" style="text-align: center;">
                    <form class="form-group" id="folderForm" action="__URL__/folder" method="post">
                        <input id="folderName" name="folderName" type="text" placeholder="请输入文件夹的名称" >
                        <!--<input id="parentId" name="parentId" type="hidden" value="" >-->
                        <input id="catId" name="catId" type="hidden" value="">
                        <input id="operation" name="operation" type="hidden" value="">
                        <button class="btn btn-sm btn-default" type="button" onclick="folderConfirm()">确定</button>
                        <button class="btn btn-sm btn-default" type="button" onclick="folderCancel()">取消</button>
                    </form>
                </div>

        </div>

        <!--文件列表-->
        <div class="table-div table-responsive">
            <table class="table table-striped table-condensed">
                <thead>
                    <tr>
                        <th style="width:10%;">序号</th>
                        <th style="width:30%;">文件名</th>
                        <th id="tr-path" style="width:20%;">路径</th>
                        <th id="tr-size" style="width:5%;">大小</th>
                        <th id="tr-date" style="width:15%;">日期</th>
                        <th style="width:15%;">生成二维码</th>
                        <th style="width:5%;">删除</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($files == ''): ?><tr>
                        <td colspan='7'>
                            <div style='height: 100px;vertical-align: middle;line-height: 100px;font-size: 18px;'>暂未查询到数据，请检查后重新查询！</div>
                        </td>
                    </tr><?php endif; ?>
                <?php if(is_array($files)): $i = 0; $__LIST__ = $files;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$f): $mod = ($i % 2 );++$i;?><tr>
                        <td id="td-content">
                            <input name="che" id="<?php echo ($f["fileid"]); ?>" type="checkbox" onchange="is_all()"/>&nbsp;<?php echo ($i); ?>
                        </td>
                        <td id="td-content">
                            <a href="__URL__/download?filename=<?php echo ($f["filename"]); ?>" style="text-decoration: none;" title="<?php echo ($f["filename"]); ?>"><?php echo ($f["shortname"]); ?></a>
                        </td>
                        <td id="td-path">./Public/Uploads</td>
                        <td id="td-size"><?php echo ($f["size"]); ?></td>
                        <td id="td-date"><?php echo date("Y.m.d H:i",strtotime($f["uploadtime"])) ?></td>
                        <td id="td-pic">
                            <button class="btn btn-default" type="button" onclick="location.href = '__URL__/create_qr?fileid=<?php echo ($f["fileid"]); ?>'"><span class="glyphicon glyphicon-qrcode"></span></button>
                        </td>
                        <td id="td-pic"><button class="btn btn-default" type="button" onclick="todel( < {$f.fileid} > )"><span class="glyphicon glyphicon-trash"></span></button></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>

            <!--button组-->
            <div id="bot" style="float:left;">
                <button class="btn btn-primary" type="button" onclick="allselect()">全选</button>
                <button class="btn btn-success" type="button" onclick="downloads()">下载</button>
                <button class="btn btn-info" type="button" onclick="deletes()">删除</button>
                
                <button class="btn btn-warning" type="button" onclick="uploadModalFunc()">上传文件</button>
            </div>

            <!--上传-->
            <div class="modal fade" id="uploadTModal">
                <div class="modal-dialog" style="margin: 200px auto;width: 450px;height: 750px;">
                    <div class="modal-content">
                        <div class="modal-body" style="margin: 20px;">
                            <div>
                                <form class="form-group-md" id="up_form" action="__URL__/upload" method="post" enctype="multipart/form-data">
                                    <input id="folderId" name="folderId" style="display: none;" type="text" value="">
                                    <input class="hidden" id="coverUpload" name="coverUpload" type="text">
                                    <div class="upload-div input-group">
                                        <input class="form-control upload-text" id="text" name="filename" type="text"
                                               value="点击上传文件" onclick="document.getElementById('fil').click()" readonly="readonly">
                                    </div>
                                    <input id="fil" type="file"  name="upfile" style="display:none;"  onChange="document.getElementById('text').value = this.value">
                                    <input class="hidden" id="res" type="reset">
                                    
                                    <!--下拉框-->
                                    <div class="btn-group" id="btnul" style="margin-top:10px;">
                                        <button type="button" class="btn btn-primary dropdown-toggle"  id="btn"
                                                data-toggle="dropdown">
                                            请选择文件夹 <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li value="0"><a href="javascript:;">根目录</a>
                                            <?php $__FOR_START_19066__=1;$__FOR_END_19066__=sizeof($lists);for($i=$__FOR_START_19066__;$i < $__FOR_END_19066__;$i+=1){ if($lists[$i] == 'ul'): ?><ul><?php endif; ?>
                                                <?php if($lists[$i] == '/ul'): if($lists[$i+1] != ''): ?></ul></li>
                                                        <?php else: ?> </ul><?php endif; endif; ?>
                                                <?php if(is_array($lists[$i])): if($lists[$i+1] == 'ul'): ?><li value="<?php echo ($lists[$i][0]); ?>"><a href="javascript:;"><?php echo ($lists[$i][1]); ?></a>
                                                        <?php elseif($lists[$i][1] != '...'): ?>
                                                        <li value="<?php echo ($lists[$i][0]); ?>"><a href="javascript:;"><?php echo ($lists[$i][1]); ?></a></li><?php endif; endif; } ?>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-md btn-primary" type="button" onclick="isUpload()">上传</button>
                            <button class='btn btn-md btn-default' id='' data-dismiss='modal'>取消</button>
                        </div>
                    </div>
                </div>
            </div>

            <!--分页-->
            <div class="fenye jogger pull-right"><?php echo ($show); ?></div>
        </div>

    </div>
</div>

<input type="hidden" value="<?php echo ($parentCode); ?>" id="parentCode"/>
<input type="hidden" value="<?php echo ($dialog_filename); ?>" id="dialog_file"/><!--弹出框二维码图片的名称-->
<!--导入js文件-->
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/zDrag.js"></script>  <!--二维码弹出框-->
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/zDialog.js"></script><!--二维码弹出框-->
<script language="javascript"  type="text/javascript"  src="__PUBLIC__/Js/jquery-1.9.1.js"></script>
<script language="javascript"  type="text/javascript"  src="__PUBLIC__/Js/Admin_Home.js"></script>

<script language="javascript"  type="text/javascript"  src="__PUBLIC__/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script language="javascript"  type="text/javascript"  src="__PUBLIC__/plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

<script src='__PUBLIC__/Js/jquery.velocity.min.js'></script> 
<script src="__PUBLIC__/Js/mtree.js"></script> 
<script type="text/javascript">
//    记忆上次打开的目录
    $(function() {
        var parent = $('#parentCode').val();
        console.log(parent);
        parent = parent.split("-");
        console.log("indexof," + parent[0].indexOf(','));
        if (parent[0].indexOf(',') >= 0) {
            //            ...目录
            console.log("charAt=" + parent[0].charAt(parent[0].length - 1));
            if (parent[0].charAt(parent[0].length - 1) == ',') {
                i0 = parent[0].substr(0, parent[0].length - 1);
                i0 = i0.replace(/,/g, 'a');
                console.log("i0=" + i0);
            } else {
                i0 = parent[0].replace(/,/g, 'a');
                console.log("i0=" + i0);
            }

            for (i = parent.length - 2; i > 0; i--) {
                console.log("parent[i]=" + parent[i]);
                $("#" + parent[i]).click();
            }
            $("#" + i0).click();
        } else {
            //            正常目录
            for (i = parent.length - 2; i >= 0; i--) {
                console.log("parent[i]=" + parent[i]);
                $("#" + parent[i]).click();
            }
        }
        $("#catId").val($('.mtree-active >a').attr("id"));
        folderIsEdit();
        
        //上传文件时，点击文件夹响应
        $("#btnul a").click(function() {
            $('#btn').html($(this).text() + '&nbsp;&nbsp;<span class="caret"></span>');
            $('#folderId').val($(this).parent().attr('value'));
        });
        
        //导航栏更改后，改变folderIcon和id
        $("#navUl a").click(function() {
            //改变将要修改文件目录的id
            $("#catId").val($(this).attr("id"));
            
            folderIsEdit();
        });
        
        // 初始化catId，用于文件目录操作
        $("#catId").val($('.mtree-active').children().eq(0).attr("id"));
        
        //模态框隐藏，初始化文件框和文件目录下拉框
        $('#uploadTModal').on('hidden.bs.modal', function () {
            $('#text').css("border-color","");
            $('#btn').attr("class","btn btn-primary dropdown-toggle");
          });
    });
    
    //点击上传文件按钮，显示上传文件模态框
    function uploadModalFunc(){
        $('#uploadTModal').modal('show');
        $('#text').click();
    }
    
    //检查是否可以上传文件
    function isUpload(){
        if(!$("#folderId").val()){
            $('#btn').attr("class","btn btn-danger dropdown-toggle");
        }
        if($("#text").val()=="点击上传文件"){
            $('#text').css("border-color","red");
        }
        if($("#folderId").val() && $("#text").val()!="点击上传文件"){
            upToDatabase();
        }
    }
    
    //判断文件夹是否可编辑
    function folderIsEdit(){
//        alert($(".mtree-active").children().eq(0).text());
        //text()为...，
        if($(".mtree-active").children().eq(0).text()=="..."){
            $("#folderIcon span").removeClass("folderT").addClass("folderF");
        }else{
            //text()为空，是根目录，根目录只可以创建目录，修改和删除图标置为灰色
//            alert("catId="+$("#catId").val());
            if($("#catId").val()==''){
                $("#folderIcon").children().eq(1).removeClass("folderT").addClass("folderF");
                $("#folderIcon").children().eq(2).removeClass("folderT").addClass("folderF");
            }else{
                $("#folderIcon span").removeClass("folderF").addClass("folderT");
            }
        }
    }
    
    //增加文件夹，folderIcon
    function folderAdd(){
        $("#operation").val("add");
        if($("#folderIcon").children().eq(0).hasClass("folderT")){
            $("#folderDiv").attr("class","show");
            $("#folderName").focus().select();
        }
    }
    
    //修改文件夹，folderIcon
    function folderEdit(){
        $("#operation").val("edit");
        if($("#folderIcon").children().eq(1).hasClass("folderT")){
            $("#folderDiv").attr("class","show");
            // 获取文件夹名
            id=$("#catId").val();
            folder=$("#"+id).text();
            $("#folderName").attr("value",folder).focus().select();
        }
    }
    
    //删除文件夹，folderIcon
    function folderDel(){
        if($("#folderIcon").children().eq(2).hasClass("folderT")){
            var catId=$('#catId').val();
            $.ajax({
                type: "POST",
                url: "__URL__/isDel",
                data: {cat_id: catId},
                success: function(returnData) {
                    if (returnData.status == "1") {
                        //可以删除该文件夹
                        if(confirm("确实要删除'"+returnData.folder+"'文件夹吗")){
                            window.location.href = "__URL__/toDel?catId="+catId;
                        }
                    } else {
                        //不能删除该文件夹的情况，1.有子文件夹；2.文件夹下有文件
                        //返回数据中可添加，不可删除的原因
                        alert("对不起，该文件夹不可删除！");
                    }
                }
            },"json");
        }
    }
    
    //文件夹目录增改，确认按钮操作
    function folderConfirm(){
        if($("#folderName").val()){
            $("#folderForm").submit();
        }else{
//            alert("value="+$("#folderName").css('border-color'));
            $("#folderName").css('border-color',"red");
        }
    }
    
    //取消文件夹增改
    function folderCancel(){
        $("#folderDiv").attr("class","hidden");
        $("#folderName").css('border-color',"");
    }
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