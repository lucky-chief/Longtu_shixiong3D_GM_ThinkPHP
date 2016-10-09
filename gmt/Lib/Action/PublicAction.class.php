<?php
class PublicAction extends Action {
	// 用户登录页面
	public function login() {
		if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->display();
		}else{
			$this->redirect('Index/index');
		}
	}
	// 登录检测
	public function checkLogin() {
		if(empty($_POST['username'])) {
			$this->error('帐号错误！');
		}elseif (empty($_POST['password'])){
			$this->error('密码必须！');
		}
		elseif (empty($_POST['verify'])){
			$this->error('验证码必须！');
		}
        //生成认证条件
        $map            =   array();
		// 支持使用绑定帐号登录
		$map['username']	= trim($_POST['username']);
        $map["status"]    	= array('gt',0);

		if($_SESSION['verify'] != md5($_POST['verify'])) {
			$this->error('验证码错误！');
		}
		import( '@.ORG.Util.RBAC' );
        $authInfo = RBAC::authenticate($map);
        //使用用户名、密码和状态的方式进行认证
        if(false === $authInfo) {
            $this->error('帐号不存在或已禁用！');
        }else {
            if($authInfo['password'] != md5($_POST['password'])) {
            	$this->error('密码错误！');
            }
            $_SESSION[C('USER_AUTH_KEY')]	=	$authInfo['id'];
	        $_SESSION['userid']             =   $authInfo['id'];
            $_SESSION['email']	            =	$authInfo['email'];
            $_SESSION['loginUserName']		=	$authInfo['nickname'];
            $_SESSION['lastLoginTime']		=	$authInfo['last_login_time'];
			$_SESSION['login_count']	    =	$authInfo['login_count'];
            if($authInfo['username']=='admin') {
            	$_SESSION['administrator']	=	true;
            }
            //保存登录信息
			$User	=	M('User');
			$ip		=	get_client_ip();
			$time	=	time();
            $data = array();
			$data['id']	=	$authInfo['id'];
			$data['last_login_time']	=	$time;
			$data['login_count']	=	array('exp','login_count+1');
			$data['last_login_ip']	=	$ip;
			$User->save($data);

			// 缓存访问权限
            RBAC::saveAccessList();
			if($_SESSION['_ACCESS_LIST']['TLJGMT']||$_SESSION[C('ADMIN_AUTH_KEY')]){
			$this->assign("jumpUrl",__APP__.'?m=Index&a=index');
			$this->success('登录成功！');
			}else{
			 $this->error('没有权限！');
			}

		}
	}
	// 用户登出
   public function logout()
    {
        if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			unset($_SESSION[C('USER_AUTH_KEY')]);
			unset($_SESSION);
			session_destroy();
            $this->assign("jumpUrl",__APP__.'?m=Public&a=login');
            $this->success('登出成功！');
        }else {
            $this->error('已经登出！');
        }
    }
    //加载模版
	public function left(){

	   $this->display();
	}
    public function right(){
        $userId = $_SESSION['userid'];
		$time = time();
		$User = M("User");
		$condition['id'] = $userId;
		$result = $User->where($condition)->find();
		//print_r($result);
		$this->assign('time',$time);
        $this->assign('lastLoginTime',$result['last_login_time']);
		$this->assign('lastLoginIp',$result['last_login_ip']);
	    $this->display();
	}
	public function top(){
	    $this->display();
	}
    //日历
	public function calendar(){
	   $this->display();	
	}
	// 更换密码
    public function changepwd()
    {
		if($_POST){
		$this->checkUser();
        //对表单提交处理进行处理或者增加非表单数据
		if(md5($_POST['verify'])	!= $_SESSION['verify']) {
			$this->error('验证码错误！');
		}
		$map	=	array();
        $map['password']= md5($_POST['oldpwd']);
        if(isset($_POST['username'])) {
            $map['username']	 =	 $_POST['username'];
        }elseif(isset($_SESSION[C('USER_AUTH_KEY')])) {
            $map['id']		=	$_SESSION[C('USER_AUTH_KEY')];
        }
        //检查用户
        $User    =   M("User");
        if(!$User->where($map)->field('id')->find()) {
            $this->error('旧密码不符或者用户名错误！');
        }else {
			$User->password	=	md5($_POST['newpwd']);
			$User->save();
			$this->success('密码修改成功！');
         }
	  }else{
	    $this->display();
	 	  }
    }
   //清空缓存
    public function rtime(){
	   $dir="Runtime";
	   $dh=opendir($dir);
	   $trace = array();
	   while($file=readdir($dh)){
          if($file!="." && $file!="..") {
            $fullpath=$dir."/".$file;
			  if(!is_dir($fullpath)){
				  unlink($fullpath);
				  $trace[] = "已删除文件 ".$fullpath;
			  }else {
				  $sh = opendir($fullpath);
				  while($files = readdir($sh)){
				    if($files!="." && $files!="..") {
				      $filepath=$fullpath."/".$files;
			          if(!is_dir($filepath)){
				        unlink($filepath);
				        $trace[] = "已删除文件 ".$filepath;
				      }
				    }
				  }
				 rmdir($fullpath);
				 $trace[] = "已清空目录 ".$fullpath;
			  }
         }
	   }//while结束
	   $this->assign("trace",$trace);
	   $this->display();
	}
	public function profile() {
			$this->checkUser();
			$User	 =	 M("User");
			$vo	=	$User->getById($_SESSION[C('USER_AUTH_KEY')]);
			$this->assign('vo',$vo);
			$this->display();
		}
   // 修改资料
	public function change() {
		$this->checkUser();
		$User	 =	 M("User");
		$data['username']  =  $_POST['username'];
		$data['nickname']  =  $_POST['nickname'];
		$data['realname']  =  $_POST['realname'];
		$data['email']     =  $_POST['email'];
		$result = $User->where('id='.$_SESSION[C('USER_AUTH_KEY')])->save($data);
		if(false !== $result) {
			$this->success('资料修改成功！');
		}else{
			$this->error('资料修改失败!');
		}
	}
	//检测用户登录
	protected function checkUser() {
		if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->assign('jumpUrl','Public/login');
			$this->error('没有登录');
		}
	}
	function verify(){  
        import('@.ORG.Util.Image');
        //英文验证码
        //Image::buildImageVerify(5,5,gif,90,30,'verify');
        //中文验证码
        Image::buildImageVerify(4);
    }   


}
?>
