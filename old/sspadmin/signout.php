<?php
require "./inc/connect.php";

session_start();

session_destroy();

if ($m == 1)
	$q = 5;
else
	$q = 4;
	
header("Location: login-screen.php?m=$q");
?>