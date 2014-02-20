<?php
require "./inc/head.php";
?>
<?php include "./inc/doctype.php"; ?>
<head>
<?php include "./inc/charset.php"; ?>
<title>SSP Admin :: Edit Album</title>
<?php include "./inc/head_elem.php"; ?>

</head>

<body>
	<div id="container">
		
		<?php include "./inc/h1.php"; ?>

		<div id="sub-nav"><strong>Edit Album Sub-Nav &raquo;</strong> Edit Album Metadata | <a href="edit-album-images.php?aid=<?php echo $aid?>">Edit Album Images</a> | <a href="edit-album-order.php?aid=<?php echo $aid; ?>">Edit Image Order</a></div>
		<?php if ($m==2)
			echo '<p class="update-msg"><small>Album Added. Edit Metadata Below, or <a href="edit-album-images.php?aid='.$aid.'">Edit the Album\'s Images</a></small></p>';
		?>
		<h2>Edit Album Metadata</h2>
		<?php if ($m==1)
			echo '<p class="update-msg"><small>Update Successful!</small></p>';
			if ($m==3)
				echo '<p class="update-msg"><small>Links Changed Successfully!</small></p>';
		?>
		
		<form action="edit-album-exe.php" method="post">
		<fieldset>Album Name:<br />
			<input name="aName" type="text" value="<?php echo $aName; ?>" /></fieldset>
		<fieldset>Album Description:<br />
			<textarea name="aDesc" rows="5" cols="40"><?php echo $aDes; ?></textarea></fieldset>
		<fieldset>Album Thumbnail:<br />
			<small>Only use this box if you want to set the Album Thumbnail to an image not in your album directory. To use a image in that directory, use the <a href="edit-album-images.php?aid=<?php echo $aid; ?>">Edit Album Images</a> page. You can do one of two things. First, you could specify a relative url. Remember, if you use a relative URL, it needs to be relative to the SlideShowPro flash file, therefore it must include the name of this admin folder (ex. <?php echo $adminDir; ?>/album-thumbs/somefile.jpg). You can also use an absolute url (http://www.myserver.com/somefile.jpg)</small><br />
			<input name="atn" style="margin-top:5px;" type="text" size="45" value="<?php echo $atn; ?>" /></fieldset>
		<fieldset>Thumbnails: <input type="checkbox" name="tn" value="1"
		<?php if ($tn == 1) echo 'checked="checked"'; ?> /><br/>
		<small>If you check this box, be sure you have uploaded thumbs into the tn directory of this album! <a href="generate-thumbs.php?aid=<?php echo $aid ?>">Click Here to Generate Thumbnails</a></small>
		</fieldset>
		
		<fieldset>Links Open in Same Window: <input type="checkbox" name="tgt" value="1"
		<?php if ($tgt == 1) echo 'checked="checked"'; ?> /><br/>
		<small>Version 1.0.3 (or higher) of SlideShowPro Required</a></small>
		</fieldset>
		
		<fieldset>Prefill Links to Point to Large Version of the Image: <br />
		<a href="auto-link.php?aid=<?php echo $aid ?>">Click Here to Prefill All Links</a>
		<small>NOTE: This will change ALL of your links, even ones you have already set, for this album. You can edit them individually from the Edit Image page.</a></small>
		</fieldset>
		
		<fieldset>Album Audio Track (Optional):<br />
			<input name="aMp3" type="text" value="<?php echo $aMp3; ?>" /> <small>(File Name only. MUST be in the album-audio folder)</small></fieldset>
		<fieldset>Album Audio Caption (Optional):<br />
			<textarea name="aMp3Desc" rows="5" cols="40"><?php echo $aMp3Cap; ?></textarea></fieldset>
		
		<fieldset><input type="submit" value="Update Album Metadata" /></fieldset>
		<input type="hidden" name="aid" value="<?php echo $aid; ?>" />
		</form>
		
	</div>
	
	
</body>
</html>
