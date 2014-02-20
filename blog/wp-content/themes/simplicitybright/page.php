<?php get_header(); ?>
<div id="content">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="post">
<h1 id="post-<?php the_ID(); ?>"><?php the_title(); ?></h1>
<div class="entrytext">
<?php the_content(__('<p>| Read the rest of this page ...</p>','relaxation')); ?>
<?php link_pages(__('<p><strong>Pages:</strong> ','relaxation'), '</p>', 'number'); ?>
</div>
</div>
<?php endwhile; endif; ?>
<!--<?php edit_post_link(__('Edit this entry.','relaxation'), '<p>', '</p>'); ?>-->
</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>