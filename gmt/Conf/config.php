<?php
return array(
    "TMPL_CACHE_ON" =>false, 
    "TOKEN_ON" =>true,                             // 是否开启令牌验证
    "TOKEN_NAME" =>'__hash__',                     // 令牌验证的表单隐藏字段名称
    "TOKEN_TYPE" =>'md5',                          // 令牌验证哈希规则 
	  "DB_DEPLOY_TYPE" =>0,                          // 数据库部署方式 ：0 集中式（单一服务器） 1 分布式（主从朋务器）
	  "DB_RW_SEPARATE" =>false,                      // 数据库读写是否分离，分布式数据库方式下面有效
	  "DB_FIELDS_CACHE" =>false,                     // 开启数据表字段缓存
	  "DB_TYPE" =>'mysql',                           // 数据库类型
	  "DB_HOST" =>'127.0.0.1',                       // 数据库服务器地址
	  "DB_NAME" =>'gmt',                            // 数据库名称
	  "DB_USER" =>'gmt',                           // 数据库用户名
	  "DB_PWD" =>'gmt',                            // 数据库密码
	  "DB_PORT" =>'3306',                            // 数据库使用的端口 
	  "DB_PREFIX" =>'gmt_',                          // 数据库表前缀
	  "DB_SUFFIX" =>'',                              // 数据库的表后缀
	  "DB_FIELDTYPE_CHECK" =>false,                  // 是否迕行字段类型检查 
	  'SHOW_PAGE_TRACE'=> 0,                         // 显示调试信息
	  'URL_MODEL'             => 0,                  // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
	  'VAR_PAGE'              => 'p',                // 分页参数
    'APP_AUTOLOAD_PATH'=>'@.TagLib',
    'SESSION_AUTO_START'=>true,
    'USER_AUTH_ON'              =>true,
    'USER_AUTH_TYPE'			=>1,		       // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY'             =>'authId',	       // 用户认证SESSION标记
    'ADMIN_AUTH_KEY'			=>'administrator',
    'USER_AUTH_MODEL'           =>'User',	       // 默认验证数据表模型
    'AUTH_PWD_ENCODER'          =>'md5',	       // 用户认证密码加密方式
    'USER_AUTH_GATEWAY'         =>'/Public/login', // 默认认证网关
    'NOT_AUTH_MODULE'           =>'Public',	       // 默认无需认证模块
    'REQUIRE_AUTH_MODULE'       =>'',		       // 默认需要认证模块
    'NOT_AUTH_ACTION'           =>'',		       // 默认无需认证操作
    'REQUIRE_AUTH_ACTION'       =>'',		       // 默认需要认证操作
    'GUEST_AUTH_ON'             =>false,           // 是否开启游客授权访问
    'GUEST_AUTH_ID'             =>0,               // 游客的用户ID
    'DB_LIKE_FIELDS'            =>'title|remark',
    'RBAC_ROLE_TABLE'           =>'gmt_role',
    'RBAC_USER_TABLE'           =>'gmt_role_user',
    'RBAC_ACCESS_TABLE'         =>'gmt_access',
    'RBAC_NODE_TABLE'           =>'gmt_node',
	  /* 语言设置 */
    'LANG_SWITCH_ON'=>true,
	  'DEFAULT_LANG'=>'zh-cn',
	  'LANG_AUTO_DETECT'=>false,
	  'LANG_LIST'=>'en-us,zh-cn,zh-tw',

	    
);
?>

