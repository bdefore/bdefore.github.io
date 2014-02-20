	<br class="clear" />

</div> <!-- Close Page -->

<hr />

<p id="footer"><small>
	<?php bloginfo('name'); ?> is powered by <a href="http://wordpress.org" title="Where children sing songs of binary bliss">WordPress <?php bloginfo('version'); ?></a> and <a href="http://binarybonsai.com/wordpress/k2/" title="Is to WordPress what math is to reality; hard to understand.">K2 <?php if (function_exists('k2info')) { k2info(version); } ?></a>. <br /><a href="http://dancameron.org/wordpress/sca4ered-theme/">Sca4ered</a> theme created by <a href="http://dancameron.org" title="Dan Cameron">Dan Cameron</a> and maintained by <a href="http://fusion94.org/blog" title="Tony 'fusion94' Guntharp">Tony 'fusion94' Guntharp.<br />
	<a href="<?php bloginfo('rss2_url'); ?>">RSS Entries</a> and <a href="<?php bloginfo('comments_rss2_url'); ?>">RSS Comments</a>

	<!-- <?php echo $wpdb->num_queries; ?> queries. <?php timer_stop(1); ?> seconds. -->
</small></p>

<?php /* Try. to understand */ ?>

	<?php do_action('wp_footer'); ?>

</body>
</html> 