<?php
// 配置文件增加设置
// USER_AUTH_ON 是否需要认证
// USER_AUTH_TYPE 认证类型
// USER_AUTH_KEY 认证识别号
// REQUIRE_AUTH_MODULE  需要认证模块
// NOT_AUTH_MODULE 无需认证模块
// USER_AUTH_GATEWAY 认证网关
// RBAC_DB_DSN  数据库连接DSN
// RBAC_ROLE_TABLE 角色表名称
// RBAC_USER_TABLE 用户表名称
// RBAC_ACCESS_TABLE 权限表名称
// RBAC_NODE_TABLE 节点表名称

class RBAC {
    // 认证方法
    static public function authenticate($map,$model='')
    {
        if(empty($model)) $model =  C('USER_AUTH_MODEL');
        //使用给定的Map进行认证
        return M($model)->where($map)->find();
		
    }

    //用于检测用户权限的方法,并保存到Session中  传入用户的ID 2.此方法不返回值，只是设置 $_SESSION['_ACCESS_LIST']的值，其中包含了所有该用户对应的用户组的有权限操作的所有节点$_SESSION['_ACCESS_LIST']['项目名']['模块名']['操作名']，以后判断权限就是判断当前项目，模块和操作是否在 $_SESSION['_ACCESS_LIST']中能找到。
    static function saveAccessList($authId=null)
    {
        if(null===$authId)   $authId = $_SESSION[C('USER_AUTH_KEY')]; 

        // 如果使用普通权限模式，保存当前用户的访问权限列表
        // 对管理员开发所有权限
        if(C('USER_AUTH_TYPE') !=2 && !$_SESSION[C('ADMIN_AUTH_KEY')] ){  //判断认证方式和是否是管理员
			$_SESSION['_ACCESS_LIST']	=	RBAC::getAccessList($authId);
            return ;
	}else{
		
		 $Role  =  M('Node');
		 $result=$Role->select();  
		 foreach($result as $key=>$val){
		   $name = $val['name'];
		   $_SESSION['_ACCESS_LIST'][$name] = $val['id'];
		 }
		 return;
	}
}
	// 取得模块的所属记录访问权限列表 返回有权限的记录ID数组
	static function getRecordAccessList($authId=null,$module='') {
        if(null===$authId)   $authId = $_SESSION[C('USER_AUTH_KEY')];
        if(empty($module))  $module	=	MODULE_NAME;
        //获取权限访问列表
        $accessList = RBAC::getModuleAccessList($authId,$module);
        return $accessList;
	}

    //检查当前模块和操作是否需要认证
    static function checkAccess()
    {
        //如果项目要求认证，并且当前模块需要认证，则进行权限认证
        if( C('USER_AUTH_ON') ){
			$_module	=	array();
			$_action	=	array();
            if("" != C('REQUIRE_AUTH_MODULE')) {
                //需要认证的模块
                $_module['yes'] = explode(',',strtoupper(C('REQUIRE_AUTH_MODULE')));
            }else {
                //无需认证的模块
                $_module['no'] = explode(',',strtoupper(C('NOT_AUTH_MODULE'))); //PUBLIC
            }
            //检查当前模块是否需要认证
            if((!empty($_module['no']) && !in_array(strtoupper(MODULE_NAME),$_module['no'])) || (!empty($_module['yes']) && in_array(strtoupper(MODULE_NAME),$_module['yes']))) {
				if("" != C('REQUIRE_AUTH_ACTION')) {
					//需要认证的操作
					$_action['yes'] = explode(',',strtoupper(C('REQUIRE_AUTH_ACTION')));
				}else {
					//无需认证的操作
					$_action['no'] = explode(',',strtoupper(C('NOT_AUTH_ACTION')));
				}
				//检查当前操作是否需要认证
				if((!empty($_action['no']) && !in_array(strtoupper(ACTION_NAME),$_action['no'])) || (!empty($_action['yes']) && in_array(strtoupper(ACTION_NAME),$_action['yes']))) {
					return true;
				}else {
					return false;
				}
            }else {
                return false;
            }
        }
        return false;
    }

