<?php
namespace Admin\Controller;
class IndexController extends CommonController {
	//头部
		public function top()
		{
			
			$this->display();
		}
		public function left()
		{
		    $id = session('id');
		    $group = M('auth_group_access')->field("group_id")->where("uid = '{$id}'")->find();
		    $group_id = $group['group_id'];
		    if ($group_id == 1){
		        $this->display('left');
		    }
		    elseif ($group_id == 9){
		        $this->display('left1');
		    }
		    elseif ($group_id == 7){
		        $this->display('left2');
		    }
		    elseif ($group_id == 10){
		        $this->display('left1.1');
		    }
		}
		public function main()
		{
			$this->display();
		}
		public function right()
		{
			$this->display();
		}
		public function login(){
	 		$this->assign('title','用户登录');
	    	$this->display();
    }

    public function index(){
    	$this->display();
    }
}