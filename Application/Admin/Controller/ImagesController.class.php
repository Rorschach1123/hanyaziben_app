<?php
namespace Admin\Controller;
use Think\Controller;
class ImagesController extends CommonController {
      public function add(){
      	$this->display();
      }

      public function insert(){
      	// 实例化上传类
		$pic = '';
		$F = array();
		for ($i=0; $i < 3; $i++) { 
			$F[$i]['name'] = $_FILES['images']['name'][$i];
			$F[$i]['type'] = $_FILES['images']['type'][$i];
			$F[$i]['tmp_name'] = $_FILES['images']['tmp_name'][$i];
			$F[$i]['error'] = $_FILES['images']['error'][$i];
			$F[$i]['size'] = $_FILES['images']['size'][$i];
		}
		for($i=0;$i<3;$i++){
			if($_FILES['images']['error'][$i] == 0){
				$upload = new \Think\Upload();   
				// 设置附件上传大小  
				$upload->maxSize = 200145728 ; 
				// 设置附件上传类型  
				$upload->exts = array('jpg', 'gif', 'png', 'jpeg');
		 		// 设置附件上传目录 
				$upload->rootPath = './Public/';
				$upload->savePath = 'Uploads/Images/';   
				$upload->subName  = '';
				// 上传文件
				$info = $upload->uploadOne($F[$i]);
				// var_dump($info);
				if(!$info){
					// 上传错误提示错误信息        
					$this->error($upload->getError());    
				}else{				
					// 上传成功！
					//图像缩放
					$image = new \Think\Image();	
					$newPicName = $info['savename']; 
					$newPath = "./Public/".$info['savepath'];
					$newPic = $newPath.$newPicName;
					$res = $image->open($newPic);
					$image->thumb(450, 675)->save($newPath."b_".$info['savename']);
					$image->thumb(226, 339)->save($newPath."m_".$info['savename']);
					$image->thumb(68, 102)->save($newPath."s_".$info['savename']);
					$pic[$i] = $newPicName;
				}
			}
		}
		
		$pic = implode(',',$pic);	
		$pic = ltrim($pic,',');
		$_POST['images']=$pic;
		//创建对象
		$images = M('cw_images');		
		$_POST['addtime']=time();
		$images->create();
		//执行添加
		if($images->add()){
			$this->success('添加成功',U('Admin/Images/index'),3);
		}else{
			$this->error('添加失败');
		}
      }

      public function index(){
      	//获取参数
		$n = I('get.n');
    	$k = I('get.k');
    	$num = !empty($n) ? $n : 10;//每页显示的条数
		$keyword = !empty($k) ? $k : '';//检索的关键字
		if($keyword != ''){
			$where['title'] = array('like', "%$keyword%");
		}


		//创建对象
		$image = M('cw_images');
		//读取总的条数
		$count = $image->where($where)->count();
		//创建分页对象
		$page = new \Think\Page($count, $num);
		// //获取limit参数
		$limit = $page->firstRow.','.$page->listRows;
		
		//读取当前页显示的数据
		$images = $image->limit($limit)->where($where)->select();
      	
		foreach ($images as $vo){
		    $img_str = $vo['images'];
		    $img_arr = explode(',',$img_str);
		    $vo['images'] = $img_arr[0];
		    $arr[] = $vo;
		}
		
		//获取页码的信息字符串
		$pages = $page->show();
		
		//分配变量
		$this->assign('images',$arr);
		$this->assign('pages',$pages);
		$this->assign('count',$count);
		$this->assign('n', $n);
		$this->assign('k', $k);
		//解析模板
		$this->display();
      	
      }
   public function detail(){
      //获取id
		$id = I('get.id');
		//创建对象
		$Images = M('cw_images');
		//读取
		$info = $Images->find($id);
		$str = $info['images'];
		$arr = explode('##',$str);
		
		
		for($i=0;$i<3;$i++){
			$info['images'][$i] = $arr[$i];
		}
		$this->assign('info', $info);
		$this->display();
	}

	// public function edit(){
	// 	//获取id
	// 	$id = I('get.id');
	// 	//创建对象
	// 	$Images = M('cw_images');
	// 	//读取
	// 	$info = $Images->find($id);

	// 	$this->assign('info', $info);
		
	// 	$this->display();
	// }

	// public function update(){
	// 	$goods = M('cw_images');
	//      //创建数据
	// 	$res = $goods->create();
		
	// 	if($goods->save()){
	// 		$this->success('更新成功', U('Admin/Images/index'), 3);
	// 	}else{
				
	// 		$this->error('更新失败');
	// 	}
	// }

	//状态的显示 与隐藏
	public function root(){
		   //实例化对象
        $root = M('cw_images');
        //创建数据
        $root->create();
        //数据的更改
        if($root->save()){
            echo 0;
        }else{
            echo 1;
        }
	}


	public function delete(){
			//获取id
		$id = I('get.id');
		//创建对象
		$images = M('cw_images');
	    $info=$images->find($id);

        $booksPath = $info['pic_name'];
        $path = dirname($booksPath);
        $filename = basename($booksPath);
		//执行删除
		if($images->delete($id)){
			@unlink(".".$booksPath);
            @unlink(".".$path."/b_".$filename);
            @unlink(".".$path."/m_".$filename);
            @unlink(".".$path."/s_".$filename);
			
			$this->success('删除成功',U('Admin/Images/index'), 3);
		}else{
			$this->error('删除失败',U('Admin/Images/index'), 3);
		}
	}
} 


?>