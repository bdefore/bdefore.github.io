<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DeFore Photography</title>
<link rel="stylesheet" href="../styles-site.css" type="text/css" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="../index.rdf" />

</head>

<body>

<table width="732" align="center" border="0" cellspacing="0" cellpadding="0">
<tr>
<td colspan="5">
<div id="banner">

<a href="../"><img src="../header.gif" border="0"></a>
</td>
</tr>

<tr valign=top>

<td width="290" bgcolor="#eeeeee">

<div id="links">

<img src="../images/text_categories.gif" valign="5" hspace="0"><br>
<img src="../images/blockthing.gif">   <a href="people/index.php">People</a><br>
<img src="../images/blockthing.gif">   <a href="nature/index.php">Nature</a><br>
<img src="../images/blockthing.gif">   <a href="inanimate/index.php">Inanimate</a><br>

<br>
<img src="../images/blockthing.gif">   <a href="utah/index.php">Utah</a><br>
<img src="../images/blockthing.gif">   <a href="new_york/index.php">New York</a><br>
<img src="../images/blockthing.gif">   <a href="new_zealand/index.php">New Zealand</a><br>
<img src="../images/blockthing.gif">   <a href="washington/index.php">Washington</a><br>

<br>
<img src="../images/blockthing.gif">   <a href="portrait/index.php">Portrait</a><br>
<img src="../images/blockthing.gif">   <a href="blurry/index.php">Blurry</a><br>
<img src="../images/blockthing.gif">   <a href="macro/index.php">Macro</a><br>
<img src="../images/blockthing.gif">   <a href="monochromatic/index.php">Monochromatic</a><br>

<br>
<img src="../images/blockthing.gif">   <a href="spring/index.php">Spring</a><br>
<img src="../images/blockthing.gif">   <a href="summer/index.php">Summer</a><br>
<img src="../images/blockthing.gif">   <a href="autumn/index.php">Autumn</a><br>
<img src="../images/blockthing.gif">   <a href="winter/index.php">Winter</a><br>
<br>
<img src="../images/blockthing.gif">   <a href="shock/index.php">Shock</a><br>
<img src="../images/blockthing.gif">   <a href="humor/index.php">Humor</a><br>
<img src="../images/blockthing.gif">   <a href="action/index.php">Action</a><br>
<img src="../images/blockthing.gif">   <a href="event/index.php">Event</a><br>
<img src="../images/blockthing.gif">   <a href="urban/index.php">Urban</a><br>
<img src="../images/blockthing.gif">   <a href="texture/index.php">Texture</a><br>
<img src="../images/blockthing.gif"> <a href="mathematical/index.php">Mathematical</a><br>
<br>

<img src="../images/text_information.gif" valign="5" hspace="0"><br>
<img src="../images/blockthing.gif"> <a href="../about.php">About</a><br>
<img src="../images/blockthing.gif"> <a href="../faq.php">FAQ</a><br>
<img src="../images/blockthing.gif"> <a href="../links.php">Links</a><br>

<br><div class="lightright"><b>412</b> photos &nbsp;</div>

<td width="460">

<div id="content">
<div class="blog">

	<div class="blogbody">

	<a name="000524"></a>

	<img src="../fullsize/kamakawatoilet.jpg" border="0">
<table width="500" align="left" border="0" cellspacing="0" cellpadding="0">
 <tr>
 <td>
	<h3 class="title">Kamakawa Toilet</h3>
 	<div class="posted">June 2001<br>

  <td><div class="lightright">

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

        include ("../connect.php");
        mysql_select_db("$database",$db);

        $entry = 524;

        $statement = "SELECT * FROM mt_placement WHERE placement_entry_id = $entry";
        $result = mysql_query($statement);

        while ($row = mysql_fetch_array($result)) {
                $catid = $row["placement_category_id"];
                $catlabelstatement = "SELECT * FROM mt_category WHERE category_id = $catid";
                $catresult = mysql_query($catlabelstatement);

                while($catname = mysql_fetch_array($catresult)) {

                        $categoryraw = $catname["category_label"];
                        $category = dirify($categoryraw);

                unset($idarraytemp);
                unset($linkarraytemp);
                unset($idarray);
                unset($linkarray);

                        include_once('arrays/' . $category . '.php');

                        for ($k =0; $k < count(${$category . "idarraytemp"}); $k++) {
                                $idarraytemp[$k] = ${$category . "idarraytemp"}[$k];
                                $linkarraytemp[$k] = ${$category . "linkarraytemp"}[$k];
                        }

                        $idarray = array_reverse($idarraytemp);
                        $linkarray = array_reverse($linkarraytemp);

                        $thisKey = array_search($entry, $idarray);
                        $thisKeyNext = $thisKey + 1;
                        $thisKeyPrevious = $thisKey - 1;
                        $previouslinkshown = "false";
                        
                        print($categoryraw);
                        print(": ");

                        if ($thisKeyPrevious < 0) {
                                // do nothing
                        } else {
                                print('<a href="');
                                print($linkarray[$thisKeyPrevious]);
                                print('">Prev</a>');
                                $previouslinkshown = "true";
                          }

                        if ($thisKeyNext >= count($linkarray)) {
                                // do nothing
                        } else {
                                if ($previouslinkshown == "true") {
                                        print(" / ");
                                }
                                print('<a href="');
                                print($linkarray[$thisKeyNext]);
                                print('">Next</a>');
                          }

                        print("<br>");

                        }// ends while loop of catname


                }// ends while loop of catid

?>

</div>
</div>
</table>
</div>
</div>

</table>

<table width="732" align="center" border="0" cellspacing="0" cellpadding="0">
<tr>
<td colspan="5" align="right">
<img src="../dotdivider.gif" border="0" vspace="5">
</tr>
<tr>
<td valign="top" align="right">
<img src="../takefive.gif" border="0">
<td colspan="4" align="right">
<?php

include ("../connect.php");
mysql_select_db("$database",$db);

$numthumbs = "5";
$blogurl = "/lmc/photos/archives/";

for ($i = 1; $i <= $numthumbs; $i++) {

        $randomarray = "SELECT entry_text, entry_id FROM mt_entry WHERE entry_blog_id=" . $blogid . " ORDER BY rand() LIMIT 1";
        $randresult = mysql_query($randomarray);
        $row = mysql_fetch_array($randresult);

        if ($row['entry_id'] < 10) {                                     echo ("<a href=00000"); }
        if ($row['entry_id'] >= 10 && $row['entry_id'] < 100) {          echo ("<a href=0000");  }
        if ($row['entry_id'] >= 100 && $row['entry_id'] < 1000) {        echo ("<a href=000");   }
        if ($row['entry_id'] >= 1000 && $row['entry_id'] < 10000) {      echo ("<a href=00");    }

        echo ($row['entry_id']);
        echo (".php>");
        echo ("<img src=../thumbs/");
        echo ($row['entry_text']);
        echo ("_thumb.jpg border=0 hspace=4></a>");

    }
?>

</tr>

<tr>
<td colspan="5">

<div class="powered">

<br /><a href="http://www.movabletype.org"><img src="../anchor.gif" border="0"></a><br />

</div>
</div>
</div>

</body>

</html>





