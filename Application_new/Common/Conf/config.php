<?php
return array(
	//'配置项'=>'配置值'

    'SESSION_PREFIX'        =>  'shop_',      // session 前缀
    'COOKIE_PREFIX'         =>  'shop_',      // Cookie前缀 避免冲突

    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'taolu_five',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'shop_',    // 数据库表前缀

    //'SHOW_PAGE_TRACE'       =>  true,     //页面追踪信息
    //权限认证
    'AUTH_CONFIG' => array(
        'AUTH_ON' => true, //认证开关
        'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_GROUP' => 'shop_auth_group', //用户组数据表名
        'AUTH_GROUP_ACCESS' => 'shop_auth_group_access', //用户组明细表
        'AUTH_RULE' => 'shop_auth_rule', //权限规则表
        'AUTH_USER' => 'shop_admin'//用户信息表
   )
);