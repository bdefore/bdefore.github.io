<?php /* Don't remove this line. */ require('blog/wp-blog-header.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>	
	<style type="text/css" media="screen">
		@import url( <?php bloginfo('stylesheet_url'); ?> );
	</style>	
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="shortcut icon" href="/favicon/bullet.ico" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />    
	<?php //comments_popup_script(); // off by default ?>
	<?php wp_head(); ?>	
</head>
<body>
<div id="rap">
<?php get_header() ?>
	<div id="main">
	<div id="content">	
	<?php if ($posts) { ?>	
		<?php $post = $posts[0]; /* Hack. Set $post so that the_date() works. */ ?>
		<h3>Search Results for '<?php echo $s; ?>'</h3>			
		<div class="post-info">Did you find what you wanted ?</div>		
		<br/>				
		<?php foreach ($posts as $post) : start_wp(); ?>				
			<?php require('post.php'); ?>
		<?php endforeach; ?>
		<div class="navigation">
			<div class="alignleft"><?php posts_nav_link('','','&laquo; Older Entries') ?></div>
			<div class="alignright"><?php posts_nav_link('','Newer Entries &raquo;','') ?></div>
		</div>	
	<?php } else { ?>
		<h2 class="center">Not Found</h2>
		<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
	<?php } ?>		
	</div>
	<div id="sidebar">
		<h2>Currently Browsing</h2><ul><li><p>You have searched the archives
			for <strong>'<?php echo $s; ?>'</strong>. If you are unable to find anything in these search results, you can try one of these links.</p></li></ul>
		<?php get_sidebar(); ?>
	</div>
<?php get_footer(); ?>
</div>
</div>
</body>
</html>
