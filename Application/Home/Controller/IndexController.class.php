<?php
namespace Home\Controller;

class IndexController extends HomeController {
    /* 空操作，用于输出404页面 */
    public function _empty() {
        $this->redirect('Index/home');
    }
    
    public function index() {
        $this->assign('current_html', "index");
        $this->display();
    }

    //登陆后跳转的首页
    Public function home() {
//        文件列表的条件
        $where;
        if(IS_POST){
            $where['filename'] = array('like', '%' . $_POST['file_name'] . '%');
            session('searchname',$_POST['file_name'] );
            session('folderId',null);
        }else{
            $where['cat_id'] = 0;
            session('folderId',0);
        }
        
        
        //文件列表的信息
        import("ORG.Util.AjaxFile");
        $file = M('file');
        $count = $file->where($where)->count();
        $page = new \Org\Util\AjaxFile($count, C('LIST_ROWS'), "fileData");
        $page->setConfig('prev', '<');
        $page->setConfig('next', '>');
        $page->setConfig('first', '<<');
        $page->setConfig('last', '>>');
        $page->setConfig('theme', '%first% %upPage% %linkPage% %downPage% %end%');
        $show = $page->show(); //分页模板
//        var_dump($where);
        $files = $file->where($where)->limit($page->firstRow . ',' . $page->listRows)->order('downloads desc,uploadtime desc')->select();
        for ($i = 0; $i < count($files); $i++) {
            if (strlen($files[$i]["filename"]) > C('PC_NAME_LENGTH')) {
                $files[$i]["shortname"] = mb_substr($files[$i]["filename"], 0, C('PC_NAME_LENGTH'), 'utf-8') . "...";
            } else {
                $files[$i]["shortname"] = $files[$i]["filename"];
            }
        }
//        var_dump($files);
//        var_dump($show);
        $this->assign('files', $files);
        $this->assign('page', $show);

//        文件分类树
        F('lists', NULL);
        $this->getlist(0);
        $lists = F('lists');
        array_shift($lists);
        $this->assign('lists', $lists);
//        var_dump($lists);

        $this->assign('searchname', $_POST['searchname']);

        $this->display();
    }

    //获取文件分类树
    public function getlist($parrentcode) {
        //使用快速缓存，保存分类树
        $tree = F('lists');
        //分类树中存入当前目录
        $cat = M('category')->where(array('cat_id' => $parrentcode))->getField('cat_name');
        $tree[] = array($parrentcode, $cat, $parrentcode);
        F('lists', $tree);

        //子目录递归实现,TODO,bug   ->order('cat_name')
        $catids = M('category')->where(array('cat_parent' => $parrentcode))->order('cat_name')->getField('cat_id', true);
//                var_dump($catids);
        if (sizeof($catids) > 0) {
            $tree = F('lists');
            $tree[] = "ul";
            F('lists', $tree);
        }
        for ($i = 0; $i < sizeof($catids); $i++) {
//            echo 'getlist('.$catids[$i].')<br>';
            $this->getlist($catids[$i]);
        }

        //获取...的id集合
        $catchild = M('category')->where(array('cat_id' => $parrentcode))->getfield('cat_child');
        $tree = F('lists');
        if ($catchild != '') {
            $fileids = M('file')->where(array('cat_id' => $parrentcode))->order('filename')->getfield('fileid', true);
//                var_dump($fileids);
            if (sizeof($fileids) > 0) {
                if (sizeof($fileids) == 1) {
                    $fileids[] = 'end';
                }
//                $tree[] = array(implode(",", $fileids), "...", implode("a", $fileids));
            }
            $tree[] = "/ul";
        }
        F('lists', $tree);
    }
    
