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
		if (theForm.aName.value == '')
		{
			alert("Please Enter a Name for the New Album");
			return false;
		}
		else if (theForm.upload.value == '')
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
div#boxes-up { width:216px; margin:0 auto; }
div.pbox { width:50px; height:50px; float:left; margin:10px; background:#c7c7c7; border:1px solid #555555;}
div.pbox-sel { width:50px; height:50px; float:left; margin:10px; background:#ffffff; border:1px solid #cc0000;}
</style>
</head>

<body>
	<div id="container">
		
	
		<?php include "./inc/h1.php"; ?>
		<div id="before">
		<div class="album-wrap">
		<h2>I Want to Upload My Files in a Zip Archive</h2>
		<?php if ( @include( 'PEAR.php' ) ) { ?>
		
		<p>Before proceeding, be sure you have created a proper Zip archive. When the archive opens, it should have the lg folder with your full size images, and optionally your tn folder with your thumbnails.</p>
		<form action="add-album-up.php" method="post" enctype="multipart/form-data" onSubmit="return checkForm(this)">
		<fieldset class="center">Give the Album Folder a Name: <input type="text" name="aName" /><br /><small>(Alphanumeric characters, spaces and underscores only!)</small>
</fieldset>
		<fieldset class="center"><label>Zip File: </label><input type="file" name="upload"></fieldset><fieldset class="center"><input name="btn" type="submit" value="Upload Zip Archive of Pictures" /></fieldset>
		</form>
		<?php } else { ?>
		<p>Using this feature requires the PEAR library for PHP. Contact your host for availability.</p>
		<?php } ?>
		</div>
		<div class="album-wrap">
		<h2>I Want to Add Files One at a Time</h2>
		<form action="add-album-up-single.php" method="post" enctype="multipart/form-data" onSubmit="return checkForm(this)">
		<fieldset class="center">Give the Album Folder a Name: <input type="text" name="aName" /><br /><small>(Alphanumeric characters, spaces and underscores only!)</small>
</fieldset>
		
		<fieldset class="center"><label>File to Upload: </label><input type="file" name="upload"></fieldset><fieldset class="center"><input name="btn" type="submit" value="Upload this Picture" /></fieldset></form></div>
		<div class="album-wrap">
		<h2>I Want to Scan an Existing Folder for Images</h2>
		<p>Before proceeding, you should have uploaded your photos to their own directory within the album directory of the SSP Admin installation. Visit the <a href="http://slideshowpro.net">SlideShowPro Site</a> for more information on preparing your photos. Follow the example directory structure below:</p>
		<div class="center"><img src="images/dir-ex.gif" class="figure-pic" /></div>
		<p>Select the name of the album folder you would like to use below. For example, using the figure above, you would select "test-album" to use the photos in that directory to create a new album. You CAN use the same folder for different albums.</p>
		<fieldset class="center"><form action="add-album-scan.php" method="post">
	<?php

if ($handle = opendir('./albums')) {
	$i = 0;
	$output = '<select name="aDir">';
    while (false !== ($file = readdir($handle))) {
    	
    	if ( $file != '.' && $file != '..' )
    	{
			if ( is_dir ( './albums/'.$file ) )
			{
				$output .= "<option>$file</option>";
				$i += 1;
			}
    	}
    }

    closedir($handle);
    
    if ( $i != 0 )
    {
    	$output .= '</select>';
    }
    else
    {
    	$output = 'No Albums Directories Found. You need to upload your photos first!';
    }
    echo($output);
}
?><input type="submit" value="Scan Folder for Pictures &raquo;" /></form></fieldset>
	</div>
		</div>
		<div id="after" class="album-wrap center"><p><strong>Uploading...Please Wait</strong></p><div id="boxes-up"><div class="pbox">&nbsp;</div><div class="pbox">&nbsp;</div><div class="pbox">&nbsp;</div></div><div style="clear:both">&nbsp;</div></div>
	</div>
	
	
</body>
</html>
