<?php
  session_start();
  // Check if the user hasn't logged in
  if (!isset($_SESSION["login"]) || (!isset($_SESSION["loginIP"]) || ($_SESSION["loginIP"] != $_SERVER["REMOTE_ADDR"])))
  {
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SSP Admin Login</title>
<link rel="stylesheet" href="styles/main.css" />
<style type="text/css">
p {
text-align:center;
}
</style>
</head>

<body>
<div id="container">
<h1>SSP Admin</h1>

<?php
	if (isset($m))
	{
		switch($m)
		{
			case 1:
			$alert = 'Invalid Login, Please Try Again';
			break;
			
			case 2:
			$alert = 'No Current Session Found. Please Log In Again.';
			break;
			
			case 3:
			$alert = 'Session Began from a Different IP Address. For security reasons, please log on again.';
			break;
			
			case 4:
			$alert = 'You Have Successfully Signed Out.';
			break;
			
			case 5:
			$alert = 'The changes to your user profile have been made. You can now sign in with your updated information.';
			break;
		}
?>
<p class="update-msg"><small><?php echo $alert ?></small></p>
<?php
	}
	else {
?>
<p>Login below to begin</p>
<?php }; ?>
<form method="post" action="login.php">
<fieldset class="center">Username: <input type="text" name="Usr" /></fieldset>
<fieldset class="center">Password: <input type="password" name="Pwd" /></fieldset>
<fieldset class="center"><input type="submit" value="Login Now" /></fieldset>
</form>
</div>
</body>
</html>
<?php
} else {
  	header("Location: index.php");
}
?>