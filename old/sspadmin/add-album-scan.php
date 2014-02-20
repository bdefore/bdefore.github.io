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
			$d = $_REQUEST['aDir'];
			$d = str_replace("/", "", $d);
			
			$album_photos_dir = 'albums/'.$d.'/lg';
			$dh  = @opendir($album_photos_dir) or die('<p>Folder <strong>'.$d.'</strong> does not exist, <a href="add-album.php">go back</a> and try again</p>');
			while (false !== ($filename = readdir($dh))) {
				if ( eregi("jpg",$filename) || eregi("swf", $filename) || eregi("flv", $filename)) {
					$album_photos[] = $filename;
				}
			}
			
			if (sizeof($album_photos) != 0)
			{ 
			?>
			<h2>Add a New Album</h2>
			<p>To add a new album with the images found below, simply give it a name and click "Add Album". You will be able to edit this later.</p>
			
			<form action="add-album-exe.php" method="post">
			<fieldset class="center"><input type="text" name="aName" /> <input type="submit" value="Add Album" /><input type="hidden" name="aDir" value="<?php echo $d; ?>" /></fieldset>
			</form>
			<?php
				echo '<p>The Following Files Were Found</p>';
				echo '<ul>';
				
				for($j = 0; $j < sizeof($album_photos); $j++) {
					echo '<li>'.$album_photos[$j].'</li>';
				}
				
				echo '</ul>';
			} 
			else
			{
				echo '<p>No Jpegs Found in the <strong>'.$d.'/lg</strong> directory!</p>';
			}
			
		?>
	
	</div>
	
</body>
</html>
