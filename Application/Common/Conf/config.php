<?php
return array(
	//'配置项'=>'配置值'
	//数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
//     'DB_HOST'   => '106.2.178.118', // 服务器地址
    'DB_HOST'   => '192.168.1.91', // 服务器地址
    'DB_NAME'   => 'CompanyWebsite', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => 'lzd0921', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => '', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_CONFIG1' => array(
        'db_type'  => 'mysql',
        'db_user'  => 'root',
        'db_pwd'   => '256wty@%^WTY',
        'db_host'  => '103.242.175.34',
        'db_port'  => 6666,
        'db_name'  => 'app',
        'db_charset' => 'utf8',
    ),
    'DB_CONFIG2' => array(
        'db_type'  => 'mysql',
        'db_user'  => 'root',
        'db_pwd'   => '256wty@%^WTY',
        'db_host'  => '103.242.175.34',
        'db_port'  => 6666,
        'db_name'  => 'xiangmushuju',
        'db_charset' => 'utf8',
    ),
    'DB_CONFIG3' => array(
        'db_type'  => 'mysql',
        'db_user'  => 'root',
        'db_pwd'   => '256wty@%^WTY',
        'db_host'  => '103.242.175.34',
        'db_port'  => 6666,
        'db_name'  => 'fangchanshuju',
        'db_charset' => 'utf8',
    ),
    'AUTH_CONFIG'=>array(
        'AUTH_ON' => true, //认证开关
        'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_GROUP' => 'auth_group', //用户组数据表名
        'AUTH_GROUP_ACCESS' => 'auth_group_access', //用户组明细表
        'AUTH_RULE' => 'auth_rule', //权限规则表
        'AUTH_USER' => 'admin'//用户信息表
    ),
//     'DEFAULT_CONTROLLER' => 'Login', // 默认控制器名称
//     'DEFAULT_ACTION' => 'login', // 默认操作名称
);