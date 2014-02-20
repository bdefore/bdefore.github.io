<?php get_header(); ?>
<div id="content">
	<?php if ($posts) : foreach ($posts as $post) : start_wp(); ?>
	<div class="entry">
		<?php foreach ($post as $key => $value) :?>
		<?php if ($key == "post_title" && $value != "") : $isFullPost = true?>
		<div class="entrytitle">
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2> 
			<h3><?php the_time('jS F, Y') ?></h3>
		</div>
		<?php endif; endforeach; ?>
		<div class="entrybody">
			<?php the_content('Read the rest of this entry &raquo;'); ?>
		</div>
		<?php if($isFullPost) : ?>		
		<div class="entrymeta">
		<div class="postinfo">
			<span class="filedto">Filed in <?php the_category(', ') ?> <?php edit_post_link('Edit', ' | ', ''); ?></span>
			<?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;', 'commentslink'); ?>
		</div>
		</div>
		<?php endif; ?>
	</div>
	<?php if($isFullPost) : ?>		
	<div class="commentsblock">
		<?php comments_template(); ?>
	</div>
	<?php endif; ?>
	<?php endforeach; ?>
		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Previous Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Next Entries &raquo;') ?></div>
		</div>
		
	<?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>