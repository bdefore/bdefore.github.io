<?php require('./inc/head.php'); ?>
		<?php

			$pid = $_GET['pid'];
			$query = "SELECT * FROM $itbl WHERE id=$pid";
			$result = mysql_query($query);
			
			
			while ($row = mysql_fetch_array($result))
			{
				$fileName = $row['src'];
				$st = $row['active'];
				$cap = $row['caption'];
				$link = $row['link'];
			}
		?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body style="margin:10px 0 0; background:#fafafa; text-align:center;font-family:Lucida Grande, Arial, Verdana;font-size:85%;">
<?php if ($m==1)
{
?>
<p style="border:1px solid #cc0000;color:#cc0000;font-weight:bold;background:#e1e1e1;padding:5px;"><small>Update Successful!</small></p>
<?php } ?>
		
		<form action="edit-pic-exe.php" method="post" style="font-weight:bold; font-size:.85em;">
		<p>File Name: <span style="font-weight:normal"><?php echo $fileName; ?></span></p>
		<fieldset style="border:0;margin:0 0 1em;padding:0;">Included in the Album? <input type="checkbox" value="1" name="inc" <?php if ($st == 1) echo 'checked="checked"'; ?> />
		<?php if (($tn == 1) &&  (!ereg("swf", $fileName)) && (!ereg("flv", $fileName)))
		{ ?>
		&nbsp;&nbsp;Album Thumbnail: <input type="checkbox" name="aTn" value="1" <?php if ($atn == $adminDir.'/albums/'.$p.'/tn/'.$fileName) echo 'checked="checked"'; ?> /><?php } ?></fieldset>
		<fieldset style="border:0;margin:0 0 1em;padding:0;">Link: <input type="text" name="link" size="80" value="<?php echo $link; ?>" style="font-size:.9em;" /></fieldset>
		<fieldset style="border:0;margin:0 0 1em;padding:0;">Caption:<br>
			<textarea name="cap" cols="50" rows="3"><?php echo $cap; ?></textarea></fieldset>
		<fieldset style="border:0;margin:0 0 1em;padding:0;"><input type="submit" value="Update Picture Information" style="font-size:.9em;" /></fieldset><input type="hidden" name="pid" value="<?php echo $pid; ?>" /><input type="hidden" name="aid" value="<?php echo $aid; ?>" /><input type="hidden" name="psrc" value="<?php echo $fileName; ?>" /><input type="hidden" name="setAtn" value="<?php echo $atn; ?>" /></form>
		<?php
			if ( ereg("swf", $fileName) ) {
			?>
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="555" height="379" id="SlideShowPro" align="middle">
									<param name="allowScriptAccess" value="sameDomain" />
									<param name="movie" value="albums/<?php echo $p; ?>/lg/<?php echo $fileName; ?>" />
									<param name="quality" value="high" />
									<param name="bgcolor" value="#666666" />
									<embed src="albums/<?php echo $p; ?>/lg/<?php echo $fileName; ?>" quality="high" bgcolor="#e1e1e1" width="555" height="379" name="SlideShowPro" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
									</object>
			<?php } else if ( ereg("flv", $fileName) ) { ?> 
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="400" height="370" id="SlideShowPro" align="middle">
									<param name="allowScriptAccess" value="sameDomain" />
									<param name="movie" value="inc/sspadmin_viewer.swf?fn=../albums/<?php echo $p; ?>/lg/<?php echo $fileName; ?>" />
									<param name="quality" value="high" />
									<param name="bgcolor" value="#666666" />
									<embed src="inc/sspadmin_viewer.swf?fn=../albums/<?php echo $p; ?>/lg/<?php echo $fileName; ?>" quality="high" bgcolor="#e1e1e1" width="400" height="370" name="SlideShowPro" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
									</object>
			<?php } else { ?>
		<img src="albums/<?php echo $p; ?>/lg/<?php echo $fileName; ?>" style="margin:0 auto; padding:5px; background:#e1e1e1; border:1px solid #c7c7c7;" />
			<?php } ?>
		
		
		
		
</body>
</html>