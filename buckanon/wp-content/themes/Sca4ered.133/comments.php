<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
	?>
			
		<p class="center"><?php _e("This post is password protected. Enter the password to view comments."); ?><p>
				
<?php	return; } }


	/* Function for seperating comments from track- and pingbacks. */
	function k2_comment_type_detection($commenttxt = 'Comment', $trackbacktxt = 'Trackback', $pingbacktxt = 'Pingback') {
		global $comment;
		if (preg_match('|trackback|', $comment->comment_type))
			return $trackbacktxt;
		elseif (preg_match('|pingback|', $comment->comment_type))
			return $pingbacktxt;
		else
			return $commenttxt;
	}

	$templatedir = get_bloginfo('template_directory');
?>

<!-- You can start editing here. -->

<?php if (($comments) or ('open' == $post-> comment_status)) { ?>

<hr />

<div class="comments" id="comments">

	<h4><a name="comments"></a><a href="#comments"><?php comments_number('No Responses', 'One Response', '% Responses' );?> to &#8220;<?php the_title(); ?>&#8221; &nbsp;</a></h4>

	<div class="metalinks">
		<span class="commentsrsslink"><?php comments_rss_link('Feed for this Entry'); ?></span>
		<?php if ('open' == $post-> ping_status) { ?><span class="trackbacklink"><a href="<?php trackback_url() ?>" title="Copy this URI to trackback this entry.">Trackback Address</a></span><?php } ?>
	</div>
	
	<ol class="commentlist" id="commentlist">

	<?php if ($comments) { ?>

			<?php $count_pings = 1; foreach ($comments as $comment) {
				if (k2_comment_type_detection() == "Comment") { ?>
		
				<li class="<?php /* Style differently if comment author is blog author */ if ($comment->comment_author_email == get_the_author_email()) { echo 'authorcomment'; } ?> item" id="comment-<?php comment_ID() ?>">
					<a name="comment-<?php comment_ID() ?>"></a>
					<?php if (function_exists('gravatar')) { ?><a href="http://www.gravatar.com/" title="What is this?"><img src="<?php gravatar("X", 32, ""); ?>" class="gravatar" alt="Gravatar Icon" /></a><?php } ?>
					<a href="#comment-<?php comment_ID() ?>" class="counter" title="Permanent Link to this Comment"><? echo $count_pings; $count_pings++; ?></a>
					<span class="commentauthor" style="font-weight: bold;"><?php comment_author_link() ?></span>
					<?php if ( $user_ID ) { edit_comment_link('<img src="'.get_bloginfo(template_directory).'/images/pencil.png" alt="Edit Link" />','<span class="commentseditlink">','</span>'); } ?>
					<small class="commentmetadata"><a href="#comment-<?php comment_ID() ?>" title="<?php if (function_exists('time_since')) { $comment_datetime = strtotime($comment->comment_date); echo time_since($comment_datetime) ?> ago<?php } else { ?>Permalink to Comment<?php } ?>"><?php comment_date('M jS, Y') ?> at <?php comment_time() ?></a></small>
	
					<div class="itemtext">
					
						<?php comment_text() ?> 
					
					</div>
	
					<?php if ($comment->comment_approved == '0') : ?>
					<p class="alert"><strong>Your comment is awaiting moderation.</strong></p>
					<?php endif; ?>
	
				</li>
		
			<?php } } /* end for each comment */ ?>
	
		</ol>

		<?php $comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = '$post->ID' AND comment_approved = '1' AND comment_type!= '' ORDER BY comment_date"); ?>

		<?php if ($comments) { ?>

		<ol class="pinglist">
		<?php $count_pings = 1; foreach ($comments as $comment) { 
			if (k2_comment_type_detection() != "Comment") { ?>	
				<li class="item" id="comment-<?php comment_ID() ?>">
					<a name="comment-<?php comment_ID() ?>"></a>
					<?php if (function_exists('comment_favicon')) { ?><span class="favatar"><?php comment_favicon(); ?></span><?php } ?>
					<a href="#comment-<?php comment_ID() ?>" title="Permanent Link to this Comment" class="counter"><? echo $count_pings; $count_pings++; ?></a>
					<span class="commentauthor"><?php comment_author_link() ?></span>
					<small class="commentmetadata"><span class="pingtype"><?php comment_type(); ?></span> on <a href="#comment-<?php comment_ID() ?>" title="<?php if (function_exists('time_since')) { $comment_datetime = strtotime($comment->comment_date); echo time_since($comment_datetime) ?> ago<?php } else { ?>Permalink to Comment<?php } ?>"><?php comment_date('M jS, Y') ?> at <?php comment_time() ?></a> <?php edit_comment_link('<img src="'.get_bloginfo(template_directory).'/images/pencil.png" alt="Edit Link" />','<span class="commentseditlink">','</span>'); ?></small>
				</li>
		
			<?php } } /* end for each comment */ ?>

		</ol>
		<?php } ?>
		
	<?php } else { // this is displayed if there are no comments so far ?>

		<?php if ('open' == $post-> comment_status) { ?> 
			<!-- If comments are open, but there are no comments. -->
				<li id="leavecomment">No Comments</li>

		<?php } else { // comments are closed ?>

			<!-- If comments are closed. -->

			<?php if (is_single) { // To hide comments entirely on Pages without comments ?>
				<li>Comments are currently closed.</li>
			<?php } ?>
	
		<?php } ?>

		</ol>

	<?php } ?>

	<?php if ($comments) { ?>
		<?php include (TEMPLATEPATH . '/navigation.php'); ?>
	<?php } ?>









</div> <!-- Close .comments container -->
<?php } ?>
