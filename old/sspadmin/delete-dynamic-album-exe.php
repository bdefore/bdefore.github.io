<?php
require "./inc/head.php";

$did = $_REQUEST['did'];

mysql_query("DELETE FROM $dtbl WHERE id = $did") or die(mysql_error());
mysql_query("DELETE FROM $dltbl WHERE did = $did") or die(mysql_error());

header("Location: edit-dynamic.php?m=1");


?>
