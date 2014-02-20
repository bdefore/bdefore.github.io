<?php
/*
	Template name: Post Archives (Generic)
*/
?>
<?php get_header(); ?>

<div id="toppanel">
	<div id="posts">
		<div class="post">
                        <h2 class="storytitle"><?php _e('Archives', 'squible'); ?></h2>
  <ul>
    <?php wp_get_archives(); ?>
  </ul>
                </div>
	<br /><br />
	</div>

</div>

<?php get_footer(); ?>
