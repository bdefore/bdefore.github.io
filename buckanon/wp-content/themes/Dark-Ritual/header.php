<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
<!-- Editable Meta Tags -->
<meta name="description" content="<?php bloginfo('description'); ?>"/>
<meta name="keywords" content=""/>
<meta name="design" content="Viktor Persson"/>
<meta name="theme" content="Sreejith - GFXedit.com"/>
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<meta name="robots" content="index,follow" />
<!-- Meta Tags End-->

<!-- Style Sheet -->
<style type="text/css" media="screen">
@import url( <?php bloginfo('stylesheet_url'); ?> );
</style>
<!-- Style Sheet End -->

<!--[if lte IE 6]>
<link href="<?php bloginfo('template_directory'); ?>/ie.css" rel="stylesheet" type="text/css" />
<![endif]-->



<!-- Feed and Ping URLS-->
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_get_archives('type=monthly&format=link'); ?>
<?php //comments_popup_script(); // off by default ?>
<?php wp_head(); ?>
<!-- End Feed and Ping URLS-->
</head>