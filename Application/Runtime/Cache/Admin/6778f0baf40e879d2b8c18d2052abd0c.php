<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML>
<html style="overflow-y:hidden;">
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/favicon.ico" >
<LINK rel="Shortcut Icon" href="/favicon.ico" />

<link href="/hanyaziben_app/Public/Admin/css/H-ui.css" rel="stylesheet" type="text/css" />
<link href="/hanyaziben_app/Public/Admin/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="/hanyaziben_app/Public/Admin/font/font-awesome.min.css"/>


<title>H-ui.admin v2.1</title>
<meta name="keywords" content="H-ui.admin v2.1,H-ui网站后台模版,后台模版下载,后台管理系统模版,HTML后台模版下载">
<meta name="description" content="H-ui.admin v2.1，是一款由国人开发的轻量级扁平化网站后台模板，完全免费开源的网站后台管理系统模版，适合中小型CMS后台系统。">
</head>
<body>
<div class="cl Hui-main">
  <aside class="Hui-aside" style="">
    <input runat="server" id="divScrollValue" type="hidden" value="" />
    <div class="menu_dropdown bk_2">
       <dl id="menu-admin">
        <dt><i class="icon-key"></i> 权限管理<b></b></dt>
        <dd>
          <ul>
            <li><a href="<?php echo U('Admin/Auth/addgroup');?>" target="rightFrame">&nbsp;&nbsp;|— 添加组</a></li>
            <li><a href="<?php echo U('Admin/Auth/groupmanager');?>" target="rightFrame">&nbsp;&nbsp;|— 组管理</a></li>
            <li><a href="<?php echo U('Admin/Auth/addrule');?>" href="" target="rightFrame">&nbsp;&nbsp;|— 添加规则</a></li>
            <li><a href="<?php echo U('Admin/Auth/rulelist');?>" target="rightFrame">&nbsp;&nbsp;|— 规则管理</a></li>
            <li><a href="<?php echo U('Admin/Auth/adduser');?>" target="rightFrame">&nbsp;&nbsp;|— 添加用户</a></li>
            <li><a href="<?php echo U('Admin/Auth/userlist');?>"  target="rightFrame">&nbsp;&nbsp;|— 用户管理</a></li>
          </ul>
        </dd>
       </dl>
       <dl id="menu-comments">
        <dt><i class="icon-comments"></i> 轮播图管理<b></b></dt>
        <dd>
          <ul>
            <li><a  href="<?php echo U('Admin/Banner/index');?>" target="rightFrame">&nbsp;&nbsp;|— 首页轮播图</a></li>
            <li><a  href="<?php echo U('Admin/Banner/adviser');?>" target="rightFrame">&nbsp;&nbsp;|— 投顾之家轮播图</a></li>
          </ul>
        </dd>
       </dl>
       <dl id="menu-comments">
        <dt><i class="icon-comments"></i> 资讯管理<b></b></dt>
        <dd>
          <ul>
            <li><a  href="<?php echo U('Admin/Information/index');?>" target="rightFrame">&nbsp;&nbsp;|— 首页最新资讯</a></li>
            <li><a  href="<?php echo U('Admin/Information/adviser');?>" target="rightFrame">&nbsp;&nbsp;|— 投顾资讯</a></li>
          </ul>
        </dd>
       </dl>
       <dl id="menu-comments">
        <dt><i class="icon-comments"></i> 服务管理<b></b></dt>
        <dd>
          <ul>
            <li><a  href="<?php echo U('Admin/Service/index');?>" target="rightFrame">&nbsp;&nbsp;|— 服务问答</a></li>
            <li><a  href="<?php echo U('Admin/Service/add');?>" target="rightFrame">&nbsp;&nbsp;|— 服务问答添加</a></li>
          </ul>
        </dd>
       </dl>
       <dl id="menu-comments">
        <dt><i class="icon-comments"></i> 客户管理<b></b></dt>
        <dd>
          <ul>
            <li><a  href="<?php echo U('Admin/User/index');?>" target="rightFrame">&nbsp;&nbsp;|— 客户列表</a></li>
          </ul>
        </dd>
       </dl>

	   <dl id="menu-comments">
        <dt><i class="icon-comments"></i> 顾问管理<b></b></dt>
        <dd>
          <ul>
            <li><a  href="<?php echo U('Admin/Adviser/index');?>" target="rightFrame">&nbsp;&nbsp;|— 待审核顾问列表</a></li>
            <li><a  href="<?php echo U('Admin/Adviser/real');?>" target="rightFrame">&nbsp;&nbsp;|— 顾问列表</a></li>
          </ul>
        </dd>
       </dl>
