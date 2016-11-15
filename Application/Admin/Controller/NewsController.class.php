<?php
namespace Admin\Controller;
use Think\Controller;
class NewsController extends CommonController {

	//新闻信息 的添加操作
	public function add(){
       $cate = M('cw_newscate');
		$cates = $cate->field('cw_newscate.*,concat(path,",",id) as paths')->order('paths')->select();
		foreach ($cates as $key => $value) {
			//拆分path
			$arr = explode(',', $value['path']);
			//计算总数
			$count = count($arr) - 1;
			$str = str_repeat('|--------', $count);
			$cates[$key]['name'] = $str.$value['name'];
		}
		
		$this->assign('cates', $cates);
		$this->display();
	}

    //新闻信息的插入操作
    public function insert(){
    	//如果有图片文件上传
        if ($_FILES['pic_name']['error'] == 0) {
            $upload = new \Think\Upload();// 实例化上传类    
            $upload->maxSize   =     3145728 ;// 设置附件上传大小    
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型    
            $upload->rootPath  =     './Public/';
            $upload->savePath  =     'Uploads/'; // 设置附件上传目录    // 上传文件     
            $info   =   $upload->upload();    
            if(!$info) {
                // 上传错误提示错误信息        
                $this->error($upload->getError());    
            }else{
                // 上传成功
                // var_dump($info);
                $image = new \Think\Image(); 
                $image->open("."."/Public/".$info['pic_name']['savepath'].$info['pic_name']['savename']);
                $image->thumb(300, 300)->save("."."/Public/".$info['pic_name']['savepath']."b_".$info['pic_name']['savename']);//大图
                $image->thumb(200, 200)->save("."."/Public/".$info['pic_name']['savepath']."m_".$info['pic_name']['savename']);//中图
                $image->thumb(100, 100)->save("."."/Public/".$info['pic_name']['savepath']."s_".$info['pic_name']['savename']);//小图
                $_POST['pic_name'] = "/Public/".$info['pic_name']['savepath'].$info['pic_name']['savename'];//原图       
            }
        }
    	//创建对象
		$cate =M('cw_news');
		$data['title']=$_POST['title'];
		$data['author']=$_POST['author'];
		$data['cw_newscate_id']=$_POST['cw_newscate_id'];
		$data['content']=strip_tags($_POST['content']);
		$data['pic_name']=$_POST['pic_name'];
		$data['addtime']=time();
		$res=$cate->add($data);

		//执行添加
		if($res){
			$this->success('添加成功',U('Admin/News/index'),3);
		}else{
			$this->error('添加失败');
		}
    }

	//新闻信息的显示
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
		$news = M('cw_news');
		//读取总的条数
		$count = $news->where($where)->count();
		//创建分页对象
		$page = new \Think\Page($count, $num);
		// //获取limit参数
		$limit = $page->firstRow.','.$page->listRows;
		
		//读取当前页显示的数据
		$Articles = $news->field('cw_news.*,cw_newscate.cate_name')->join('left join cw_newscate on cw_news.cw_newscate_id=cw_newscate.id')->limit($limit)->where($where)->select();
      	

		//获取页码的信息字符串
		$pages = $page->show();
		
		//分配变量
		$this->assign('Articles',$Articles);
		$this->assign('pages',$pages);
		$this->assign('count',$count);
		$this->assign('n', $n);
		$this->assign('k', $k);
		//解析模板
		$this->display();
    }

    //新闻信息的编辑
    public function edit(){
    	$cate = M('cw_newscate');
		$cates = $cate->field('cw_newscate.*,concat(path,",",id) as paths')->order('paths')->select();
		foreach ($cates as $key => $value) {
			//拆分path
			$arr = explode(',', $value['path']);
			//计算总数
			$count = count($arr) - 1;
			$str = str_repeat('|--------', $count);
			$cates[$key]['name'] = $str.$value['name'];
		}
		$this->assign('cates', $cates);
		//获取id
		$id = I('get.id');
		//创建对象
		$Article = M('cw_news');
		//读取
		$info = $Article->find($id);

		$this->assign('info', $info);
		
		$this->display();
    }

    //新闻信息的更新操作
   public function update(){
        
        $oldpath = $_POST['oldpic'];
        $oldDirname = dirname($oldpath);
        $oldFileName = basename($oldpath);
       
        //如果有文件上传,执行文件上传操作
        if($_FILES['pic_name']['error'] == 0){
            $upload = new \Think\Upload();// 实例化上传类    
            $upload->maxSize   =     97145728 ;// 设置附件上传大小    
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型    
            $upload->rootPath  =     './Public/';
            $upload->savePath  =     'Uploads/'; // 设置附件上传目录    
            // 上传文件     
            $info   =   $upload->upload();    
            if(!$info) {// 上传错误提示错误信息        
                $this->error($upload->getError());
            }else{

                //获取上传文件的绝对路径
                $image = new \Think\Image(); 
                $image->open("."."/Public/".$info['pic_name']['savepath'].$info['pic_name']['savename']);
                $image->thumb(300, 300)->save("."."/Public/".$info['pic_name']['savepath']."b_".$info['pic_name']['savename']);//大图
                $image->thumb(200, 200)->save("."."/Public/".$info['pic_name']['savepath']."m_".$info['pic_name']['savename']);//中图
                $image->thumb(100, 100)->save("."."/Public/".$info['pic_name']['savepath']."s_".$info['pic_name']['savename']);//小图
                $_POST['pic_name'] = "/Public/".$info['pic_name']['savepath'].$info['pic_name']['savename'];
            }
        }
             $goods =M('cw_news');
   
             $goods->create();
             $oldpic=I('get.oldpic');
             $oldpica=explode('/',$oldpic);
             $oldpicm=implode('/m',$oldpica);
             $oldpicb=explode('/',$oldpic);
             $oldpicz=implode('/z',$oldpica);
           if($goods->save()){
             if($_FILES['pic_name']['error'] == 0){
                //删除原图
                @unlink(".".$oldpath);
                @unlink(".".$oldDirname."/b_".$oldFileName);
                @unlink(".".$oldDirname."/m_".$oldFileName);
                @unlink(".".$oldDirname."/s_".$oldFileName);
             }

             $this->success('更新成功', U('Admin/News/index'), 5);
            }else{
             $this->error('更新失败', U('Admin/News/index'), 5);
            }
       

	}

	//状态的显示 与隐藏
	public function root(){
		   //实例化对象
        $root = M('cw_news');
        //创建数据
        $root->create();
        //数据的更改
        if($root->save()){
            echo 0;
        }else{
            echo 1;
        }
	}

	//新闻信息的删除操作
	public function delete(){
			//获取id
		$id = I('get.id');
		//创建对象
		$News = M('cw_news');
	    $info=$News->find($id);
       // 获取原数据库的图片路径
        $booksPath = $info['pic_name'];
        //分解图片的路径
        $path = dirname($booksPath);
        $filename = basename($booksPath);
		//执行删除
		if($News->delete($id)){
			@unlink(".".$booksPath);
            @unlink(".".$path."/b_".$filename);
            @unlink(".".$path."/m_".$filename);
            @unlink(".".$path."/s_".$filename);
			$this->success('删除成功',U('Admin/News/index'), 3);
		}else{
			$this->error('删除失败',U('Admin/News/index'), 3);
		}
	}
}
?>