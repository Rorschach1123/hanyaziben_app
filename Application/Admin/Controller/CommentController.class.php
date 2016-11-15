<?php
namespace Admin\Controller;
use Think\Controller;
class CommentController extends CommonController {
   //评论内容的显示
   public function index(){

   	   //获取参数
		$n=I('get.n');
		$k=I('get.k');
		$num=!empty($n) ? $n : 10;//每页显示 的条数
		$keyword=!empty($k) ? $k : ''; //检索关键字
		if($keyword !=''){
			$where['username']=array('like',"%keyword%");
		}

		//创建对象
		$comment=M('cw_comment');
		//读取总条数
		$count=$comment->where($where)->count();
		
		// var_dump($count);die;
		//创建分页对象
		$page=new \Think\Page($count,$num);
		//获取limit参数
		$limit=$page->firstRow.','.$page->listRows;
		//读取当前显示的数据
		// $comments=$comment->join('left join cw_news on `cw_comment`.cw_news_id=cw_news_id')
				// ->limit($limit)->where($where)->select();
        $comments=$comment->join('left join cw_user on `cw_comment`.cw_user_id=cw_user.id')
		->join('left join cw_news on `cw_comment`.cw_news_id=cw_news.id')
		->field('`cw_comment`.id,`cw_comment`.address,`cw_comment`.comment_text,cw_news.id as cw_news_id,cw_user.id as cw_user_id,`cw_comment`.addtime,`cw_comment`.state')->limit($limit)->select();
       
	    //获取页码信息的字符串
		$pages=$page->show();
		//分配变量
		$this->assign('n',$n);
		$this->assign('count',$count);
		$this->assign('k',$k);
		$this->assign('comments',$comments);
		$this->assign('pages',$pages);
		//解析模板
		$this->display();
   }

   public function edit(){
   	  //获取参数
		$id=I('get.id');
		//创建 对象
		$comment=M('cw_comment');
		//读取
		$info=$comment->find($id);

		//创建参数
		$comment->create();
		//分配变量
		$this->assign('info',$info);
		
		//解析模板
		$this->display();
   }

   public function update(){
   	//创建对象
		$comment=M('cw_comment');
		//创建参数
		$comment->create();
		//执行修改
		if($comment->save()){
			$this->success('修改成功!',U('Admin/Comment/index'),3);
		}else{
			$this->error('修改失败!',U('Admin/Comment/index'),5);
		}
   }

   //新闻信息的删除操作
   public function delete(){
   	   //获取参数
		$id=I('get.id');
		//创建对象
		$comment=M('cw_comment');
		//执行删除
		if($comment->delete($id)){
			$this->success('删除成功!',U('Admin/Comment/index'),3);
		}else{
			$this->error('删除失败!',U('Admin/Comment/index'),5);
		}
   }
}
?>