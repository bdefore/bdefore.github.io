<?php get_header(); ?>

<div id="container">
	<div id="content" class="narrowcolumn">

<?php if (have_posts()) : ?>

<?php $post = $posts[0]; // KUBRICK HACK: SETS $POST SO THAT THE_DATE() WORKS ?>
<?php if (is_category()) { ?>				
		<p class="post-date-single">{ Category Archives }</p>
		<h2 class="post-title-single"><?php echo single_cat_title(); ?></h2>		
<?php } elseif (is_day()) { ?>
		<p class="post-date-single">{ Daily Archives }</p>
		<h2 class="post-title-single"><?php the_time('F jS, Y'); ?></h2>		
<?php } elseif (is_month()) { ?>
		<p class="post-date-single">{ Monthly Archives }</p>
		<h2 class="post-title-single"><?php the_time('F Y'); ?></h2>
<?php } elseif (is_year()) { ?>
		<p class="post-date-single">{ Yearly Archives }</p>
		<h2 class="post-title-single"><?php the_time('Y'); ?></h2>		
<?php } elseif (is_search()) { ?>
		<p class="post-date-single">{ &#8220; <?php echo wp_specialchars($s); ?> &#8221; }</p>
		<h2 class="post-title-single">Search Results</h2>		
<?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="post-title-single">Blog Archives</h2>
<?php } ?>

<?php while (have_posts()) : the_post(); ?>
				
		<div id="post-<?php the_ID(); ?>" class="post">
			<div class="post-container">
				<div class="post-content">
					<h2 class="post-title"><a href="<?php the_permalink() ?>" title="Permalink to <?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<div class="post-entry">
						<?php the_content('<span class="more-link">Continue Reading &raquo;</span>'); ?>
						<?php link_pages('<p style="margin:-1em 0 0 0;"><strong>Pages: ', '</strong></p>', 'number'); ?>
						<!-- <?php trackback_rdf(); ?> -->
					</div><!-- END POST-ENTRY -->
				</div><!-- END POST-CONTENT  -->
			</div><!-- END-CONTAINER -->
			<div class="post-header">
				<h3 class="post-date"><?php the_time('Y m d') ?></h3>
				<p class="post-categories"><?php the_category('<br/>') ?></p>
				<p class="post-comments"><?php comments_popup_link('Comments (0)', 'Comments (1)', 'Comments (%)'); ?></p>
				<p class="post-permalink"><a href="<?php the_permalink() ?>" title="Permalink to <?php the_title(); ?>" rel="permalink">Permalink</a></p>
				<?php edit_post_link('Edit', '<p class="post-edit">', '</p>'); ?>
			</div><!-- END POST-FOOTER -->
		</div><!-- END POST -->

<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older posts') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer posts &raquo;') ?></div>
		</div><!-- END NAVIGATION -->

<?php else : ?>

		<div id="post-error" class="post single-post">
			<h2 class="post-title-single">Not Found</h2>
			<div class="post-entry">
				<p>Apologies. But something you were looking for just can't be found. Please have a look around and try searching for what you're looking for.</p>
			</div><!-- END POST-ENTRY  -->
		</div><!-- END POST -->

<?php endif; ?>

	</div><!-- END CONTENT -->
</div><!-- END CONTAINER  -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>