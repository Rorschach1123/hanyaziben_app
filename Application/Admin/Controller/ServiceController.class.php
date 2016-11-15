<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class ServiceController extends CommonController {
    // 发行服务热线列表
    public function index(){
        //获取参数
        $n = I('get.n');
        $k = I('get.k');
        $num = !empty($n) ? $n : 10;//每页显示的数量
        $keyword = !empty($k) ? $k : '';//检索的关键字
        if($keyword != ''){
            // 按照问题进行查询
            $where['question']=array('like',"%{$keyword}%");
        }
        // 实例化服务表
        $Service = M('cw_service');
        //读取总的条数
        $count = $Service->where($where)->count();
        //创建分页对象
        $page = new \Think\Page($count,$num);
        //获取limit参数
        $limit=$page->firstRow.','.$page->listRows;
        
        $info = $Service->where($where)->order("order_id asc")->limit($limit)->select();
        
        //获取页码显示的信息字符串
        $pages = $page->show();
        
        // 分配变量
        $this->assign('k',$k);
        $this->assign('count',$count);
        $this->assign('info',$info);
        $this->assign('pages',$pages);
        
        $this->display();
    }
    // 服务问答添加
    public function add(){
        if (IS_POST){
            $data = I("post.");
            
            $data['date'] = date("Y-m-d",time());
            $Service = M('cw_service');
            $result = $Service->data($data)->add();
            
            if ($result){
                $this->success('问答添加成功',U("Admin/Service/index"),1);
            }
            else {
                $this->error("问答添加失败",$_SERVER['HTTP_REFERER'],1);
            }
            exit();
        }
        else {
            $this->display();
        }
    }
    // 服务问答修改    
    public function edit(){
        if (IS_POST){
            $data = I("post.");
            $Service = M("cw_service");
            $result = $Service->data($data)->save();
            if ($result == 0 || $result){
                $this->success("问答修改成功",U("Admin/Service/index"));
            }
            else {
                $this->error("问答修改失败",$_SERVER['HTTP_REFERER'],1);
            }
            exit();
        }
        else {
            $id = I("get.id");
            $Service = M("cw_service");
            $info = $Service->where("id = '{$id}'")->find();
            $this->assign("info",$info);
            $this->display();
        }
        
    }
    public function order(){
        if (IS_POST){
            $ids = I("post.ids");
            $order_ids = I("post.order_ids");
            $idArr = explode(",",$ids);
            $order_idArr = explode(",",$order_ids);
            $Service = M("cw_service");
            for ($i=0;$i<count($idArr);$i++){
                $data['id'] = $idArr[$i];
                $data['order_id'] = $order_idArr[$i];
                $result = $Service->data($data)->save();
                if ($result == 0 || $result){
                    $msg = "排序成功";
                }
                else {
                    $msg = "排序失败";
                }
            }
            $this->ajaxReturn($msg);
        }
        else {
            $this->show("此页面无内容");
        }
    }
}