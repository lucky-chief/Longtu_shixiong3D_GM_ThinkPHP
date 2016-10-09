<?php

error_reporting(E_ALL^E_WARNING^E_NOTICE);
define('SKEY', '4cb2c636e384993af6fdf7a854144187');
define('HOST','127.0.0.1');
define('USERNAME','root');
define('PASSWORD','123456');
define('DBNAME','gmt');
//define('MSERVER_SQL',"select `db_name` from `mysql` where `status`=1");
/*
define('HOST','127.0.0.1');
define('USERNAME','root');
define('PASSWORD','12345');
define('DBNAME','gmts');
*/

function build_request($action,$key) {
    $ts = time();
    $sign = md5($action . md5($ts . $key));
    return array(
        'action' => $action,
        'ts' => $ts,
        'sign' => $sign
    );
}



function request_admin($request) {
    // 构造 HTTP POST 请求
    $query = http_build_query($request);

    // 用 curl 发送 HTTP POST 请求
    $curl = curl_init();
    $options = array(
                    CURLOPT_URL => ADMIN_URL,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $query,
                    CURLOPT_HEADER => false,
                    CURLOPT_RETURNTRANSFER => true
                    );
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);

    // 解析服务器的响应
    if (curl_error($curl)) {
        $msg = sprintf("GMT命令运行失败", ADMIN_URL,curl_error($curl));
        return array('result'=>false, 'info'=>$msg);
    }
    else {
        $info = curl_getinfo($curl);
        if ($info['http_code'] == 200) {
            $result = json_decode($response, true);
            if (is_array($result)) {
                //return $result;
				return "命令运行成功！";
            }
        }
        $msg = sprintf("code=%d, response=%s\n", $info['http_code'], $response);
        return array('result'=>false, 'info'=>$msg);
    }
}

class GMT{
    private static $log;
//    private static $auth;
    private static $userid;
    private static $connect;
    private static $instance;
    private static $sql;
    private function __construct($userid){
        self::$connect[]=connect(HOST,USERNAME,PASSWORD,DBNAME);
        self::$log=new gamelog($userid,self::$connect[0]);
        //self::$auth=new authfilter($userid);
        self::$userid=$userid;
    }
	
    public static function construct($userid){
        if (!isset(self::$instance)){
            $c = __CLASS__;
            self::$instance = new $c($userid);
        }
        return self::$instance;
    }

    public function __toString(){
        return strval(self::$log->get_sql().'userid:'.self::$userid);
    }

