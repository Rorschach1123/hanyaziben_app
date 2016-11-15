<?php
namespace Admin\Controller;
use Think\Controller;
header("Content-Type:text/html; charset=utf-8");
class NoticeController extends CommonController {

   //公告的 添加操作
   public function add(){
      $this->display();
   }

   //公告的插入操作
   public function insert(){
      //创建对象
    $notice =M('cw_notice');
    $data['name']=$_POST['name'];
    $data['content']=strip_tags($_POST['content']);
    $data['addtime']=time();
     //添加默认状态
    $_POST['status'] = 0;//待审核
    $res=$notice->add($data);

    //执行添加
    if($res){
      $this->success('添加成功',U('Admin/Notice/index'),3);
    }else{
      $this->error('添加失败',U('Admin/Notice/index'),3);
    }
 
}

   public function index(){

   	 //获取参数
   	 $n = I('get.n');
   	 $k = I('get.k');
   	 $num = !empty($n) ? $n : 10;//每页显示的数量
   	 $keyword = !empty($k) ? $k : '';//检索的关键字
   	 if($keyword != ''){
		  $where['name']=array('like',"%keyword%");
       }
     // 创建对象
     $notice = M('cw_notice');

     //读取总的条数
     $count = $notice->where($where)->count();
     //创建分页对象
     $page = new \Think\Page($count,$num);
     //获取limit参数
     $limit=$page->firstRow.','.$page->listRows;
     //读取当前显示的数据
     $notices = $notice->limit($limit)->where($where)->select();
     //获取页码显示的信息字符串
     $pages = $page->show();
     //分配变量
     $this->assign('n',$n);
     $this->assign('k',$k);
     $this->assign('count',$count);
     $this->assign('notices',$notices);
     $this->assign('pages',$pages);
     //解析模板
   	  $this->display();
   }

     //公告的编辑页
    public function edit(){
       //获取id
       $id = I('get.id');
       //创建对象
       $notice = M('cw_notice');
       //读取数据库数据
       $info = $notice->find($id);
       $this->assign('info',$info);
       
       //解析模板
       $this->display();
    }

       //更新公告信息
    public function update(){
      
        $notice = M('cw_notice');
        
        //创建数据
        $notice->create();
        if($notice->save()){
              $this->success('更新成功',U('Admin/Notice/index'),2);
        }else{
              $this->error('更新失败',U('Admin/Notice/index'),2);
           }
    }


    //执行删除操作
   public function delete(){
   	  //获取id
   	  $id = I('get.id');
   	  //创建对象
   	  $notice = M('cw_notice');
   	  //执行删除操作
   	  if($notice->delete($id)){
   	  	$this->success('删除成功！',U('Admin/Notice/index'),2);
   	  }else{
   	  	$this->error('删除失败！',U('Admin/Notice/index'),2);
   	  }
   }

}
?>