<?php // Do not delete these lines
if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
die ('Please do not load this page directly. Thanks!');
if (!empty($post->post_password)) { // if there's a password
if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
?>
<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments."); ?><p>
<?php
return;
}
}
/* This variable is for alternating comment background */
$oddcomment = 'alt';
?>
<!-- You can start editing here. -->
<?php if ($comments) : ?>
<p class="commentheader" id="comments"><?php comments_number(__('No Responses','relaxation'), __('One Response','relaxation'), __('% Responses','relaxation') );?> <?php _e('to','relaxation'); ?> &#8220;<?php the_title(); ?>&#8221;</p> 
<ol class="commentlist">
<?php $relax_comment_count=1; ?>
<?php foreach ($comments as $comment) : ?>
<li class="commentbody" id="comment-<?php comment_ID() ?>">
<?php comment_author_link() ?>
<?php if ($comment->comment_approved == '0') : ?>
<em><?php _e('Your comment is awaiting moderation.','relaxation') ?></em>
<?php endif; ?>
<br />
<a class="commentlink" href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('j. F Y') ?> um <?php comment_time('H:i') ?></a> <?php edit_comment_link('(#)','',''); ?>

<?php comment_text() ?>
</li>
<?php $relax_comment_count++; ?>
<?php endforeach; /* end for each comment */ ?>
</ol>
<?php else : // this is displayed if there are no comments so far ?>
<?php if ('open' == $post-> comment_status) : ?> 
<!-- If comments are open, but there are no comments. -->
<?php else : // comments are closed ?>
<!-- If comments are closed. -->
<p class="nocomments"><?php _e('Comments are closed.','relaxation'); ?></p>
<?php endif; ?>
<?php endif; ?>
<?php if ('open' == $post-> comment_status) : ?>
<p class="commentheader" id="respond"><?php _e('Leave a Reply','relaxation'); ?></p>
<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p><?php _e('You must be logged in to post a comment.','relaxation'); ?></p>
<?php else : ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php if ( $user_ID ) : ?>
<p><?php _e('Logged in as','relaxation'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account') ?>"><?php _e('Logout','relaxation'); ?> &raquo;</a></p>
<?php else : ?>
<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
<label for="author"><small><?php _e('Name','relaxation'); ?> <?php if ($req) _e('(required)'); ?></small></label></p>
<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
<label for="email"><small><?php _e('Mail (will not be published)','relaxation'); ?> <?php if ($req) _e('(required)'); ?></small></label></p>
<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
<label for="url"><small><?php _e('Website','relaxation'); ?></small></label></p>
<?php endif; ?>
<!--<p><small><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></small></p>-->
<p><textarea name="comment" id="comment" cols="50" rows="10" tabindex="4"></textarea></p>
<p><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit','relaxation'); ?>" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>
<?php do_action('comment_form', $post->ID); ?>
</form>
<?php endif; // If registration required and not logged in ?>
<?php endif; // if you delete this the sky will fall on your head ?>