<?php
namespace Admin\Controller;
use Think\Controller;
header("Content-Type:text/html; charset=utf-8");
class AssetController extends CommonController {
   //信息的添加
   public function add(){
      $this->display();
   }

   //信息的插入操作
   public function add_post(){
       $asset = M('cw_asset');
      //创建数据
      $asset->create();
      //执行添加
      if($asset->add()){
        $this->success('添加成功',U('Admin/asset/index'),2);
      }else{
        $this->error('添加失败',U('Admin/asset/add'),3);
      }
   }
   //页面的显示
   public function index(){
   	 //获取参数
   	 $n = I('get.n');
   	 $k = I('get.k');
   	 $num = !empty($n) ? $n : 10;//每页显示的数量
   	 $keyword = !empty($k) ? $k : '';//检索的关键字
   	 if($keyword != ''){
		  $where['username']=array('like',"%keyword%");
       }
     // 创建对象
     $asset = M('cw_asset');
     //读取总的条数
     $count = $asset->where($where)->count();
     //创建分页对象
     $page = new \Think\Page($count,$num);
     //获取limit参数
     $limit=$page->firstRow.','.$page->listRows;
     //读取当前显示的数据
     $assets = $asset->limit($limit)->where($where)->select();
     //获取页码显示的信息字符串
     $pages = $page->show();
     //分配变量
     $this->assign('n',$n);
     $this->assign('k',$k);
     $this->assign('count',$count);
     $this->assign('assets',$assets);
     $this->assign('pages',$pages);
     //解析模板
   	  $this->display();
   }

   public function edit(){
      $id = I('get.id');
      $asset = M('cw_asset');
      $info = $asset->find($id);
      $this->assign('info',$info);
      $this->display();
   }

    public function update(){
      
        $asset = M('cw_asset');
        
        //创建数据
        $asset->create();
        if($asset->save()){
              $this->success('更新成功',U('Admin/Asset/index'),2);
        }else{
              $this->error('更新失败',U('Admin/Asset/index'),2);
           }
    }

    //执行删除操作
   public function delete(){
   	  //获取id
   	  $id = I('get.id');
   	  //创建对象
   	  $asset = M('cw_asset');
   	  //执行删除操作
   	  if($asset->delete($id)){
   	  	$this->success('删除成功！',U('Admin/Asset/index'),2);
   	  }else{
   	  	$this->error('删除失败！',U('Admin/Asset/index'),2);
   	  }
   }

}
?>