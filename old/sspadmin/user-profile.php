<?php
require "./inc/head.php";
?>
<?php include "./inc/doctype.php"; ?>
<head>
<?php include "./inc/charset.php"; ?>
<title>SSP Admin :: User Profile</title>
<?php include "./inc/head_elem.php"; ?>
</head>

<body>
	<div id="container">
		
		<?php include "./inc/h1.php"; ?>
		<?php
			$aid = $_GET['aid'];
			$query = "SELECT * FROM $utbl WHERE id=$uid";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result);
			$pwd = $row['pwd'];
			
		?>
		
		<h2>Edit User Profile</h2>
		<p>NOTE: After updating your profile, you will be automatically logged out so that you can sign in with your new information.</p>
		
		<?php 
		if ($m==1)
			echo '<p class="update-msg"><small>Update Successful!</small></p>';
		else if ($m==2)
			echo '<p class="update-msg"><small>Passwords Did Not Match! Please Try Again.</small></p>';
		?>

		<form action="edit-user-exe.php" method="post">
		<fieldset>User Name:<br />
			<input name="usr" type="text" value="<?php echo $currentUser; ?>" /></fieldset>
		<fieldset>Password:<br />
			<input name="pwd" type="password" value="<?php echo $pwd; ?>" /></fieldset>
		<fieldset>Password Again:<br />
			<input name="pwd2" type="password" value="<?php echo $pwd; ?>" /></fieldset>
		
		<fieldset><input type="submit" value="Update User Profile" /></fieldset>
		<input type="hidden" name="uid" value="<?php echo $uid; ?>" />
		</form>
		
		<h2>Edit Gallery Name</h2>
		<?php 
		if ($m==3)
			echo '<p class="update-msg"><small>Gallery Name Updated!</small></p>';
		
		?>
		<form action="edit-gallery-exe.php" method="post">
		<fieldset>Gallery Name:<br />
			<input name="gn" type="text" value="<?php echo $gName; ?>" /></fieldset>
			<fieldset><input type="submit" value="Update Gallery Name" /></fieldset>
			<input type="hidden" name="uid" value="<?php echo $uid; ?>" />
		</form>
		
	</div>
	
	
</body>
</html>
