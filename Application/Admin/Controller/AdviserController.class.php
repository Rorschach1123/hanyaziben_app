<?php
namespace Admin\Controller;
class AdviserController extends CommonController{
    public function index(){
        //获取参数
        $n = I('get.n');
        $k = I('get.k');
        $num = !empty($n) ? $n : 10;//每页显示的数量
        $keyword = !empty($k) ? $k : '';//检索的关键字
        if($keyword != ''){
            $where['phone_number']=array('like',"%{$keyword}%");
        }
        $where['check_status'] = 0;
        // 创建对象
        $Adviser = M('ad_user');
        //读取总的条数
        $count = $Adviser->where($where)->count();
        //创建分页对象
        $page = new \Think\Page($count,$num);
        //获取limit参数
        $limit=$page->firstRow.','.$page->listRows;
        //读取当前显示的数据
        $advisers = $Adviser->where($where)->order('id desc')->limit($limit)->select();
        
        //获取页码显示的信息字符串
        $pages = $page->show();
        //分配变量
        $this->assign('n',$n);
        $this->assign('k',$k);
        $this->assign('count',$count);
        $this->assign('advisers',$advisers);
        $this->assign('pages',$pages);
        
        $this->display();
    }
    public function real(){
        //获取参数
        $n = I('get.n');
        $k = I('get.k');
        $num = !empty($n) ? $n : 10;//每页显示的数量
        $keyword = !empty($k) ? $k : '';//检索的关键字
        if($keyword != ''){
            $where['phone_number']=array('like',"%{$keyword}%");
        }
        // 创建对象
        $Real_user = M('ad_real_user');
        //读取总的条数
        $count = $Real_user->where($where)->count();
        //创建分页对象
        $page = new \Think\Page($count,$num);
        //获取limit参数
        $limit=$page->firstRow.','.$page->listRows;
        //读取当前显示的数据
        $real_users = $Real_user->where($where)->order("id desc")->limit($limit)->select();
    
        //获取页码显示的信息字符串
        $pages = $page->show();
        //分配变量
        $this->assign('n',$n);
        $this->assign('k',$k);
        $this->assign('count',$count);
        $this->assign('real',$real_users);
        $this->assign('pages',$pages);
    
        $this->display();
    }
    public function pass(){
        $id = I('get.id');
        $Adviser = M('ad_user');
        $adviser = $Adviser->where("id = '{$id}'")->find();
        $adviser['pass_date'] = $adviser['registration_date'];
        unset($adviser['registration_date']);
        if(M('ad_real_user')->add($adviser)){
            $saveini['check_status'] = 1;
            $Adviser->where("id = '{$id}'")->save($saveini);
            $this->success('审核成功',$_SERVER['HTTP_REFERER'],1);
        }
        else {
            $this->fail('审核失败',$_SERVER['HTTP_REFERER'],1);
        }
        
    }
    public function not_pass(){
        $id = I('get.id');
        $Adviser = M('ad_user');
        $saveini['check_status'] = 3;
        if($Adviser->where("id = '{$id}'")->save($saveini)){
            $this->success('退回成功',$_SERVER['HTTP_REFERER'],1);
        }
        else {
            $this->fail('退回失败',$_SERVER['HTTP_REFERER'],1);
        }
    }
}