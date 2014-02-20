<div id="sidebar">
	<ul>
<?php /* IF THIS IS THE FRONTPAGE */ if ( !is_home() || is_paged() ) { ?>
		<li id="home-link">
			<h2><a href="<?php echo get_settings('home'); ?>/" title="<?php bloginfo('name'); ?>">Home</a></h2>
		</li>
<?php } ?>
<?php /* FUNCTION FOR SIDEBAR WIDGETS */ if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Main Sidebar') ) : ?>
<?php /* DISPLAYS THE SIDEBAR ADD-IN TEXT, IF SELECTED IN THE THEME OPTIONS MENU */ barthelme_sidebartext(); ?>
		<?php wp_list_pages('title_li=<h2>Pages</h2>' ); ?>
		<li id="category-links">
			<h2>Categories</h2>
			<ul>
				<?php wp_list_cats('sort_column=name&hierarchical=0'); ?>
			</ul>
		</li>
<?php /* IF THIS IS THE FRONTPAGE */ if ( is_home() || is_page() ) { ?>		
		<?php get_links_list(); ?>
		<li id="feed-link">
			<h2>RSS Feeds</h2>
			<ul>
				<li><a href="<?php bloginfo('rss2_url'); ?>" title="<?php bloginfo('name'); ?> RSS 2.0 (XML) Feed" rel="alternate" type="application/rss+xml">Posts</a> <!-- UNCOMMENT IF YOU WANT LITTLE FEED ICONS TO SHOW <a href="<?php bloginfo('rss2_url'); ?>" title="<?php bloginfo('name'); ?> RSS 2.0 (XML) Feed" rel="alternate" type="application/rss+xml"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/feed.png" alt="RSS 2.0 XML Feed" /></a> --></li>
				<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php bloginfo('name'); ?> Comments RSS 2.0 (XML) Feed" rel="alternate" type="application/rss+xml">Comments</a> <!-- UNCOMMENT IF YOU WANT LITTLE FEED ICONS TO SHOW<a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php bloginfo('name'); ?> Comments RSS 2.0 (XML) Feed" rel="alternate" type="application/rss+xml"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/feed.png" alt="Comments RSS 2.0 XML Feed" /></a> --> </li>
			</ul>
		</li>
		<li id="meta-links">
			<h2>Meta</h2>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
		</li>
<?php } ?>
<?php /* IF THIS IS THE FRONTPAGE */ if ( !is_404() ) { ?>
		<li id="search">
			<h2><label for="s">Search</label></h2>
			<?php include (TEMPLATEPATH . '/searchform.php'); ?>
		</li>
<?php } ?>
<?php endif; /* END FOR WIDGETS CALL */ ?>
	</ul>
</div>