	// 登录检查
	static public function checkLogin() {
        //检查当前操作是否需要认证
        if(RBAC::checkAccess()) {
            //检查认证识别号
            if(!$_SESSION[C('USER_AUTH_KEY')]) {
                if(C('GUEST_AUTH_ON')) {
                    // 开启游客授权访问
                    if(!isset($_SESSION['_ACCESS_LIST']))
                        // 保存游客权限
                        RBAC::saveAccessList(C('GUEST_AUTH_ID'));
                }else{
                    // 禁止游客访问跳转到认证网关
                    redirect(PHP_FILE.C('USER_AUTH_GATEWAY'));
                }
            }
        }
        return true;
	}

    //权限认证的过滤器方法。就是检测当前项目模块操作是否在$_SESSION['_ACCESS_LIST']数组中，也就是说在 $_SESSION['_ACCESS_LIST'] 数组中$_SESSION['_ACCESS_LIST']['当前操作']['当前模块']['当前操作']是否存在。如果存在表示有权限否则返回flase
    static public function AccessDecision($appName=APP_NAME)
    {
        //检查是否需要认证
        if(RBAC::checkAccess()) {
            //存在认证识别号，则进行进一步的访问决策
            $accessGuid   =   md5($appName.MODULE_NAME.ACTION_NAME);
            if(empty($_SESSION[C('ADMIN_AUTH_KEY')])) {
                if(C('USER_AUTH_TYPE')==2) {
                    //加强验证和即时验证模式 更加安全 后台权限修改可以即时生效
                    //通过数据库进行访问检查
                    $accessList = RBAC::getAccessList($_SESSION[C('USER_AUTH_KEY')]);
                }else {
                    // 如果是管理员或者当前操作已经认证过，无需再次认证
                    if( $_SESSION[$accessGuid]) {
                        return true;
                    }
                    //登录验证模式，比较登录后保存的权限访问列表
                    $accessList = $_SESSION['_ACCESS_LIST'];
                }
                //判断是否为组件化模式，如果是，验证其全模块名
                $module = defined('P_MODULE_NAME')?  P_MODULE_NAME   :   MODULE_NAME;

                if(!isset($accessList[strtoupper($appName)][strtoupper($module)][strtoupper(ACTION_NAME)])) {
                    $_SESSION[$accessGuid]  =   false;
                    return true;
                }
                else {
                    $_SESSION[$accessGuid]	=	true;
                }
            }else{
                //管理员无需认证
				return true;
			}
        }
        return true;
    }

