<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!--<style type="text/css" media="screen">

<?php
// Checks to see whether it needs a sidebar or not
if ( !$withcomments && !is_single() ) {
?>
	#page { background: url("<?php bloginfo('stylesheet_directory'); ?>/images/kubrickbg-<?php bloginfo('text_direction'); ?>.jpg") repeat-y top; border: none; }
<?php } else { // No sidebar ?>
	#page { background: url("<?php bloginfo('stylesheet_directory'); ?>/images/kubrickbgwide.jpg") repeat-y top; border: none; }
<?php } ?>

</style>
-->

<?php wp_head(); ?>
</head>
<body>
<div id="page">

	<?php
    global $wp_query;
    $thePostID = $wp_query->post->ID;
    $thePost_parent = $wp_query->post->post_parent;
    
    $mainPages = $wpdb->get_results("SELECT ID, guid, post_title, post_type, post_parent FROM " . $wpdb->posts . " WHERE post_parent='0' AND post_type='page' AND post_status='publish' ORDER BY menu_order");
    if ($mainPages) {
    ?> <ul id="nav"> 
        <li class="page_item <?php if (is_home()) { ?> current_page_item <?php } ?>"><a href="<?php echo get_settings('home'); ?>" title="Home Page"><span class="tabs"><?php _e('Home'); ?></span></a></li>
    <?php
    foreach ($mainPages as $onePage) {
                $url = get_permalink($onePage->ID);
                $arc_title = $onePage->post_title;
    
                if ($arc_title) $text = strip_tags($arc_title);
                else $text = $arcresult2->ID;
                
                $title_text = wp_specialchars($text, 1); 
                ?>
    
    <li class="page_item<?php if (is_page($onePage->ID)) {?> current_page_item<?php } ?><?php if(get_the_title($thePost_parent) == $onePage->post_title) { ?> current_page_parent<?php } ?>"><a href="<?php echo $url; ?>" title="<?php echo $title_text; ?>"><span class="tabs"><?php echo wptexturize($text); ?></span></a></li>
    <?php
    }
    ?> </ul> <?php
    }
    ?>


<?php include (TEMPLATEPATH . '/ads.php'); ?>

<div id="header">
		<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<div class="todaysDate"><?php echo date ("l, F d, Y ");?></div>
    <!--<div id="headerimg">
		<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<div class="description"><?php bloginfo('description'); ?></div>
	</div>-->
</div>

<div class="breadcrumb">
<?php
if (class_exists('breadcrumb_navigation_xt'))
{
// Display a prefix
echo 'Location: ';
// New breadcrumb object
$mybreadcrumb = new breadcrumb_navigation_xt;
// Display the breadcrumb
$mybreadcrumb->display();
}
?>
</div>
<div id="searchBar">
<?php include (TEMPLATEPATH . '/searchform.php'); ?>
</div>
