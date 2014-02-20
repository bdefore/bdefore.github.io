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
		
		<h2>Edit Dynamic Gallery : <?php echo $aName; ?></h2>
		<?php if ($m==1) echo '<p class="update-msg"><small>Album Order Changed Successfully!</small></p>'; ?>
		<p>This Album's ID for Linking: <strong><?php echo $did; ?></strong></p>
		<?php
			$query = "SELECT id, name FROM $atbl";
			$result = mysql_query($query);
	
			if (mysql_num_rows($result) != 0){
			echo '<ul class="album-sub">';
			while ($row = mysql_fetch_array($result))
			{
				$aName = $row['name'];
				$aid = $row['id'];
				$iquery = "SELECT did FROM $dltbl WHERE did = '$did' AND aid = '$aid'";
				$iresult = mysql_query($iquery);
				if (mysql_num_rows($iresult) == 0){
					echo "<li>$aName - Not Part of this Dynamic Gallery : <a href=\"edit-album-dynamic-exe.php?aid=$aid&did=$did&action=1\">Add</a>";
				} else {
					echo "<li>$aName - Part of this Dynamic Gallery : <a href=\"edit-album-dynamic-exe.php?aid=$aid&did=$did&action=2\">Remove</a>";
				}
			}
			echo '</ul>';
			} else {
				echo '<p><em>You Have Not Added Any Albums to SSPAdmin Yet. Click Add an Album in the Navigation Bar to Get Started.</em></p>';
			}
		?>
		
		<h2>Edit Display Order</h2>
		<p>To Edit the Album Display Order, Order the Albums Below into the sequence you would like them to appear. Albums will appear from top to bottom. Click Update Album Display Order to save your changes.</p>
		
		<?php 
		$ac = mysql_num_rows(mysql_query("SELECT * FROM $dltbl WHERE did = '$did' ORDER BY display"));
		
		if ($ac == 0) { ?>
		<p class="update-msg">No Albums Have Been Added to this Dynamic Gallery</p>
		<?php } else if ($ac == 1) { ?>
		<p class="update-msg">Only One Album. Nothing to Reorder...</p>
		<?php } else { ?>
		
		<fieldset><input type="button" value="Update Album Display Order" onClick="saveDynAlbOrder(<?php echo $did; ?>)" /></fieldset>
		
			<?php dynAlbumsListOrder($did); ?>

		<?php } ?>
	</div>
	
	
</body>
</html>