    //ajax获取文件信息
    public function fileData() {
        $where;
        if(IS_POST){  //普通分页，需保存上一次的文件夹id
            $where['cat_id'] = I('folderId');
            session('folderId',$where['cat_id']);
        }elseif(session('?folderId')){  //主页分页，保存文件夹id为0
            $where['cat_id'] = session('folderId');
            session('folderId',$where['cat_id']);
        }else{  //搜索分页，不保存文件夹id，获取搜索名
            $where['filename']=array('like', '%' . session('searchname') . '%');
            $this->assign('searchname', $where['cat_id']);
        }

        import("ORG.Util.AjaxFile"); // 导入分页类  注意导入的是自己写的AjaxPage类
        $credit = M('file');
        $count = $credit->where($where)->count();
        $limitRows = C('LIST_ROWS'); 

        $p = new \Org\Util\AjaxFile($count, $limitRows, "fileData"); //第三个参数是你需要调用换页的ajax函数名
        $p->setConfig('prev', '<');
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');
        $p->setConfig('theme', '%first% %upPage% %linkPage% %downPage% %end%');
        $page = $p->show(); 
        $this->assign('page', $page);
//        dump($page);
        
        $limit_value = $p->firstRow . "," . $p->listRows;
        $data = $credit->where($where)->order('downloads desc,uploadtime desc')->limit($limit_value)->select(); 
        for ($i = 0; $i < count($data); $i++) {
            if (strlen($data[$i]["filename"]) > C('PC_NAME_LENGTH')) {
                $data[$i]["shortname"] = mb_substr($data[$i]["filename"], 0, C('PC_NAME_LENGTH'), 'utf-8') . "...";
            } else {
                $data[$i]["shortname"] = $data[$i]["filename"];
            }
        }

        $this->assign('files', $data);
        $this->display();
    }

    public function check_filename() {
        $this->assign('current_html', "index");
        $this->display();
    }

