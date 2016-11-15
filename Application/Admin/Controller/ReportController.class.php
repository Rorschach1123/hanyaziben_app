<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class ReportController extends CommonController{
    public function index(){
        //获取参数
        $n = I('get.n');
        $k = I('get.k');
        $num = !empty($n) ? $n : 10;//每页显示的数量
        $keyword = !empty($k) ? $k : '';//检索的关键字
        if($keyword != ''){
            $where['username']=array('like',"%keyword%");
        }
        
        //每日播报数据实例化
        $Report = M('cw_report');
        //读取总的条数
        $count = $Report->where($where)->count();
        //创建分页对象
        $page = new \Think\Page($count,$num);
        //获取limit参数
        $limit=$page->firstRow.','.$page->listRows;
        
        $info = $Report->where($where)->order("id desc")->limit($limit)->select();
        
        //获取页码显示的信息字符串
        $pages = $page->show();
        
        //         echo '<pre>';
        //         print_r($info);
        //         exit();
        $this->assign('n',$n);
        $this->assign('info',$info);
        $this->assign('count',$count);
        $this->assign('pages',$pages);
        
        $this->display();
    }
    // 每日播报添加
    public function add(){
        if (IS_POST){
            
            $data = I("post.");
            
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/'; // 设置附件上传根目录
            $upload->savePath  =      '/Uploads/Images/report/'; // 设置附件上传（子）目录
            // 上传文件 
            $info   =   $upload->upload();
            if(!$info) {// 上传错误提示错误信息
//                 $this->error($upload->getError());
            }else{// 上传成功 获取上传文件信息
                foreach($info as $key=>$file){
                    $new_key = 'image'.($key+1);
                    $data[$new_key] = 'http://app.hanyalicai.com/Public'.$file['savepath'].$file['savename'];
                }
            }
            if ($data['report_date']==''){
                $data['report_date']=='0000-00-00';
            }
            
            // 记录管理员账号及时间
            
            $data['aid'] = session('id');
            
            $data['add_time'] = time();
            
            $result = M('cw_report')->data($data)->add();
            if ($result || $result == 0){
                $this->success('上传数据成功',U('Admin/Report/index'),1);
            }
            else {
                $this->error('上传数据失败',$_SERVER['HTTP_REFERER'],1);
            }
            exit();
        }
        else {
            $this->display();
        }
        
    }
    public function edit(){
        if (IS_POST){
            $id = I('post.id');
            $data = I("post.");
            $Report = M('cw_report');
            $report = $Report->where("id = '{$id}'")->field("image1,image2,image3,image4")->find();
            $image1 = $report['image1'];
            $image2 = $report['image2'];
            $image3 = $report['image3'];
            $image4 = $report['image4'];
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/'; // 设置附件上传根目录
            $upload->savePath  =      '/Uploads/Images/report/'; // 设置附件上传（子）目录
            // 上传文件
            $info   =   $upload->upload();
            if(!$info) {// 上传错误提示错误信息
//                 $this->error($upload->getError());
                // 如果没有文件被上传，则读取数据库中的文件路径
                for ($i = 0;$i < 4;$i++){
                    $new_key = 'image'.($i+1);
                    $data[$new_key] = $report[$new_key];
                }
                
            }else{// 上传成功 获取上传文件信息
                foreach($info as $key=>$file){
                    $new_key = 'image'.($key+1);
                    if ($file[$key] != ''){
                        $data[$new_key] = $report[$new_key];
                    }
                    else {
                        $data[$new_key] = 'http://app.hanyalicai.com/Public'.$file['savepath'].$file['savename'];
                    }
                }
                
            }
            $result = M('cw_report')->data($data)->save();
            if ($result || $result == 0){
                $this->success('修改数据成功',U('Admin/Report/index'),1);
            }
            else {
                $this->success('修改数据失败',$_SERVER['HTTP_REFERER'],1);
            }
        }
        else {
            $id = I("get.id");
            $Report = M('cw_report');
            $info = $Report->where("id = '{$id}'")->find();
            $this->assign('info',$info);
            $this->display();
        }
    }
}