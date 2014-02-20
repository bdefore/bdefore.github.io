<!--[if IE]>
</div>

				<![endif]-->
				
				<div class="secondary">

<div style="position: fixed; _position: none; bottom: 50px ">
<!--[if lte IE 5]>
</div>

				<![endif]-->
	<!-- Reply Form -->
	<?php if ('open' == $post-> comment_status) : ?>
	<div id="loading" style="display: none;">
		Posting Your Comment<br />
		Please Wait
	</div>

		<h2>Leave a Reply</h2>
		
		<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
		
			<p>You must <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">log in</a> to post a comment.</p>
		
		<?php else : ?>
		
<?php /* Load Live Commenting if enabled in the K2 Options Panel */ 
	$k2lc = get_option('k2livecommenting'); if ($k2lc == 0) { ?>

		<form id="commentform" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" onsubmit="new Ajax.Updater({success: 'commentlist'}, '<?php bloginfo('stylesheet_directory') ?>/comments-ajax.php', {asynchronous: true, evalScripts: true, insertion: Insertion.Bottom, onComplete: function(request){complete(request)}, onFailure: function(request){failure(request)}, onLoading: function(request){loading()}, parameters: Form.serialize(this)}); return false;">

	<?php } else { ?>

		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

	<?php } ?>

	<div id="error" style="display:none">
		There was an error with your comment, please try again.
	</div>
		
		<?php if ( $user_ID ) { ?>

			<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account') ?>">Logout &raquo;</a></p>

			<script language="javascript" type="text/javascript">window.onload = HideUtils;</script>

		<?php } elseif ($comment_author != "") { ?>

			<p><small>Welcome back <strong><?php echo $comment_author; ?></strong>
			<span id="showinfo">(<a href="javascript:ShowUtils();">Change</a>)</span>
			<span id="hideinfo">(<a href="javascript:HideUtils();">Close</a>)</span></small></p>

			<script language="javascript" type="text/javascript">window.onload = HideUtils;</script>

		<?php } ?>

			<div id="authorinfo">
				<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
				<label for="author"><small><strong>Name</strong> <?php if ($req) _e('(required)'); ?></small></label></p>

				<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
				<label for="email"><small><strong>Mail</strong> (will not be published) <?php if ($req) _e('(required)'); ?></small></label></p>

				<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
				<label for="url"><small><strong>Website</strong></small></label></p>
			</div>
		
			<!--<p><small><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></small></p>-->
		
			<p><textarea name="comment" id="comment" cols="40%" rows="5" tabindex="4"></textarea></p>
		
			<?php if (function_exists('show_subscription_checkbox')) { show_subscription_checkbox(); } ?>
		
			<p>
				<input name="submit" type="submit" id="submit" tabindex="5" value="Submit" />
				<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
				<br class="clear" />
			</p>
	
			<?php do_action('comment_form', $post->ID); ?>

			</form>


<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
</div>