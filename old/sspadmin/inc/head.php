<?php
require "./inc/connect.php";
require "./inc/authentication.php";
session_start();
sessionAuthenticate();
$currentUser = $_SESSION["login"];
// Get Album Name
$query = "SELECT gName
			FROM $utbl
			WHERE usr = '$currentUser'";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
$gName = $row["gName"];

if (empty($gName))
	$gName = 'SSPAdmin';
	
if (isset($_GET['m']))
	$m = $_GET['m'];
else
	$m = '';

// Get User Profile
$query = "SELECT id
			FROM $utbl
			WHERE usr = '$currentUser'";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
$uid = $row["id"];

// If Album ID is present, get album info

if (isset($_GET['aid']))
{
	$aid = $_GET['aid'];
	$query = "SELECT * FROM $atbl WHERE id=$aid";
	$result = mysql_query($query);
	
	
	while ($row = mysql_fetch_array($result))
	{
		$aName = $row['name'];
		$aDes = $row['description'];
		$p = $row['path'];
		$tn = $row['tn'];
		$tgt = $row['target'];
		$atn = $row['aTn'];
		$aMp3 = $row['audioFile'];
		$aMp3Cap = $row['audioCap'];
	}
}

if (isset($_GET['did']))
{
	$did = $_GET['did'];
	$query = "SELECT * FROM $dtbl WHERE id=$did";
	$result = mysql_query($query);
	
	
	while ($row = mysql_fetch_array($result))
	{
		$aName = $row['name'];
	}
}


?>