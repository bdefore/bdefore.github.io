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
	mysql_query("UPDATE $dltbl SET display=$o WHERE aid = $id and did=$did") or die(mysql_error());
	}
}
			
header("Location: edit-dynamic-single.php?m=1&did=$did");

?>
