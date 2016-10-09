<?php

require('http_interface.php');
require(CONF_PATH.'config.gc.php');//gamecenter

// 本类由系统自动生成，仅供测试用途
class ServerAction extends Action{
    public function index(){
		if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->display();
		}else{
		    $this->redirect('Public/login');
		}
    }
	
	 //SERVERLIST
	  public function s_list(){
		if(Role(ACTION_NAME)===false){
		      $this->error("没有权限！",'');
		 }
	    $Ser = M();
		$sql = "SELECT * FROM gmt_server";
		$result = $Ser->query($sql);
		//PAGE
		import("@.ORG.Util.Page");
		$count  = count($result);
		$p = new Page($count,15);
		$start = $p->firstRow;
		$end   = $p->listRows;
		$sql.=" limit ".$start.",".$end."";
		$result1 = $Ser->query($sql);

		$page = $p->show();
		$this->assign("list",$result1);
		$this->assign("page",$page);
		$this->display();
	  }
	  //SERVERADD
      public function s_add(){
        if(Role(ACTION_NAME)===false){
		      $this->error("没有权限！",'');
		    }
	    if($_POST){
		  $data['id']     =   trim($_POST['id']);
		  $data['name']     =   trim($_POST['name']);
		  $data['game_url'] =   trim($_POST['game_url']);
		  $data['game_port'] =   trim($_POST['game_port']);
		  $data['gmt_url'] =   trim($_POST['gmt_url']);
		  $data['gmt_port'] =   trim($_POST['gmt_port']);
		  $data['db_user']  =   trim($_POST['db_user']);
		  $data['db_pwd']   =   trim($_POST['db_pwd']);
		  $data['db_host']  =   trim($_POST['db_host']);
		  $data['db_port']  =   trim($_POST['db_port']);
		  $data['db_name']  =   trim($_POST['db_name']);
		  $data['log_host'] =   trim($_POST['log_host']);
		  $data['log_name'] =   trim($_POST['log_name']);
          $data['log_user'] =   trim($_POST['log_user']);
		  $data['log_pwd']  =   trim($_POST['log_pwd']);
		  $data['log_port'] =   trim($_POST['log_port']);
		  $data['remark']   =   trim($_POST['remark']);

		  $SER = M('Server');
		  if(true == $SER->add($data)){
		     $this->success("成功，服务器ID'".$data['id']."'","index.php?m=Server&a=s_list");
		  }
		}else{
		  $this->display();
		}
	  }
	//SERVER DEL
	public function s_del(){
	  
	  $ID  = $_GET['id'];
	  $SER = M("Server");
	  if($LastID = $SER->where("id=".$ID)->delete()){
	    $this->success("删除成功！","index.php?m=Server&a=s_list");
	  }
	}
	//SERVER UPD
	public function s_upd(){
	  
	  $ID  = $_GET['id'];
	  $SER = M("Server");
	  $result = $SER->where("id=".$ID)->select();
	  //echo $SER->getLastSql();
	  $this->assign("list",$result);
	  $this->display();
	}
	//SERVER SAVE
	public function s_save(){
	 $ID = $_POST['sid'];

	 $data['id']      = $ID;
	 $data['game_url']= trim($_POST['game_url']);
	 $data['game_port'] =   trim($_POST['game_port']);
     $data['gmt_url'] =   trim($_POST['gmt_url']);
     $data['gmt_port'] =   trim($_POST['gmt_port']);
	 $data['name']    = trim($_POST['name']);
	 $data['db_user'] = trim($_POST['db_user']);
	 $data['db_pwd']  = trim($_POST['db_pwd']);
	 $data['db_host'] = trim($_POST['db_host']);
	 $data['db_port'] = trim($_POST['db_port']);
	 $data['db_name'] = trim($_POST['db_name']);
	 $data['log_host']= trim($_POST['log_host']);
	 $data['log_name']= trim($_POST['log_name']);
     $data['log_user']= trim($_POST['log_user']);
	 $data['log_pwd'] = trim($_POST['log_pwd']);
	 $data['log_port']= trim($_POST['log_port']);
	 $data['remark']  = trim($_POST['remark']);
	 
	 $SER = M("Server");
	 if($LastID = $SER->where("id=".$ID)->save($data)){
	    $this->success("数据修改成功！","?m=Server&a=s_list");
	 }
	}
	
	//SERVER NOTICE
	public function notice(){
	  $NOTICE = M("Notice");
	  $result = $NOTICE->select();
	  if (count($result) == 0) {
	   //插入一个
	   $data = array();
	   $data['notice'] = '';
	   $id = $NOTICE->add($data);
	   $data['id'] = $id;
	   $result[0] = $data;
	  }
	  $this->assign("list",$result);
	  $this->display();
	}
	
	//notice SAVE
	public function save_notice(){
	 $ID = $_POST['id'];
	 $data['id']      = $ID;
	 $data['notice']= trim($_POST['notice']);
	 $NOTICE = M("Notice");
	 if(false !== $NOTICE->where("id=".$ID)->save($data)){
	    $this->success("数据修改成功！","?m=Server&a=notice");
	 }
	}
	
	//发布server数据到gameCenter
    public function publish_server(){
        
    	 $gc_req = build_request("updateServerList",GAMECENTERKEY);
         
         $result = request_admin($gc_req, GAMECENTERURL);
    	 
    	 if ($result['result'] == true) {
    	    $this->success("发布成功！","?m=Server&a=s_list");
    	 }
    	 else {
    	    $this->error("发布失败！".$result['info'],"?m=Server&a=s_list");
    	}
	}
	
	//发布notice数据到gameCenter
    public function publish_notice(){
        
    	 $gc_req = build_request("updateNotice",GAMECENTERKEY);
         
         $result = request_admin($gc_req, GAMECENTERURL);
    	 
    	 if ($result['result'] == true) {
    	    $this->success("发布成功！","?m=Server&a=notice");
    	 }
    	 else {
    	    $this->error("发布失败！".$result['info'],"?m=Server&a=notice");
    	}
	}	
}
?>
