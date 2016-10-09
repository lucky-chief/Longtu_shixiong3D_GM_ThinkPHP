<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends CommonAction {
    public function index(){
        header("Content-Type:text/html; charset=utf-8");
		if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->display();
		}else{
		    $this->redirect('Public/login');
		}
    }
	
		
}
?>