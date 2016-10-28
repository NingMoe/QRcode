$().ready(function() {
    
        
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
        window.location.href = "/qrcode3/index.php/Home/Index/del?fileid=" + fid;
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
            window.location.href = "/qrcode3/index.php/Home/Index/lot_download?fileids=" + ids.join(',');
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
            window.location.href = "/qrcode3/index.php/Home/Index/lot_del?fileids=" + ids.join(',');
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
