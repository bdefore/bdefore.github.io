<?php
require "./inc/head.php";

			$d = $_REQUEST['aDir'];
			$aName = $_REQUEST['aName'];
			$path = $d;
			
			mysql_query("INSERT INTO $atbl (id, name, path) VALUES (NULL, '$aName', '$path')") or die("Error".mysql_error());
			
			$aid = mysql_insert_id();
			
			$album_photos_dir = 'albums/'.$d.'/lg';
			$dh  = @opendir($album_photos_dir) or die('<p>Folder <strong>'.$d.'</strong> does not exist, <a href="add-album.php">go back</a> and try again</p>');
			while (false !== ($filename = readdir($dh))) {
				if ( eregi("jpg",$filename) || eregi("swf", $filename) || eregi("flv", $filename)) {
					$album_photos[] = $filename;
				}
			}
			$i = 0;
			for($j = 0; $j < sizeof($album_photos); $j++) {
				$n = $album_photos[$j];
				mysql_query("INSERT INTO $itbl (id, aid, src) VALUES (NULL, $aid, '$n')") or die("Error".mysql_error());
				$i+=1;
			}
			
			header("Location: edit-album.php?aid=$aid&m=2");
?>
