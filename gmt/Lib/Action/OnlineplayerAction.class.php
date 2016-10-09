<?php
require('http_interface.php');
require(CONF_PATH.'config.gc.php');//gamecenter
// 本类由系统自动生成，仅供测试用途
class OnlineplayerAction extends Action {
    public function index(){
		if(!isset($_SESSION[C('USER_AUTH_KEY')])){
		   $this->redirect('Public/login');
		  }else{	
			$SER = M('Server');
		    $result = $SER->select();
		    $this->assign("list",$result);
		    $this->display();
		  }
    }
	//添加玩家经验
	public function expAdd(){
		$op = $_POST['action'];
		$gm_req['passportId'] = $_POST['playerName'];
		$gm_req['playerName'] = '1234';
		$gm_req['exp_num'] = $_POST['exp_num'];
		$result = send_GM_Command($op,$gm_req,$_POST['server']);
		if($result['result']){
			echo '命令执行成功';
		 }else{
      	    echo('命令执行失败');
		 }
	}
	//添加道具
	public function itemAdd(){
		$op = $_POST['action'];
		$gm_req['passportId'] = $_POST['playerName'];
		$gm_req['playerName'] = '1234';
		$gm_req['itemId'] = $_POST['itemId'];
		$gm_req['itemNum'] = $_POST['itemNum'];
		$result = send_GM_Command($op,$gm_req,$_POST['server']);
		if($result['result']){
			echo '命令执行成功';
		 }else{
      	    echo('命令执行失败');
		 }
	}
	//设置等级
	public function upLevel(){
		$op = $_POST['action'];
		$gm_req['passportId'] = $_POST['playerName'];
		$gm_req['playerName'] = '1234';
		$gm_req['level'] = $_POST['level'];
		$result = send_GM_Command($op,$gm_req,$_POST['server']);
		if($result['result']){
			echo '命令执行成功';
		 }else{
      	    echo('命令执行失败');
		 }
	}
	/**
	 * 增加经济属性
	 */
	public function addMoney() 
    {
		$op = $_POST['action'];
        $gm_req['passportId'] = $_POST['playerName'];
		$gm_req['playerName'] = '1234';
		$gm_req['addmoney']	= $_POST['addMoney']; 
		$gm_req['moneyType']	= $_POST['moneyType'];
        $result = send_GM_Command($op,$gm_req,$_POST['server']);
		if($result['result']){
			echo '命令执行成功';
		 }else{
      	    echo('命令执行失败');
		 }
    }
     /**
	  * 充值操作
	  */
	public function exePay() 
    {
		$op = $_POST['action'];
        $gm_req['passportId'] = $_POST['playerName'];
		$gm_req['playerName'] = '1234';
		$gm_req['payNum']	= $_POST['payNum']; 
        $result = send_GM_Command($op,$gm_req,$_POST['server']);
		if($result['result']){
			echo '命令执行成功';
		 }else{
      	    echo('命令执行失败');
		 }
    }
		
}
