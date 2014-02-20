        <div id="nifty">
        <div style="padding-left: 20px; padding-right: 20px;">
        <div class="tooltitle"><?php _e('Tags', 'squible'); ?></div>
         <p style="margin-top: -2px; margin-bottom: 4px;" class="post-footer"><em>
		<?php
		if (function_exists('UTW_ShowRelatedTagsForCurrentPost')) {
			UTW_ShowTagsForCurrentPost("commalist",'',3);
        	} else {
			show_tags(3); 
		} 
		?>
        </em></p>
        <div class="tooltitle"><?php _e('Conversation', 'squible'); ?></div>
        <p style="margin-top: -2px; margin-bottom: 4px;" class="post-footer"><em>
        <a href="http://www.technorati.com/search/<?php the_permalink() ?>">Technorati Cosmos</a>
        </em></p>
        <div class="tooltitle"><?php _e('Full Post', 'squible'); ?></div>
        <p style="margin-top: -2px; margin-bottom: 4px;" class="post-footer"><em>
        <a href="<?php the_permalink() ?>" rel="bookmark">Read this post</a>
        </em></p>
        <div class="tooltitle"><?php _e('Comments', 'squible'); ?></div>
        <p style="margin-top: -2px; margin-bottom: 4px;" class="post-footer"><em>
	<a href="<?php the_permalink() ?>#comments" rel="bookmark"><?php _e('view comments', 'squible'); ?></a><br />
        <a href="<?php the_permalink() ?>#postcomment" rel="bookmark"><?php _e('Post a comment', 'squible'); ?></a>
        <?php edit_post_link(__('<br />Edit This Post', 'squible')); ?>
        </em></p>
        </div>
        </div>

