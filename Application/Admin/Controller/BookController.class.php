<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
header("Content-Type:text/html; charset=utf-8");
class BookController extends CommonController {
   public function index(){
   	 //获取参数
   	 $n = I('get.n');
   	 $k = I('get.k');
   	 $num = !empty($n) ? $n : 10;//每页显示的数量
   	 $keyword = !empty($k) ? $k : '';//检索的关键字
   	 if($keyword != ''){
		  $where['bookname']=array('like',"%keyword%");
       }
     // 创建对象
     $book = M('cw_book');
     //读取总的条数
     $count = $book->where($where)->count();
     //创建分页对象
     $page = new \Think\Page($count,$num);
     //获取limit参数
     $limit=$page->firstRow.','.$page->listRows;
     //读取当前显示的数据
     $books = $book->alias('b')->field('b.*,p.name,p.tel')->join('cw_planner as p on b.pid = p.id','left')->where($where)->order('id desc')->limit($limit)->select();
     //获取页码显示的信息字符串
     $pages = $page->show();
     //分配变量
     $this->assign('n',$n);
     $this->assign('k',$k);
     $this->assign('count',$count);
     $this->assign('books',$books);
     $this->assign('pages',$pages);
     //解析模板
   	  $this->display();
   }

    //执行删除操作
   public function delete(){
   	  //获取id
   	  $id = I('get.id');
   	  //创建对象
   	  $book = M('cw_book');
   	  //执行删除操作
   	  if($book->delete($id)){
   	  	$this->success('删除成功！',U('Admin/Book/index'),2);
   	  }else{
   	  	$this->error('删除失败！',U('Admin/Book/index'),2);
   	  }
   }

}
?>