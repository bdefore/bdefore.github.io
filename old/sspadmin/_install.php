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
	require_once('./inc/connect.php');
	
	$u = $_REQUEST['usr'];
	$p = $_REQUEST['pwd'];
	
	if (!$u){
		$u = 'admin';
		$p = substr(md5(uniqid(microtime())), 0, 6);
	}
	
	// Create Tables
	
	mysql_query("CREATE TABLE $atbl(id INT AUTO_INCREMENT, PRIMARY KEY(id), name VARCHAR(100), description BLOB, path VARCHAR(50), tn TINYINT(1) NOT NULL DEFAULT '0', aTn VARCHAR(150), active TINYINT(1) NOT NULL DEFAULT '0', startHere TINYINT(1) NOT NULL DEFAULT '0', audioFile VARCHAR(100) DEFAULT NULL, audioCap VARCHAR(200) DEFAULT NULL, displayOrder TINYINT(4) DEFAULT '800', target TINYINT(1) NOT NULL DEFAULT '0')") or die("There was an error when writing to the DB:".mysql_error());
	
	echo "<p>Albums Table Written Successfully...</p>";
	
	flush();
	
	mysql_query("CREATE TABLE $itbl(id INT AUTO_INCREMENT, PRIMARY KEY(id), aid INT, src VARCHAR(50), caption TEXT, link TEXT, active TINYINT(1) NOT NULL DEFAULT '1', seq TINYINT(4) DEFAULT NULL)") or die("There was an error when writing to the DB:".mysql_error());
	
	echo "<p>Images Table Written Successfully...</p>";
	
	flush();
	
	mysql_query("CREATE TABLE $utbl(id INT AUTO_INCREMENT, PRIMARY KEY(id), usr VARCHAR(50), pwd VARCHAR(50), gName VARCHAR(200) DEFAULT NULL)") or die("There was an error when writing to the DB:".mysql_error());
	
	echo "<p>Users Table Written Successfully...</p>";
	
	mysql_query("CREATE TABLE $dtbl(id INT AUTO_INCREMENT, PRIMARY KEY(id), name VARCHAR(100))") or die("There was an error when writing to the DB:".mysql_error());
	
	mysql_query("CREATE TABLE $dltbl(id INT AUTO_INCREMENT, PRIMARY KEY(id), did INT, aid INT, display TINYINT(4) DEFAULT '800')") or die("There was an error when writing to the DB:".mysql_error());
	
	echo "<p>Dynamic Gallery Tables Written Successfully...</p>";
	
	
	mysql_query("INSERT INTO $utbl (id, usr, pwd) VALUES (NULL, '$u', '$p')") or die("Error".mysql_error());

?>
	
	<p>Success! <a href="login-screen.php">Click here</a> to login for the first time with the username <strong><?php echo $u ?></strong> and the password <strong style="color:#fafafa;"><?php echo $p ?></strong>(Select the empty area to see your password. It is highly recommended that you now delete start.php, write.php and _install.php from your webserver.</p>

</div>
</body>
</html>
