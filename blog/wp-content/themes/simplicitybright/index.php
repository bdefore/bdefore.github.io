<?php get_header(); ?>
<div id="content">

<?php if (have_posts()) : ?><?php $first = 1; while (have_posts()) : the_post(); ?>
<div class="post">

<h1 id="post-<?php the_ID(); ?>"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a></h1>

<div class="entry">

<?php if ( 1 == $first && is_home() && !is_paged() ) { 
  the_content('<br />|Den ganzen Beitrag lesen...<br />','relaxation');
  $first = 0;
} else {
the_content('<br />|Den ganzen Beitrag lesen...<br />','relaxation');
} ?>
</div>

<p class="postmetadata"><?php the_time(__('d. F Y','relaxation')) ?> | <?php the_category(', ') ?> <!--| <a href="<?php trackback_url(true); ?>" rel="Trackback"> Trackback-Url</a> -->| <?php comments_popup_link(__('Comments (0)','relaxation'), __('Comments (1)','relaxation'), __('Comments (%)','relaxation')); ?></p>

</div>

<?php endwhile; ?>

<div class="pagenavigation2">
<div class="alignleft"><?php next_posts_link('&laquo; &Auml;ltere Beitr&auml;ge') ?></div>
<div class="alignright"><?php previous_posts_link('Neuere Beitr&auml;ge &raquo;') ?></div>
</div>


<?php else : ?>
<h2 class="center"><?php _e('Not found.'); ?></h2>
<p class="center"><?php _e("Sorry, but you are looking for something that isn't here."); ?></p>
<?php include (TEMPLATEPATH . "/searchform.php"); ?>
<?php endif; ?>
</div>

</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>

