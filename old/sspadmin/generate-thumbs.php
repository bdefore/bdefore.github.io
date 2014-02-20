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

		<div id="sub-nav"><strong>Edit Album Sub-Nav &raquo;</strong> <a href="edit-album-images.php?aid=<?php echo $aid?>">Edit Album Metadata</a> | <a href="edit-album-images.php?aid=<?php echo $aid?>">Edit Album Images</a> | <a href="edit-album-order.php?aid=<?php echo $aid; ?>">Edit Album Order</a></div>
		<?php if ($m==2)
			echo '<p class="update-msg"><small>Album Added. Edit Metadata Below, or <a href="edit-album.php?aid='.$aid.'">Edit the Album\'s Images</a></small></p>';
		?>
		<h2>Generate Thumbnails</h2>

<?php
if (isset($_GET['dim']))
{
flush();
$dim = $_GET['dim'];
$gd2=checkgd();

// Perms check on the albums directory
$perms = substr(sprintf('%o', fileperms("./albums/")), -4);
if ($perms != '0777')
{
@chmod(('albums/'), 0777) or die("<h2>Error</h2><p>SSPAdmin does not have the proper permissions to read from the albums directory. SSPAdmin tried to set the permissions for you, but was rejected. Please chmod this folder to 777. Your current permissions on this directory are $perms");
}

// Perms check on the specific album directory
$perms = substr(sprintf('%o', fileperms("./albums/$p/")), -4);
if ($perms != '0777')
{
@chmod(('albums/'.$p.'/'), 0777) or die("<h2>Error</h2><p>SSPAdmin does not have the proper permissions to read from the folder: albums/$p SSPAdmin tried to set the permissions for you, but was rejected. Please chmod this folder to 777. Your current permissions on this directory are $perms");
}

// Perms Check on the lg Folder
$perms = substr(sprintf('%o', fileperms("./albums/$p/lg/")), -4);
if ($perms != '0777')
{
@chmod(('albums/'.$p.'/lg/'), 0777) or die("<h2>Error</h2><p>SSPAdmin does not have the proper permissions to read from the folder: albums/$p/lg SSPAdmin tried to set the permissions for you, but was rejected. Please chmod this folder to 777. Your current permissions on this directory are $perms");
}

$pics=directory('albums/'.$p.'/lg/',"jpg,JPG,JPEG,jpeg");

if ( !@is_dir ('albums/'.$p.'/tn/') )
{
	if ( !@mkdir ('albums/'.$p.'/tn/', 0777))
	{
		die("No <b>tn</b> folder found in this album's directory. SSPAdmin tried to make one, but did not have the necessary priveleges. Make sure this album's directory is CHMODed to 777.");
	}
} else
{
	$perms = substr(sprintf('%o', fileperms("./albums/$p/tn/")), -4);
	if ($perms != '0777')
	{
	@chmod(('albums/'.$p.'/tn/'), 0777) or die("<h2>Error</h2><p>SSPAdmin does not have the proper permissions to write to the folder: albums/$p/tn/ SSPAdmin tried do set the permissions for you, but was rejected. Please chmod this folder to 777. Your current permissions on this directory are $perms");
	}
}

if ($pics[0]!=""){
	foreach ($pics as $pi){
		createthumb('albums/'.$p.'/lg/'.$pi,'albums/'.$p.'/tn/'.$pi,$dim,$dim);
		flush();
	}
	flush();
	mysql_query("UPDATE $atbl SET tn=1 WHERE id = $aid") or die(mysql_error());
	echo '<p>Thumbnails Created Successfully! Your album is now set to display thumbnails.</p>';
} else {
	echo '<p>No Images Found...</p>';
}
} else
{ 
	function gdVersion($user_ver = 0)
{
   if (! extension_loaded('gd')) { return; }
   static $gd_ver = 0;
   // Just accept the specified setting if it's 1.
   if ($user_ver == 1) { $gd_ver = 1; return 1; }
   // Use the static variable if function was called previously.
   if ($user_ver !=2 && $gd_ver > 0 ) { return $gd_ver; }
   // Use the gd_info() function if possible.
   if (function_exists('gd_info')) {
       $ver_info = gd_info();
       preg_match('/\d/', $ver_info['GD Version'], $match);
       $gd_ver = $match[0];
       return $match[0];
   }
   // If phpinfo() is disabled use a specified / fail-safe choice...
   if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
       if ($user_ver == 2) {
           $gd_ver = 2;
           return 2;
       } else {
           $gd_ver = 1;
           return 1;
       }
   }
   // ...otherwise use phpinfo().
   ob_start();
   phpinfo(8);
   $info = ob_get_contents();
   ob_end_clean();
   $info = stristr($info, 'gd version');
   preg_match('/\d/', $info, $match);
   $gd_ver = $match[0];
   return $match[0];
} 

