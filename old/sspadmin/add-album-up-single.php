<?php
require "./inc/head.php";
?>
<?php include "./inc/doctype.php"; ?>
<head>
<?php include "./inc/charset.php"; ?>
<title>SSP Admin :: Add an Album</title>
<?php include "./inc/head_elem.php"; ?>
<?php 
	$chk = strpos($_SERVER['HTTP_USER_AGENT'], 'Safari');
?>
<script type="text/javascript">

	var selbox = 0;
	var isSafari = <?php ($chk === false) ? $r = 'false' : $r= 'true'; echo $r; ?>;
	
	function checkForm(theForm)
	{
	
		if (theForm.upload.value == '')
		{
			alert("Please Select a File to Upload");
			return false;
		}
		else
		{
			if (isSafari)
			{
				theForm.btn.disabled = true;
				theForm.btn.value = 'Uploading...Please Wait';
			} else {
				document.getElementById('before').style.display = 'none';
				document.getElementById('after').style.display = 'block';
				progress();
			}
			return true;
		}
	}
	
	function progress()
	{
		var elem = document.getElementById('boxes-up');
		var a = elem.getElementsByTagName('DIV');
		if (selbox == 3)
			selbox = 0;
		if (selbox == 0)
			a[2].className = 'pbox';
		else
			a[selbox-1].className = 'pbox';
		a[selbox].className = 'pbox-sel';
		selbox += 1;
		setTimeout(progress, 500);
	}
