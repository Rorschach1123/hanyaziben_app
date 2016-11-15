<?php
namespace Admin\Controller;
class AuthController extends CommonController {
	//添加组(group)
	public function addgroup(){
		$this->display();


	}

	//执行组添加
	public function insertgroup()
	{
		$role=M("auth_group");
		$role->create();
		if($role->add())
		{
			$this->success("添加成功",U('Admin/Auth/addgroup'),3);
		}
		else
		{
			$this->error("添加失败",U('Admin/Auth/addgroup'),3);
		}
	}

	// 组管理
	public function groupmanager(){
		$group=M("auth_group");
		$groups=$group->select();
		$count=$group->count();
		$this->count=$count;
		$this->assign("groups",$groups);
		$this->display();
	}
	//删除组
	public function delgroup()
	{
		$role=M("auth_group");
		 $role_group=M('auth_group_access');
		$id=I("get.rid");
		if($role->where('id='.$id)->delete())
		{
			 if($role_group->where('group_id='.$id)->delete())
			 {
				$this->success("删除成功",U('Admin/Auth/groupmanager'),3);
			}
		}
		else
		{
			$this->error("删除失败",U('Admin/Auth/groupmanager'),3);
		}
	}

	//添加规则(节点)
	public function addrule()
	{
		
		$this->display();
	}

	//执行添加规则(节点)
	public function insertrule()
	{
		$node=M("auth_rule");
		$node->create();
		if($node->add())
		{
			$this->success("添加成功",U('Admin/Auth/rulelist'),3);
		}
		else
		{
			$this->error("添加失败",U('Admin/Auth/rulelist'),3);

		}
	}
	//编辑规则页面显示
	public function editrule()
	{
		$id=I('get.rid');
		$rule=M('auth_rule');
		$info=$rule->find($id);
		$this->info=$info;
		$this->display();
	}

	//执行规则修改
	public function updaterule()
	{
		//获取id
		$id=I('post.id');
		$rule=M("auth_rule");
		$rule->create();
		$res=$rule->save();
		if($res)
		{
			$this->success('修改规则成功',U("Admin/Auth/rulelist"),3);
		}
		else
		{
			$this->error('修改规则失败',U("Admin/Auth/rulelist"),5);
		}

	}

	//规则列表(管理)
	public function rulelist()
	{
		$node=M("auth_rule");
		$n=I('get.n');
		$num=!empty($n)?$n:10;
		$count=$node->count();
		$page=new \Think\Page($count,$num);
		$pages=$page->show();
		$nodes=$node->order('name desc')->limit($page->firstRow.','.$page->listRows)->select();

		$this->count=$count;
		$this->assign('pages',$pages);
		$this->assign("nodes",$nodes);
		$this->assign("n",$n);
		$this->display();
	}

	//执行规则删除
	public function delrule()
	{
		$node=M("auth_rule");
		$id=I('get.rid');
		$res=$node->where('id='.$id)->delete();
		if($res)
		{
			$this->success("删除成功",U('Admin/Auth/rulelist'),3);
		}
		else
		{
			$this->error("删除失败",U('Admin/Auth/rulelist'),3);
		}
	}

	//添加管理员
	public function adduser()
	{
		$group=M("auth_group");
		$admin=M('admin');
		$groups=$group->select();
		$username=I('post.username');
		$res=$admin->where("username='$username'")->find();
		if(IS_AJAX)
		{
				if(!empty($res))
				{
					echo 0;
				}
				else
				{
					echo 1;
				}

		}



		$this->assign("groups",$groups);
		$this->display();
	}

	//执行管理员添加
	public function insertuser()
	{
		$group=M("auth_group_access");

		$user=M('admin');

		$group_id=$_POST['id'];

		$data1['username']=$_POST['username'];
		$data1['name']=$_POST['name'];
		$data1['email']=$_POST['email'];
		$data1['pass']=md5($_POST['password']);
		$data1['addtime']=time();
		$data1['state']=$_POST['state'];
		$user->create($data1);
		$res=$user->add();
		if($res)
		{
			$lastid=$res;
			$data['uid']=$lastid;
			$data['group_id']=$group_id;
			$group->create($data);
			$result=$group->add();

			if($result)
			{
				$this->success("添加成功",U('Admin/Auth/userlist'),3);
			}
		}
		else{
			$this->error("添加失败",U('Admin/Auth/userlist'),3);
		}
	}

