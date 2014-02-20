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

		dragsort.makeListSortable(document.getElementById("albums"),
				verticalOnly, saveOrder)

	}

	function verticalOnly(item) {
		item.toolManDragGroup.verticalOnly()
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
		
		<h2>Edit Album Display Order</h2>
		<?php if ($m==1) echo '<p class="update-msg"><small>Album Order Changed Successfully!</small></p>'; ?>
		
		<p>To Edit the Album Display Order, drag the albums into the the order you want them to show up in the gallery. Albums will appear from top to bottom. Click Update Album Display Order to save your changes.</p>
		
		<?php 
		$ac = mysql_num_rows(mysql_query("SELECT id FROM $atbl WHERE active = '1'"));
		
		if ($ac == 0) { ?>
		<p class="update-msg">No Active Albums</p>
		<?php } else if ($ac == 1) { ?>
		<p class="update-msg">Only One Active Album. Nothing to Reorder...</p>
		<?php } else { ?>
		
		<fieldset><input type="button" value="Update Album Display Order" onClick="saveAlbOrder()" /></fieldset>

			<?php albumsListOrder(); ?>
			 
		<?php } ?>
	</div>
	
	
</body>
</html>
