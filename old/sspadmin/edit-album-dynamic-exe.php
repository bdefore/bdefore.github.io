<?php
require "./inc/head.php";

			$aid = $_GET['aid'];
			$did = $_GET['did'];
			$action = $_GET['action'];
			
			if ($action == 1){
			mysql_query("INSERT INTO $dltbl (did, aid) VALUES ('$did', '$aid')") or die("Error".mysql_error());
			
			}
			
			if ($action == 2){
			mysql_query("DELETE From $dltbl WHERE aid='$aid' AND did='$did'") or die("Error".mysql_error());
			
			}
			
			header("Location: edit-dynamic-single.php?did=$did&m=2");
?>
