<?php
require "./inc/head.php";

$safety = get_magic_quotes_gpc();
$gn = ($safety) ? $_REQUEST['gn'] : mysql_real_escape_string($_REQUEST['gn']);
$uid = $_REQUEST['uid'];

mysql_query("UPDATE $utbl SET gName='$gn' WHERE id = $uid") or die(mysql_error());

header("Location: user-profile.php?m=3");

?>
