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
		
		<ul>
		<li><strong>Active Albums:</strong> <?php getCount('album'); ?></li>
		
		<li><strong>Photos:</strong> <?php getCount('img'); ?></li>
		
		</ul>
		
		<?php albumsList(); ?>
	</div>
	
	
</body>
</html>
