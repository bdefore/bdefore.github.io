	<div id="footer">
		<p>
			<?php /* DISPLAYS THE FOOTER ADD-IN TEXT, IF SELECTED IN THE THEME OPTIONS MENU */ barthelme_footertext(); ?>
			&copy; <?php echo(date('Y')); ?> <?php the_author('nickname'); ?>
			|
			Thanks, <a href="http://wordpress.org/" title="WordPress">WordPress</a>
			|
			<a href="http://www.plaintxt.org/themes/barthelme/" title="Barthelme for WordPress" rel="follow">Barthelme</a> theme by <a href="http://scottwallick.com/" title="scottwallick.com" rel="follow">Scott</a>
			|
			Sponsor: <a href="http://www.digitalflowers.com/New+York/New+York/New+York_NY.htm" title="New York City Flower Delivery">New York City Flower Delivery</a>
			<?php /* The last link above is from my sponsor, whose support makes it possible for me to spend the time to create these themes for free. It is appreciated, though not necessary, for you to allow this link to remain. Do please allow the link to this theme to remain. Thanks. -- scott */ ?>
			|
			Valid <a href="http://validator.w3.org/check?uri=<?php echo get_settings('home'); ?>&amp;outline=1&amp;verbose=1" title="Valid XHTML 1.0 Strict" rel="nofollow">XHTML</a> &amp; <a href="http://jigsaw.w3.org/css-validator/validator?uri=<?php bloginfo('stylesheet_url'); ?>&amp;profile=css2&amp;warning=no" title="Valid CSS" rel="nofollow">CSS</a>
			|
			RSS: <a href="<?php bloginfo('rss2_url'); ?>" title="<?php bloginfo('name'); ?> RSS 2.0 (XML) Feed" rel="alternate" type="application/rss+xml">Posts</a> &amp; <a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php bloginfo('name'); ?> Comments RSS 2.0 (XML) Feed" rel="alternate" type="application/rss+xml">Comments</a>
			<?php do_action('wp_footer'); ?>
		</p>
	</div>

</div><!-- END WRAPPER -->

<!-- The "Barthelme" theme copyright (c) 2006 Scott Allan Wallick - http://www.plaintxt.org/themes/ -->

</body>
</html>