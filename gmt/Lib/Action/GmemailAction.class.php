<?php
require('http_interface.php');
require(CONF_PATH.'config.gc.php');//gamecenter
class GmemailAction extends Action {
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

	public function sendMail() 
    {
//收件人
//标题
//发件人
//内容
//附件内容
//钻石
//附件内容
		
		$op = $_POST['action'];
		$gm_req['owner'] = $_POST['dstName'];
		$gm_req['title'] = $_POST['title'];
		$gm_req['sender'] = $_POST['srcName'];
		$gm_req['content'] = $_POST['content'];
		$gm_req['superMoney'] = $_POST['superMoney'];
		$gm_req['finance'] = $_POST['finance'];
        //检索邮件标题
		foreach($this->keyword as $key=>$val){
			$rs = stristr($title,$val);
			if($rs){
			 echo "标题包含敏感词 ".$val." 请重新填写！";
			 exit(0);
			}
	    }
		//检索邮件内容
		foreach($this->keyword as $key=>$val){
			$rs = stristr($content,$val);
			if($rs){
			 echo "内容包含敏感词 ".$val." 请重新填写！";
			 exit(0);
			}
		}
		
        $att = explode("@",$_POST['attach']);
		echo $att;
		foreach ($att as $val)
		{
			if ($str != "")
			{
				$str =$str.",";
			}
			$arr = explode("，",$val);
			$itemId = substr($arr[1],9);
			$amount = substr($arr[2],9);
			$str=$str.strval($itemId).",".strval($amount);
		}
		$gm_req['items'] = $str;
		$result = send_GM_Command($op,$gm_req,$_POST['server']);
		if($result['result']){
			echo '命令执行成功';
		 }else{
			echo('命令执行失败');
		 }
    }	 
}
?>
