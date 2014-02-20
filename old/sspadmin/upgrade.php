<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SSP Admin :: Upgrade to 1.3</title>
<link rel="stylesheet" href="styles/main.css" /> 
</head>

<body>

<div id="container">
<h1>SSPAdmin Upgrade 1.3</h1>
<?php
	require_once('./inc/connect.php');
	
	
	
	@mysql_query("ALTER TABLE $itbl CHANGE link link TEXT");
	
	echo "<h2>Tables Altered Successfully...</h2>";

?>
	
	<p>Success! The upgrade is complete. You may now delete this file from your web server and use SSPAdmin as you normally do. <a href="index.php">Dashboard</a></p>

</div>
</body>
</html>
