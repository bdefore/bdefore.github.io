<?php
require "./inc/head.php";
?>
<?php include "./inc/doctype.php"; ?>
<head>
<?php include "./inc/charset.php"; ?>
<title>SSP Admin :: Add an Album</title>
<?php include "./inc/head_elem.php"; ?>
</head>

<body>
	<div id="container">
		
		<?php include "./inc/h1.php"; ?>
		
		<?php
			if ($_GET['go'])
			{
				$aid = $_GET['aid'];
				$q = "SELECT path FROM $atbl WHERE id = $aid";
				$r = mysql_query($q);
				$row = mysql_fetch_array($r);
				
				$d = $row['path'];
				
				$album_photos_dir = 'albums/'.$d.'/lg';
				$dh  = @opendir($album_photos_dir);
				while (false !== ($filename = readdir($dh))) {
					if ( eregi("jpg",$filename) || eregi("swf", $filename) || eregi("flv", $filename)) {
						$album_photos[] = $filename;
					}
				}
				$i = 0;
				for($j = 0; $j < sizeof($album_photos); $j++) {
					$n = $album_photos[$j];
					$q = "SELECT id FROM $itbl WHERE aid = $aid AND src = '$n'";
					$r = mysql_query($q);
					
					if (mysql_num_rows($r) == 0)
					{
						mysql_query("INSERT INTO $itbl (id, aid, src) VALUES (NULL, $aid, '$n')") or die("Error".mysql_error());
						echo '<p>New File '.$n,' Found...</p>';
						$i+=1;
						flush();
					}
				}
				echo '<h2>Rescan Album Folder for New Images</h2>';
				if ($i == 0)
					echo '<p>Scan Complete. No New Files Found.</p>';
				else if ($i == 1)
					echo '<p>Scan Complete. '.$i.' New File Found and Added.</p>';
				else
					echo '<p>Scan Complete. '.$i.' New Files Found and Added.</p>';
				
				echo '<p><a href="album-rescan.php?aid='.$aid.'&go=true">Scan Again</a></p>';
			}
			else
			{
			?>
			<h2>Rescan Album Folder for New Images</h2>
			<p>This process checks for newly added images to your album directory and adds them to the album.</p>
			<p><a href="album-rescan.php?aid=<?php echo $aid; ?>&go=true">Rescan Now</a></p>
			
			<?php } ?>
	</div>
	
</body>
</html>
