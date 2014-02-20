<div class="tooltitle"><?php _e("Asides", "squible"); ?> <a style="text-decoration: none; font-size: 8pt;" href="<?php echo bloginfo('url'); ?>/index.php?cat=<?php echo $asides_cat ?>"><?php _e("(more...)", "squible"); ?></a></div>
<div style="line-height:18px;">

<?php 
$my_query = new WP_Query('showposts=100'); 
$found=0;
?>
<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
<?php if (!in_category($asides_cat)) {continue;} ?>
<?php $found++; ?>
	<div class="asides">
		<?php $mycontent=wptexturize($post->post_content); echo $mycontent; echo ' '; comments_popup_link('(0)', '(1)', '(%)' ) ?> <?php edit_post_link("(e)", "", ""); ?>
	</div>
<?php if ($found == $asidesnum) {break;} ?>
<?php endwhile; ?>

</div>