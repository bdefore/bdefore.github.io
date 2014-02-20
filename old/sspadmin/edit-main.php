<?php
require "./inc/head.php";
?>
<?php include "./inc/doctype.php"; ?>
<head>
<?php include "./inc/charset.php"; ?>
<title>SSP Admin :: Albums List</title>
<?php include "./inc/head_elem.php"; ?>
</head>

<body>
	<div id="container">
		
		<?php
		include "./inc/h1.php";
		
		if ($m==1)
			echo '<p class="update-msg"><small>Album Deleted</small></p>';
		else if ($m==2)
			echo '<p class="update-msg"><small>Album Activated</small></p>';
		else if ($m==3)
			echo '<p class="update-msg"><small>Album Deactivated</small></p>';
		else if ($m==4)
			echo '<p class="update-msg"><small>startHere Album Set</small></p>';
		else if ($m==5)
			echo '<p class="update-msg"><small>startHere Album Disabled</small></p>';
	
		albumsList();
		?>
	
	</div>
	
	
</body>
</html>
