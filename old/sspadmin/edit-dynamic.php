<?php
require "./inc/head.php";
?>
<?php include "./inc/doctype.php"; ?>
<head>
<?php include "./inc/charset.php"; ?>
<title>SSP Admin :: Dashboard</title>
<?php include "./inc/head_elem.php"; ?>
</head>

<body>
	<div id="container">
		
		<?php include "./inc/h1.php"; ?>
		
		<h2>Add a New Dynamic Gallery</h2>
		<p>Add a new dynamic gallery by giving it a name below. Once added, you can specify what albums to include in the dynamic gallery and what order they should appear in.</p>
		<form action="add-dynamic-exe.php" method="post">
		<fieldset>
		<input type="text" name="dName" />&nbsp;&nbsp;<input type="submit" value="Add New Dynamic Gallery" />
		</fieldset>
		</form>
		<h2>Edit a Dynamic Gallery</h2>
		<?php if ($m==1) echo '<p class="update-msg"><small>Dynamic Album Delted!</small></p>'; ?>
		<p>To edit an existing dynamic gallery, select it from the list below.</p>
		<?php dynAlbumsList(); ?>
	</div>
	
	
</body>
</html>