<!-- 
        <dl id="menu-comments">
        <dt><i class="icon-comments"></i> 资产查询记录管理<b></b></dt>
        <dd>
          <ul>
            <li><a  href="<?php echo U('Admin/Record/index');?>" target="rightFrame">资产查询记录列表</a></li>
          </ul>
        </dd>
      </dl>  
 -->      
      <dl id="menu-page">
        <dt><i class="icon-paste"></i> 理财师管理<b></b></dt>
        <dd>
          <ul>
            <li><a href="<?php echo U('Admin/Book/index');?>" target="rightFrame">&nbsp;&nbsp;|— 预约理财师列表</a></li>
            <li><a href="<?php echo U('Admin/Planner/index');?>"target="rightFrame">&nbsp;&nbsp;|— 明星理财师列表</a></li>
            <li><a href="<?php echo U('Admin/Planner/add');?>"target="rightFrame">&nbsp;&nbsp;|— 明星理财师添加</a></li>
          </ul>
        </dd>
      </dl>

      <dl id="menu-article">
        <dt><i class="icon-edit"></i> 积分商城<b></b></dt>
        <dd>
          <ul>
          	<dt>&nbsp;&nbsp;&nbsp;&nbsp;|— 商城控制</dt>
            <li><a  href="<?php echo U('Admin/Prize/switch_button');?>"target="rightFrame">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|— 商城开关</a></li>
          	<dt>&nbsp;&nbsp;&nbsp;&nbsp;|— 积分礼品</dt>
            <li><a  href="<?php echo U('Admin/Prize/index');?>"target="rightFrame">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|— 礼品列表</a></li>
            <li><a  href="<?php echo U('Admin/Prize/add');?>"target="rightFrame">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|— 礼品信息录入</a></li>
            <dt>&nbsp;&nbsp;&nbsp;&nbsp;|— 兑换记录</dt> 
            <li><a  href="<?php echo U('Admin/Prize/record');?>"target="rightFrame">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|— 订单维护</a></li>
          </ul>
        </dd>
      </dl>
      
      <dl id="menu-article">
        <dt><i class="icon-edit"></i> 项目管理<b></b></dt>
        <dd>
          <ul>
            <li><a  href="<?php echo U('Admin/Project/index');?>"target="rightFrame">&nbsp;&nbsp;|— 项目列表</a></li>
          </ul>
          <ul>
            <li><a  href="<?php echo U('Admin/Project/add');?>"target="rightFrame">&nbsp;&nbsp;|— 项目数据录入</a></li>
          </ul>
        </dd>
      </dl>
      
      <dl id="menu-comments">
        <dt><i class="icon-comments"></i> 每日战报<b></b></dt>
        <dd>
          <ul>
            <li><a  href="<?php echo U('Admin/Report/index');?>" target="rightFrame">&nbsp;&nbsp;|— 每日战报列表</a></li>
            <li><a  href="<?php echo U('Admin/Report/add');?>" target="rightFrame">&nbsp;&nbsp;|— 每日战报录入</a></li>
          </ul>
        </dd>
       </dl>
<!-- 
       <dl id="menu-article">
        <dt><i class="icon-edit"></i> 新闻管理<b></b></dt>
        <dd>
          <ul>
            <li><a  href="<?php echo U('Admin/News/index');?>"target="rightFrame">新闻管理</a></li>
            <li><a  href="<?php echo U('Admin/News/add');?>"target="rightFrame">添加新闻</a></li>
            <dt>新闻分类</dt> 
            <li><a  href="<?php echo U('Admin/NewsCate/index');?>"target="rightFrame">新闻分类列表</a></li>
            <li><a  href="<?php echo U('Admin/NewsCate/add');?>"target="rightFrame">添加新闻分类</a></li>
          </ul>
        </dd>
      </dl>

       <dl id="menu-comments">
        <dt><i class="icon-comments"></i> 评论管理<b></b></dt>
        <dd>
          <ul>
            <li><a  href="<?php echo U('Admin/comment/index');?>" target="rightFrame">评论列表</a></li>
          </ul>
        </dd>
      </dl> 
      <dl id="menu-comments">
        <dt><i class="icon-comments"></i> 图片管理<b></b></dt>
        <dd>
          <ul>
            <li><a  href="<?php echo U('Admin/Images/index');?>" target="rightFrame">图片列表</a></li>
          </ul>
        </dd>
      </dl>
      
      <dl id="menu-comments">
        <dt><i class="icon-comments"></i> 公告管理<b></b></dt>
        <dd>
          <ul>
            <li><a  href="<?php echo U('Admin/Notice/index');?>" target="rightFrame">公告列表</a></li>
          </ul>
        </dd>
      </dl>
 -->
    </div>
    
  </aside>
  <div class="dislpayArrow"><a class="pngfix" href="javascript:void(0);"></a></div>
</div>
<script type="text/javascript" src="/hanyaziben_app/Public/Admin/js/jquery.min.js"></script>
<script type="text/javascript" src="/hanyaziben_app/Public/Admin/js/Validform_v5.3.2_min.js"></script> 
<script type="text/javascript" src="/hanyaziben_app/Public/Admin/layer/layer.min.js"></script>
<script type="text/javascript" src="/hanyaziben_app/Public/Admin/js/H-ui.js"></script>
<!-- <script type="text/javascript" src="/hanyaziben_app/Public/Admin/js/H-ui.admin.js"></script> -->

</body>
</html>