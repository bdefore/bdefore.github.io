<?php

$entries = $_POST["totalEntries"];
$content = "";

foreach($_POST as $key => $value) {		
//	$content .= $_POST["entr$i];
	echo "$key: $value\n";
}

//$content = "Name:".$_POST["name"]." Address:".$_POST["address"];
//$fp = fopen("myText.txt","wb");
echo $content;
//fwrite($fp,$content);
//fclose($fp);
?> 