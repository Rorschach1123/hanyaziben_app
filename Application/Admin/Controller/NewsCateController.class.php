<?php
namespace Admin\Controller;
use Think\Controller;
class NewsCateController extends CommonController {
    //分类的添加操作
    public function add(){
    	$cate = M('cw_newscate');
		$cates=$cate->field('cw_newscate.*,concat(path,",",id) as paths')->order('paths')->select();
		
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

    public function insert(){
    	//创建对象
		$newscate = M('cw_newscate');
		//顶级分类
		
		if($_POST['pid'] == 0){
			$_POST['path'] = 0;
		}else{
			//查询父级分类信息
			$info = $newscate->find($_POST['pid']);
			$_POST['path'] = $info['path'].','.$info['id'];
		}

		$newscate->create();
		//执行添加
		if($newscate->add()){
			$this->success('添加成功',U('Admin/NewsCate/index'),3);
			
		}else{
			$this->error('添加失败');
		}
    }

    public function index(){
    	//获取参数
		$n = I('get.n');
    	$k = I('get.k');
		$keyword = !empty($k) ? $k : '';//检索的关键字
		if($keyword != ''){
			$where['c1.cate_name'] = array('like', "%$keyword%");

		}
		

		//创建对象
		$cate = M('cw_newscate');
		//读取总的条数
		$count = $cate->where($where)->count();
		//创建分页对象
		$page = new \Think\Page($count, $num);
		//获取limit参数
		$limit = $page->firstRow.','.$page->listRows;//$page->getLimit();
		
		//读取当前页显示的数据
		
		$Articles = $cate->table('cw_newscate as c1')->join('left join cw_newscate as c2 on c1.pid=c2.id')->field("c1.path,c1.cate_name,c1.pid,c1.id,c2.cate_name as names,concat(c1.path,',',c1.id) as paths")->order('paths')->where($where)->select();
		 //获取页码的信息字符串
		
		$pages = $page->show();
		foreach ($Articles as $key => $value) {
			//拆分path
			$arr = explode(',', $value['path']);
			//计算总数
			$count = count($arr) - 1;
			$str = str_repeat('|--------',	$count);
			$Articles[$key]['name'] = $str.$value['name'];
		}
		
		//分配变量
		$this->assign('Articles',$Articles);
		$this->assign('pages',$pages);
		$this->assign('n', $n);
		$this->assign('k', $k);
		//解析模板
		$this->display();
    }

    public function edit(){
    	$cate = M('cw_newscate');
		$cates = $cate->select();
		foreach ($cates as $key => $value) {
			//拆分path
			$arr = explode(',', $value['path']);
			//计算总数
			$count = count($arr) - 1;
			$str = str_repeat('|--------',$count);
			$cates[$key]['name'] = $str.$value['name'];
		}
		$this->assign('cates', $cates);
		//获取id
		$id = I('get.id');
		//创建对象
		$Article = M('cw_newscate');
		//读取
		$info = $Article->find($id);
		

		$this->assign('info', $info);
		$this->display();
    }

    public function update(){
    	$Article = M('cw_newscate');
		//顶级分类
		if($_POST['pid'] ==0)
		{
			$_POST['path'] = 0;
		}else
		{
			//查询父级分类的信息
			$info = $Article->find($_POST['pid']);
			$_POST['path'] = $info['path'].','.$info['id'];
		}
		//创建数据
		$Article->create();	
		if($Article->save()){
			$this->success('更新成功', U('Admin/NewsCate/index'), 5);
		}else{
			$this->error('更新失败', U('Admin/NewsCate/index'), 5);
		}
    }

    public function delete(){
    	//获取id
		$id = I('get.id');
		//创建对象
		$newscate = M('cw_newscate');
		$info = $newscate->where("pid=$id")->find();
		if(!empty($info))
		{
			$this->error('当前的分类下存在子分类 不允许删除');
		}
		//执行删除
		if($newscate->delete($id)){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
    }

}

?>