    //登录验证
    Public function login() {
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

    //搜索
    Public function search() {
        $where['filename'] = $_POST['file_name'];
        $this->redirect('Index/home', array('searchname' => $where['filename']));
    }
    
    //对上传的文件夹名称进行过滤
    public function folderCheck() {
        $folder['cat_parent']=I('folderId');
        $folder['cat_name']=I('folderName');
        $operation=I("operation");
        if($operation=='add'){
            if(M("category")->where($folder)->find()){
                $data['status']=0;
                $this->ajaxReturn($data);
            }  else {
                $data['status']=1;
                $this->ajaxReturn($data);
            }
        }else{
            $fid=M('category')->where(array('cat_id'=>I('folderId')))->getField('cat_parent');
            $folder['cat_parent']=$fid;
            if(M("category")->where($folder)->find()){
                $data['status']=0;
                $this->ajaxReturn($data);
            }  else {
                $data['status']=1;
                $this->ajaxReturn($data);
            }
        }
        
    }

    //文件夹目录编辑，增改
    public function folder() {
        $operation=$_POST['operation'];
        $catId=$_POST['catId']?$_POST['catId']:0;
        $folderName=$_POST['folderName'];
        $category=M('category');
        if($operation=="add"){
            $data['cat_name']=$folderName;
            $data['cat_parent']=$catId;
            $newId=$category->add($data);
            
            //父目录增加cat_child
            if($catId){
                $catchild=$category->where(array('cat_id' => $catId))->getField('cat_child');
                $category->where(array('cat_id' => $catId))->setField('cat_child',$catchild.$newId.',');
            }
        }
        if($operation=="edit"){
            $category->where(array('cat_id' => $catId))->setField('cat_name',$folderName);
        }
        
        $this->redirect('Index/home');
    }

    //文件夹是否可删除
    public function isDel() {
        $catId = $_POST["cat_id"];
        //先找子目录
        $folder = M('category')->where(array('cat_parent' => $catId))->find();
        //找目录下是否直接有文件
        $files = M('file')->where(array('cat_id' => $catId))->find();
        if (!($folder || $files)) {
            $data['folder'] = M('category')->where(array('cat_id' => $catId))->getField('cat_name');
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $data['status'] = 0;
            $this->ajaxReturn($data);
        }
    }

    //文件夹删除
    public function toDel() {
        $catId = $_GET["catId"];
        $newChild='';
        $category=M('category');
        $parent = $category->where(array('cat_id' => $catId))->getField('cat_parent');
        //删除数据库中的目录
        $category->where(array('cat_id' => $catId))->delete();
        //修改父目录的cat_child，父目录为0不修改
        if($parent){
            $child = $category->where(array('cat_id' => $parent))->getField('cat_child');
            $arrChild = explode(',', $child);
            $key = array_search($catId, $arrChild);
            if ($key !== false){
                array_splice($arrChild, sizeof($arrChild)-1, 1);
                array_splice($arrChild, $key, 1);
            }
            var_dump($arrChild);
            if(sizeof($arrChild)){
                $newChild=  implode(',', $arrChild);
                $newChild=$newChild.',';
    //            echo $newChild;
            }else{
                $newChild='';
            }
            $category->where(array('cat_id' => $parent))->setField('cat_child',$newChild);
        }
        
        $this->redirect('Index/home');
    }

    //判断是否已经上传过
    public function isUpload() {
        $file = M("file");
        $text_name = $_POST["text_name"];

        //检查文件是否已经上传过
        $where['filename'] = $text_name;
        $where['cat_id'] = $_POST["cat_id"];
        
        if ($file->where($where)->find()) {
            $data['status'] = 2;   //状态为2表示上传不成功
            $this->ajaxReturn($data);
        }
    }
    
    //文件上传
    Public function upload() {
        $file = M("file");
        $data;
//        $text_name = htmlspecialchars($_POST["text_name"]);
        $text_name = $_POST["text"];
        $p=strrpos($text_name, '\\');
        $text_name= ($p) ? substr($text_name, $p+1) : $text_name;
        $text_name=  str_replace('—', '_', $text_name);
        
        $p=strrpos($text_name, '.');
        $fold_name= substr($text_name, 0 , $p) ;
        
        $folderId=$_POST['folderId']?$_POST['folderId']:0;
        
        $where['filename'] = $text_name;
        $where['cat_id'] = $folderId;
        $coverUpload= $_POST['coverUpload'];
//        var_dump($where);
        
        //实例化上传文件类
        $up_obj = new \Think\Upload(); // 实例化上传类
        $up_obj->maxSize = 20971520; // 设置附件上传大小,100M
        $up_obj->autoSub=false;  //不保存进子目录
        $up_obj->replace=true;  //存在同名覆盖
        $wh['filename'] = $text_name;
        $up_obj->rootPath  = './Public/Uploads/'; // 设置附件上传目录
        $up_obj->savePath  =  '';  //保存子目录名
        
        $wh['filename'] = $text_name;
        if($file->where($wh)->find()){
            if($file->where($where)->find() && $coverUpload=="cover"){  //覆盖
                $up_obj->saveName = $folderId.'_'.$fold_name;
                $info = $up_obj->upload();
                if (!$info) {
                    $this->error($up_obj->getError());
                }
                
                $size = $_FILES["upfile"]['size'];
                if ($size / 1024 < 1) {
                    $size = $size . "B"; //文件大小小于1KB
                } else {
                    if ($size / 1024 < 1024) {
                        $size = sprintf("%.2f", $size / 1024) . "KB"; //文件大小小于1MB
                    } else {
                        $size = sprintf("%.2f", $size / 1024 / 1024) . "MB"; //文件大小小于1GB
                    }
                }
                if($size=='0B'){
                    $this->error('对不起，文件为空，请重新选择文件！');
                }
                
                $coverData['size'] = $size;
                $coverData['uploadtime'] = date("Y-m-d H:i:s", time());
                $coverData['filerepeat'] = $folderId.'_'.$text_name;
                $whereCover['filename']=$text_name;
                $whereCover['cat_id']=$folderId;
                $file->where($whereCover)->save($coverData);
                $this->redirect('Index/home');
                
            }else{   //同名文件，不同文件夹
                $up_obj->saveName = $folderId.'_'.$fold_name;
                $data['filerepeat'] = $folderId.'_'.$text_name;
                $info = $up_obj->upload();
                if (!$info) {
                    $this->error($up_obj->getError());
                }
                
                $size = $_FILES["upfile"]['size'];
                if ($size / 1024 < 1) {
                    $size = $size . "B"; //文件大小小于1KB
                } else {
                    if ($size / 1024 < 1024) {
                        $size = sprintf("%.2f", $size / 1024) . "KB"; //文件大小小于1MB
                    } else {
                        $size = sprintf("%.2f", $size / 1024 / 1024) . "MB"; //文件大小小于1GB
                    }
                }
                if($size=='0B'){
                    $this->error('对不起，文件为空，请重新选择文件！');
                }
                
                // 保存表单数据 包括附件数据
                $data['filename'] = $text_name;
                $data['filerepeat'] = $folderId.'_'.$text_name;
                $data['size'] = $size;
                $data['uploadtime'] = date("Y-m-d H:i:s", time());
                $data['cat_id'] = $folderId;
                $file->data($data)->add();
                $this->redirect('Index/home');
            
            }
        }else{  //全新的文件
            $up_obj->saveName = $folderId.'_'.$fold_name;
            $info = $up_obj->upload();
            if (!$info) {
                $this->error($up_obj->getError());
            }
            
            $size = $_FILES["upfile"]['size'];
            if ($size / 1024 < 1) {
                $size = $size . "B"; //文件大小小于1KB
            } else {
                if ($size / 1024 < 1024) {
                    $size = sprintf("%.2f", $size / 1024) . "KB"; //文件大小小于1MB
                } else {
                    $size = sprintf("%.2f", $size / 1024 / 1024) . "MB"; //文件大小小于1GB
                }
            }
            if($size=='0B'){
                $this->error('对不起，文件为空，请重新选择文件！');
            }

            // 保存表单数据 包括附件数据
            $data['filename'] = $text_name;
            $data['filerepeat'] = $folderId.'_'.$text_name;
            $data['size'] = $size;
            $data['uploadtime'] = date("Y-m-d H:i:s", time());
            $data['cat_id'] = $folderId;
            $file->data($data)->add();
            $this->redirect('Index/home');
            
        }
    }

//手机下载页面
    public function down() {
//        获取文件名
        $where['fileid'] = $_GET['id'];
        $file=M('file');
        $filename = $file->where($where)->getField('filename');
        $filerepeat = $file->where($where)->getField('filerepeat');
        $filerepeat = $filerepeat? $filerepeat :$filename;
//        获取文件后缀
        $post = explode('.', $name);
        $post = $post[count($post) - 1];

        $this->assign('fileid', $_GET['id']);
        $this->assign('filename', $filename);
        $this->assign('filerepeat', $filerepeat);
        $this->assign('post', $post);
        $this->display();
    }
    
    //手机扫码下载计数
    public function countDown() {
        $where['fileid']=I('fileId');
        //统计下载次数
        $fileM=M('file');
        $n=$fileM->where($where)->getField('downloads');
	$saveData['downloads']=$n+1;
	$fileM->where($where)->save($saveData);
        exit();
    }

    //单个文件下载
    public function download() {
        $where['fileid'] = $_GET['fileid'];
        $fileM=M('file');
        $file_dir = "./Public/Uploads/";        //下载文件存放目录    
        $file_name = $fileM->where($where)->getField('filename');
        $filerepeat = $fileM->where($where)->getField('filerepeat');  //文件名  
        $filerepeat = $filerepeat?$filerepeat:$file_name;
        $filerepeat = iconv("utf-8", "gb2312", $filerepeat);     //文件名 
        $down_name = str_replace(' ', ' ',$file_name);  //去除文件名中的空格,文件下载到本地的名字
        
//        echo $file_name;
        //检查文件是否存在    
        if (!file_exists($file_dir.$filerepeat)) {
            echo "File not found,".$filerepeat;
            exit();
        } else {
            //打开文件    
            $file = fopen($file_dir.$filerepeat, "r");
            //输入文件标签     
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Content-Length: " . filesize($file_dir.$filerepeat));
            Header("Content-Disposition: attachment; filename=\"".$down_name."\"");
            //输出文件内容     
            //读取文件内容并直接输出到浏览器    
            readfile($file_dir.$filerepeat);
            fclose($file);
            
        }
    }

    //批量文件下载
    public function lot_download() {
        $file = M('file');
        $fileid_arr = explode(',', $_GET['fileids']);
//        $zip_name = "neoway.zip";     //下载文件名   
        $zip_name = date('Ymd_His', time()) . rand(0, 9) . ".zip";     //下载文件名  
        $file_dir = "./Public/Uploads/";        //下载文件存放目录    
        $zip = new \ZipArchive();
        if ($zip->open($file_dir . $zip_name, \ZipArchive::OVERWRITE) == true) {//打开压缩包，如果压缩包存在覆盖，不存在新建
            $zip->addEmptyDir('neoway'); //增加一个目录的原因是，如果zip包没东西会一直下载，永不停止
            for ($i = 0; $i < count($fileid_arr); $i++) {
                $file_name = $file->where('fileid=' . $fileid_arr[$i])->getField('filerepeat');
                $file_name = $file_name?$file_name:($file->where('fileid=' . $fileid_arr[$i])->getField('filename'));
                $file_name = iconv("utf-8", "gb2312", $file_name);
                $zip->addFromString("neoway/" . $file_name, file_get_contents($file_dir . $file_name));
            }
        }

        $zip->close();
        //打开文件    
        $file = fopen($file_dir . $zip_name, "r");
        //输入文件标签     
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Content-Length: " . filesize($file_dir . $zip_name));
        Header("Content-Disposition: attachment; filename=" . $zip_name);
        //输出文件内容     
        //读取文件内容并直接输出到浏览器    
        readfile($file_dir . $zip_name);
        fclose($file);
        unlink($file_dir . $zip_name);
        exit();
    }

    //弹出生成的二维码提示框
    public function dialog_qrdownload() {
        $where['fileid'] = $_GET['fileid'];
        $fileM=M('file');
        $file_name = $fileM->where($where)->getField('filename');
        $filerepeat = $fileM->where($where)->getField('filerepeat');  //文件名  
        $filerepeat = $filerepeat?$filerepeat:$file_name;
        $filerepeat=$filerepeat.".png";
        $filerepeat = iconv("utf-8", "gb2312", $filerepeat);     //文件名 
        $down_name = str_replace(' ', ' ',$file_name.".png");  //去除文件名中的空格,文件下载到本地的名字
        
        $file_dir = "./Public/logoQR/";        //下载文件存放目录    
        //检查文件是否存在    
//        echo $file_name;
        if (!file_exists($file_dir . $filerepeat)) {
            echo "File not found".$filerepeat;
            exit();
        } else {
            //打开文件    
            $file = fopen($file_dir . $filerepeat, "r");
            //输入文件标签     
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Content-Length: " . filesize($file_dir . $filerepeat));
            Header("Content-Disposition: attachment; filename=" . $down_name);
            //输出文件内容     
            //读取文件内容并直接输出到浏览器    
            readfile($file_dir . $filerepeat);
            fclose($file);
            exit();
        }
    }

    //单个文件删除
    public function del() {
        $fileid = $_GET['fileid'];
        $filename = M('file')->where('fileid=' . $fileid)->getField('filerepeat');
        $filename = $filename ? $filename : M('file')->where('fileid=' . $fileid)->getField('filename');
        if (M('file')->where('fileid=' . $fileid)->delete()) {
            unlink('./Public/Uploads/' . iconv("utf-8", "gb2312", $filename)); //删除Public/Uploads文件夹下的文件
            unlink('./Public/logoQR/' . iconv("utf-8", "gb2312", $filename) . '.png'); //删除Public/logoQR文件夹下的文件
            $this->redirect('Index/home');
        }
    }

    //批量文件删除
    public function lot_del() {
        $file = M('file');
        $fileids = $_GET['fileids'];
        $id_arr = explode(',', $fileids);
//        dump($id_arr);
        for ($i = 0; $i < count($id_arr); $i++) {
            $fileid = $id_arr[$i];
            $filename = $file->where('fileid=' . $fileid)->getField('filerepeat');
            $filename=$filename?$filename:$file->where('fileid=' . $fileid)->getField('filename');
            if ($file->where('fileid=' . $fileid)->delete()) {
                unlink('./Public/Uploads/' . iconv("utf-8", "gb2312", $filename)); //删除Public/Uploads文件夹下的文件
                unlink('./Public/logoQR/' . iconv("utf-8", "gb2312", $filename) . '.png'); //删除Public/logoQR文件夹下的文件
            }
        }
        $this->redirect('Index/home');
    }

    //生成二维码
    public function create_qr() {
        include 'Public/plugins/phpqrcode.php';
//        $ip = "192.168.1.103";
        $ip = "218.244.134.57";
        $fileM = M('file');
        $fileid = $_POST['fileid'];
        $file_name = $fileM->where(array('fileid'=>$fileid))->getField('filename');
        $filerepeat = M('file')->where('fileid =' . $fileid)->getField('filerepeat');
        $filerepeat = $filerepeat?$filerepeat:$file_name;
        $filerepeat = iconv('utf-8', 'gb2312', $filerepeat); //文件名改为gb312格式，生成二维码
        $content = "http://" . $ip . "/qrcode3/index.php/Home/Index/down?id=" . $fileid;

        $qr_pic = 'Public/QRcode/' . $filerepeat . '.png';
        //生成原始不带logo的二维码
        \QRcode::png($content, $qr_pic, "M", 6, 3);
        $logo = "Public/Images/qrlogo.png";
        $QR = $qr_pic;
        //合成带logo的二维码
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR); //二维码图片宽度   
            $QR_height = imagesy($QR); //二维码图片高度   
            $logo_width = imagesx($logo); //logo图片宽度   
            $logo_height = imagesy($logo); //logo图片高度   
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小   
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        //输出图片   
        imagepng($QR, 'Public/logoQR/' . $filerepeat . '.png');
        $dialog_filename = iconv('gb2312', 'utf-8', $filerepeat) . '.png'; //文件名改回utf-8格式，输出在页面上
        $this->assign('dialog_filename', $dialog_filename);

//        $data['status']=1;
        $data['dialog_fileid']=$fileid;
        $data['dialog_filename']=$dialog_filename;
        $data['dialog_displayname']=$file_name. '.png';
        $this->ajaxReturn($data);
    }

    //注销
    Public function logoff() {
        //登陆后跳转到index页面
        session('userid', null);
        session('username', null);
        $this->redirect('Index/index');
    }
}