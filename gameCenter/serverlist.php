<?php

require( "./Conf/config.php");

function connect_mysql_db() {
    $dsn = sprintf('mysql:host=%s;dbname=%s', GC_MYSQL_HOST, GC_MYSQL_DB);
    $dbh = new PDO($dsn, GC_MYSQL_USER, GC_MYSQL_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->query("SET NAMES UTF8");

    return $dbh;
}
 
$passport_id = $_POST['passportId'];

$dbh = connect_mysql_db();
    
$sql = "SELECT * from gc_passport where passportId = ? order by serverId asc";

$sth = $dbh->prepare($sql);
$sth->execute(array($passport_id));
$sth->setFetchMode(PDO::FETCH_ASSOC);
$list = array();
while ($row = $sth->fetch()) {
    $list[$row['serverId']] = $row['level'];
}

require("servercache.php");

$result = array('accountList' => &$list, 'serverList' => &$serverlist);

echo json_encode($result);