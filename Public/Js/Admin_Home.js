window.onload = function() {
    var dia_shortfile;
    dia_file = document.getElementById('dialog_file').value;
    if (dia_file) {
        var diag = new Dialog();
        diag.Width = 300;
        diag.Height = 260;
        diag.Title = "下载二维码图片吗？";
        //截取文件名
        if (dia_file.length > 10) {
            dia_shortfile = dia_file.substr(0, 10) + "...";
        } else {
            dia_shortfile = dia_file;
        }

        diag.InnerHtml = "<img src='/qrcode/Public/logoQR/" + dia_file + "' alt='二维码'/><br><span style='font-size:18px;color:#0055FF' title='" + dia_file + "'>" + dia_shortfile + "</span>";
        diag.OKEvent = function() {
            window.location.href = "/qrcode/index.php/Index/dialog_qrdownload?filename=" + dia_file;
            setTimeout('window.history.back();', 100);
            diag.close();
        }; //点击确定后调用的方法
        diag.CancelEvent = function() {
            window.history.back();
        }
        diag.show();
//            setTimeout('window.location.href="/qrcode/index.php/Index/home";',1000);
    }
}
$().ready(function() {
    //上传前判断是否已经上传过
    $('#up_form').submit(function(event) {
        event.preventDefault();
        t = $('#text').val();
        //去除名称中的文件路径
        p = t.lastIndexOf('\\');
        t = (p != -1) ? t.substr(p + 1) : t;
        t = t.replace('—', '_');
        $.post("/qrcode/index.php/Index/upload", {text_name: t}, function(msg) {
            if (msg.status == 2) {   //文件已经上传过
                // 文件末尾自动添加（2）等字符
//                $('#modalBodyText').html(msg.info);
//                $('#countDown').html(5);
//                $('#uploadModal').modal('show');
//                $("#modalFButton").attr("class", "btn btn-sm btn-default");
//
//                fBtn = 0;
//                $('#modalFButton').click(function() {
//                    fBtn = 1;
//                    $('#uploadModal').modal('hide');
//                    $('#res').click();
//
//                });
//                interval = setInterval("var n=parseInt($('#countDown').html());n--;$('#countDown').html(n)", 1000);
//                setTimeout("$('#uploadModal').modal('hide');clearInterval(interval);if(fBtn==0){$('#modalButton').click();}", 5000);
//                $('#countDown').html(5);
//                $('#modalButton').click(function() {
//                    document.getElementById("text").value = msg.data;
//                });

                //覆盖上传
                if(confirm("确定要覆盖原上传文件吗？")){
                    //标记，是覆盖上传
                    $("#coverUpload").val("cover");
                    
                    document.getElementById('up_form').submit();
                }
            } else {
                //文件没有上传过，继续上传
                document.getElementById('up_form').submit();
            }
        });
    })
});
function upToDatabase() {
    var txt = document.getElementById("text").value;
    if (txt && txt != "点击上传文件") {
        //上传，写入数据库
//            document.getElementById('up_form').submit();
        var strtest = /[~!@#$^&*=|{}';,<>~！@#￥……*（）—【】‘；：”“'。，、？]/;
        if (strtest.test(txt)) {
            alert("文件名请不要包含特殊字符！");
        } else {
            $('#up_form').submit();
        }

    } else {
        $('#modalBodyText').html("请选择文件后，再上传！");
        $('#countDown').html(2);
        $('#uploadModal').modal('show');
        $("#modalFButton").attr("class", "btn btn-sm btn-default hidden");

        interval = setInterval("var n=parseInt($('#countDown').html());n--;$('#countDown').html(n)", 1000);
        setTimeout("$('#uploadModal').modal('hide');clearInterval(interval);", 2000);
        $('#countDown').html(2);
    }
}
function todel(fid) {
    if (confirm("确定删除吗？")) {
        window.location.href = "/qrcode/index.php/Index/del?fileid=" + fid;
    }
}
function allselect() {
    //选中全部
    chec = document.getElementsByName("che");
    tag = 1; //标志位为1表示已经全选
    for (i = 0; i < chec.length; i++) {
        if (chec[i].checked == false)
        {
            tag = 0;
            break;
        }
    }
    if (tag) {
        //1.已经全选时点击，全不选
        for (i = 0; i < chec.length; i++) {
            chec[i].checked = false;
        }
        document.getElementById("all").checked = false;
    } else {
        //2.非全选时，点击选中全部
        for (i = 0; i < chec.length; i++) {
            if (chec[i].checked == false) {
                chec[i].checked = true;
            }
        }
    }
}
function getids() {
    //获取选中的id集合
    chec = document.getElementsByName("che");
    var ids = new Array();
    for (i = 0; i < chec.length; i++) {
        if (chec[i].checked == true) {
            ids.push(chec[i].id);
        }
    }
    return ids;
}
function downloads() {
    ids = getids();
    if (ids.length) {
        //下载选中的文件
        if (confirm("确认下载所选项吗？")) {
            window.location.href = "/qrcode/index.php/Index/lot_download?fileids=" + ids.join(',');
        }
    } else {
        alert("请至少选中一项后，再进行操作！");
    }
}
function deletes() {
    //删除选中的文件
    ids = getids();
    if (ids.length) {
        if (confirm("确认删除所选项吗？")) {
            window.location.href = "/qrcode/index.php/Index/lot_del?fileids=" + ids.join(',');
        }
    } else {
        alert("请至少选中一项后，再进行操作！");
    }
}
function is_all() {
    //检查是否全选，决定全选复选框是否true
    chec = document.getElementsByName("che");
    all_tag = 1; //标志位为1表示全选
    for (i = 0; i < chec.length; i++) {
        if (chec[i].checked == false) {
            all_tag = 0;
            break;
        }
    }
    if (all_tag) {
        document.getElementById("all").checked = true;
    } else {
        document.getElementById("all").checked = false;
    }
}
