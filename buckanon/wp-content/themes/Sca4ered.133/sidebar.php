<hr />

<div class="secondary">

	<div class="sb-search"><h2><?php _e('Search'); ?></h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>
	</div>


	<?php global $notfound; ?>
	<?php /* Creates a menu for pages beneath the level of the current page */
		if (is_page() and ($notfound != '1')) {
			$current_page = $post->ID;
			while($current_page) {
				$page_query = $wpdb->get_row("SELECT ID, post_title, post_parent FROM $wpdb->posts WHERE ID = '$current_page'");
				$current_page = $page_query->post_parent;
			}
			$parent_id = $page_query->ID;
			$parent_title = $page_query->post_title;

			if ($wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_parent = '$parent_id'")) { ?>

			<div class="sb-pagemenu"><h2><?php echo $parent_title; ?> Subpages</h2>
				<ul>
					<?php wp_list_pages('sort_column=menu_order&title_li=&child_of='. $parent_id); ?>
				</ul>
			
				<?php if ($parent_id != $post->ID) { ?>
					<a href="<?php echo get_permalink($parent_id); ?>">Back to <?php echo $parent_title; ?></a>
				<?php } ?>
			</div>
		<?php } ?>
	
				
	<?php /* If this is a permalink */ } elseif (is_single()) { ?>


	<?php } ?>
	
	<?php if ((function_exists('UTW_ShowRelatedPostsForCurrentPost')) && is_single()) { ?> 
	
		<div class="sb-pagemenu"><h2>Related Posts</h2>
<ul><?php UTW_ShowRelatedPostsForCurrentPost(postcommalist,'',5) ?></ul> 

	
	
	<?php } ?>
	<div class="secondary">

		<?php /* If there is a custom about message, use it on the frontpage. */ $k2about = get_option('k2aboutblurp'); if ((is_home() && $k2about != '') or !(is_home()) && !(is_page()) && !(is_single())) { ?>
		
		<div class="sb-about"><h2><?php _e('About'); ?></h2>
			<?php /* If this is the frontpage */ if  (is_home()) { ?>
			<p><?php echo stripslashes($k2about); ?></p>
			
			<?php if ((function_exists('get_myStatus'))) { ?>
				<div class="sb-about"><h2><?php _e('My Status'); ?></h2> 
					<?php get_myStatus(); ?>   
				</div>
			<?php } ?>	


                        <?php if ((function_exists('wpaudioscrobbler'))) { ?>
                                <div class="sb-about"><h2><?php _e('Now Playing - Music'); ?></h2>
                                        <?php wpaudioscrobbler(); ?>
                                </div>          
                        <?php } ?>  
                        			
			<?php /* If this is a category archive */ } elseif (is_category()) { ?>
			<p><?php printf( __('You are currently browsing the %1$s weblog archives for the \'%2$s\' category.'), '<a href="' . get_settings('siteurl') .'">' . get_bloginfo('name') . '</a>', single_cat_title('', false) ) ?></p>


			<?php /* If this is a day archive */ } elseif (is_day()) { ?>
			<p>You are currently browsing the <a href="<?php echo get_settings('siteurl'); ?>"><?php echo bloginfo('name'); ?></a> weblog archives for the day <?php the_time('l, F jS, Y'); ?>.</p>


			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<p>You are currently browsing the <a href="<?php echo get_settings('siteurl'); ?>"><?php echo bloginfo('name'); ?></a> weblog archives for <?php the_time('F, Y'); ?>.</p>


			<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<p>You are currently browsing the <a href="<?php echo get_settings('siteurl'); ?>"><?php echo bloginfo('name'); ?></a> weblog archives for the year <?php the_time('Y'); ?>.</p>


			<?php /* If this is a search page */ } elseif (is_search()) { ?>
			<p>You have searched the <a href="<?php echo get_settings('siteurl'); ?>"><?php echo bloginfo('name'); ?></a> weblog archives
			for <strong>'<?php echo wp_specialchars($s); ?>'</strong>. If you are unable to find anything in these search results, you can try one of the following sections.</p>


			<?php /* If this is an author archive */ } elseif (is_author()) { ?>
			<p>Archive for <strong><?php the_author(); ?></strong>.</p>
			<p><?php the_author_description(); ?></p>


			<?php } elseif (function_exists('is_tag') and is_tag()) { ?>
			<p>You are currently browsing the <a href="<?php echo get_settings('siteurl'); ?>"><?php echo bloginfo('name'); ?></a> weblog archives for <?php UTW_ShowCurrentTagSet("", array('first'=>'%tagdisplay%', 'default'=>', %tagdisplay%', 'last'=>' %operatortext% %tagdisplay%')); ?>.</p>


			<?php /* If this is a paged archive */ } elseif (is_paged) { ?>
			<p>You are currently browsing the <a href="<?php echo get_settings('siteurl'); ?>"><?php echo bloginfo('name'); ?></a> weblog archives.</p>


			<?php /* If this is a permalink */ } elseif (is_single()) { ?>
			<p><?php next_post('%', 'Next: ','yes') ?><br/>
			<?php previous_post('%', 'Previous: ' ,'yes') ?></p>


			<?php /* If this is the frontpage */ } elseif (is_home()) { ?>
			<p><? echo stripslashes($k2about); ?></p>

			<?php } ?>

			<?php if (is_archive() or is_search()) { ?>
				Longer entries are truncated. Click the headline of an entry to read it in its entirety.
			<?php } ?>
		</div>
			
	<?php } ?>


	<?php if ((function_exists('blc_latest_comments')) && is_home()) { ?> 
	<div class="sb-comments"><h2><?php _e('Comments'); ?></h2>
		<a href="<?php bloginfo('comments_rss2_url'); ?>" title="RSS Feed for all Comments" class="feedlink"><img src="<?php bloginfo('template_directory'); ?>/images/feed.png" alt="RSS" /></a>
		<ul>
			<?php blc_latest_comments('5','3','false'); ?>
		</ul>
	</div>
	<?php } ?>


	<?php /* Show Asides only on the frontpage */ if (!is_paged() && is_home()) { $k2asidescategory = get_option('k2asidescategory'); $k2asidesnumber = get_option('k2asidesnumber'); if (get_option('k2asidesposition') != '0') { ?>
	<div class="sb-asides"><h2><?php _e('Snippets'); ?></h2>
		<span class="metalink"><a href="<?php bloginfo('url'); ?>/?feed=rss&cat=<?php echo $k2asidescategory; ?>" title="RSS Feed for Asides" class="feedlink"><img src="<?php bloginfo('template_directory'); ?>/images/feed.png" alt="RSS" /></a></span>
		<?php $temp_query = $wp_query; // save original loop ?>
		<div><?php /* Choose a category to be an 'aside' in the K2 options panel */ query_posts("cat=$k2asidescategory&showposts=$k2asidesnumber"); while (have_posts()) : the_post(); if (($k2asides != '0') && (in_category($k2asidescategory) && !$single)) { ?>
			<p class="aside" id="p<?php the_ID(); ?>">&raquo;&nbsp;<a style="font-size: 1.5em;" href="<?php the_permalink($post->ID) ?>" rel="bookmark" title='Permanent Link to this aside'><?php echo wptexturize($post->post_title) ?></a>&nbsp;&nbsp;<span class="metalink"><?php comments_popup_link('0', '1', '%', '', ' '); ?></span>&nbsp;&nbsp;<span class="metalink"><?php edit_post_link('edit'); ?><?php the_excerpt() ?><br /></p>
		<?php /* End Asides Loop */ } endwhile; ?></div>
		<?php $wp_query = $temp_query; // revert to original loop ?>
	</div>
	<?php } } ?>


	<?php if ( (is_home()) or (is_search() or (is_404()) or ($notfound == '1')) or ( (is_archive()) && (!is_author()) ) ) { ?>
	<div class="sb-latest"><h2><?php _e('Latest'); ?></h2>
		<span class="metalink"><a href="<?php bloginfo('rss2_url'); ?>" title="RSS Feed for Blog Entries" class="feedlink"><img src="<?php bloginfo('template_directory'); ?>/images/feed.png" alt="RSS" /></a></span>
		<ul>
			<?php wp_get_archives('type=postbypost&limit=10'); ?>
		</ul>
	</div>
	<?php } ?>


	<?php if ((function_exists('get_flickrRSS')) && is_home() && !(is_paged())) { ?> 
	<div class="sb-flickr"><h2>Flickr</h2>
		<div>
			<?php get_flickrRSS(); ?>
		</div>
	</div>
	<?php } ?>


	<?php if ((function_exists('delicious')) && is_home() && !(is_paged()) ) { $k2deliciousname = get_option('k2deliciousname'); ?> 
	<div class="sb-delicious"><h2><a href="http://del.icio.us/<?php echo $k2deliciousname; ?>" title="My del.icio.us links library">Del.icio.us</a></h2>
		<span class="metalink"><a href="http://del.icio.us/rss/<?php echo $k2deliciousname; ?>" title="RSS Feed for del.icio.us links" class="feedlink"><img src="<?php bloginfo('template_directory'); ?>/images/feed.png" alt="RSS" /></a></span>
		<div>
			<?php delicious($k2deliciousname); ?>
		</div>
	</div>
	<?php } ?>


	<?php /* If this is the frontpage */ if ( (is_home()) && !(is_page()) && !(is_single()) && !(is_search()) && !(is_archive()) && !(is_author()) && !(is_category()) && !(is_paged()) ) { ?>				
	<div class="sb-links">
		<ul>
			<?php get_links_list(); ?>
		</ul>
	</div>


	<!-- Commented out because it has little use for 99% of users.
	<div class="sb-meta"><h2><?php _e('Meta'); ?></h2>
		<ul>
			<li><?php wp_loginout(); ?></li>
			<li><a href="http://validator.w3.org/check/referer" title="<?php _e('This page validates as XHTML 1.0 Transitional'); ?>"><?php _e('Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr>'); ?></a></li>
			<li><a href="http://jigsaw.w3.org/css-validator/check/referer" title="<?php _e('This page validates has valid CSS'); ?>"><?php _e('Valid <abbr title="Cascading Style Sheets">CSS</abbr>'); ?></a></li>
			<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
			<li><a href="http://wordpress.org/" title="<?php _e('Powered by WordPress, state-of-the-art semantic personal publishing platform.'); ?>">WordPress</a></li>
			<?php wp_meta(); ?>
		</ul>
	</div>-->

	
	<?php } ?>


	<?php /* If this is the an archive page or a search page */ if ( (is_archive()) or (is_search()) or (is_paged()) or ($notfound == '1') ) { ?>				
	<div class="sb-months"><h2><?php _e('Archives'); ?></h2>
		<ul>
			<?php wp_get_archives('type=monthly'); ?>
		</ul>
	</div>


	<div class="sb-categories"><h2><?php _e('Categories'); ?></h2>
		<ul>
			<?php list_cats(0, '', 'name', 'asc', '', 1, 0, 1, 1, 1, 1, 0,'','','','','') ?>
		</ul>
	</div>
		
	<?php } ?>

	<?php if ((function_exists('related_posts')) && is_single() && ($notfound != '1')) { ?> 
	<div class="sb-delicious"><h2>Related Entries</h2>
		<ul>
			<?php related_posts(10, 0, '<li>', '</li>', '', '', false, false); ?>
		</ul>
	</div>
	<?php } ?>

</div>