	//管理员管理列表
	public function userlist()
	{
		$admin=M("admin");
		$admins=$admin->field('admin.id,admin.username,admin.name,admin.email,admin.addtime,auth_group.title')->join('join auth_group_access on admin.id=auth_group_access.uid join auth_group on auth_group_access.group_id=auth_group.id')->select();
		$id=$admins['id'];
		$title=$admins['title'];
		$rules=$admins['rules'];
		$this->assign('id',$id);
		$this->assign("name",$title);
		$this->assign('rules',$rules);
		$this->assign('admins',$admins);
		//var_dump($name);
		$this->display();
	} 

	//管理员修改页面
	public function edituser()
	{
		$id=I('get.rid');
		$admin=M('admin');
		$group=M('auth_group');
		$groups=$group->select();
		$info=$admin->field("admin.id,admin.username,admin.name,admin.pass,admin.email,admin.state,auth_group_access.group_id")->join('auth_group_access on admin.id=auth_group_access.uid')->where('id='.$id)->find();
		// echo $admin->_sql();
		$this->assign('info',$info);
		$this->assign('groups',$groups);
		$this->display();
	}

	//修改管理员
	public function updateuser()
	{
		
		$id=I('post.id');
		$username=$_POST['username'];
		$password=md5($_POST['password']);
		$group_id=$_POST['group_id'];
		$name=$_POST['name'];
		$email=$_POST['email'];
		$state=$_POST['state'];
		$data['username']=$username;
		$data['pass']=$password;
		$data['name']=$name;
		$data['email']=$email;
		$data['state']=$state;
		$content['group_id']=$group_id;
		$admin=M('admin');

		$group_access=M('auth_group_access');

		$res=$admin->where("id=".$id)->save($data);
		$result=$group_access->where('uid='.$id)->save($content);
		if($res||$result)
		{
			$this->success("修改成功",U('Admin/Auth/userlist'),3);
		}
		else{
			$this->error("修改失败",U('Admin/Auth/userlist'),5);
		}
	}

	//删除管理员
	public function deluser()
	{
		$id=I('get.rid');
		$admin=M('admin');
		$group_access=M("auth_group_access");
		$res=$admin->where('id='.$id)->delete();
		if($res)
		{
			$result=$group_access->where('uid='.$id)->delete();
			if($result)
			{
				$this->success('删除成功',U('Admin/Auth/userlist'),3);
			}
		}
		else
		{
			$this->error("删除失败",U('Admin/Auth/userlist'),5);
		}
	}

	//配置权限页面
	public function access()
	{
		$id=I('get.rid');
		$group=M('auth_group');
		$rule=M("auth_rule");
		// $group_rule=$group->where('id='.$id)->find();
		$rules=$group->where('id='.$id)->getField('rules');
		$group_rule=explode(',',$rules);
		$rules=$rule->select();
		$title = $group->where('id='.$id)->getField('title');
		$this->assign('group_rule',$group_rule);
		$this->assign('id',$id);
		$this->assign('title',$title);
		$this->assign('rules',$rules);
		$this->display();
	}
	//添加角色权限表(节点表)
	public function setaccess()
	{
		$id=I('post.id');
		$name=I('post.title');
		// var_dump($_POST);
		$access=$_POST['access'];
		$str=implode(",",$access);
		$data['rules']=$str;
		$group_rule=M("auth_group");
		$res=$group_rule->where('id='.$id)->save($data);
		// echo $group_rule->_sql();die;
		if($res)
		{
			$this->success("为角色<span style='color:blue;margin-left:15px;margin-right:15px;'>".$name."</span>分配权限成功",U("Admin/Auth/groupmanager"),3);
		}
		else
		{
			$this->error("为角色<span style='color:blue;margin-left:15px;margin-right:15px;'>".$name."</span>分配权限失败",U("Admin/Auth/groupmanager"),5);
		}
	}
}
?>