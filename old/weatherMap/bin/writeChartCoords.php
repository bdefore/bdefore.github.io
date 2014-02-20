<?php

$entries = $_POST["totalEntries"];
$content = "";

for($i=0; $i<=$entries; $i++) {		
	$content .= $_POST["entry".$i];
}

//$content = "Name:".$_POST["name"]." Address:".$_POST["address"];
$fp = fopen("cachedChartCoords.txt","wb");
fwrite($fp,$content);
fclose($fp);
?> 