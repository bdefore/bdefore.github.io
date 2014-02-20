<?php
require "./inc/head.php";
?>
<?php
	if ( $_POST ) {
		require "./inc/upload_class.php";
		require "./inc/Zip.php";
		$perms = substr(sprintf('%o', fileperms('./albums/')), -4);
		if ($perms != '0777')
			@chmod(('albums/'), 0777) or die("<h2>Error</h2><p>SSPAdmin does not have the proper permissions to write to the albums folder. SSPAdmin tried do set the permissions for you, but was rejected. Please chmod this folder to 777");
		$clean_name = $_POST['aName'];
		$the_id = str_replace(" ", "-", $clean_name);
		$max_size = 1024*100; // the max. size for uploading
			
		$my_upload = new file_upload;
		$my_upload->upload_dir = realpath('./albums/') . '/'; // "files" is the folder for the uploaded files (you have to create this folder)
		$my_upload->extensions = array(".zip"); // specify the allowed extensions here
		$my_upload->max_length_filename = 100; // change this value to fit your field length in your database (standard 100)
			
		$my_upload->the_temp_file = $_FILES['upload']['tmp_name'];
		$my_upload->the_file = $the_id.'.zip';
		$my_upload->http_error = $_FILES['upload']['error'];
		$my_upload->replace = (isset($_POST['replace'])) ? $_POST['replace'] : "n"; // because only a checked checkboxes is true
		$my_upload->do_filename_check = (isset($_POST['check'])) ? $_POST['check'] : "n"; // use this boolean to check for a valid filename
		if ($my_upload->upload()) { 
			if (!@mkdir('./albums/'.$the_id, 0777))
			{
				unlink(realpath('./albums/'.$the_id.'.zip'));
				$m = 2;
			} else {
				$my_zip = new Archive_Zip(realpath('./albums/'.$the_id.'.zip'));
				$my_zip->extract(Array('add_path'=>realpath('./albums/'.$the_id.'/')));
				unlink(realpath('./albums/'.$the_id.'.zip'));
				header("Location: add-album-exe.php?aDir=$the_id&aName=$clean_name");
			}
		} else {
			$m = true;
		}
	}
?> 
<?php include "./inc/doctype.php"; ?>
<head>
<?php include "./inc/charset.php"; ?>
<title>SSP Admin :: Add an Album</title>
<?php include "./inc/head_elem.php"; ?>
</head>

<body>
	<div id="container">
		
		<?php include "./inc/h1.php"; ?>
		
		

<?php
				if ( $m == 1) {
		?>
		
		<h2>Error</h2>
		<p>There was an error uploading your files. Please check the documentation or the SSPAdmin forums.</p>
		
		<?php } else if ($m == 2) { ?>
		<h2>Error</h2><p>There is already an album folder with the name <strong><?php echo $the_id; ?></strong>. Please <a href="add-album.php">go back</a> and select another name.</p>
		<?php } ?>
	</div>
	
	
</body>
</html>