    public function __clone(){
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function get_identifier(){
        return self::$connect;
    }

    public function __destruct(){
       // unset(self::$auth);
        self::$log='';
        mysql_close(self::$connect[0]);
    }

    public function get_userid(){
        return self::$userid;
    }

    public function build_request($action,$server) {
        $ts = time();
        $result=$this->get_mysqlurl($server);
        $sign = md5($action . md5($ts . $result['key']));

        return array(
            'action' => $action,
            'ts' => $ts,
            'sign' => $sign
        );
    }

    public function writelog($arr,$result,$serverid=''){
        $status=($result=='命令运行成功！')?1:0;
        self::$log->writelog($arr,$status,$serverid);
    }

    /*
    public function is_authrized(){
        return self::$auth->is_authorized();
    }
     */
    public function dumplog($serverurl,$id,$sql,$type='',$cur='',$page=''){
          switch($type){
              case 'last':
                  break;
              case 'next':
                  break;
              case 'info':
                  break;
              case 'go':
                  break;
              default:
                  break;
          }
    }

    public function get_sql($type=''){
        switch($type){
            case 'log':
                return self::$log->get_sql();
                break;
            case 'auth':
                //return self::$auth->get_sql();
                break;
            default:
                return self::$log->get_sql();//.'<br/>'.self::$auth->get_sql();
                break;
        }
    }

    //获得  游戏服务器用户名密码
    //获得玩家账号id和角色id
    public function query_bypname($servername,$playername){
        $result=$this->get_mysqlurl($servername);
		//$sql="select `game_url`,`db_user`,`db_pwd`,`db_host`,`db_port`,`db_name` from gmt_server where `db_name`='$servername'";
		var_dump($result);
        $connect=self::$connect[]=connect($result['server_url'],$result['username'],$result['password'],$result['db_realname'],1);
        $playername=$result['prefix'].trim($playername);
        $sql="select `ID`,`UserName` from PlayerTable where `Name`='$playername'";
        if($result=query($sql,$connect)){
            return $result;
        }
        else
            return mysql_error();
    }


    //广播发公告。
    public function broadcast($serverlist,$arr,$stack=10,$log=1){
        if($log){
            $mh = curl_multi_init();
            //读服务器地址 数组 foreach($serverlist as $val){}
            while(($num=count($serverlist))&&($num>$stack)&&($num-=$stack)>$stack){
                curl_execi($mh,$serverlist,$arr,$stack);
            }
            curl_execi($mh,$serverlist,$arr,$num);
            curl_multi_close($mh);
        }
        //发完公告直接记录

        if(count($serverlist)==1){
            $game_url=$serverlist[0];
            $connect=connect(HOST,USERNAME,PASSWORD,'server_conf');
            $sql="select `id` from `mysql` where `game_url`='$game_url'";
            $result=query($sql,$connect);
            mysql_close($connect);
            $id=$result['id'];
        }else
            $id=0;

        $this->writelog($arr,'命令运行成功！',$id);
    }


    //发邮件
  /*  public function mailto($playerid,$arr){
        //发完了';
        return true;
    }
  */
    //获取服务器列表地址数组
    public function get_serverlist($servername){
        foreach($servername as $val){
            $gameurl=get_serverurl($val,self::$connect[0]);
            $server[]=$gameurl['game_url'];
        }
        return $server;
    }

    //获取服务器数据库地址,用户名密码
    public function get_mysqlurl($servername){
		echo 'sqllog';
        $sql="select `game_url`,`db_user`,`db_pwd`,`db_host`,`db_port`,`db_name` from gmt_server where `db_name`='$servername'";
        if($result=query($sql,self::$connect[0])){
            return $result;
        }else
            return  mysql_error();
    }
    //单目标发送gmt命令
    public function send_request($arr,$servername){
        $connect=array_pop(self::$connect);
        $server=$this->get_mysqlurl($servername);
        $result=send_request($arr,$server['game_url']);
        mysql_close($connect);
        $this->writelog($arr,$result,$server['id']);
        return $result;
    }

   /* public function check_action($action){
        return self::$auth->check_action($action);
    } */
}

class gamelog{
    private $userid;
    private $sql;
    private $connect;
    private $insertid;
    public function __construct($userid,$connect){
        $this->userid=$userid;
        $this->connect=$connect;
    }

    public function __destruct(){
    }

    public function get_sql(){
        return $this->sql;
    }

