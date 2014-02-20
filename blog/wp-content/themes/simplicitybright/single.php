<?php get_header(); ?>
<div id="content">
<div class="post">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

       <h1 id="post-<?php the_ID(); ?>"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a></h1>

           <div class="entrytext">
           <?php the_content(__('<p>| Read the rest of this entry ...</p>','relaxation')); ?>
           <?php link_pages(__('<p><strong>Pages:</strong> ','relaxation'), '</p>', 'number'); ?>
           <br />

<p class="postmetadata2 alt">
<?php _e('This entry was posted on','relaxation'); ?> <?php the_time(__('l, F dS, Y','relaxation')) ?> 
<?php _e('and is filed under','relaxation'); ?> <?php the_category(', ') ?>.
<?php _e('You can follow any responses to this entry through the','relaxation'); ?> <?php comments_rss_link('RSS 2.0'); ?> <?php _e('feed.','relaxation'); ?> 
<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
// Both Comments and Pings are open ?>
<?php _e('You can <a href="#respond">leave a response</a> or','relaxation'); ?> <a href="<?php trackback_url(true); ?>">Trackback</a> <?php _e('from your own site.','relaxation'); ?><br />
<br class="clear" />
<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
// Only Pings are Open ?>
<?php _e('Responses are currently closed, but you can','relaxation'); ?> <a href="<?php trackback_url(display); ?> ">trackback</a> <?php _e('from your own site.','relaxation'); ?> 
<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
// Comments are open, Pings are not ?>
<?php _e('You can skip to the end and leave a response. Pinging is currently not allowed.','relaxation'); ?> 
<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
// Neither Comments, nor Pings are open ?>
<?php _e('Both comments and pings are currently closed.','relaxation'); ?>
 
<?php } edit_post_link(__('Edit this entry.','relaxation'),'',''); ?>
</p>

<div>&nbsp;</div>
<div class="navigation">
<div class="alignleft"><?php previous_post('&laquo; %','','yes') ?></div>
<div class="alignright"><?php next_post(' % &raquo;','','yes') ?></div>
</div>
<div>&nbsp;</div>
<div>&nbsp;</div>

</div>
</div>



<?php comments_template(); ?>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>

</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>


