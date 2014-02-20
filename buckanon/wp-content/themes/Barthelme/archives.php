<?php
/*
TEMPLATE NAME: Archives Page
*/
?>
<?php get_header(); ?>

<div id="container">
	<div id="content" class="widecolumn">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" class="post single-post">
			<h2 class="post-title-single"><?php the_title(); ?></h2>
			<div class="post-entry">
				<?php the_content(); ?>
					<div style="clear:both;width:45%;padding:0 1em;float:left;">
						<p style="margin-bottom:0;"><strong>Categories</strong></p>
						<ul style="margin-top:0;">
							<?php wp_list_cats('sort_column=name&optioncount=1&feed=(RSS)&feed_image='.get_bloginfo('template_url').'/images/feed.png&hierarchical=1'); ?>
						</ul>
					</div>
					<div style="width:45%;padding:0 1em;float:left;">
						<p style="margin-bottom:0;"><strong>Archives</strong></p>
						<ul style="margin-top:0;">
							<?php wp_get_archives('type=monthly&show_post_count=1'); ?>
						</ul>
					</div>
					<div class="clearer"></div>
				<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
			</div><!-- END POST-ENTRY  -->
			<!-- <?php trackback_rdf(); ?> -->
		</div><!-- END POST -->

<?php endwhile; endif; ?>

	</div><!-- END CONTENT -->
</div><!-- END CONTAINER  -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>