<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller {

	protected function _initialize(){
	// 	读取session中的id信息
		 $sid = session('id');
        if(empty($sid)){
            $this->error('您还没有登陆,请重新登陆',U('Admin/Login/login'),1);
        }
		 $auth = new \Think\Auth();
        if(!$auth->check(MODULE_NAME."/".CONTROLLER_NAME."/".ACTION_NAME,session('id')))
        {
            $this->show('您没有操作权限');
            exit();
        }
		
	}

}
