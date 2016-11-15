<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class InformationController extends CommonController{
    public function index(){
        // 首页最新资讯
        if (IS_POST){
            $data = I("post.");
            $data['id'] = 1;
            $result = M("cw_information")->data($data)->save();
            if ($result || $result == 0){
                $this->success('资讯更新成功',U('Admin/Information/index'),1);
            }
            else {
                $this->error('资讯更新失败',$_SERVER['HTTP_REFERER'],1);
            }
        }
        else {
            $info = M('cw_information')->where("type = 'index'")->find();
            $this->assign('info',$info);
            $this->display();
        }
    }
    public function adviser(){
        // 投顾之家资讯
        if (IS_POST){
            $data = I("post.");
            $data['id'] = 2;
            $result = M("cw_information")->data($data)->save();
            if ($result || $result == 0){
                $this->success('资讯更新成功',U('Admin/Information/adviser'),1);
            }
            else {
                $this->error('资讯更新失败',$_SERVER['HTTP_REFERER'],1);
            }
        }
        else {
            $info = M('cw_information')->where("type = 'adviser'")->find();
            $this->assign('info',$info);
            $this->display();
        }
    }
}