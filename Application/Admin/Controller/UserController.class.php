<?php

namespace Admin\Controller;
header("Content-Type:text/html; charset=utf-8");
class UserController extends CommonController {
   //添加会员
   public function add(){
      $this->display();
   }

   //会员的插入操作
   public function add_post(){
       $user = M('cw_user');
       $user->create();
       //执行添加
       if($user->add()){
           $this->success('添加成功！',U('Admin/User/index'),3);
       }else{
           $this->error('数据添加失败！');
       }
   }
   //邮箱列表的显示
   public function index(){
   	 //获取参数
   	 $n = I('get.n');
   	 $k = I('get.k');
   	 $num = !empty($n) ? $n : 10;//每页显示的数量
   	 $keyword = !empty($k) ? $k : '';//检索的关键字
   	 if($keyword != ''){
		  $where['username']=array('like',"%{$keyword}%");
       }
     // 创建对象
     $mail = M('cw_user');
     //读取总的条数
     $count = $mail->where($where)->count();
     //创建分页对象
     $page = new \Think\Page($count,$num);
     //获取limit参数
     $limit=$page->firstRow.','.$page->listRows;
     //读取当前显示的数据
     $mails = $mail->limit($limit)->where($where)->order('id desc')->select();
     //获取页码显示的信息字符串
     $pages = $page->show();
     //分配变量
     $this->assign('n',$n);
     $this->assign('k',$k);
     $this->assign('count',$count);
     $this->assign('mails',$mails);
     $this->assign('pages',$pages);
     //解析模板
   	  $this->display();
   }
   

  public function edit(){
      //获取参数
    $id=I('get.id');
    //创建 对象
    $user=M('cw_user');
    //读取
    $info=$user->find($id);

    //创建参数
    $user->create();
    //分配变量
    $this->assign('info',$info);
    
    //解析模板
    $this->display();
   }

   public function update(){
    //创建对象
    $user=M('cw_user');
    //创建参数
    $user->create();
    //执行修改
    if($user->save()){
      $this->success('修改成功!',U('Admin/User/index'),3);
    }else{
      $this->error('修改失败!',U('Admin/User/index'),5);
    }
   }
  
   public function password(){
      //获取id
      $id=I('get.id');
      // var_dump($id);die;
      //创建对象
      $user=M('cw_user');

      //读取数据
      $info=$user->find($id);
      //分配变量
      $this->assign('info',$info);
      //创建数据
      $user->create();
      //解析模板
      $this->display();
    }

    public function upd(){
    $user = M('cw_user');
    $_POST['password'] = md5($_POST['password']);
    $user->create();
      if($user->save()){
        $this->success('修改成功',U('Admin/User/index'),3);
      }else{
        $this->error('修改失败',U('Admin/User/index'),5);
      }
    }

   //执行删除操作
   public function delete(){
   	  //获取id
   	  $id = I('get.id');
   	  //创建对象
   	  $mail = M('cw_user');
   	  //执行删除操作
   	  if($mail->delete($id)){
   	  	$this->success('删除成功！',U('Admin/User/index'),2);
   	  }else{
   	  	$this->error('删除失败！',U('Admin/User/index'),2);
   	  }
   }

}