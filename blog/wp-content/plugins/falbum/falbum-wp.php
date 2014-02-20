<?php
require_once('../../../wp-blog-header.php');

$tdir = get_template_directory();
if (file_exists($tdir."/falbum-wp.php")) {
	include_once($tdir."/falbum-wp.php");
} else {

get_header(); ?>

<script type="text/javascript" src="<?php echo get_settings('siteurl'); ?>/wp-content/plugins/falbum/falbum.js"></script>
<script type="text/javascript" src="<?php echo get_settings('siteurl'); ?>/wp-content/plugins/falbum/overlib.js"></script>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
	<div id="content" class="narrowcolumn">
		 <?php fa_show_photos($_GET['album'], $_GET['photo'], $_GET['page'], $_GET['tags'], $_GET['show']); ?>
	</div>
<?php 

get_sidebar();

get_footer(); 

}?>


