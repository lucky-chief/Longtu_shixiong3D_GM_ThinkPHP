<?php
class UserAction extends Action{
    // 管理员列表
    public function index(){
		if(!isset($_SESSION[C('USER_AUTH_KEY')])){
		   $this->redirect('Index/login');
		  }else{	
		   $model = M("User");
		   $list = $model->where("id!=1")->select();
		   foreach($list as $key=>$val){
		   $role = M();
		   $id = $list[$key]['id'];
		   $sql="select role.name from gmt_role as role,gmt_role_user as role_user where role_user.user_id='{$id}' and role.id=role_user.role_id";
           $rr = $role->query($sql);
           $role_name = $rr['0']['name'];
		   $list[$key]['last_login_time']=date("Y-m-d H:i:s",$val['last_login_time']);
		   $list[$key]['role_name']=$role_name;
		   }
           $this->assign("list",$list);
		   $this->display();
		  }
	}

	 //添加成员

	public function add_user(){
		if(Role(ACTION_NAME)===false){
		   $this->error("没有权限！",'');
		}
		$model = M("Role");
        $list = $model->select();
		$this->assign("list",$list);
		$this->display();

	}
	
	public function save_user(){
		$data = array();
		$data['username'] = $_POST['username'];
		$data['realname'] = $_POST['realname'];
		$data['nickname'] = $_POST['nickname'];
		$data['password'] = md5($_POST['password']);
		$data['email']    = $_POST['email'];
		$data['remark']   = $_POST['remark'];
		$data['last_login_ip'] = $_SERVER["REMOTE_ADDR"];
		$data['last_login_time'] = time();
		$data['create_time'] = time();
				
		//增加User数据
		$User = M("User");
        $InLast = $User->add($data);
		//获得权限组ID和新增GMID
        $User2 = M("User");
		$result = $User2->where("id=".$InLast)->select();
		$rs['user_id'] = $result['0']['id'];
		$rs['role_id'] = $_POST['role'];
		$role = M("Role_user");
		$lastInsId = $role->add($rs);
		if($lastInsId && $InLast){
		   $Userlog = M("Userlog");
		   $dt['time']     = date("Y-m-d H:i:s",time());
		   $dt['gmId']     = $_SESSION['userid'];
		   $dt['gmName']   = $_SESSION['loginUserName'];
		   $dt['action']   = '增加账号';
		   $dt['userId']   = $InLast;
		   $dt['userName'] = $_POST['username'];
		   $dt['nickName'] = $_POST['nickname'];
		   $dt['status']   = '成功';
		   $dt['ip']       = get_client_ip();
		   //print_r($dt);
		   $Userlog->add($dt);
		   $this->success("",'');
		}else{
		   exit($Userlog->getError());	
		}
	}
	
     //成员修改
	 public function upd_user(){
		//if(Role(ACTION_NAME)===false){
		   //   $this->error("没有权限！",'');
		 //}
		if($_POST){
			$id               = $_POST['userid'];
			$data['username'] = $_POST['username'];
			$data['password'] = md5($_POST['password']);
			$data['nickname'] = $_POST['nickname'];
			$data['realname'] = $_POST['realname'];
			$data['email']    = $_POST['email'];
			$data['remark']   = $_POST['remark'];
			$role_id = $_POST['role'];
			$role = M();
			if($role_id!=""){
			  $sql="update gmt_role_user set role_id ='$role_id'  where user_id='$id'";
			  $role->query($sql);
		    }
			$User = M("User");
			$lastInsId = $User->where('id='.$id)->data($data)->save();
			if($lastInsId){
			 $Userlog = M("Userlog");
			 $dt['time']     = date("Y-m-d H:i:s",time());
			 $dt['gmId']     = $_SESSION['userid'];
			 $dt['gmName']   = $_SESSION['loginUserName'];
			 $dt['action']   = '修改账号';
			 $dt['userId']   = $id;
			 $dt['userName'] = $_POST['username'];
			 $dt['nickName'] = $_POST['nickname'];
			 $dt['status']   = '成功';
			 $dt['ip']       = get_client_ip();
			 $Userlog->add($dt);
		     $this->success("",'index.php?m=User&a=index');
		    }else{
			 exit('账号修改失败！');
		    }
			
		}else{
			$role = M("Role");
			$list = $role->select();
			$this->assign("lt",$list);

			$data['id'] = $_GET['id'];
			$id = $_GET['id'];
			$cc = M();
			$sql="select role.name from gmt_role as role,gmt_role_user as role_user where role_user.user_id='{$id}' and role.id=role_user.role_id";
			$rr = $cc->query($sql);
			$role_name = $rr['0']['name'];
			$this->assign("role_name",$role_name);
	
			$User = M("User");
			$list = $User->where($data)->find();
			$this->assign("list",$list);
			$this->display();
       }
	 }
	 
