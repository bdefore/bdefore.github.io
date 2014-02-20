<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sanxuary Photography</title>
<link rel="stylesheet" href="../photos/styles-site.css" type="text/css" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="../photos/index.rdf" />

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
<br>
<br>
<img src="takefive.gif" border="0"><br>
<?php

include ("connect.php");
mysql_select_db("$database",$db);

$numthumbs = "3";
$blogurl = "../photos/archives";

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
        echo ("_thumb.jpg border=0 vspace=4></a><br>");

    }
?>


<br><div class="lightright"><b>412</b> photos &nbsp;</div>

<td width="460">
<img src="images/questions.gif">
<div id="content">
<div class="blogtight">


&nbsp;<a href="#1">Where are you from?</a><br>
&nbsp;<a href="#2">How long have you been photographing?</a><br>
&nbsp;<a href="#3">How does this site work?</a><br>
&nbsp;<a href="#5">Can I order a print of one of the photos?</a><br>
&nbsp;<a href="#4">What camera(s) do you use?</a><br>

<p><a href="#1"></a><b>Where are you from?</b><br>I was raised in Altona, New York. Since then I've attended college in Tacoma, Washington. I'm very fortunate to have visited many places in the between. You'll see a few photos from New Zealand, Utah, and California. I confess; I have wanderlust, and I don't think that's going to change anytime soon.

<p><a href="#2"></a><b>How long have you been photographing?</b><br>I suppose I'm an odd fish, but, were it not my discovery of photography three years ago, I would not have particularly been interested in art. Since then, I've taken quite a liking to web-design and graphic-design, as you might notice from <a href="http://www.sanxuary.com">www.sanxuary.com</a>.

<p><a name="#3"></a><b>How does this site work?</b><br>

I created this site in the beginning of 2003 by using an open-source toolkit called <a href="http://www.movabletype.org">Movable Type</a>. The site runs from Perl/CGI and a MySQL database to dynamically update the content. This allows me to alter the presentation of the site without having to manually modify hundreds of individual files.

<p>Web-design is one of many fields that benefit from open-source software. If you are new to the idea, it is worth reading into. However, there are other places online that discuss the philosophy of open-source software better than I can. I can summarize to say that the generous development time of programmers all over the world lies at the core of this and many other database-driven websites around the world. I am indebted to this community, and I hope that my work on inter-category links can be of help to others. For more specific credits, see the <a href="about.php">About</a> page.

<p><a name="#5"></a><b>Can I order a print of one of the photos?</b><br>
Super probably. In most cases, yes. Some photos are more inclined to a full-sized print than others. The ones you see on the site are significantly smaller versions of originals, which are generally of good resolution for print on an 8x10 or sometimes larger.
<p>If you're privy to a photo and would like a print, give me an email: bdefore at ups.edu</p>

<p><a name="#4"></a><b>What camera(s) do you use?</b><br>
<table align="left" cellpadding="10">
Although I've used other cameras, all of the photography on this site was taken with one of the following cameras:
<tr>
<td>
<tr><td>Olympus D-40. My current camera. I really appreciate the technology in this one. It has all of the features that I need in a digital camera: a sliding door to protect the lens, small size to accommodate tight situations, a decent optical zoom, enough megapixels (4) to retain detail, the usual Olympus interface that I'm comfortable with. Everything else is gravy, and it will be difficult to ween me off this one.<td bgcolor="999999"><img src="images/oly_c40z.gif" align="left" hspace=10 vspace=10><br></tr>
<tr><td>The majority of photographs on the site at this point are with this gem from Olympus. All of the New Zealand photographs were taken with the C-3030, which at 3 megapixels is a decent enough quality to justify just about any purpose at this point in time. You have great a great amount of control over the functions of this camera.<td bgcolor="999999"><img src="images/oly_c3030z.gif" align="right" hspace=10 vspace=10><p></tr>
<tr><td>Although I only had this camera for a short while, it turns out I took a few of my favorites with the Kodak 4800. True to the Kodak reputation, color reproduction is very aggressive, especially noticeable in shots of foliage or sunset. I admit; the only reason I upgraded from this camera was for its limited ability to manipulate automatic settings. Otherwise, it's a strong camera.<td bgcolor="999999"><img src="images/kodak_dc4800.gif" align="right" hspace=10 vspace=10><p></tr>
<tr><td>The Oly C-2020 was the first camera that made me take digital photography seriously, particularly for use in Photoshop. I'll never forget the summer where I awoke to what photography could be, and it was all due to this camera. If there are those of you on the fence to buying a camera, this camera, or any other 2 megapixel camera, would be a great entry into the realm of photography.<td bgcolor="999999"><img src="images/oly_c2020z.gif" align="right" hspace=10 vspace=10><p></tr>
<tr><td>My second camera. I believe the only picture taken with this camera here is Tahoma Back Country. Indeed, there would be pictures from older cameras, were it not for a hard drive failure I had in 2001. <b>Let that be a warning to you, remember to back up your photos!</b><td bgcolor="999999"><img src="images/oly_d340r.gif" align="right" hspace=10 vspace=10></td></tr>
</table>

</div>

</div>



</table>




<table width="732" align="center" border="0" cellspacing="0" cellpadding="0">

<tr>
<td>

<td colspan="5">

<div class="powered">

<br /><a href="http://www.movabletype.org"><img src="anchor.gif" border="0"></a><br />

</div>
</div>
</div>

</body>

</html>





