<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">

	<title><?php wp_title(''); ?> <?php if ( !(is_404()) && (is_single()) or (is_page()) or (is_archive()) ) { ?> at <?php } ?> <?php bloginfo('name'); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	<meta name="template" content="K2 <?php if (function_exists('k2info')) { k2info('version'); } ?>" />
 	<meta name="description" content="<?php bloginfo('description'); ?>" />
 
	<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_url'); ?>" />
	<?php /*Let's get the custom CSS */ if (get_option('k2scheme') != '') { ?>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php k2info('scheme'); ?>" />
	<?php } ?>

	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />

	<?php if (is_single() or is_page()) { ?>
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php } ?>
	
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/prototype.js.php"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/effects.js.php"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/k2functions.js"></script>
	
	<?php /* Load Live Commenting if enabled in the K2 Options Panel and the page is a permalink */ if ((get_option('k2livecommenting') == 0) and (is_single())) { ?>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/ajax_comments.js"></script>
	<?php } ?>

	<?php /* Load LiveSearch if enabled in the K2 Options Panel */ if (get_option('k2livesearch') == 0) { ?>
	<script type="text/javascript">//<![CDATA[
		<?php include_once("js/livesearch.js.php"); ?>

		window.onload = liveSearchInit;
	//]]></script>
	<?php } ?>


	<?php trackback_rdf(); ?>
	<?php wp_get_archives('type=monthly&format=link'); ?>
	<?php wp_head(); ?>

	<?php /* Sets Matt's asides in motion */ function stupid_hack($str) { return preg_replace('|</ul>s*<ul class="asides">|', '', $str); } ob_start('stupid_hack'); ?>

</head>

<body class="<?php /* Is Flexible Width Enabled? */ if (get_option('k2widthtype') == 0) echo flex; ?> <?php if (is_single()) echo permalink; ?>">

<div id="page">

	<div id="header">

		<h1><a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a></h1>
		<p class="description"><?php bloginfo('description'); ?></p>
	
		<ul class="menu">
			<li class="<?php if (((is_home()) && !(is_paged())) or (is_archive()) or (is_single()) or (is_paged()) or (is_search())) { ?>current_page_item<?php } else { ?>page_item<?php } ?>"><a href="<?php echo get_settings('home'); ?>">Blog</a></li>
			<?php wp_list_pages('sort_column=menu_order&depth=1&title_li='); ?>
			<?php wp_register('<li style="position: absolute; right: 0px;">','</li>'); ?>
		</ul>
	
	</div>

		<hr />