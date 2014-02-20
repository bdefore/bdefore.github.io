<?php
require "./inc/head.php";
			$safety = get_magic_quotes_gpc();
			$aName = ($safety) ? $_REQUEST['dName'] : mysql_real_escape_string($_REQUEST['dName']);
			
			mysql_query("INSERT INTO $dtbl (id, name) VALUES (NULL, '$aName')") or die("Error".mysql_error());
			
			$aid = mysql_insert_id();
			
			header("Location: edit-dynamic-single.php?did=$aid");
?>