    public function writelog($arr,$status,$serverid=''){
        $str=serialize($arr);
        $action=$arr['GMCommandId'];
        $type=$arr['type']?$arr['type']:$arr['moneyType'];
        $time=time();
        $playername=$arr['playerName'];
        $playerid=$arr['playerId'];
        $cost=get_cost($arr);
        $ip=$_SERVER['REMOTE_ADDR'];
        $content=$arr['note'];
        $interval=$arr['interval'];
        $start=$arr['startime']?$arr['startime']:0;
        $end=$arr['stoptime']?$arr['stoptime']:0;

        $sql="insert into `gmt_log`(`userid`,`time`,`action`,`opid`,`opname`,`type`,`cost`,`orglog`,`status`,`serverid`,`ip`) values('$this->userid','$time','$action','$playerid','$playername','$type','$cost','$str','$status','$serverid','$ip')";
        $this->sql=$sql;
        mysql_query($sql,$this->connect);
        $this->insertid=mysql_insert_id($this->connect);
        if($action==28){
            $form=$arr['noticetype'];
            $type=$arr['broadcasttype'];

            $sql="insert into `gmt_announce`(`inserttime`,`last`,`content`,`interval`,`gm`,`status`,`type`,`form`,`starttime`,`endtime`,`serverid`,`gm_req`) values('$time','$start','$content','$interval','$this->userid','$status','$type','$form','$start','$end','$serverid','$str')";
            mysql_query($sql,$this->connect);
            $this->insertid=mysql_insert_id($this->connect);
        }
    }

}

/*
 class authfilter{
    private $userid;
    public $sql;
    public function __construct($userid){
        $this->userid=$userid;
        try{
            $dsn = "mysql:host=localhost;dbname=test";
            $this->dbh=new PDO($dsn,'root','12345');
            $this->dbh->exec("set names 'utf8'");
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

    public function __destruct(){

    }

    public function get_sql(){
        return $this->sql;
    }

    //根据动作不同，获得不同的权限码
    private function get_auth($action){
        $this->sql="select * from action where `action`=$action";
        $result=query($this->sql);
        return $result;
    }

    //用于对比权限码
    private function check($action){
        $authcode=$this->get_auth($action);
        var_dump($authcode);
        return true;
    }

    //检查动作权限  ,传action id
    public function check_action($action){
        if($this->check($action)){
            return true;
        }else{
            return false;
        }
    }

    public function is_authorized(){

        return true;
    }
}
  */
//分页函数
function page($sql,$type,$page,$starttime='',$endtime='',$num=10){
    $connect=connect(HOST,USERNAME,PASSWORD,DBNAME);
    switch($type){
        case 'info':
            $page=1;
            break;
        case 'next':
            $page+=1;
            break;
        case 'last':
            $page=(($page-1)>0)?$page-1:1;
    }
    $offset=($page>0)?($page-1)*$num:0;
    $sql.=" limit $offset,$num";
    $query=mysql_query($sql,$connect);
    $result='';
    for($i=0;$i<$num;$i++){
        $haha=mysql_fetch_row($query);
        $haha['orglog']=json_decode($haha['orglog']);
        $result[$i]=$haha;
    }
    $result['cur']=$page;
    return $result;
}

//批量执行curl
function  curl_execi(&$mh,$serverlist,$arr,$num){
     for($i=0;$i<$num;$i++){
         $server=array_pop($serverlist);
         $ch[$i]=curl_init();
         $query = http_build_query($arr);
         $options = array(
             CURLOPT_URL => $server,
             CURLOPT_POST => true,
             CURLOPT_POSTFIELDS => $query,
             CURLOPT_HEADER => false,
             CURLOPT_RETURNTRANSFER => true
         );
         curl_setopt_array($ch[$i], $options);
         curl_multi_add_handle($mh,$ch[$i]);
     }

    do {
        $mrc = curl_multi_exec($mh, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);

    while ($active && $mrc == CURLM_OK) {
        if (curl_multi_select($mh) <=0)break;
	else {
            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }

    for($i=0;$i<$num;$i++){
        curl_multi_remove_handle($mh,$ch[$i]);
    }

}

//create an array for announcement
function create_request($type,$note,$key,$ktime){
    $gm_req = build_request('immediately',$key);

    $gm_req['GMCommandId'] = 28;
    $gm_req['noticetype']=$type;
    $gm_req['broadcasttype']=0;
    $gm_req['note'] = $note;
    $gm_req['startime'] =0;
    $gm_req['stoptime'] =0;
    $gm_req['dfsdfnterval'] =0;
	$gm_req['ktime']=$ktime;
    $gm_req['end']=0;
    return $gm_req;
}

//generate immediately announcement curl handler
function create_ch($type,$note,$key,$url,$ktime){
	file_put_contents('www',var_export($ktime,true));
    $ch=curl_init();
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(create_request($type,$note,$key,$ktime)),
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch,$options);
/*	$f=fopen('./cccbbb.txt','a');
	fwrite($f,var_export($options,true).$url);
	fclose($f);
*/
    return $ch;
}

//send different ch to different server
function curl_exec_mul(&$mh,&$ch,$num){
    for($i=0;$i<$num;$i++){
        $ch_[$i]=array_pop($ch);
        curl_multi_add_handle($mh,$ch_[$i]);
    }

    do{
        $mrc=curl_multi_exec($mh,$active);
    }while($mrc==CURLM_CALL_MULTI_PERFORM);

    while($active&&$mrc==CURLM_OK){
		if(curl_multi_select($mh)<=0)break;
		else{
            do{
                $mrc=curl_multi_exec($mh,$active);
            }while($mrc==CURLM_CALL_MULTI_PERFORM);
        }
    }
    for($i=0;$i<$num;$i++){
        curl_multi_remove_handle($mh,$ch_[$i]);
    }
}
/*
function connect($host,$username,$password,$dbname){
    $connect=mysql_connect($host,$username,$password,1) or die(mysql_error());
    mysql_select_db($dbname,$connect) or die(mysql_error());
    mysql_set_charset('utf8');
    return $connect;
}

function query($sql,$identifier){
    $result=mysql_query($sql,$identifier);
    if($result&&mysql_affected_rows($identifier)){
        return mysql_fetch_assoc($result);
    }else{
        echo mysql_error();
        return false;
    }
}

*/
function get_serverurl($server,$identifier){
    //取得服务器url与端口，表名待改动。
    $sql="select `game_url` from server_conf.mysql where `db_name`='$server'";
    if($result=query($sql,$identifier)){
        return $result;
    }else
        return  mysql_error();
}
 //发送gmt请求
function send_request($arr,$server){
    $query = http_build_query($arr);
    // 用 curl 发送 HTTP POST 请求
    $curl = curl_init();

    $options = array(
        CURLOPT_URL => $server,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $query,
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);
    // 解析服务器的响应
    if (curl_error($curl)||!$response) {
        $msg = sprintf("GMT命令运行失败", $server,curl_error($curl));
        return array('result'=>false, 'info'=>$msg);
    }
    else {
        $info = curl_getinfo($curl);
        if ($info['http_code'] == 200) {
            $result = json_decode($response, true);
            if (is_array($result)) {
                //return $result;
                return "命令运行成功！";
            }
        }
        $msg = sprintf("code=%d, response=%s\n", $info['http_code'], $response);
        return array('result'=>false, 'info'=>$msg);
    }
}

function get_cost($arr){
    $costarr[]=$arr['addexp'];
    $costarr[]=$arr['addmoney'];
    $costarr[]=$arr['pay'];
    foreach($costarr as $val){
        if(isset($val)){
            return $val;
        }
    }
}

function get_servername(){
    $conn=connect(HOST,USERNAME,PASSWORD,'server_conf') or die(mysql_error());
    $sql="select `db_name` from `mysql` where `status`=1";
    $query=mysql_query($sql,$conn);
    mysql_close($conn);
    return $query;
}

//////////////////////////////以下为测试用例，呕心沥血披星戴月跳楼大甩卖之作，谁敢删我砍了他//////////////////////
/*
alert('111111111111111');
$gmt=GMT::construct(1);
 ///////////////////////////////                强制下线           / //////////////////////////////////
$action='enforce';
//获取玩家名称
//$playerName = trim($_POST['playerName']);
$playerName='乞丐皇帝打我';
alert('111111111111122');
$result=$gmt->query_bypname('gmt',$playerName);
$playerId=$result['ID'];
$username=$result['UserName'];

alert('11111111111133');
$gm_req = build_request($action);
$gm_req['GMCommandId'] = 27;
$gm_req['playerId'] = $playerId;
$gm_req['playerName'] = $username;
$gm_req['type'] = 3;
$gm_req['tt']=0;
$gm_req['end']=0;
var_dump($gm_req);
//$result = request_admin($gm_req);
$result=$gmt->send_request($gm_req,'TLGameDB_10_23');
//$gmt->writelog($gm_req,$result);
echo($result);
*/
//////////////////////////////////////// 多服务器发送公告///////////////////////////////////

/*$action ='immediately';


$note ="公告测试";
$AnnouncementType=1;

$gm_req = build_request($action);

$gm_req['GMCommandId'] = 28;//命令id
$gm_req['noticetype']=$AnnouncementType;//公告类型
$gm_req['broadcasttype']=0;//公告播放类型
$gm_req['note'] = $note;//公告内容
$gm_req['startime'] =0;//开始时间
$gm_req['stoptime'] =0;//结束时间
$gm_req['interval'] =0;//间隔时间
$gm_req['end']=0;//参数结束
$server[]='http://115.182.42.24:8009';
$result = $gmt->broadcast($server,$gm_req);
echo($result);
*/
//查询动作名字 $sql="select `name` from `gmt_action` where `action`=$action and `type`=$type";


?>

