<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />

<link type="text/css" rel="stylesheet" href="/hanyaziben_app/Public/Admin/css/H-ui.css"/>
<link type="text/css" rel="stylesheet" href="/hanyaziben_app/Public/Admin/css/H-ui.admin.css"/>
<link type="text/css" rel="stylesheet" href="/hanyaziben_app/Public/Admin/font/font-awesome.min.css"/>

<style type="text/css">
</style>
<title>用户管理</title>
</head>
<body>

<nav class="Hui-breadcrumb"><i class="icon-home"></i> 用户管理 <span class="c-gray en">&gt;</span>  <span class="c-gray en">&gt;</span> 用户列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" id="ref" title="刷新" ><i class="icon-refresh"></i></a></nav>
<div class="pd-20">
  <div class="text-c">
  <form class="Huiform" action="<?php echo U('Admin/Adviser/index');?>" method="get">
  <select class="input-small" id="DropDownTimezone" name="n">
          <option value="10" <?php if(($n) == "10"): ?>selected = "selected"<?php endif; ?>>10</option>
          <option value="20" <?php if(($n) == "20"): ?>selected = "selected"<?php endif; ?>>20</option>
          <option value="30" <?php if(($n) == "30"): ?>selected = "selected"<?php endif; ?>>30</option>
          <option value="40" <?php if(($n) == "40"): ?>selected = "selected"<?php endif; ?>>40</option>
          <option value="50" <?php if(($n) == "50"): ?>selected = "selected"<?php endif; ?>>50</option>
          <option value="100" <?php if(($n) == "100"): ?>selected = "selected"<?php endif; ?>>100</option>
    </select>条
  
    <!-- <input type="text" name="k" id="" value="<?php echo ($k); ?>" placeholder=" " style="width:250px" class="input-text"> --><button name="" id="" class="btn btn-success" type="submit"><i class="icon-search"></i>搜索</button>
  </div>
  </form>
  <div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a> </a><a class="" href="javascript:;"<i class=""></i></a></span></a>
   <span class="r">共有数据:<strong><?php echo ($count); ?></strong> 条</span> </div>
<!-- 
  <div class="cl pd-5 bg-1 bk-gray mt-20">
    <a href="<?php echo U('Admin/User/add');?>" class="btn btn-primary radius"><i class="icon-plus"></i>&nbsp;添加新用户</a></span>
  </div>
 --> 
  <table class="table table-border table-bordered table-bg table-hover table-sort">
    <thead>
      <tr class="text-c">
        <!-- <th width="30"><input name="" type="checkbox" value=""></th> -->
        <th width="80">账号</th>
        <th width="50">顾问姓名</th>
        <th width="50">职位</th>
        <th width="80">所属分公司</th>
        <th width="50">注册日期</th>
        <th width="80">审核</th>
        <!-- 
        <th width="80">操作</th>
         -->
      </tr>
    </thead>
    <tbody>
      <?php if(is_array($advisers)): foreach($advisers as $key=>$vo): ?><tr class="text-c">
        <!-- <td><input name="" type="checkbox" value=""></td> -->
        <td><?php echo ($vo['phone_number']); ?></td>
        <td><?php echo ($vo['user_name']); ?></td>
        <td><?php echo ($vo['post']); ?></td>
        <td><?php echo ($vo['brach_company']); ?></td>
        <td><?php echo ($vo['registration_date']); ?></td>
        <td><a href="<?php echo U('Admin/Adviser/pass');?>?id=<?php echo ($vo['id']); ?>">通过审核</a>&nbsp;&nbsp;<a href="<?php echo U('Admin/Adviser/not_pass');?>?id=<?php echo ($vo['id']); ?>">退回审核</a></td>
        <!-- 
        <td class="f-14 picture-manage">
         <a style="text-decoration:none" class="ml-6" href="<?php echo U('Admin/User/edit');?>?id=<?php echo ($vo['id']); ?>" title="编辑"><i class="icon-edit"></i></a>&nbsp;&nbsp;
         <a style="text-decoration:none" class="ml-5" id="del" href="<?php echo U('Admin/User/delete');?>?id=<?php echo ($vo['id']); ?>" title="删除"><i class="icon-trash"></i></a>
        </td>
         -->
      </tr><?php endforeach; endif; ?>
    </tbody>
  </table>
      
        <div id="pageNav" class="pageNav"><?php echo ($pages); ?></div>
</div>

<script type="text/javascript" src="/hanyaziben_app/Public/Admin/js/jquery.min.js"></script>
<script type="text/javascript" src="/hanyaziben_app/Public/Admin/layer/layer.min.js"></script> 
<script type="text/javascript" src="/hanyaziben_app/Public/Admin/js/pagenav.cn.js"></script> 
<script type="text/javascript" src="/hanyaziben_app/Public/Admin/plugin/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/hanyaziben_app/Public/Admin/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/hanyaziben_app/Public/Admin/js/H-ui.js"></script> 
<script type="text/javascript" src="/hanyaziben_app/Public/Admin/js/H-ui.admin.js"></script>
<script type="text/javascript">
     
 
      //页面加载时绑定按钮点击事件
      $(function(){
          $("#ref").click(function(){
              refresh();
          });
      });
      //点击按钮调用的方法
      function refresh(){
      window.location.reload();//刷新当前页面.
     }
      //删除事件绑定
      $('.ml-5').click(function(){
        
        var res = confirm('是否删除当前邮件');
        if(res){
            return true;
        }else{
            return false;
        }
    })
   
 </script>
</body>
</html>