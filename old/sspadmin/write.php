<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SSP Admin :: Install</title>
<link rel="stylesheet" href="styles/main.css" />
<script type="text/javascript">
	function checkPwd(){
		elem = document.getElementById('theForm');
		var pwd = elem.pwd.value;
		var pwd2 = elem.pwd2.value;
		
		if (pwd == pwd2)
		{
			return true;
		}
		else
		{
			alert('Passwords Do Not Match!');
			return false;
		}
	}
		
</script>
</head>

<body>

<div id="container">
<h1>SSP Admin Install</h1>

<?php
if ( !$_POST )
{
	die('<h2>Error</h2><p>Please start the installation from <a href="start.php">start.php</a>...</p>');
} else
{
$server = $_REQUEST['svr'];
$db = $_REQUEST['db'];
$usr = $_REQUEST['usr'];
$pwd = $_REQUEST['pwd'];
$fldr = $_REQUEST['fldr'];
$pr = $_REQUEST['pr'];

$filename = 'inc/conf.php';

$perms = substr(sprintf('%o', fileperms($filename)), -4);

if ($perms != '0777')
	@chmod($filename, 0777) or die("<h2>Error</h2><p>SSPAdmin does not have the proper permissions to write to the inc/conf.php file. SSPAdmin tried do set the permissions for you, but was rejected. Please chmod this file to 777 and start the installation over. If you cannot set the permissions to this file, open it in a text editor and fill in the values manually. Then simply run _install.php");
				
$fill = "<?php\n\n";
$fill .= '$host = \''.$server."';\n";
$fill .= '$db = \''.$db."';\n";
$fill .= '$user = \''.$usr."';\n";
$fill .= '$pass = \''.$pwd."';\n\n";
$fill .= '$adminDir = \''.$fldr."';\n";
$fill .= '$pre = \''.$pr."';\n\n";
$fill .= '?>';

$handle = fopen($filename, 'w+');

if (fwrite($handle, $fill) == false)
{
	die('<p>An Error Occured. Your Server May Not Allow Writing Files Via PHP. If not, follow the instructions for creating the configuration file in the help documents.</p>');
}

fclose($handle);

echo '<p>Your configuration file was successfully created. You are now ready to install SSP Admin. All that is needed is for you to pick a username and password below. This is the username and password you will use to login to SSPAdmin</p>';
?> 

<form id="theForm" action="_install.php" method="post" onsubmit="return checkPwd()">
	<fieldset>Username: <input type="text" name="usr" /></fieldset>
	<fieldset>Password: <input type="password" name="pwd" /></fieldset>
	<fieldset>Password Again: <input type="password" name="pwd2" /></fieldset>
	<fieldset><input type="submit" value="Install SSP" /></fieldset>
</form>
<?php } ?>

</div>
</body>
</html>