</script>
<style type="text/css">
div#after { display:none; }
div#boxes-up { width:230px; margin:0 auto; }
div.pbox { width:50px; height:50px; float:left; margin:10px; background:#c7c7c7; border:1px solid #555555;}
div.pbox-sel { width:50px; height:50px; float:left; margin:10px; background:#ffffff; border:1px solid #cc0000;}
</style>
</head>

<body>
	<div id="container">
		
		<?php include "./inc/h1.php"; ?>
<?php
	if ( $_POST ) {
		require "./inc/upload_class.php";
		
		if ($_POST['aName'])
		{
		$clean_name = $_POST['aName'];
		$the_id = str_replace(" ", "-", $clean_name);
		$perms = substr(sprintf('%o', fileperms(realpath('./albums/'))), -4);
		if ($perms != '0777')
			@chmod(('albums/'), 0777) or die("<h2>Error</h2><p>SSPAdmin does not have the proper permissions to write to the albums folder. SSPAdmin tried to set the permissions for you, but was rejected. Please chmod this folder to 777");
		@mkdir('./albums/'.$the_id, 0777) or die("<h2>Error</h2><p>There is already an album folder with the name <strong>$the_id</strong>. Please <a href=\"add-album.php\">go back</a> and select another name.");
		$max_size = 1024*100; // the max. size for uploading
		mkdir('./albums/'.$the_id.'/lg/', 0777);
		$my_upload = new file_upload;
		$my_upload->upload_dir = realpath('./albums/'.$the_id.'/lg/') . '/'; // "files" is the folder for the uploaded files (you have to create this folder)
		$my_upload->extensions = array(".jpeg", ".jpg", ".JPEG", ".JPG", ".swf", ".SWF", ".FLV", ".flv"); // specify the allowed extensions here
		$my_upload->max_length_filename = 100; // change this value to fit your field length in your database (standard 100)
		$my_upload->the_temp_file = $_FILES['upload']['tmp_name'];
		$my_upload->the_file = $_FILES['upload']['name'];
		$my_upload->http_error = $_FILES['upload']['error'];
		if (!$my_upload->upload()) { 
			$m = true;
			echo($my_upload->http_error);
		} else {
			$aName = $_POST['aName'];
			mysql_query("INSERT INTO $atbl (id, name, path) VALUES (NULL, '$aName', '$clean_name')") or die("Error".mysql_error());
			$aid = mysql_insert_id();
			mysql_query("INSERT INTO $itbl (id, aid, src) VALUES (NULL, $aid, '$my_upload->the_file')") or die("Error".mysql_error());
		}
?>
<?php
			if ( $m ) {
		?>
		
		<h2>Error</h2>
		<p>There was an error uploading your files. Please check the documentation or the SSPAdmin forums.</p>
		
		<?php } else { ?>
		<h2>Success!</h2>
		<p>Your image (<strong><?php echo $my_upload->the_file; ?></strong>) was uploaded and a new album (named <strong><?php echo $aName ?></strong>) was created. <a href="edit-album.php?aid=<?php echo $aid ?>">Click here to edit it</a>. You can also use the form below to add another image to this album.</p>
		
		<div class="album-wrap">
		<h2>Add Another File</h2>
		<form action="add-album-up-single.php" method="post" enctype="multipart/form-data">
		
		<fieldset class="center"><label>File to Upload: </label><input type="file" name="upload"></fieldset><fieldset class="center"><input name="btn" type="submit" value="Upload this Picture" /></fieldset><input type="hidden" name="aid" value="<?php echo $aid; ?>"</form></div>
		<?php } ?>

	</div>
<?php
}
		else
		{
			$aid = $_POST['aid'];
			$q = "SELECT path, name FROM $atbl WHERE id = $aid";
			$r = mysql_query($q);
			$rw = mysql_fetch_row($r);
			$path = $rw[0];
			$name = $rw[1];
			$max_size = 1024*100; // the max. size for uploading
			$my_upload = new file_upload;
			$my_upload->upload_dir = realpath('./albums/'.$path.'/lg/') . '/'; // "files" is the folder for the uploaded files (you have to create this folder)
			
			$perms = substr(sprintf('%o', fileperms(realpath('./albums/'))), -4);
			if ($perms != '0777')
				@chmod(('albums/'), 0777) or die("<h2>Error</h2><p>SSPAdmin does not have the proper permissions to write to the albums folder. SSPAdmin tried to set the permissions for you, but was rejected. Please chmod this folder to 777");
				
			$perms = substr(sprintf('%o', fileperms(realpath('./albums/'.$path.'/lg/'))), -4);
			if ($perms != '0777')
				@chmod(('albums/'.$path.'/lg/'), 0777) or die("<h2>Error</h2><p>SSPAdmin does not have the proper permissions to write to the folder: albums/$path/lg/ SSPAdmin tried to set the permissions for you, but was rejected. Please chmod this folder to 777");
				
			$my_upload->extensions = array(".jpeg", ".jpg", ".JPEG", ".JPG", ".swf", ".SWF", ".FLV", ".flv"); // specify the allowed extensions here
			$my_upload->max_length_filename = 100; // change this value to fit your field length in your database (standard 100)
			$my_upload->the_temp_file = $_FILES['upload']['tmp_name'];
			$my_upload->the_file = $_FILES['upload']['name'];
			$my_upload->http_error = $_FILES['upload']['error'];
			if (!$my_upload->upload()) { 
				$m = true;
			} else {
				mysql_query("INSERT INTO $itbl (id, aid, src) VALUES (NULL, $aid, '$my_upload->the_file')") or die("Error".mysql_error());
			}
			
			?>
			<h2>Success!</h2>
		<p>Your image (<strong><?php echo $my_upload->the_file; ?></strong>) was uploaded and added to the following album: <strong><?php echo $name; ?></strong></p>
		
		<div id="before">
		<div class="album-wrap">
		<h2>Add Another File</h2>
		<form action="add-album-up-single.php" method="post" enctype="multipart/form-data" onSubmit="return checkForm(this)">
		
		<fieldset class="center"><label>File to Upload: </label><input type="file" name="upload"></fieldset><fieldset class="center"><input name="btn" type="submit" value="Upload this Picture" /></fieldset><input type="hidden" name="aid" value="<?php echo $aid; ?>"</form></div></div>
		<?php
		}
	} else {
		$aid = $_GET['aid'];
		$q = "SELECT name FROM $atbl WHERE id = $aid";
		$r = mysql_query($q);
		$rw = mysql_fetch_row($r);
		$name = $rw[0];
?> 	
	<h2>Add an Image to an Existing Gallery</h2>
	<p>Use the form below to upload a file to the following album: <strong><?php echo $name; ?></strong></p>
	<div id="before">
	<div class="album-wrap">
	<form action="add-album-up-single.php" method="post" enctype="multipart/form-data" onSubmit="return checkForm(this)">
		
		<fieldset class="center"><label>File to Upload: </label><input type="file" name="upload"></fieldset><fieldset class="center"><input name="btn" type="submit" value="Upload this Picture" /></fieldset><input type="hidden" name="aid" value="<?php echo $aid; ?>"</form>
	</div></div>
<?php } ?>
<div id="after" class="album-wrap center"><p><strong>Uploading...Please Wait</strong></p><div id="boxes-up"><div class="pbox">&nbsp;</div><div class="pbox">&nbsp;</div><div class="pbox">&nbsp;</div></div><div style="clear:both">&nbsp;</div></div>
</div>
</body>
</html>
