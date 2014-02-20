	<div class="main_right">

		<div class="padded">
			
			<h1>Recent Comments</h1>
			<ul class="recent">
			<?php get_recent_comments(); ?>
			</ul>
		<div class="widget">
			<?php if ( !function_exists('dynamic_sidebar')
			|| !dynamic_sidebar() ) : ?>
			<?php
			$link_cats = $wpdb->get_results("SELECT cat_id, cat_name FROM $wpdb->linkcategories");
			foreach ($link_cats as $link_cat) {
			?>
	<h1><?php echo $link_cat->cat_name; ?></h1>
	<ul class="menu">
	<?php wp_get_links($link_cat->cat_id); ?>
	</ul>
			<?php } ?>
			
		<h1><?php _e('Meta:'); ?></h1>
			<ul class="meta">
			<?php wp_register(); ?>
			<li><?php wp_loginout(); ?></li>
			<li><a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>"><img border="0" src="http://www.feedburner.com/fb/images/pub/feed-icon16x16.png">  <?php _e('<abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
			<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('The latest comments to all posts in RSS'); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
			<li><a href="http://validator.w3.org/check/referer" title="<?php _e('This page validates as XHTML 1.0 Transitional'); ?>"><?php _e('Valid <abbr title="This page validates as XHTML 1.0 Transitional, eXtensible HyperText Markup Language">XHTML</abbr>'); ?></a></li>
			<li><a href="http://gmpg.org/xfn/" ><abbr title="XHTML Friends Network">XFN</abbr></a></li>
			<?php wp_meta(); ?>
			</ul>
			<?php endif; ?>
		</div>
</div>
	</div>
