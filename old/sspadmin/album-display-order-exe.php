<?php
require "./inc/head.php";

$q = $_GET['q'];

$q = explode("|", $q);

for ($i=0; $i < count($q); $i++)
{
	if ($q[$i] != '')
	{
	$s = explode(",", $q[$i]);
	$o = $s[0];
	$id = $s[1];
	mysql_query("UPDATE $atbl SET displayOrder=$o WHERE id = $id") or die(mysql_error());
	}
}
			
header("Location: edit-album-display-order.php?m=1");

?>
