<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>default</title>
</head>
<body bgcolor="#000000">
<!--url's used in the movie-->
<!--text used in the movie-->
<!-- saved from url=(0013)about:internet -->
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="550" height="400" id="default" align="middle">
<param name="movie" value="default.swf" />
<?
	if($_GET['user']!=null) { print "<param name=\"FlashVars\" value=\"user=" . $_GET['user'] . "&id=" . $_GET['id'] . "\" />"; }
?>
<param name="quality" value="high" />
<param name="scale" value="noscale" />
<param name="bgcolor" value="#000000" />
<?
//	print "<embed src=\"default.swf\" quality=\"high\" scale=\"noscale\" bgcolor=\"#000000\" width=\"550\" height=\"400\" name=\"default\" align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />";
	print "<embed ";
	if($_GET['user']!=null) { print "FlashVars=\"user=" . $_GET['user'] . "&id=" . $_GET['id'] . "\" "; }
	print "src=\"default.swf\" quality=\"high\" scale=\"noscale\" bgcolor=\"#000000\" width=\"550\" height=\"400\" name=\"default\" align=\"middle\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />"; 
?>
</object>
</body>
</html>
