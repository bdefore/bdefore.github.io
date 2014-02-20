<?php
require "./inc/head.php";

			$aid = $_GET['aid'];
			$act = $_GET['act'];
			
if ($act == 1)
{
	mysql_query("UPDATE $atbl SET startHere = 1 WHERE id = $aid");
			
	mysql_query("UPDATE $atbl SET startHere = 0 WHERE id <> $aid");
	$mes = 4;
			
} else {
	
	mysql_query("UPDATE $atbl SET startHere = 0 WHERE id = $aid");
	$mes = 5;

}
				
			header("Location: edit-main.php?aid=$aid&m=$mes");
?>
	
