<?php
require "./inc/head.php";

$aid = $_GET['aid'];
$q = $_GET['q'];

$q = explode("|", $q);

for ($i=0; $i < count($q); $i++)
{
	if ($q[$i] != '')
	{
	$s = explode(",", $q[$i]);
	$o = $s[0];
	$id = $s[1];
	mysql_query("UPDATE $itbl SET seq=$o WHERE id = $id") or die(mysql_error());
	}
}
			
header("Location: edit-album-order.php?aid=$aid&m=1");

?>
