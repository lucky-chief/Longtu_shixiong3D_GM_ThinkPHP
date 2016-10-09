<?php
include 'GmtAction.class.php';
include 'conf.php';

$f=fopen(DIRPATH.'url/url'.$argv[1].'.txt','r+');
var_dump($f);
$content=array();
$j=0;
$connect=connect(HOST,USERNAME,PASSWORD,DBNAME);

while(($line=fgets($f))){
	$i=0;$id=0;
	while(($ch=$line[$i++])!=' '){
		$content[$j][0].=$ch;
	}

	$content[$j][1]=$line[$i];
	$i+=2;

	while(($ch=$line[$i++])!=' ')
		$content[$j][2].=$ch;
		
	while(($ch=$line[$i++])!=' ')
		$id.=$ch;
		
	$type=$line[$i];
	$i+=2;
	
	if($type==1){
		$sql="update gmt_announce set `status`=0 where `id`=$id";
		mysql_query($sql);
	}else{
		$time=time();
		$sql="update gmt_announce set `last`=$time,`status`=if($time<`endtime`,1,0) where `id`=$id";
		mysql_query($sql);
	}

	
	$num=strlen($line);

	while(($ch=$line[$i++])!=' ')
		$content[$j][4].=$ch;


	while(($ch=$line[$i++])!='')
		$content[$j][3].=$ch;
	$j++;
}
if(count($content)==0)exit(0);
$ch=array();
mysql_close($connect);
foreach($content as $val){
	$ch[]=create_ch($val[1],$val[3],$val[2],$val[0],$val[4]);	
}

var_dump($content);
$mh=curl_multi_init();
$num=count($content);
while($num>$argv[2]){
	$num-=$argv[2];
	curl_exec_mul($mh,$ch,$argv[2]);
}

curl_exec_mul($mh,$ch,$num);
curl_multi_close($mh);
ftruncate($f,0);
fclose($f);
?>
