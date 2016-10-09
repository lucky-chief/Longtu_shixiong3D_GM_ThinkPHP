<?php
include 'GmtAction.class.php';
include 'conf.php';
$content=array();
$connect=connect(HOST,USERNAME,PASSWORD,DBNAME);
$sql="select `game_url`,`key` from gmt_server where `id`<>8 and `id`<>9 and `id`<>27";
$query=mysql_query($sql,$connect);
while($result=mysql_fetch_assoc($query)){
	$urlarr[]=$result;
}

$f=fopen(DIRPATH.'url/all.txt','r+');
//id type form content
$j=0;
while($line=fgets($f)){
	$i=0;
	$id=0;
	while(($ch=$line[$i++])!=' '){
		$id.=$ch;
	}
	$type=$line[$i];
	$i+=2;

	$content[$j][0]=$line[$i];
	$i+=2;
	
	if($type==1){
		$sql="update gmt_announce set `status`=0 where `id`=$id";
		mysql_query($sql);
	}else{
		$time=time();
		$sql="update gmt_announce set `last`=$time,`status`=if($time<`endtime`,1,0) where `id`=$id";
		mysql_query($sql);
	}

	while(($ch=$line[$i++])!=' ')
		$content[$j][2].=$ch;
	
	while(($ch=$line[$i++])!='')
		$content[$j][1].=$ch;
	$j++;
}

//$val[0] form    $val[1] $content
mysql_close($connect);
if(count($content)==0||count($urlarr)==0)exit(0);
$mh=curl_multi_init();
foreach($content as $val){
	sendtoall($mh,$val,$urlarr,$argv[1]);
}
curl_multi_close($mh);

ftruncate($f,0);
fclose($f);
function sendtoall(&$mh,$content,$urlarr,$exec_num){
	$ch=create_requestarr($content[0],$content[1],$urlarr,$content[2]);
	$num=count($urlarr);
//	file_put_contents('num',$num);

	while($num>$exec_num){
		$num-=$exec_num;
		curl_exec_mul($mh,$ch,$exec_num);
	}
	curl_exec_mul($mh,$ch,$num);
}

function create_requestarr($form,$msg,$urlarr,$ktime){
	foreach($urlarr as $val){
		$ch[]=create_ch($form,$msg,$val['key'],$val['game_url'],$ktime);
	}
	return $ch;	
}
?>
