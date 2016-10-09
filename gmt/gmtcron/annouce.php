<?php
include 'GmtAction.class.php';
include 'conf.php';
define('MAX_ROW',200);
define('MULTI_EXEC_LIMIT',20);
$connect=connect(HOST,USERNAME,PASSWORD,DBNAME);
$time=time();


$sql="select gmt_announce.id,`content`,`serverid`,`endtime`,`key`,`form`,`gmt_announce`.`type`,`game_url`,`kTime` from `gmt_announce` left join gmts.gmt_server on gmt_announce.serverid=gmts.gmt_server.id where gmt_announce.status=1 and `gmt_announce`.`type`<>0 and `starttime`<'$time' and ($time-`last`)/60 > `interval`";
$query=mysql_query($sql);
while($result=mysql_fetch_assoc($query)){
	$serverlist[]=$result;
}


if(is_null($serverlist))
	exit(0);

$i=0;$allserver=array();

while($i<count($serverlist)){
	for($j=0;$j<MAX_ROW,$i<count($serverlist);$i++,$j++){
		$f=fopen(DIRPATH.'url/url'.floor($i/MAX_ROW).'.txt','w');
		if($serverlist[$i]['serverid'])
			fwrite($f,$serverlist[$i]['game_url'].' '.$serverlist[$i]['form'].' '.$serverlist[$i]['key'].' '.$serverlist[$i]['id'].' '.$serverlist[$i]['type'].' '.$serverlist[$i]['kTime'].' '.$serverlist[$i]['content']."\n");	
		else{
			$allserver[$i][4]=$serverlist[$i]['kTime'];
			$allserver[$i][3]=$serverlist[$i]['content'];
			$allserver[$i][2]=$serverlist[$i]['form'];
			$allserver[$i][1]=$serverlist[$i]['type'];
			$allserver[$i][0]=$serverlist[$i]['id'];
		}
		fclose($f);	
	}
	exec(PHPPATH.' '.DIRPATH.'sendone.php '.floor($i/MAX_ROW).' '.MULTI_EXEC_LIMIT.' &');
	//echo PHPPATH.' '.DIRPATH.'sendone.php '.floor($i/MAX_ROW).' '.MULTI_EXEC_LIMIT.' &';
}

$f=fopen(DIRPATH.'url/all.txt','w');
foreach($allserver as $val){
	//id type form content
	fwrite($f,$val[0].' '.$val[1].' '.$val[2].' '.$val[4].' '.$val[3]."\n");	
}
fclose($f);




exec(PHPPATH.' '.DIRPATH.'sendall.php '.MULTI_EXEC_LIMIT.' &');

mysql_close($connect);  
?>
