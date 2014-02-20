<?php get_header(); ?>
<body>

<div class="container">
	<div class="header">
		<h1><a title="<?php bloginfo('name'); ?>" href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
	</div>
<?php include_once('rightsidebar.php'); ?>
<?php get_sidebar(); ?>
		
	<div class="main">

		<div class="padded">

			<div id="content">
		<?php if ($posts) : foreach ($posts as $post) : start_wp(); ?>
			<h2 class="post-title">
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a>
			</h2>
			<p class="meta"><?php the_time('F j, Y'); ?> by <?php the_author_posts_link() ?></p>
			<div class="post-content">
				<?php the_content(__('<strong>Read more...</strong>')); ?>
				<?php wp_link_pages(); ?>
			</div>
				
			<div class="post-footer">
				<?php if(is_page()) {}
						else { 
					?>

				<span class="cat"><?php the_category(',') ?></span> |   
				<span class="comment"><?php comments_popup_link(__('Comments (0)'), __('Comments (1)'), __('Comments (%)')); ?></span>
				<?php edit_post_link('Edit this'); ?>
				<?php } ?>
			</div>
				<!-- <?php trackback_rdf(); ?>	-->					
				<?php 
					if(is_page()) {}
					else { comments_template(); }
				?>
				<?php endforeach; else: ?>
				<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>			
				<?php endif; ?>		
							
				<div align="center" class="center">
				<?php posts_nav_link('&nbsp;|&nbsp;', __('<strong>&laquo; Previous Page</strong>'), __('<strong>Next Page &raquo;</strong>')); ?>
				</div>			
	</div>


				
				
		</div>

	</div>
	
	<div class="clearer"><span></span></div>
<?php get_footer(); ?>


</div>

</body>

</html>