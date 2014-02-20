<?php
require "./inc/head.php";
?>
<?php include "./inc/doctype.php"; ?>
<head>
<?php include "./inc/charset.php"; ?>
<title>SSP Admin :: Edit Album</title>
<?php include "./inc/head_elem.php"; ?>
</head>

<body>
	<div id="container">
		
		<?php include "./inc/h1.php"; ?>
		
		<div id="sub-nav"><strong>Edit Album Sub-Nav &raquo;</strong> <a href="edit-album.php?aid=<?php echo $aid; ?>">Edit Album Metadata</a> | Edit Album Images | <a href="edit-album-order.php?aid=<?php echo $aid; ?>">Edit Image Order</a></div>
		
		<h2>Edit Image Information :: <?php echo $aName; ?></h2>
		<p>Click on a picture to edit it's properties</p>
		<p style="text-align:right;">Thumbnail View Height: <select id="thumb-size" onchange="sizeThumbs(true)"><option value="25">25</option><option value="50">50</option><option value="75" selected="selected">75</option><option value="100">100</option><option value="150">150</option></select></p>
		<?php writeImageBox($aid,$p,true); ?>
		
		<iframe id="pic-holder" frameborder="0"></iframe>
	</div>
	
	
</body>
</html>
