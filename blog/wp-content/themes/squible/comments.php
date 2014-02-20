<?php include("squible_options.php"); ?>
<?php
        if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {$ie=TRUE;}
?>
<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__('Please do not load this page directly. Thanks!', 'squible'));

        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
				?>
				
				<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments.", "squible"); ?><p>
				
				<?php
				return;
            }
        }

		/* This variable is for alternating comment background */
		session_start();

?>

<!-- You can start editing here. -->

<div class="comments" id="comments">
<ol class="commentlist" id="commentlist">

<?php if ($comments) : ?>
	<?php if ($ie) { ?><div style="margin-left: -35px;"><?php } ?>
	<h3 style="" id="comments" style=''><?php comments_number(__('No Responses', 'squible'), __('One Response', 'squible'), __('% Responses', 'squible') );?> <?php _e('to', 'squible'); ?> &#8220;<?php the_title(); ?>&#8221;</h3> <div style='display:inline' id='commentsrss'><span class="metalink"><?php comments_rss_link('RSS'); ?></span>
                <?php if ('open' == $post-> ping_status) { ?><span class="metalink"><a href="<?php trackback_url() ?>" title="<?php _e('Copy this URI to trackback this entry.', 'squible'); ?>"><?php _e('Trackback', 'squible'); ?></a></span><?php } ?></div>
	<?php if ($ie) { ?></div><?php } ?>

<?php if ($ie) { ?>
 <?php $count=0; ?>
        <?php foreach ($comments as $comment) : ?>
        <?php $count++; ?>

<div style="padding: 5px; padding-bottom: 20px; margin-left: -42px;">

<?php
$email = $comment->comment_author_email;
$default = get_bloginfo('stylesheet_directory') . "/images/default.gif";
$size = 20;
$grav_url = "http://www.gravatar.com/avatar.php?  gravatar_id=".md5($email).  "&amp;default=".urlencode($default).  "&amp;size=".$size;
?>
                <a id="comment-<?php comment_ID() ?>"></a>
                        <div class="commentheader">
                        <?php echo "<div style=\"color: #000;padding-left: 10px;\"><div style=\"display:inline; font-weight: bold; font-family: 'Century Gothic', Verdana, sans-serif; font-size: 10pt;\">$count</div>&nbsp;&nbsp;"; ?>
			<div style='display:inline; color: #000; font-family: Verdana, sans-serif; font-size: 10pt;'>
                        <?php echo "by <b>"; comment_author_link(); ?></b> on
                        <a href="#comment-<?php comment_ID() ?>" title=""><?php the_time('l, F jS, Y') ?></a>
                        <?php edit_comment_link('e','|',''); ?>
                        </div></div>
                        <?php if ($comment->comment_approved == '0') : ?>
                        <em><?php _e('Your comment is awaiting moderation.', 'squible'); ?></em>
                        <?php endif; ?>
			</div>

			<div style="background-color: #fff" class="noajax">
			<img style="margin-top: 15px; margin-right: 5px; float: left; border: 1px solid #fff;" src="<?php echo $grav_url; ?>" alt="" /><?php comment_text() ?></div>

	</div>
        <?php endforeach; /* end for each comment */ ?>

<?php } else { ?>
	<?php foreach ($comments as $comment) : ?>
<?php
$email = $comment->comment_author_email;
$default = get_bloginfo('stylesheet_directory') . "/images/default.gif";
$size = 20;
$grav_url = "http://www.gravatar.com/avatar.php?  gravatar_id=".md5($email).  "&amp;default=".urlencode($default).  "&amp;size=".$size;
?>

		<a name="comment-<?php comment_ID() ?>"></a>
		<!-- div class="commentbox" -->
		<li class="norm" id="comment-<?php comment_ID() ?>">
			<span style="font: normal 11px Verdana, Helvetica, serif;">
			<b><?php comment_author_link() ?></b>
			<?php if ($comment->comment_approved == '0') : ?>
			<em><?php _e('Your comment is awaiting moderation.', 'squible'); ?></em>
			<?php endif; ?>

			<small><?php comment_date('F jS, Y') ?> at <?php comment_time() ?> <?php edit_comment_link('e','',''); ?> | <a style='text-decoration: none;' href="#comment-<?php comment_ID() ?>"><?php _e('Permalink', 'squible'); ?></a></small>

			<img style="margin-top: 12px; margin-right: 5px; float: left; border: 1px solid #fff;" src="<?php echo $grav_url; ?>" alt="" /><?php comment_text() ?>
		</span>
		</li>
		<!-- /div -->

	<?php endforeach; /* end for each comment */ ?>

<?php } ?>
	</ol>

 <?php else : // this is displayed if there are no comments so far ?>
	</ol>

  <?php if ('open' == $post-> comment_status) : ?> 
		<!-- If comments are open, but there are no comments. -->
		
	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e('Comments are closed.', 'squible'); ?></p>
		
	<?php endif; ?>
