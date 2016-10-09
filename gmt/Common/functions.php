<?php
//项目公共函数
//数据库操作
function connect($host,$username,$password,$dbname){
    $connect=mysql_connect($host,$username,$password,1) or die(mysql_error());
    mysql_select_db($dbname,$connect) or die(mysql_error());
    mysql_set_charset('utf8');
    return $connect;
}
function query($sql,$coon){
    $result=mysql_query($sql,$identifier);
    if($result&&mysql_affected_rows($identifier)){
        return mysql_fetch_assoc($result);
    }else{
        echo mysql_error();
        return false;
    }
}

function query_m($sql,$conn){
    $result='';
    $query=mysql_query($sql,$identifier);
    if(mysql_errno())
        exit(mysql_error());
    while($val=mysql_fetch_assoc($query)){
        $result[]=$val;
    }
    return $result;
}
//权限验证
function Role($action){
   $Role = $_SESSION['_ACCESS_LIST'];
   if(empty($Role[$action])){
	  return false;
	}
}
