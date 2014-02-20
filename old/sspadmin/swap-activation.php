<?php
require "./inc/head.php";

			$aid = $_GET['aid'];
			$op = $_GET['opt'];
			mysql_query("UPDATE $atbl SET active = $op, startHere = 0 WHERE id = $aid");
			
			if ($op == 1)
				$m = 2;
			else
				$m = 3;
				
			header("Location: edit-main.php?aid=$aid&m=$m");
		?>
	
