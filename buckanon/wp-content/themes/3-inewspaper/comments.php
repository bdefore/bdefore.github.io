<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments">This post is password protected. Enter the password to view comments.</p>

			<?php
			return;
		}
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'class="alt" ';
?>

<!-- You can start editing here. -->

<?php
$comment_class = (1 == $comment->user_id) ? 'comment-admin' : 'comment';
?>

<?php $commentsNum = 1;?>
	
<?php if (!$comments) : ?>
    <h3 id="comments"><?php comment_type_count('comment');?> Comments on &#8220;<?php the_title(); ?>&#8221;</h3>

	 <p class="themeRSS"><?php comments_rss_link('Subscribe to this post\'s RSS feed'); ?></p>

<h3><?php comment_type_count('ping');?> Trackbacks/Pings <a href="<?php trackback_url(); ?>" rel="trackback">(Trackback URL)</a></h3>


<?php endif; ?>

<?php if ($comments) : ?>
    <h3 id="comments"><?php comment_type_count('comment');?> Comments on &#8220;<?php the_title(); ?>&#8221;</h3>

	 <p class="themeRSS"><?php comments_rss_link('Subscribe to this post\'s RSS feed'); ?></p>
	<ol class="commentlist">

	<?php foreach ($comments as $comment) : ?>
    
    	<?php $comment_type = get_comment_type(); ?>
		<?php if($comment_type == 'comment') { ?>
    	



		<li <?php echo $oddcomment; ?>id="comment-<?php comment_ID() ?>">
            <span class="commentsNum"><?php echo $commentsNum;?>.</span>
            <cite><?php comment_author_link() ?></cite>
			<?php if ($comment->comment_approved == '0') : ?>
			<em>Your comment is awaiting moderation.</em>
			<?php endif; ?>
			<br />

			<span class="commentDate"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('F jS, Y') ?> at <?php comment_time() ?></a> <?php edit_comment_link('edit','&nbsp;&nbsp;',''); ?></span>

			<?php comment_text() ?>

		</li>
 	<?php $commentsNum++;?>

	<?php
		/* Changes every other comment to a different class */
		$oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : '';
	?>
    
    <?php } /* End of is_comment statement */ ?>

	<?php endforeach; /* end for each comment */ ?>

	</ol>

   
        
        <h3><?php comment_type_count('ping');?> Trackbacks/Pings <a href="<?php trackback_url(); ?>" rel="trackback">(Trackback URL)</a></h3>

      
        

	<?php if (get_comment_type_count('ping') > 0 ) { ?>	
      
        <ol class="commentlist">
        
<?php $trackbackNum = 1;?>
		
		<?php foreach ($comments as $comment) : ?>
        
		
		<?php $comment_type = get_comment_type(); ?>
        <?php if($comment_type != 'comment') { ?>
        
        <?php if (($trackbackNum % 2) == 0) { echo "<li class=\"alt\"> "; } else { echo "<li>"; }?>
        <span class="commentsNum"><?php echo $trackbackNum;?>.</span>
        <cite><?php comment_author_link() ?></cite>
        <span class="commentDate"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('F jS, Y') ?> at <?php comment_time() ?></a> <?php edit_comment_link('edit','&nbsp;&nbsp;',''); ?></span>
        
               </li> 
               <?php $trackbackNum++;?>
               
        <?php } ?>
        <?php endforeach; ?>
        </ol>
<?php } else {} ?>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<!--<p class="nocomments">Comments are closed.</p>-->
		</div>
	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>


<h3 id="respond">Leave a Reply</h3>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></p>

<?php else : ?>

<p><label for="author"><small>Your Name: </small></label><br /><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
</p>

<p><label for="email"><small>Your Email: (will not be published) </small></label><br /><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
</p>

<p><label for="url"><small>Your Website Address: </small></label><br /><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
</p>

<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->

<p><small>Comment:</small> <br /><textarea name="comment" id="comment" cols="100" rows="10" tabindex="4"></textarea></p>

<input name="submit" type="submit" alt="Submit Comment" id="submit" tabindex="5" value="Submit Comment" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />

<?php do_action('comment_form', $post->ID); ?>

</form>


<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
