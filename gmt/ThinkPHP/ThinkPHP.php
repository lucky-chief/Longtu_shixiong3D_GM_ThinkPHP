<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id: ThinkPHP.php 2 2015-11-09 05:26:46Z renkai $

// ThinkPHP 入口文件

//记录开始运行时间
$GLOBALS['_beginTime'] = microtime(TRUE);
// 记录内存初始使用
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
if(MEMORY_LIMIT_ON) $GLOBALS['_startUseMems'] = memory_get_usage();
defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');
defined('RUNTIME_PATH') or define('RUNTIME_PATH',APP_PATH.'Runtime/');
defined('APP_DEBUG') or define('APP_DEBUG',false); // 是否调试模式
$runtime = defined('MODE_NAME')?'~'.strtolower(MODE_NAME).'_runtime.php':'~runtime.php';
defined('RUNTIME_FILE') or define('RUNTIME_FILE',RUNTIME_PATH.$runtime);

$CFG['client.assets']="E:\\Flash Project\\mhclient\\assets"; 
$CFG['mongo.host']="127.0.0.1:27017"; 
$CFG['mongo.db']="czj_arpg";        

define('IMG_URL', 'http://10.10.0.132/passport/assets');
define('SKEY', '4cb2c636e384993af6fdf7a854144187');
// GM server 
define('ADMIN_URL', 'http://172.16.50.1:8208');
//httpidip 
define('IDIP_URL', 'http://115.182.42.24:8009');
define('USERID', 'admin');
define('PASSWD', 'admin');


if(!APP_DEBUG && is_file(RUNTIME_FILE)) {
    // 部署模式直接载入运行缓存
    require RUNTIME_FILE;
}else{
    // 系统目录定义
    defined('THINK_PATH') or define('THINK_PATH', dirname(__FILE__).'/');
    // 加载运行时文件
    require THINK_PATH.'Common/runtime.php';
}