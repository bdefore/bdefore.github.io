
<?php get_header(); ?>

		<?php get_sidebar(); ?>

	<div id="content" class="narrowcolumn" style="margin:0px; ">

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<div class="navigation">
			<div class="alignleft"><?php previous_post('&laquo; %','','yes') ?></div>
			<div class="alignright"><?php next_post(' % &raquo;','','yes') ?></div>
		</div>

		<div class="post">
			<h2 id="post-<?php the_ID(); ?>"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a></h2>
			<small><?php the_time('F jS, Y') ?> <!-- by <?php the_author() ?> --></small>
	
			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
	
				<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
	
				<p class="postmetadata alt">
					<small>
						This entry was posted
						<?php /* This is commented, because it requires a little adjusting sometimes.
							You'll need to download this plugin, and follow the instructions:
							http://binarybonsai.com/archives/2004/08/17/time-since-plugin/ */
							/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?> 
						on <?php the_time('l, F jS, Y') ?> at <?php the_time() ?>
						and is filed under <?php the_category(', ') ?>.
						 
						<?php  edit_post_link('Edit this entry.','',''); ?>
						
					</small>
				</p>
	
			</div>
		</div>
		
	<?php comments_template(); ?>
	
	<?php endwhile; else: ?>
	
		<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
	
<?php endif; ?>

	</div>

<?php get_footer(); ?>