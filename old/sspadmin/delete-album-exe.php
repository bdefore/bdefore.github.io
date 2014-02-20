<?php
require "./inc/head.php";

$aid = $_REQUEST['aid'];

mysql_query("DELETE FROM $atbl WHERE id = $aid") or die(mysql_error());
mysql_query("DELETE FROM $itbl WHERE aid = $aid") or die(mysql_error());
mysql_query("DELETE FROM $dltbl WHERE aid = $aid") or die(mysql_error());

header("Location: edit-main.php?m=1");


?>
