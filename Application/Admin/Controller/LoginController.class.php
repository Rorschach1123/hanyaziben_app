<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
    //登录页面
    public function login(){
    	$this->assign('title','后台用户登录');
    	$this->display();
    }

    public function checked(){
    	//获取参数
    	$where['username'] = $_POST['username'];
    	$password = I('post.pass');
    	$where['pass'] = md5($password);
    	//创建对象
    	$admin = M('admin');
    	$res = $admin->where($where)->select();
    	if(!empty($res)){
    		session('name',$res[0]['name']);
			session('id',$res[0]['id']);
			$this->success('登录成功！',U('Admin/Index/index'),1);
    	}else{
    		$this->error('登录失败,请重试~~~~');
    	}
    }

    public function code(){
    	ob_clean();
		$Verify = new \Think\Verify();  
		$Verify->fontSize = 18;  
		$Verify->length   = 4;  
		$Verify->useNoise = false;  
		$Verify->codeSet = '0123456789';  
		$Verify->imageW = 130;  
		$Verify->imageH = 50;  
		$Verify->entry();
    }
    
    public function check_code(){
        $verify = new \Think\Verify();
        $code = I('post.code');
        $state = $verify->check($code,$id='');
        $this->ajaxReturn($state);
    }

    public function loginout(){
    	session_destroy();
    	echo "
    	    <script>
    	       window.open('login');
    	       window.close();
    	    </script>";
    }
}