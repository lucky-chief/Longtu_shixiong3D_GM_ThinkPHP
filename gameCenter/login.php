<?php

require( "./Conf/config.php");

function connect_mysql_db() {
    $dsn = sprintf('mysql:host=%s;dbname=%s', GC_MYSQL_HOST, GC_MYSQL_DB);
    $dbh = new PDO($dsn, GC_MYSQL_USER, GC_MYSQL_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->query("SET NAMES UTF8");

    return $dbh;
}

if (!isset($_POST['passportId']))
    return;

$passport_id = $_POST['passportId'];

$dbh = connect_mysql_db();
    
$sql = "SELECT * from gc_passport where passportId = ? order by lastLoginTime desc limit 1";

$sth = $dbh->prepare($sql);
$sth->execute(array($passport_id));
$sth->setFetchMode(PDO::FETCH_ASSOC);
$server_id = 0;
while ($row = $sth->fetch()) {
    $server_id = $row['serverId'];
    break;
}

require("servercache.php");

if ($server_id > 0) {
    echo $serverlist[$server_id];
}
else {
    echo end($serverlist);
}