<?php if (function_exists('c2c_get_recently_commented')) { ?>
<div class="tooltitle"><?php _e("Recent Comments", "squible"); ?></div>
	<?php c2c_get_recently_commented($show_recent_comments); ?>
<?php } ?>