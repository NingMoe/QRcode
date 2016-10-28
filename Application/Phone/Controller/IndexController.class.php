<?php

namespace Phone\Controller;

use Think\Controller;

class IndexController extends Controller {

    /* 空操作，用于输出404页面 */
    public function _empty() {
        $this->redirect('Index/index');
    }
    //网站初始化，读取配置信息
    protected function _initialize() {
        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置
        if (!C('WEB_SITE_CLOSE')) {
            $this->error('站点已经关闭，请稍后访问~');
        }
    }
    
    public function index() {
        $this->assign('current_html', "index");
        $this->display();
    }

    //登陆后跳转的首页
    Public function home() {
//        文件列表的条件
        $where['filename'] = array('like', '%' . $_GET['searchname'] . '%');

//        文件分类树
        F('lists', NULL);
        $this->getlist(0);
        $lists = F('lists');
        array_shift($lists);
        $this->assign('lists', $lists);
//        var_dump($lists);

        $this->assign('searchname', $_GET['searchname']);
        $this->display();
    }

    //获取文件分类树
    public function getlist($parrentcode) {
        //使用快速缓存，保存分类树
        $tree = F('lists');
        //分类树中存入当前目录
        $cat = M('category')->where(array('cat_id' => $parrentcode))->getField('cat_name');
        $tree[] = array($parrentcode, $cat);
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
            //获取文件信息集合
            $file = M('file');
            $count = $file->where(array('cat_id' => $parrentcode))->count();
//            dump($count);
            
            //ajax获取数据
            $pageList =new \Org\Util\AjaxPageTree($count, 10, "ajaxDataFuncTree",$parrentcode);
            $pageList->setConfig('prev', '<');
            $pageList->setConfig('next', '>');
            $pageList->setConfig('first', '<<');
            $pageList->setConfig('last', '>>');
            $pageList->setConfig('theme', '%first% %upPage% %linkPage% %downPage% %end%');
            $show = $pageList->show();
            
            $files = $file->where(array('cat_id' => $parrentcode))->limit($pageList->firstRow . ',' . $pageList->listRows)->order('downloads desc,uploadtime desc')->select();
            for ($i = 0; $i < count($files); $i++) {
                if (strlen($files[$i]["filename"]) > C('PHONE_NAME_LENGTH')) {
                    $files[$i]["shortname"] = mb_substr($files[$i]["filename"], 0, C('PHONE_NAME_LENGTH'), 'utf-8') . "...";
                } else {
                    $files[$i]["shortname"] = $files[$i]["filename"];
                }
            }
//            $files = M('file')->where(array('cat_id' => $parrentcode))->order('filename')->select();
//                var_dump($fileids);
            if (sizeof($files) > 0) {
                $tree[] = array($files, "...",$show,$parrentcode);
            }
            $tree[] = "/ul";
        }
        F('lists', $tree);
    }

    //ajax获取分页数据
    public function ajaxData() {
        $where['cat_id'] = I('catId');
        $this->assign('catId', $where['cat_id']);

        import("ORG.Util.AjaxPage"); // 导入分页类  注意导入的是自己写的AjaxPage类
        $credit = M('file');
        $count = $credit->where($where)->count(); //计算记录数
        $limitRows = 10; // 设置每页记录数

        $p = new \Org\Util\AjaxPage($count, $limitRows, "ajaxDataFunc"); //第三个参数是你需要调用换页的ajax函数名
        $p->setConfig('prev', '<');
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');
        $p->setConfig('theme', '%first% %upPage% %linkPage% %downPage% %end%');
        $page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
        $this->assign('page', $page);
//        dump($page);
        
        $limit_value = $p->firstRow . "," . $p->listRows;
        $data = $credit->where($where)->order('downloads desc,uploadtime desc')->limit($limit_value)->select(); // 查询数据
        for ($i = 0; $i < count($data); $i++) {
            if (strlen($data[$i]["filename"]) > C('PHONE_NAME_LENGTH')) {
                $data[$i]["shortname"] = mb_substr($data[$i]["filename"], 0, C('PHONE_NAME_LENGTH'), 'utf-8') . "...";
            } else {
                $data[$i]["shortname"] = $data[$i]["filename"];
            }
        }
        
        $this->assign('ajaxList', $data);
        $this->display();
    }
    
    //ajaxTree获取分页数据
    public function ajaxDataTree() {
        $where['cat_id'] = I('catId');
        $this->assign('catId', $where['cat_id']);

        import("ORG.Util.AjaxPageTree"); // 导入分页类  注意导入的是自己写的AjaxPage类
        $credit = M('file');
        $count = $credit->where($where)->count(); //计算记录数
        $limitRows = 10; // 设置每页记录数

        $p = new \Org\Util\AjaxPageTree($count, $limitRows, "ajaxDataFuncTree",$where['cat_id']); //第三个参数是你需要调用换页的ajax函数名
        $p->setConfig('prev', '<');
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');
        $p->setConfig('theme', '%first% %upPage% %linkPage% %downPage% %end%');
        $page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
        $this->assign('page', $page);
//        var_dump($page);
        
        $limit_value = $p->firstRow . "," . $p->listRows;
        $data = $credit->where($where)->order('downloads desc,uploadtime desc')->limit($limit_value)->select(); // 查询数据
        for ($i = 0; $i < count($data); $i++) {
            if (strlen($data[$i]["filename"]) > C('PHONE_NAME_LENGTH')) {
                $data[$i]["shortname"] = mb_substr($data[$i]["filename"], 0, C('PHONE_NAME_LENGTH'), 'utf-8') . "...";
            } else {
                $data[$i]["shortname"] = $data[$i]["filename"];
            }
        }
        
        $this->assign('ajaxList', $data);
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

    //第一次搜索
    Public function firstsearch() {
        $where['filename'] = array('like', '%' . I('searchname') . '%');
        $this->assign('searchname', I('searchname'));

        import("ORG.Util.AjaxPage"); // 导入分页类  注意导入的是自己写的AjaxPage类
        $credit = M('file');
        $count = $credit->where($where)->count(); //计算记录数
        $limitRows = 10; // 设置每页记录数

        $p = new \Org\Util\AjaxPage($count, $limitRows, "ajaxSearch"); //第三个参数是你需要调用换页的ajax函数名
        $p->setConfig('prev', '<');
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');
        $p->setConfig('theme', '%first% %upPage% %linkPage% %downPage% %end%');
        $page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
        $this->assign('page', $page);
//        dump($page);
        
        $limit_value = $p->firstRow . "," . $p->listRows;
        $data = $credit->where($where)->order('downloads desc,uploadtime desc')->limit($limit_value)->select(); // 查询数据
        for ($i = 0; $i < count($data); $i++) {
            if (strlen($data[$i]["filename"]) > C('PHONE_NAME_LENGTH')) {
                $data[$i]["shortname"] = mb_substr($data[$i]["filename"], 0, C('PHONE_NAME_LENGTH'), 'utf-8') . "...";
            } else {
                $data[$i]["shortname"] = $data[$i]["filename"];
            }
        }
        
        $this->assign('ajaxList', $data);
        $this->display();
    }
    
    //搜索
    Public function search() {
        $where['filename'] = array('like', '%' . I('searchname') . '%');
        $this->assign('searchname', I('searchname'));

        import("ORG.Util.AjaxPage"); // 导入分页类  注意导入的是自己写的AjaxPage类
        $credit = M('file');
        $count = $credit->where($where)->count(); //计算记录数
        $limitRows = 10; // 设置每页记录数

        $p = new \Org\Util\AjaxPage($count, $limitRows, "ajaxSearch"); //第三个参数是你需要调用换页的ajax函数名
        $p->setConfig('prev', '<');
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');
        $p->setConfig('theme', '%first% %upPage% %linkPage% %downPage% %end%');
        $page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
        $this->assign('page', $page);
//        dump($page);
        
        $limit_value = $p->firstRow . "," . $p->listRows;
        $data = $credit->where($where)->order('downloads desc,uploadtime desc')->limit($limit_value)->select(); // 查询数据
        for ($i = 0; $i < count($data); $i++) {
            if (strlen($data[$i]["filename"]) > C('PHONE_NAME_LENGTH')) {
                $data[$i]["shortname"] = mb_substr($data[$i]["filename"], 0, C('PHONE_NAME_LENGTH'), 'utf-8') . "...";
            } else {
                $data[$i]["shortname"] = $data[$i]["filename"];
            }
        }
        
        $this->assign('ajaxList', $data);
        $this->display();
    }

    //单个文件下载
    public function download() {
        $where['fileid']=$_GET['fileid'];
        $fileM=M('file');
        $file_name = $fileM->where($where)->getField('filerepeat');      //下载文件名    
        $file_name = $file_name?$file_name:($fileM->where($where)->getField('filename'));
        $file_name = iconv("utf-8", "gb2312", $file_name);     //文件名 
        $file_dir = "./Public/Uploads/";        //下载文件存放目录    
        $down_name = str_replace(' ', ' ',$fileM->where($where)->getField('filename')); 
//        echo $file_name;
        //检查文件是否存在    
        if (!file_exists($file_dir . $file_name)) {
            echo "File not found";
            exit();
        } else {
            //打开文件    
            $file = fopen($file_dir . $file_name, "r");
            //输入文件标签     
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Content-Length: " . filesize($file_dir . $file_name));
            Header("Content-Disposition: attachment; filename=\"" . $down_name . "\"");
            //读取文件内容并直接输出到浏览器    
            readfile($file_dir . $file_name);
            fclose($file);
        }
        //统计下载次数
        $n=$fileM->where($where)->getField('downloads');
	$saveData['downloads']=$n+1;
	$fileM->where($where)->save($saveData);
        exit();
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

    //在线阅读pdf
    public function pdfRead() {
        $where['fileid']=I("fileid");
        $file=M('file');
        $filename=$file->where($where)->getField('filename');
        $filerepeat=$file->where($where)->getField('filerepeat');
        $filerepeat=$filerepeat?$filerepeat:$filename;
        $this->assign('filename',$filename);
        $this->assign('filerepeat',$filerepeat);
        
        //统计阅读次数
        $file->where($where)->setInc('readpdfs',1);
        $this->display();
    }

    //注销
    Public function logoff() {
        //登陆后跳转到index页面
        session('userid', null);
        session('username', null);
        $this->redirect('Index/index');
    }

}