<?php endif; ?>


<div id="comment_loading" style="display:none;margin-top: 5px;"><?php _e('Loading...', 'squible'); ?></div>

<?php if ('open' == $post-> comment_status) : ?>

<br />
<h3 id="respond"><?php _e('Leave a Reply', 'squible'); ?></h3>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p><?php _e('You must be', 'squible'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>"><?php _e('logged in', 'squible'); ?></a> <?php _e('to post a comment.', 'squible'); ?></p>
<?php else : ?>

<a name="postcomment"></a>
<?php if(!$ie) { ?>
<?php if ($use_ajax) { ?>
<form id="commentform" onsubmit="new Ajax.Updater({success:'commentlist'}, '<?php bloginfo('stylesheet_directory') ?>/ajax_comments.php', {asynchronous:true, evalScripts:true, insertion:Insertion.Bottom, onComplete:function(request){complete(request)}, onFailure:function(request){failure(request)}, onLoading:function(request){loading()}, parameters:Form.serialize(this)}); return false;">
<?php } else { ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php } ?>
<?php } else { ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php } ?>

<div id="errors" style="display:none"><?php _e('There was an error with your comment -- sorry dude.', 'squible'); ?></div>

<?php if ( $user_ID ) : ?>

<p><?php _e('Logged in as', 'squible'); ?> <a style='color: #fff;' href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account', 'squible') ?>"><?php _e('Logout', 'squible'); ?> &raquo;</a></p>

<?php else : ?>

<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
<label for="author"><small><?php _e('Name', 'squible'); ?> <?php if ($req) _e('(required)', 'squible'); ?></small></label></p>

<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
<label for="email"><small><?php _e('Mail (will not be published)', 'squible'); ?> <?php if ($req) _e('(required)', 'squible'); ?></small></label></p>

<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
<label for="url"><small><?php _e('Website', 'squible'); ?></small></label></p>

<?php endif; ?>

<div id="formlinks" style="display:inline">
<p><textarea name="comment" id="comment" cols="50" rows="10" tabindex="4"></textarea></p>

<p><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment', 'squible'); ?>" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>
<?php do_action('comment_form', $post->ID); ?>

</form>
</div>

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>

<?php if($ie) { ?><br/><br/><?php } ?>
</div>

<div style="padding: 35px;">
                                        <small>
                                                <?php _e('This entry was posted on', 'squible'); ?> <?php the_time('l, F jS, Y') ?> <?php _e('at', 'squible'); ?> <?php the_time() ?>.

                                                <?php _e('You can follow any responses to this entry through the', 'squible'); ?> <?php comments_rss_link('RSS 2.0'); ?> <?php _e('feed.', 'squible'); ?>
                                                
                                                        <?php _e("If you're wondering how to get your own icon next to your comment, go visit", "squible"); ?> <a href="http://www.gravatar.com/">gravatar.com</a> <?php _e('and get yourself hooked up.', 'squible'); ?>

                                        </small>
</div>
