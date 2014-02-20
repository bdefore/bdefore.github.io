<?php
require "./inc/head.php";
?>
<?php include "./inc/doctype.php"; ?>
<head>
<?php include "./inc/charset.php"; ?>
<title>SSP Admin :: Edit Album</title>
<?php include "./inc/head_elem.php"; ?>
<script language="JavaScript" type="text/javascript" src="scripts/tool-man/core.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/tool-man/events.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/tool-man/css.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/tool-man/coordinates.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/tool-man/drag.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/tool-man/dragsort.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/tool-man/cookies.js"></script>

<script language="JavaScript" type="text/javascript"><!--
	var dragsort = ToolMan.dragsort()
	var junkdrawer = ToolMan.junkdrawer()

	window.onload = function() {

		dragsort.makeListSortable(document.getElementById("boxes"),
				saveOrder)

		/*
		dragsort.makeListSortable(document.getElementById("twolists1"),
				saveOrder)
		dragsort.makeListSortable(document.getElementById("twolists2"),
				saveOrder)
		*/
	}

	function saveOrder(item) {
		var group = item.toolManDragGroup
		var list = group.element.parentNode
		var id = list.getAttribute("id")
		if (id == null) return
		group.register('dragend', function() {
			ToolMan.cookies().set("list-" + id, 
					junkdrawer.serializeList(list), 365)
		})
	}

	//-->
</script>

</head>

<body id="order">
	<div id="container">
		
		<?php include "./inc/h1.php"; ?>
		
		<div id="sub-nav"><strong>Edit Album Sub-Nav &raquo;</strong> <a href="edit-album.php?aid=<?php echo $aid; ?>">Edit Album Metadata</a> | <a href="edit-album-images.php?aid=<?php echo $aid; ?>">Edit Album Images</a> | Edit Image Order</a></div>
		
		<h2>Edit Image Order :: <?php echo $aName; ?></h2>
		<?php if ($m==1) echo '<p class="update-msg"><small>Album Order Changed Successfully!</small></p>'; ?>
		<p>Drag images into the order you would like them to be shown in the slideshow. Be sure to click Save Order when you are done! <em>NOTE: Only active images are shown in this window.</em></p>
		<p style="text-align:right;"><span style="float:left"><input type="button" value="Save Order" onClick="saveOrderDB(<?php echo $aid; ?>)" /></span>Thumbnail View Height: <select id="thumb-size" onchange="sizeThumbs(false)"><option value="25">25</option><option value="50">50</option><option value="75" selected="selected">75</option><option value="100">100</option><option value="150">150</option></select></p>
		<?php writeImageBox($aid,$p,false); ?>
	</div>
	
	
</body>
</html>
