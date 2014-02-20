<?php
require "./inc/head.php";

$safety = get_magic_quotes_gpc();
$pid = $_REQUEST['pid'];
$aid = $_REQUEST['aid'];
$st = $_REQUEST['inc'];
$link = $_REQUEST['link'];
$cap = ($safety) ? $_REQUEST['cap'] : mysql_real_escape_string($_REQUEST['cap']);
$atn = $_REQUEST['aTn'];
$fn = $_REQUEST['psrc'];
$setAtn = $_REQUEST['setAtn'];

$link = htmlspecialchars($link);
$cap = htmlspecialchars($cap);

if (empty($st))
	$st = 0;
	
if (empty($atn))
	$atn = 0;
else
{
	$q = "SELECT path FROM $atbl WHERE id = $aid";
	$r = mysql_query($q);
	$row = mysql_fetch_array($r);
	$p = $row['path'];
	$fn = $adminDir.'/albums/'.$p.'/tn/'.$fn;
}

mysql_query("UPDATE $itbl SET active=$st, link='$link', caption='$cap' WHERE id = $pid AND aid = $aid") or die(mysql_error());

if ($atn == 1)
{
	mysql_query("UPDATE $atbl SET aTn='$fn' WHERE id = $aid") or die(mysql_error());
}
else
{
	if ($setAtn == $fn)
		mysql_query("UPDATE $atbl SET aTn='' WHERE id = $aid") or die(mysql_error());
}
			
header("Location: edit-pic.php?pid=$pid&aid=$aid&m=1");

?>
