<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DeFore Photography</title>
<link rel="stylesheet" href="/lmc/photos/styles-site.css" type="text/css" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="/lmc/photos/index.rdf" />

</head>

<body>

<table width="732" align="center" border="0" cellspacing="0" cellpadding="0">
<tr>
<td colspan="5">
<div id="banner">

<a href=""><img src="header.gif" border="0"></a>
</td>
</tr>

<tr valign=top>

<td width="290" bgcolor="#eeeeee">

<div id="links">

<img src="images/text_findimages.gif" valign="5" hspace="0"><br>
<img src="images/blockthing.gif"> <a href="random.php">At Random</a><br>
<img src="images/blockthing.gif"> <a href="bycat.php">By Category</a><br>
<br>

<img src="images/text_information.gif" valign="5" hspace="0"><br>
<img src="images/blockthing.gif"> <a href="about.php">About</a><br>
<img src="images/blockthing.gif"> <a href="faq.php">FAQ</a><br>
<img src="images/blockthing.gif"> <a href="links.php">Links</a><br>

<br>

<br><div class="lightright"><b>412</b> photos &nbsp;</div>


<td width="460">
<div id="content">
<div class="blog">

<?php

//hooray and thanks to "empty pages" for this quick code, found here: http://www.emptypages.org/more/snips/dirify_function.html

        function dirify ($s) {
        $s = strtolower($s);
        $patterns = array('/<[\/\!]*?[^<>]*?&gt;/s', '/&[^;\s]+;/',
        '/[^\w\s]/', '/ /');
        $replace = array('', '', '', '_');
        $s = preg_replace($patterns, $replace, $s);
        return $s;
        }

        include ("connect.php");
        mysql_select_db("$database",$db);

        $randomarray = "SELECT entry_text, entry_id FROM mt_entry WHERE entry_blog_id=" . $blogid . " ORDER BY rand() LIMIT 1";
        $randresult = mysql_query($randomarray);
        $row = mysql_fetch_array($randresult);
        $entry = $row['entry_id'];

        echo ("<img src=fullsize/");
        echo ($row['entry_text']);
        echo (".jpg border=0><br><br><h3 class=title>");
        echo ($row['entry_title']);
        echo ("</h3>");
        echo ($row['entry_excerpt']);
        echo ("At Random");

?>


</div>
</div>

</table>

<table width="732" align="center" border="0" cellspacing="0" cellpadding="0">

<tr>
<td colspan="5" align="right">
<img src="dotdivider.gif" border="0" vspace="5">
</tr>
<tr>
<td valign="top" align="right">
<img src="takefive.gif" border="0">
<td colspan="4" align="right">
<?php

include ("connect.php");
mysql_select_db("$database",$db);

$numthumbs = "5";
$blogurl = "/lmc/photos/archives/";

for ($i = 1; $i <= $numthumbs; $i++) {

        $randomarray = "SELECT entry_text, entry_id FROM mt_entry WHERE entry_blog_id=" . $blogid . " ORDER BY rand() LIMIT 1";
        $randresult = mysql_query($randomarray);
        $row = mysql_fetch_array($randresult);

        if ($row['entry_id'] < 10) {                                     echo ("<a href=archives/00000"); }
        if ($row['entry_id'] >= 10 && $row['entry_id'] < 100) {          echo ("<a href=archives/0000");  }
        if ($row['entry_id'] >= 100 && $row['entry_id'] < 1000) {        echo ("<a href=archives/000");   }
        if ($row['entry_id'] >= 1000 && $row['entry_id'] < 10000) {      echo ("<a href=archives/00");    }

        echo ($row['entry_id']);
        echo (".php>");
        echo ("<img src=thumbs/");
        echo ($row['entry_text']);
        echo ("_thumb.jpg border=0 hspace=4></a>");

    }
?>

<tr>
<td colspan="5">

<div class="powered">

<br /><a href="http://www.movabletype.org"><img src="anchor.gif" border="0"></a><br />

</div>
</div>
</div>

</body>

</html>





