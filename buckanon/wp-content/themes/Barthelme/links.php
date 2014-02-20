<?php
/*
TEMPLATE NAME: Links Page
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
<?php
	$link_cats = $wpdb->get_results("SELECT cat_id, cat_name FROM $wpdb->linkcategories");
	foreach ($link_cats as $link_cat) {
?>
					<p style="margin-bottom:0;font-weight:bold;"><?php echo $link_cat->cat_name; ?></p>
					<ul id="page-linkcat-<?php echo $link_cat->cat_id; ?>" style="margin-top:0;">
					<?php get_links($link_cat->cat_id, '<li>', '</li>', ' &mdash; ', FALSE, '', TRUE, FALSE, -1, TRUE); ?>
					</ul>
<?php } ?>				
				<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
			</div><!-- END POST-ENTRY  -->
			<!-- <?php trackback_rdf(); ?> -->
		</div><!-- END POST -->

<?php endwhile; endif; ?>

	</div><!-- END CONTENT -->
</div><!-- END CONTAINER  -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>