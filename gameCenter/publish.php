<?php

require( "./Conf/config.php");

function connect_mysql_db() {
    $dsn = sprintf('mysql:host=%s;dbname=%s', GMT_MYSQL_HOST, GMT_MYSQL_DB);
    $dbh = new PDO($dsn, GMT_MYSQL_USER, GMT_MYSQL_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->query("SET NAMES UTF8");

    return $dbh;
}
 
$ts = 0;
$sign = '';
$action = '';
if (isset($_POST['ts']))
    $ts = $_POST['ts'];

if (isset($_POST['sign']))
    $sign = $_POST['sign'];
    
if (isset($_POST['op']))
    $action = $_POST['op'];

if (time() - 600 > $ts) {
    echo json_encode(array('result'=>false, 'info'=>'time invalid!'.$ts . ' '. time()));
    return;
}

if (md5($action.md5($ts.SKEY)) != $sign) {
    echo json_encode(array('result'=>false, 'info'=>'verify key failed!'));
    return;
}

switch ($action) {
    case 'updateServerList':
        updateServerList();
        return;
    break;
    case 'updateNotice':
        updateNotice();
    break;
    default:
    break;
}

function updateServerList() {
    $dbh = connect_mysql_db();
    
    $sql = "SELECT id, name, game_url, game_port from gmt_server order by id asc";
    
    $sth = $dbh->prepare($sql);
    $sth->execute(array());
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $list = array();
    while ($row = $sth->fetch()) {
        $id = $row['id'];
        $name = $row['name'];
        $ip = $row['game_url'];
        $port = $row['game_port'];
        
        $list[$id] = "$id,$name,$ip,$port";
    }
    //写文件
    $fp = fopen("./servercache.php",'w');
    $text = '<?php $serverlist='.var_export($list,true).';'; 
    fwrite($fp, $text);
    fclose($fp);
    echo json_encode(array('result'=>true, 'info'=>'OK!'));
}

function updateNotice() {
    $dbh = connect_mysql_db();
    
    $sql = "SELECT notice from gmt_notice limit 1";
    
    $sth = $dbh->prepare($sql);
    $sth->execute(array());
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $sth->fetch()) {
        $notice = $row['notice'];
        
        //写文件
        $fp=fopen("./notice.txt",'w');
        fwrite($fp, $notice);
        fclose($fp);
        echo json_encode(array('result'=>true, 'info'=>'OK!'));
        return;
    }
    echo json_encode(array('result'=>false, 'info'=>'notice data not found!'));
}
