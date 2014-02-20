<?php header("HTTP/1.1 404 Not Found"); ?>
<?php get_header(); ?>

<div id="container">
	<div id="content" class="widecolumn">
		<div id="post-error404" class="post single-post">
			<p class="post-date-single">{ Error 404 }</p>
			<h2 class="post-title-single">Page Not Found</h2>
			<div class="post-entry">
				<p>Apologies. There's been a problem finding the page you're looking for. Perhaps . . .</p>
				<ul>
					<li>the page your looking for was moved</li>
					<li>your referring site gave you an incorrect address</li>
					<li>something went terribly wrong</li>
				</ul> 
				<p>Use the search box and see if you can't find what you're looking for.</p>
				<form id="searchform" method="get" action="<?php bloginfo('home'); ?>/">
					<div>
						<input id="s" name="s" type="text" value="<?php echo wp_specialchars($s, 1); ?>" tabindex="1" size="10" /> <input id="searchsubmit" name="searchsubmit" type="submit" value="Find" tabindex="2" />
					</div>
				</form> 
			</div><!-- END POST-ENTRY  -->
		</div><!-- END POST -->
	</div><!-- END CONTENT -->
</div><!-- END CONTAINER  -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>