<?php get_header(); ?>

	<div id="content">


		<?php if (have_posts()) : ?>

		 <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
<?php /* If this is a category archive */ if (is_category()) { ?>				
		<h4>Archiv der Kategorie &#8216;<?php echo single_cat_title(); ?>&#8216; </h4>
		
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h4>Tagesarchiv f&uuml;r den <?php the_time('j. F Y'); ?></h4>
		
	 <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h4>Monatsarchiv f&uuml;r <?php the_time('F Y'); ?></h4>

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h4>Jahresarchiv f&uuml;r <?php the_time('Y'); ?></h4>
				
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h4>Autoren Archiv</h4>

		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>

		<?php } ?>

<div>&nbsp;</div>
<div>&nbsp;</div>
		

		<?php $first = 1; while (have_posts()) : the_post(); ?>

		<div class="post">
				<h1 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h1>
				
				
				<div class="entry">
					
<?php if ( 1 == $first && is_category() && !is_paged() ) { 
  the_content('<br />Den ganzen Beitrag lesen...<br />','relaxation');
  $first = 0;
} else {
the_content('<br />Den ganzen Beitrag lesen...<br />','relaxation');
} ?>
				</div>
		
				
<p class="postmetadata"><?php the_time(__('d. F Y','relaxation')) ?> | <?php the_category(', ') ?> <!--| <a href="<?php trackback_url(true); ?>" rel="Trackback"> Trackback-Url</a> -->| <?php comments_popup_link(__('Comments (0)','relaxation'), __('Comments (1)','relaxation'), __('Comments (%)','relaxation')); ?> </p>

			</div>
	
		<?php endwhile; ?>

<div class="pagenavigation2">
<div class="alignleft"><?php next_posts_link('&laquo; &Auml;ltere Beitr&auml;ge') ?></div>
<div class="alignright"><?php previous_posts_link('Neuere Beitr&auml;ge &raquo;') ?></div>
</div>
	
	<?php else : ?>

		<h2 class="center">Nichts gefunden.</h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>
		
	</div>

</div>
<?php get_sidebar(); ?>

<?php get_footer(); ?>
