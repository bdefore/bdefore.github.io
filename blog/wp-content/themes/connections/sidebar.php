<h2><?php _e('Categories:'); ?></h2>
	<ul><?php wp_list_cats('optioncount=1');    ?></ul>


<h2><?php _e('Monthly:'); ?></h2>
	<ul><?php wp_get_archives('type=monthly&show_post_count=true'); ?></ul>

<h2><?php _e('RSS Feeds:'); ?></h2>
	<ul>
		<li>
			<a title="RSS2 Feed for Posts" href="<?php bloginfo('rss2_url'); ?>">Posts</a> | <a title="RSS2 Feed for Comments" href="<?php bloginfo('comments_rss2_url'); ?>">Comments</a></li>	
	</ul>	
		