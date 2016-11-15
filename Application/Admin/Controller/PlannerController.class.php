<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class PlannerController extends CommonController{
    public function index(){
        
        $n = I('get.n');
        $k = I('get.k');
        $num = !empty($n) ? $n : 10;//每页显示的数量
        $keyword = !empty($k) ? $k : '';//检索的关键字
        if($keyword != ''){
            $where['name']=array('like',"%{$keyword}%");
        }
        
        $planner = M('cw_planner');
        
        //读取总的条数
        $count = $planner->where($where)->count();
        //创建分页对象
        $page = new \Think\Page($count,$num);
        //获取limit参数
        $limit=$page->firstRow.','.$page->listRows;
        
        $info = $planner -> where($where) -> order('is_show desc,order_id asc') -> limit($limit) -> select();
        
        //获取页码显示的信息字符串
        $pages = $page->show();
        
        $this -> assign('count',$count);
        
        $this -> assign('pages',$pages);
        
        $this -> assign('info',$info);
        
        $this -> display();
    }
    public function add(){
        if (IS_POST){
            
            $data = I("post.");
            
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/'; // 设置附件上传根目录
            $upload->savePath  =      '/Uploads/Images/planner/'; // 设置附件上传（子）目录
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['head']);
            if(!$info) {// 上传错误提示错误信息
            
                //                 $this->error($upload->getError());
                $this->show('没有图片被上传');
            }else{// 上传成功 获取上传文件信息
                $url = $info['savepath'].$info['savename'];
                $data['image'] = $url;
            }
            $result = M('cw_planner')->data($data)->add();
            if ($result){
                $this->success('明星理财师添加成功',U('Admin/Planner/index'),1);
            }
            else {
                $this->error('明星理财师添加失败',$_SERVER['HTTP_REFERER'],1);
            }
        }
        else {
            $this->display();
        }
    }
    public function edit(){
        if (IS_POST){
            
            $data = I("post.");
            
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/'; // 设置附件上传根目录
            $upload->savePath  =      '/Uploads/Images/planner/'; // 设置附件上传（子）目录
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['head']);
            if(!$info) {// 上传错误提示错误信息
    
                //                 $this->error($upload->getError());
                $this->show('没有图片被上传');
            }else{// 上传成功 获取上传文件信息
                $url = $info['savepath'].$info['savename'];
                $data['image'] = $url;
            }
            $result = M('cw_planner')->data($data)->save();
            if ($result || $result == 0){
                $this->success('明星理财师修改成功',U('Admin/Planner/index'),1);
            }
            else {
                $this->error('明星理财师修改失败',$_SERVER['HTTP_REFERER'],1);
            }
        }
        else {
            $id = I('get.id');
            $info = M('cw_planner')->where("id = '{$id}'")->find();
            $this->assign('info',$info);
            $this->display();
        }
    }
}