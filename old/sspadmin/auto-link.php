<?php
	require "./inc/head.php";
	
	$aid = $_GET['aid'];
	$now = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
	$now = str_replace('auto-link.php', '', $now);
	
	$q = "SELECT path FROM $atbl WHERE id = '$aid'";
	$r = mysql_query($q);
	$rw = mysql_fetch_row($r);
	
	$path = 'http://'.$now.'albums/'.$rw[0].'/lg/';
	
	$nq = "UPDATE $itbl SET link = CONCAT('$path', $itbl.src) WHERE aid = '$aid'";
	mysql_query($nq);
	header("Location: edit-album.php?m=3&aid=$aid");
	
?>