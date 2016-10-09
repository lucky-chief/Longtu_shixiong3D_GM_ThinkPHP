<?php

require( "./Conf/config.php");

function connect_mysql_db() {
    $dsn = sprintf('mysql:host=%s;dbname=%s', MYSQL_HOST, MYSQL_DB);
    $dbh = new PDO($dsn, MYSQL_USER, MYSQL_PASSWORD);
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
    case 'login':
        updateLogin();
        return;
    break;
    case 'levelUp':
        updateLevel();
    break;
    default:
    break;
}

function updateLogin() {
    $dbh = connect_mysql_db();
    
    $sql = "insert into gc_passport(`passportId`, `serverId`, `level`, `lastLoginTime`) values (?, ?, ?, ?) on duplicate key update `level` = ?, `lastLoginTime` = ?";
    
    $sth = $dbh->prepare($sql);
    $res = $sth->execute(array($_POST['passportId'], $_POST['serverId'], $_POST['level'], $_POST['lastLoginTime'], $_POST['level'], $_POST['lastLoginTime']));
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    if ($res)
        echo json_encode(array('result'=>true, 'info'=>'OK!'));
    else {
        echo json_encode(array('result'=>false, 'info'=>'update db failed!'));
    }
}

function updateLevel() {
    $dbh = connect_mysql_db();
    
    $sql = "insert into gc_passport(`passportId`, `serverId`, `level`, `lastLoginTime`) values (?, ?, ?, ?) on duplicate key update `level` = ?";
    
    $sth = $dbh->prepare($sql);
    $res = $sth->execute(array($_POST['passportId'], $_POST['serverId'], $_POST['level'], $_POST['lastLoginTime'], $_POST['level']));
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    if ($res)
        echo json_encode(array('result'=>true, 'info'=>'OK!'));
    else {
        echo json_encode(array('result'=>false, 'info'=>'update db failed!'));
    }
}