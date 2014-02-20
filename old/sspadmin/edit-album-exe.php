<?php
require "./inc/head.php";

$safety = get_magic_quotes_gpc();
$aid = $_REQUEST['aid'];
$n = ($safety) ? $_REQUEST['aName'] : mysql_real_escape_string($_REQUEST['aName']);
$d = ($safety) ? $_REQUEST['aDesc'] : mysql_real_escape_string($_REQUEST['aDesc']);
$t = $_REQUEST['tn'];
$at = $_REQUEST['atn'];
$ad = $_REQUEST['aMp3'];
$adc = ($safety) ? $_REQUEST['aMp3Desc'] : mysql_real_escape_string($_REQUEST['aMp3Desc']);
$tgt = $_REQUEST['tgt'];

if (empty($t))
	$t = 0;
	
if (empty($tgt))
	$tgt = 0;

mysql_query("UPDATE $atbl SET name='$n', description='$d', tn=$t, aTn='$at', audioFile='$ad', audioCap='$adc', target=$tgt WHERE id = $aid") or die(mysql_error());

			
header("Location: edit-album.php?aid=$aid&m=1");

?>