	 //成员删除
	 public function del_user(){
	   //if(Role(ACTION_NAME)===false){
		     // $this->error("没有权限！",'');
		 //}
		 $id = $_GET['id'];
		 $User = M("User");
		 $result = $User->where('id='.$id)->select();
		 $lastInsId = $User->where('id='.$id)->delete();
		 
		 $RoleUser = M("Role_user");
		 $lastRoleId = $RoleUser->where('user_id='.$id)->delete();
		 if($lastInsId && $lastRoleId){
		   $Userlog = M("Userlog");
		   $dt['time']     = date("Y-m-d H:i:s",time());
		   $dt['gmId']     = $_SESSION['userid'];
		   $dt['gmName']   = $_SESSION['loginUserName'];
		   $dt['action']   = '删除账号';
		   $dt['userId']   = $id;
		   $dt['userName'] = $result[0]['username'];
		   $dt['nickName'] = $result[0]['nickname'];
		   $dt['status']   = '成功';
		   $dt['ip']       = get_client_ip();
		   $Userlog->add($dt);
	      $this->success("",'');
	   }else{
		  exit($User->getError());  
	   }
	 }

	 //分组列表
	 public function user_group(){
		if(Role(ACTION_NAME)===false){
		      $this->error("没有权限！",'');
		 }
	    $role = M("Role");
		$result = $role->select();

        $Node  = M('Node');
		$Node1 = $Node->where("level=2")->order("id")->select();

        foreach($result as $k => $val){
			  $skey  = $k;
			  $id    = $result[$skey]['id'];
		      $Acc   =   M('Access');
			  $Alist =   $Acc->where("role_id=".$id)->order("node_id")->select();
			  $node_list =array();
			  foreach($Alist as $key => $v){
				   $node_list[$key] = $v[node_id]; 
		          }
			  foreach($Node1 as $key => $th){

			     if(in_array($th['id'],$node_list)){

			         $result[$skey]['node'][$key]= 1;

			      }else{

			       $result[$skey]['node'][$key]= 0;
			      }
			 }

		}
        $this->assign("list",$result);
        $this->display();
	 }
	 //添加分组
	 public  function add_usergroup(){
		if(Role(ACTION_NAME)===false){
		      $this->error("没有权限！",'');
		 }
		if($_POST){
		  $data['name']       =  $_POST['groupname'];
		  $data['status']     =  $_POST['status'];
		  $data['remark']     =  $_POST['remark'];
		  $data['pid']        =  $_POST['pid'];
		  $data['create_time'] =  time();
		  $data['update_time'] =  time();

          $Role = M("Role");
          $lastInsId = $Role->add($data);
			  $Acc = M('Access');
			  $node = $_POST['role'];
			  
			  foreach($node as $key => $value){
                  $arr['role_id'] = $lastInsId; 
				  $arr['node_id'] = $value;
				  $lastID= $Acc->add($arr);
			  }
              if($lastID){
		        $this->success("添加成功",'');
			  }
		 

		}else{
		//读取分组列表
	    $role = M('Role');
		$list = $role->select();
        //读取节点列表
		$role = M('Node');
        $result  = $role->where("pid=0")->select();  //项目名称
		$result1 = $role->where("pid=1")->select();
		
		foreach($result1 as $key => $rs ){
		  $result2 = $role->where('pid='.$result1[$key][id])->select();
		  $v = $key;
		  foreach($result2 as $key => $rr){
			 $s = $key;
			 if($rr!=''){
			 $result1[$v]['class2'][$key]['id']    = $rr['id'];
		     $result1[$v]['class2'][$key]['title'] = $rr['title'];
             }
		    $result3 = $role->where('pid='.$rr[id])->select();
           
            foreach($result3 as $key => $val){
			$d = $key;
		    $result1[$v]['class2'][$s]['class3'][$key]['id']    = $val['id'];
		    $result1[$v]['class2'][$s]['class3'][$key]['title'] = $val['title'];
			
			 $result4 = $role->where('pid='.$val[id])->select();

			 foreach($result4 as $key => $val){   //最总目录 封号 禁言

                $result1[$v]['class2'][$s]['class3'][$d]['class4'][$key]['id']    = $val['id'];
		        $result1[$v]['class2'][$s]['class3'][$d]['class4'][$key]['title'] = $val['title'];
			 }
			}
		  }
		}
		
		$this->assign("value",$list);
        $this->assign("list",$result);
		$this->assign("list1",$result1);
	    $this->display();
		}
	 }
	 //修改组信息
	 public function upd_usergroup(){
		if(Role(ACTION_NAME)===false){
		      $this->error("没有权限！",'');
		    }
	    if($_POST){
            $id             = $_POST['groupid'];  //分组ID
			$data['name']   = $_POST['groupname'];
			$data['pid']    = $_POST['pid'];
			$data['status'] = $_POST['status'];
			$data['remark'] = $_POST['remark'];
			$node =  $_POST['role'];
			
          $Role = M("Role");
          $Role->where('id='.$id)->data($data)->save();
		  $Acc = M('Access');
		  $Acc->where("role_id=".$id)->delete();
		  foreach($node as $key => $value){
                  $arr['role_id'] = $id; 
				  $arr['node_id'] = $value;
				  $lastID= $Acc->add($arr);
			  }
              if($lastID){
		        $this->success("添加成功",'index.php?m=User&a=user_group');
			  }
		  //$this->success("","index.php?m=User&a=user_group");
		
		}else{
		 $data['id'] = $_GET['id'];
		 $role = M("Role");
		 $value  = $role->select();
		 $list = $role->where($data)->find();
		 $Acc = M("Access");
		 $Acc_list = $Acc->where("role_id=".$_GET[id])->select();
		   foreach($Acc_list as $key => $str){
			   $node_arr[$key] = $str['node_id'];
		   }

         //读取节点列表
		 $role    = M('Node');
         $result  = $role->where("pid=0")->select();  //项目名称

		  
		  //查找当前组所包含的权限节点
		  
		  foreach($result as $kk => $cc){
		    if(in_array($cc[id],$node_arr)){
			   $result[$kk]['acc_id'] = 1;
			}else{
			   $result[$kk]['acc_id'] = 0;
			}
		  }
		 $result1 = $role->where("pid=1")->select();
		
		 foreach($result1 as $key => $rs ){

		  /******************当前组权限检测*****************/

          if(in_array($rs[id],$node_arr)){
			   $result1[$key]['acc_id'] = 1;
			}else{
			   $result1[$key]['acc_id'] = 0;
			}
		  /**************************************************/
		  $result2 = $role->where('pid='.$result1[$key][id])->select();
		  $v = $key;
		  foreach($result2 as $key => $rr){

		  /******************当前组权限检测*****************/

           if(in_array($rr[id],$node_arr)){
			   $result1[$v]['class2'][$key]['acc_id'] = 1;
			 }else{
			   $result1[$v]['class2'][$key]['acc_id'] = 0;
			}
		  /**************************************************/
			 $s = $key;
			 if($rr!=''){
			   $result1[$v]['class2'][$key]['id']    = $rr['id'];
		       $result1[$v]['class2'][$key]['title'] = $rr['title'];
             }
		    $result3 = $role->where('pid='.$rr[id])->select();
           
            foreach($result3 as $key => $val){

			 /******************当前组权限检测*****************/

			  if(in_array($val[id],$node_arr)){
				   $result1[$v]['class2'][$s]['class3'][$key]['acc_id'] = 1;
				}else{
				   $result1[$v]['class2'][$s]['class3'][$key]['acc_id'] = 0;
				}
		    /**************************************************/
			  $d = $key;
		      $result1[$v]['class2'][$s]['class3'][$key]['id']    = $val['id'];
		      $result1[$v]['class2'][$s]['class3'][$key]['title'] = $val['title'];
			
			  $result4 = $role->where('pid='.$val[id])->select();

			 foreach($result4 as $key => $val){   //最总目录 封号 禁言

			/******************当前组权限检测*****************/

			  if(in_array($val[id],$node_arr)){
				   $result1[$v]['class2'][$s]['class3'][$d]['class4'][$key]['acc_id'] = 1;
				}else{
				   $result1[$v]['class2'][$s]['class3'][$d]['class4'][$key]['acc_id'] = 0;
				}
		      /**************************************************/

                $result1[$v]['class2'][$s]['class3'][$d]['class4'][$key]['id']    = $val['id'];
		        $result1[$v]['class2'][$s]['class3'][$d]['class4'][$key]['title'] = $val['title'];
			 }
			}
		  }
		}
         $this->assign("vtt",$list);
		 $this->assign("list",$result);
		 $this->assign("list1",$result1);
         $this->assign("value",$value);
		 $this->display();
		}
	    
	 }
	  //删除分组
	  public function del_group(){
		 if(Role(ACTION_NAME)===false){
		      $this->error("没有权限！",'');
		    }
	     $id = $_GET['id'];
		 $Role = M("Role");
	     $Role->where('id='.$id)->delete();
	     $this->success("",'');
	  }
 } 
?>
