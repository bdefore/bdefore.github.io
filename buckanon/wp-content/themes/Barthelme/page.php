<?php get_header(); ?>

<div id="container">
	<div id="content" class="widecolumn">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" class="post single-post">
			<h2 class="post-title-single"><?php the_title(); ?></h2>
			<div class="post-entry">
				<?php the_content(); ?>
				<?php link_pages('<p>Pages: ', '</p>', 'number'); ?>
				<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
			</div><!-- END POST-ENTRY  -->
			<!-- <?php trackback_rdf(); ?> -->
		</div><!-- END POST -->

<?php endwhile; endif; ?>

	</div><!-- END CONTENT -->
</div><!-- END CONTAINER  -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>