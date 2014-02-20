<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SSP Admin :: Install</title>
<link rel="stylesheet" href="styles/main.css" /> 
</head>

<body>

<div id="container">
<h1>SSP Admin Install</h1>

<?php
	
	$s = $_SERVER['PHP_SELF'];
	$s = str_replace('/start.php', '', $s);
	$s = explode('/', $s);
	
	$c = count($s);
	
	$dir = $s[$c-1];

?>

<form action="write.php" method="post">
	<h2>Server Details :: Be sure you have created the database first!</h2>
	<fieldset>MySQL Server Name: <input type="text" name="svr" /> <small>ex. mysql.server.com</small></fieldset>
	<fieldset>Database Name: <input type="text" name="db" /></fieldset>
	<fieldset>UserName: <input type="text" name="usr" /></fieldset>
	<fieldset>Password: <input type="password" name="pwd" /></fieldset>
	
	<h2>Advanced Settings</h2>
	<fieldset>SSP Admin Install Folder: <input type="text" name="fldr" value="<?php echo $dir; ?>" /> <small>Should have guessed this correctly</small></fieldset>
	<fieldset>MySQL Table Prefix: <input type="text" name="pr" value="ssp_" /> <small>Change if this causes a conflict with existing tables in your database.</small></fieldset>
	<fieldset><input type="submit" value="Start Install" /></fieldset>
</form>

</div>
</body>
</html>
