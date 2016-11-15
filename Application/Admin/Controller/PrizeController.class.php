<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class PrizeController extends CommonController{
    public function index(){
        
        //获取参数
        $n = I('get.n');
        $k = I('get.k');
        $num = !empty($n) ? $n : 10;//每页显示的数量
        $keyword = !empty($k) ? $k : '';//检索的关键字
        if($keyword != ''){
            $where['prize_name']=array('like',"%{$keyword}%");
        }
        
        // 礼品信息列表
        $Prize = M('pt_prizes');
        //读取总的条数
        $count = $Prize->where($where)->count();
        //创建分页对象
        $page = new \Think\Page($count,$num);
        //获取limit参数
        $limit=$page->firstRow.','.$page->listRows;
        
        $prizes = $Prize -> field() -> where($where) -> order('creation_date desc') -> limit($limit) -> select();
        
        //获取页码显示的信息字符串
        $pages = $page->show();
        
        $this->assign('k',$k);
        $this -> assign('info',$prizes);
        $this -> assign('count',$count);
        $this -> assign('pages',$pages);
        
        $this -> display();
    }
    public function record(){
        
        //获取参数
        $n = I('get.n');
        $k = I('get.k');
        $num = !empty($n) ? $n : 10;//每页显示的数量
        $keyword = !empty($k) ? $k : '';//检索的关键字
        if($keyword != ''){
            $where['username']=array('like',"%$keyword%");
        }
        $where['is_effective'] = 1;
        
        // 礼品兑换记录
        $Record = M('pt_exchange_orders');
        //读取总的条数
        $count = $Record->where($where)->count();
        //创建分页对象
        $page = new \Think\Page($count,$num);
        //获取limit参数
        $limit=$page->firstRow.','.$page->listRows;
        $records = $Record->alias('r')->field('r.*,u.username,u.realname')->join('cw_user as u on r.user_id = u.id','left')->where($where)->order('r.id desc')->limit($limit)->select();
            
        foreach ($records as $key => $vo){
            $prizes = $vo['prize_ids'];
            $prize_arr = explode(';',$prizes);
            $amounts = $vo['prize_amounts'];
            $amount_arr = explode(';',$amounts);
            $Prize = M('pt_prizes');
            
            foreach ($prize_arr as $kk => $vv){
                $prize = $Prize->field('prize_sname')->where("id = '{$vv}'")->find();
                $prize_name = $prize['prize_sname'];
                $prize_amount = $amount_arr[$kk];
                $vo['prizes_amounts'] .= $prize_name.'：'.$prize_amount.'个；<br>';
            }
            $records[$key]=$vo;
        }
        
        //获取页码显示的信息字符串
        $pages = $page->show();
        
        $this->assign('k',$k);
        $this->assign('info',$records);
        $this->assign('count',$count);
        $this->assign('pages',$pages);
        
        $this->display();
    }
    public function add(){
        if (IS_POST){
            
            $data = I("post.");
            
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/'; // 设置附件上传根目录
            // 上传文件
            if ($_FILES['image']['name'][0] != ''){
                $upload->savePath  =      '/Uploads/Images/prizes/thumb/'; // 设置附件上传（子）目录
            }
            if ($_FILES['image']['name'][1] != ''){
                $upload->savePath  =      '/Uploads/Images/prizes/detail/'; // 设置附件上传（子）目录
            }
            if ($_FILES['image']['name'][2] != ''){
                $upload->savePath  =      '/Uploads/Images/prizes/hot/'; // 设置附件上传（子）目录
            }
            $info   =   $upload->upload();
            if(!$info) {// 上传错误提示错误信息
//                 $this->error($upload->getError());
                $this->show('没有图片被上传');
            }else{// 上传成功 获取上传文件信息
                $data['prize_image'] = $info[0]['savepath'].$info[0]['savename'];
                $data['detail_image'] = $info[1]['savepath'].$info[1]['savename'];
                $data['hot_image'] = $info[2]['savepath'].$info[2]['savename'];
            }
            if ($data['start_date']==''){
                $data['start_date']=='0000-00-00';
            }
            if ($data['end_date']==''){
                $data['end_date']=='0000-00-00';
            }
            
            $result = M('pt_prizes')->data($data)->add();
            
            if ($result || $result == 0){
                $this->success('上传数据成功',U('Admin/Prize/index'),1);
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

            $data = I("post.");
            
            $id = $data['id'];
            $Prizes = M('pt_prizes');
            $prize = $Prizes->where("id = '{$id}'")->find();
            
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/'; // 设置附件上传根目录
            // 上传文件
            if ($_FILES['image']['name'][0] != ''){
                $upload->savePath  =      '/Uploads/Images/prizes/thumb/'; // 设置附件上传（子）目录
            }
            if ($_FILES['image']['name'][1] != ''){
                $upload->savePath  =      '/Uploads/Images/prizes/detail/'; // 设置附件上传（子）目录
            }
            if ($_FILES['image']['name'][2] != ''){
                $upload->savePath  =      '/Uploads/Images/prizes/hot/'; // 设置附件上传（子）目录
            }
            $info   =   $upload->upload();
            if(!$info) {// 上传错误提示错误信息
                //                 $this->error($upload->getError());
                
                $data['prize_image'] = $prize['prize_image'];
                $data['detail_image'] = $prize['detail_image'];
                $data['hot_image'] = $prize['hot_image'];
                $this->show('没有图片被上传');
            }else{// 上传成功 获取上传文件信息
                if ($info[0] != ''){
                    $data['prize_image'] = $info[0]['savepath'].$info[0]['savename'];
                }
                else {
                    $data['prize_image'] = $prize['prize_image'];
                }
                if ($info[1] != ''){
                    $data['detail_image'] = $info[1]['savepath'].$info[1]['savename'];
                }
                else {
                    $data['detail_image'] = $prize['detail_image'];
                }
                if ($info[2] != ''){
                    $data['hot_image'] = $info[2]['savepath'].$info[2]['savename'];
                }
                else {
                    $data['hot_image'] = $prize['hot_image'];
                }
            }
            
            $result = $Prizes->data($data)->save();
            
            if ($result || $result == 0){
                $this->success('上传数据成功',U('Admin/Prize/index'),1);
            }
            else {
                $this->error('上传数据失败',$_SERVER['HTTP_REFERER'],1);
            }
            exit();
        }
        else {
            $id = I('get.id');
            $info = M('pt_prizes')->where("id = '{$id}'")->find();
            $this->assign('info',$info);
            $this->display();
        }
    }
    public function logistics(){
        if (IS_POST){
            $Orders = M('pt_exchange_orders');
            if ($Orders->create()){
                if ($Orders->save()){
                    $this->success('绑定物流成功',U('Admin/Prize/record'),1);
                }
                else {
                    $this->error('绑定物流失败',$_SERVER['HTTP_REFERER'],1);
                }
            }
            else {
                $this->getError();
            }
        }
        else {
            $id = I('get.id');
            $this->assign('id',$id);
            $this->display();
        }
    }
    public function switch_button(){
        if (IS_POST){
            $PointsMall = M('pt_points_mall');
            $data = I('post.');
            
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/'; // 设置附件上传根目录
            $upload->savePath  =      '/Uploads/Images/gsjj/'; // 设置附件上传（子）目录
            $upload->autoSub = false;
            $upload->saveName = "敬请期待";
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['image']);
            if(!$info) {// 上传错误提示错误信息
                
//                 $this->error($upload->getError());
                $this->show('没有图片被上传');
            }else{// 上传成功 获取上传文件信息
                $url = "http://app.hanyalicai.com/Public".$info['savepath'].$info['savename'];
                $data['image'] = $url;
            }
            $result = $PointsMall->data($data)->save();
            if ($result || $result == 0){
                $this->success("保存成功",U("Admin/Prize/switch_button"),1);
            }
            else {
                $this->error('保存失败',$_SERVER['HTTP_REFERER'],1);
            }
        }
        else {
            $info = M('pt_points_mall')->find();
            $this->assign(info,$info);
            $this->display();
        }
    }
}