    /**
     * 取得当前认证号的所有权限列表，通过查询数据库 返回权限列表 $_SESSION['_ACCESS_LIST']的值
     * @param integer $authId 用户ID
     */
    static public function getAccessList($authId)
    {
        // Db方式权限数据
        $db     =   Db::getInstance(C('RBAC_DB_DSN'));
        $table  = array('role'=>C('RBAC_ROLE_TABLE'),'user'=>C('RBAC_USER_TABLE'),'access'=>C('RBAC_ACCESS_TABLE'),'node'=>C('RBAC_NODE_TABLE'));
		//读取项目权限
        $sql    =   "select node.id,node.name from ".
                    $table['role']." as role,".
                    $table['user']." as user,".
                    $table['access']." as access ,".
                    $table['node']." as node ".
                    "where user.user_id='{$authId}' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and access.node_id=node.id and node.level=1 and node.status=1";

        $apps =   $db->query($sql);
        $access =  array();
        foreach($apps as $key=>$app) {
            $appId	  =	 $app['id'];
            $appName  =	 $app['name'];
			$access   =  array();        
            $access[$appName]   =  $appId;     
            $sql    =   "select node.id,node.name from ".
                    $table['role']." as role,".
                    $table['user']." as user,".
                    $table['access']." as access ,".
                    $table['node']." as node ".
                    "where user.user_id='{$authId}' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and access.node_id=node.id and node.level=2 and node.pid={$appId} and node.status=1";
            $modules  =   $db->query($sql);
            foreach ($modules as $m){
					$access[$m['name']]  = $m['id'];
             }
            // 判断是否存在公共模块的权限,在GMT里面没有涉及到
			/*
            $publicAction  = array();
            foreach($modules as $key=>$module) {
                $moduleId	 =	 $module['id'];
                $moduleName = $module['name'];
                if('PUBLIC'== strtoupper($moduleName)) {
                $sql    =   "select node.id,node.name from ".
                    $table['role']." as role,".
                    $table['user']." as user,".
                    $table['access']." as access ,".
                    $table['node']." as node ".
                    "where user.user_id='{$authId}' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and access.node_id=node.id and node.level=3 and node.pid={$moduleId} and node.status=1";
                    $rs =   $db->query($sql);
                    foreach ($rs as $a){
                        $publicAction[$a['name']]	 =	 $a['id'];
                    }
                    unset($modules[$key]);
                    break;
                } 
            }  */
            // 依次读取模块的操作权限
			// L3
            foreach($modules as $key=>$module) {
				$action = array();
                $moduleId	 =	 $module['id'];
                $moduleName = $module['name'];
                $sql    =   "select node.id,node.name from ".
                    $table['role']." as role,".
                    $table['user']." as user,".
                    $table['access']." as access ,".
                    $table['node']." as node ".
                    "where user.user_id='{$authId}' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and access.node_id=node.id and node.level=3 and node.pid={$moduleId} and node.status=1";
                $rs =   $db->query($sql);
				foreach ($rs as $c){
                    //$action[$a['name']]	 =	 $a['id'];
					$access[$c['name']]  = $c['id'];
                }
				//L4
				foreach($rs as $key=>$rs){
				 $rsId	 =	 $rs['id'];
                 $rsName =   $rs['name'];
                 $sql    =   "select node.id,node.name from ".
                    $table['role']." as role,".
                    $table['user']." as user,".
                    $table['access']." as access ,".
                    $table['node']." as node ".
                    "where user.user_id='{$authId}' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and access.node_id=node.id and node.level=4 and node.pid={$rsId} and node.status=1";
				  $rt =   $db->query($sql);
				   foreach ($rt as $b){
                    //$action[$a['name']]	 =	 $a['id'];
					 $access[$b['name']]  = $b['id'];
                  }
				 //L5
				foreach($rt as $key=>$rt){
				 $rtId	 =	 $rt['id'];
                 $rtName =   $rt['name'];
                 $sql    =   "select node.id,node.name from ".
                    $table['role']." as role,".
                    $table['user']." as user,".
                    $table['access']." as access ,".
                    $table['node']." as node ".
                    "where user.user_id='{$authId}' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and access.node_id=node.id and node.level=5 and node.pid={$rtId} and node.status=1";
				  $rr =   $db->query($sql);
				  //print_r($rr);exit(0);
				  foreach ($rr as $a){
                    //$action[$a['name']]	 =	 $a['id'];
					$access[$a['name']]  = $a['id'];
                    }
				  }

				}
                
				//$access[strtoupper($rtName)]  =   array_change_key_case($action,CASE_UPPER);
                // 和公共模块的操作权限合并
                //$action += $publicAction;
                //$access   =  array_change_key_case($action,CASE_UPPER);
            }
        }
        return $access; //返回权限值数组
    }

	// 读取模块所属的记录访问权限
	static public function getModuleAccessList($authId,$module) {
        // Db方式
        $db     =   Db::getInstance(C('RBAC_DB_DSN'));
        $table = array('role'=>C('RBAC_ROLE_TABLE'),'user'=>C('RBAC_USER_TABLE'),'access'=>C('RBAC_ACCESS_TABLE'));
        $sql    =   "select access.node_id from ".
                    $table['role']." as role,".
                    $table['user']." as user,".
                    $table['access']." as access ".
                    "where user.user_id='{$authId}' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and  access.module='{$module}' and access.status=1";
        $rs =   $db->query($sql);
        $access	=	array();
        foreach ($rs as $node){
            $access[]	=	$node['node_id'];
        }
		return $access;
	}
}