<?php
namespace Admin\Controller;
class ProjectController extends CommonController{
    public function index(){
        
        //获取参数
        $n = I('get.n');
        $k = I('get.k');
        $num = !empty($n) ? $n : 10;//每页显示的数量
        $keyword = !empty($k) ? $k : '';//检索的关键字
        if($keyword != ''){
            $where['product_name']=array('like',"%{$keyword}%");
        }
        
        //礼品兑换记录
        $Shuju = M('Shuju','yunying_','DB_CONFIG2');
        //读取总的条数
        $count = $Shuju->where($where)->count();
        //创建分页对象
        $page = new \Think\Page($count,$num);
        //获取limit参数
        $limit=$page->firstRow.','.$page->listRows;
        
        $info = $Shuju->where($where)->limit($limit)->select();
        
        //获取页码显示的信息字符串
        $pages = $page->show();
        
        
        $this->assign('k',$k);
        $this->assign('info',$info);
        $this->assign('count',$count);
        $this->assign('pages',$pages);
        
        $this->display();
    }
    // 项目信息添加
    public function add(){
        
        if (IS_POST){
            
            $Shuju = M('Shuju','yunying_','DB_CONFIG2');
            
            $data = $_POST;
            if ($data['announcement_date'] == ''){
                $data['announcement_date'] = '0000-00-00';
            }
            if ($data['product_publish'] == ''){
                $data['product_publish'] = '0000-00-00';
            }
            if ($data['over_date'] == ''){
                $data['over_date'] = '0000-00-00';
            }
            
            // 记录管理员账号及时间
            
            $data['aid'] = session('id'); 
            
            $data['add_time'] = time();
            
            if ($Shuju->data($data)->add()){
                $this->success('添加成功',U('Admin/Project/index'),1);
            }
            else {
                $this->error('添加失败',$_SERVER['HTTP_REFERER'],1);
            }
            exit();
        }
        else {
            $this->display();
        }
        
    }
    // 项目信息维护
    public function edit(){
        $Shuju = M('Shuju','yunying_','DB_CONFIG2');
        if (IS_POST){
            $id = I("post.id");
            $data = $_POST;
            if ($data['announcement_date'] == ''){
                $data['announcement_date'] = '0000-00-00';
            }
            if ($data['product_publish'] == ''){
                $data['product_publish'] = '0000-00-00';
            }
            if ($data['over_date'] == ''){
                $data['over_date'] = '0000-00-00';
            }
            unset($data['id']);
            $result = $Shuju->where("id = '{$id}'")->data($data)->save();
            if ($result || $result == 0){
                $this->success('维护数据成功',U('Admin/Project/index'),1);
            }
            else {
                $this->error('维护数据失败',$_SERVER['HTTP_REFERER'],1);
            }
            exit();
        }
        else {
            // 实例化项目数据表
            $id = I("get.id");
            $info = $Shuju->where("id = '{$id}'")->find();
            $this->assign('info',$info);
            $this->display();
        }
    }
    public function progress(){
        $this->display();
    }
}