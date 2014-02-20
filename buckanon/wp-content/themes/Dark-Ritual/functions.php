<?php
if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<div class="title">',
        'after_title' => '</div>',
    ));
?><?php

function get_recent_comments($no_comments = 5, $comment_lenth = 5, $before = '<li>', $after = '</li>', $show_pass_post = false) {
        global $wpdb, $tablecomments, $tableposts;
        $request = "SELECT ID, comment_ID, comment_content, comment_author FROM $tableposts, $tablecomments WHERE $tableposts.ID=$tablecomments.comment_post_ID AND post_status = 'publish' ";
        if(!$show_pass_post) { $request .= "AND post_password ='' "; }
        $request .= "AND comment_approved = '1' ORDER BY $tablecomments.comment_date DESC LIMIT $no_comments";
        $comments = $wpdb->get_results($request);
        $output = '';
        foreach ($comments as $comment) {
                $comment_author = stripslashes($comment->comment_author);
                $comment_content = strip_tags($comment->comment_content);
                $comment_content = stripslashes($comment_content);
                $words=split(" ",$comment_content);
                $comment_excerpt = join(" ",array_slice($words,0,$comment_lenth));
                $permalink = get_permalink($comment->ID)."#comment-".$comment->comment_ID;
                $output .= $before . '<strong>' . $comment_author . ':</strong> <a href="' . $permalink;
                $output .= '" title="View the entire comment by ' . $comment_author.'">' . $comment_excerpt . '...</a>' . $after;
            }
        echo $output;
}
?>