if ($gdv = gdVersion()) {

?>

	<p>This process will make thumbnails from your existing full size images for this album. WARNING: If you have existing thumbnails, this process will overwrite them.</p>
	<form action="generate-thumbs.php" method="get">
		<fieldset>Maximum Width or Height: <input name="dim" type="text" size="4" value="90" /> <input type="submit" value="Generate Thumbs" /></fieldset>
		<input type="hidden" name="aid" value="<?php echo $aid ?>" />
	</form>
<?php } else { ?>
	<p>To generate thumbnails, you must have PHP compiled with the GD extension. Contact your host or system administrator for more information.</p>
<?php } ?>

<?php
}
/*
	Function checkgd()
	checks the version of gd, and returns "yes" when it's higher than 2
*/
function checkgd(){
	$gd2="";
	ob_start();
	phpinfo(8);
	$phpinfo=ob_get_contents();
	ob_end_clean();
	$phpinfo=strip_tags($phpinfo);
	$phpinfo=stristr($phpinfo,"gd version");
	$phpinfo=stristr($phpinfo,"version");
	preg_match('/\d/',$phpinfo,$gd);
	if ($gd[0]=='2'){$gd2="yes";}
	return $gd2;
}

/*
	Function ditchtn($arr,$thumbname)
	filters out thumbnails
*/
function ditchtn($arr,$thumbname){
	foreach ($arr as $item){
		if (!preg_match("/^".$thumbname."/",$item)){$tmparr[]=$item;}
	}
	return $tmparr;
}

/*
	Function createthumb($name,$filename,$new_w,$new_h)
	creates a resized image
	variables:
	$name		Original filename
	$filename	Filename of the resized image
	$new_w		width of resized image
	$new_h		height of resized image
*/	
function createthumb($name,$filename,$new_w,$new_h){
	global $gd2;
	$system=explode(".",$name);
	if (preg_match("/jpg|jpeg|JPG|JPEG/",$system[1])){$src_img=imagecreatefromjpeg($name);}
	if (preg_match("/png/",$system[1])){$src_img=imagecreatefrompng($name);}
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);
	if ($old_x > $old_y) {
		$thumb_w=$new_w;
		$thumb_h=$old_y*($new_h/$old_x);
	}
	if ($old_x < $old_y) {
		$thumb_w=$old_x*($new_w/$old_y);
		$thumb_h=$new_h;
	}
	if ($old_x == $old_y) {
		$thumb_w=$new_w;
		$thumb_h=$new_h;
	}
	if ($gd2==""){
			$dst_img=ImageCreate($thumb_w,$thumb_h);
			imagecopyresized($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
	}else{
		$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
	}
	if (preg_match("/png/",$system[1])){
		imagepng($dst_img,$filename); 
	} else {
		imagejpeg($dst_img,$filename); 
	}
	imagedestroy($dst_img); 
	imagedestroy($src_img); 
}

/*
        Function directory($directory,$filters)
        reads the content of $directory, takes the files that apply to $filter 
		and returns an array of the filenames.
        You can specify which files to read, for example
        $files = directory(".","jpg,gif");
                gets all jpg and gif files in this directory.
        $files = directory(".","all");
                gets all files.
*/
function directory($dir,$filters){
	$handle=opendir($dir);
	$files=array();
	if ($filters == "all"){while(($file = readdir($handle))!==false){$files[] = $file;}}
	if ($filters != "all"){
		$filters=explode(",",$filters);
		while (($file = readdir($handle))!==false) {
			for ($f=0;$f<sizeof($filters);$f++):
				$system=explode(".",$file);
				if ($system[1] == $filters[$f]){$files[] = $file;}
			endfor;
		}
	}
	closedir($handle);
	return $files;
}
?>

</div>
	
	
</body>
</html>
