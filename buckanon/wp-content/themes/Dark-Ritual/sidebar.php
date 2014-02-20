<div class="subnav">
	<h1><?php _e('Categories:'); ?></h1>
	<ul><?php list_cats(0, '', 'name', 'ASC', '/', true, 0, 0);    ?></ul>
	
	<h1><?php _e('Archives'); ?></h1>
	<ul>
		<?php wp_get_archives('type=monthly&show_post_count=0'); ?>
	</ul>


</div>
