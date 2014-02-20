<div class="tooltitle"><?php _e("Previous Posts", "squible"); ?></div>

<?php $first=0; ?>
<?php rewind_posts(); ?>
<?php 
/* 
100 is just to guarantee that we will have enough posts to show
between asides and regular posts, don't worry, the loop will break after it finds
10 regular posts. There's probably a better way to do this and I just don't know it.
*/
$my_query = new WP_Query('showposts=100'); 
?>

<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
<?php $comments = $wpdb->get_var("SELECT COUNT(*) FROM $tablecomments WHERE comment_post_ID = '$post->ID'"); ?>
<?php if (in_category($asides_cat)) {continue;} ?>
<?php if ($first == 0) { $first++; continue;} ?>
<?php if ($first > 10) {break; } ?>
	<div class="prevposts">
	<div style="display: inline;float: right;"><?php the_time('d M y'); ?></div>
        <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
<?php
	$words = get_the_content('', 0, '');
	if ($words) {
        	$post = strip_tags($words);
               	$post = explode(' ', $post);
               	$totalcount = count($post);
	} else {
		$totalcount=0;
	}
?>
	<div class="showwords"><?php echo " $totalcount"; ?> <?php _e("words", "squible"); ?></div>
	</div>
<?php $first++; ?>
<?php endwhile; ?>