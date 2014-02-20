<?php
require "./inc/connect.php";
require "./inc/authentication.php";

// Clean the data collected in the <form>
$loginUsername = mysqlclean($_POST, "Usr", 20, $connection);
$loginPassword = mysqlclean($_POST, "Pwd", 20, $connection);

session_start();

// Authenticate the user
if (authenticateUser($connection, $loginUsername, $loginPassword, $utbl))
{
  // Register the loginUsername
  $_SESSION["login"] = $loginUsername;

  // Register the IP address that started this session
  $_SESSION["loginIP"] = $_SERVER["REMOTE_ADDR"];

  // Relocate back to the first page of the application
  header("Location: index.php");
  exit;
}
else
{
  // The authentication failed: setup a logout message
  $_SESSION["message"] = "Could not connect to the application as '{$loginUsername}'";

  // Relocate to the logout page
  header("Location: login-screen.php?m=1");
  exit;
}
?>
