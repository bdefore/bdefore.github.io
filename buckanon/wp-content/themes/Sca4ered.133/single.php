<?php get_header(); ?>

<div class="content">
	
	<div class="primary">

		<?php include (TEMPLATEPATH . '/theloop.php'); ?>
		<?php comments_template(); ?>

	</div>

	<?php get_sidebar(); ?><?php include("commentsbox.php"); ?>

</div></div>


<?php get_footer(); ?>