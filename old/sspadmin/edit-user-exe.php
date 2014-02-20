<?php
require "./inc/head.php";

$uid = $_REQUEST['uid'];
$usr = $_REQUEST['usr'];
$pwd = $_REQUEST['pwd'];
$pwd2 = $_REQUEST['pwd2'];

if (rtrim($pwd) == rtrim($pwd2))
{

mysql_query("UPDATE $utbl SET usr='$usr', pwd='$pwd' WHERE id = $uid") or die(mysql_error());

header("Location: signout.php?m=1");
}
else 
{
header("Location: user-profile.php?m=2");
}

?>
