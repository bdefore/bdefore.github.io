<?php
/*
Plugin Name: Hide/Cut Post Text
Version: 1.0
Plugin URI: http://www.coffee2code.com/wp-plugins/
Author: Scott Reilly
Author URI: http://www.coffee2code.com
Description: Hide (so user never sees) or cut (so only viewable on post's permalink page) portion(s) of post text.  Link text for cuts can be defined per-cut or omitted, and if used, cut links are uniquely anchored.  Quicktag buttons for the new tags are automatically created (but can be disabled).

=>> Visit the plugin's homepage for more information and latest updates  <<=


Installation:

1. Download the file http://www.coffee2code.com/wp-plugins/hide-or-cut-text.zip and unzip it into your 
/wp-content/plugins/ directory.
-OR-
Copy and paste the the code ( http://www.coffee2code.com/wp-plugins/hide-or-cut-text.phps ) into a file called 
hide-or-cut-text.php, and put that file into your /wp-content/plugins/ directory.
2. Optional: Change configuration options in the file to your liking
	a.) In the arguments to function c2c_hide_or_cut_text(), $allowed_types is a space-separated list that can include
	    'cut', 'hide', and/or 'show' (to indicate which you wish to use); the default is 'cut hide show'; you probably 
	    don't need to change this because you could simply not make use of the tags in your post if you don't want to
	b.) In the arguments to function c2c_cut_link_handler(), change $default_linktext to be the string used for the
	    link for cut text when no per-cut link text is defined
	c.) If you don't want ANY quicktags created for you in the Post Edit admin page, find and comment out the following
	    line in the code (by putting two forward slashes before it, i.e. "//"):
	    
	    add_filter('admin_footer', 'add_cuthideshow_buttons');

	    If you don't want one or two of the three buttons from being created, edit the array in the function
	    c2c_add_cuthideshow_buttons() to remove the button(s).
3. Activate the plugin from your WordPress admin 'Plugins' page.

Note: Users who have installed v0.9 of this plugin may want to undo the manually edits to quicktags.js that created the
"cut" and "hide" buttons so that the preferred method for dynamic button creation implemented by this version can be used.
If not, no biggie.


Note: The <!--cut=none--> tag is being phased out in favor of <!--show=single-->


Example:

Let's say this is your post text.
<!--hide-->
This line will never be publically available.
<!--/hide-->
This line, however, will be visible.
<!--cut-->
This line will only appear in the permalink page for this entry.  Instead of this text, non-permalink archive/main
pages will display the default linktext for cuts: "Read more..."
<!--/cut-->
This particular line will be visible on any non-permalink archive/main page.
<!--cut=Read the lengthy explanation...-->
Another section of cut text.  However, the linktext for this cut has just been defined to be "Read the
lengthy explanation..."
<!--/cut-->
Once again, this line is visible.  Each of the cuts made above will be automatically hyperlinked to the appropriate 
location in the permalink page for this post, to where that particular cut text begins.
<!--show=single-->
This text is part of a special kind of cut.  It will ONLY be visible from the PERMALINK page for this particular
post.  No indication that this text is part of the post will be evident anywhere else but the permalink page.
<!--/show-->
This line is always visible...
<!--show=nonsingle-->
Another special cut.  This text will ONLY be visible from NON-PERMALINK pages.  No indication that this text is part 
of the post will be evident on the permalink page.
<!--/show-->
And, as expected, this line is visible.

*/

/*
Copyright (c) 2004-2005 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/* This is a helper function. Change $default_linktext if you like. */
function c2c_cut_link_handler ($linktext, $default_linktext='Read more...') {
	global $cutid, $id;
	$linktext = rtrim($linktext);
	$linktext = ltrim($linktext, " =");
	if (($linktext{0} == '"') && (substr($linktext,-1) == '"')) 
		$linktext = substr($linktext,1,strlen($linktext)-2);
	if (empty($linktext)) {
		if (empty($default_linktext)) return '<a name="cut-' . ++$cutid . '"></a>';
		$linktext = $default_linktext;
	}
	if ('none' == $linktext) return '';
	return '<a href="' . get_permalink($id) . '#cut-' . ++$cutid .'">' . $linktext . '</a>';
} //end c2c_cut_link_handler

/* $allowed_types can include 'cut', 'hide', and/or 'show' */
function c2c_hide_or_cut_text ($text, $allowed_types='cut hide show') {
	$types = array();
	$types = explode(" ", $allowed_types);
	if (in_array('hide', $types)) {
		$text = preg_replace(
			"#(<!--[ ]*hide[ ]*=?[^\-]*-->.+<!--[ ]*/hide[ ]*-->)#imsU",
			"",
			$text
		);
	}
	if (in_array('show', $types)) {
		global $single;
		$show = array();
		if ($single) {
			$text = preg_replace(
				"#(<!--[ ]*show[ ]*=[ ]*nonsingle[ ]*-->.+<!--[ ]*/show[ ]*-->)#imsU",
				"",
				$text
			);
		} else {
			$text = preg_replace(
				"#(<!--[ ]*show[ ]*=[ ]*single[ ]*-->.+<!--[ ]*/show[ ]*-->)#imsU",
				"",
				$text
			);
		}
	}
	if (in_array('cut', $types)) {
		// If 'cut' and not in permalink page, then use cut link.
		global $cutid, $single;
		$cutid = 0;
		if (!$single) {
			$text = preg_replace(
				"#(<!--[ ]*cut[ ]*=?([^\-]*)-->.+<!--[ ]*/cut[ ]*-->)#ismeU",
				"c2c_cut_link_handler(\"$2\")",
				$text
			);
		} else {
			// Replace start cut tag with anchor
			$text = preg_replace(
				"#(<!--[ ]*cut[ ]*=?[^\-]*-->)#eimsU",
				"c2c_cut_link_handler(\"\", \"\")",
				$text
			);
			// Remove end cut tag
			$text = preg_replace(
				"#(<!--[ ]*/cut[ ]*-->)#imsU",
				"",
				$text
			);
		}
	}
	return $text;
} //end c2c_hide_or_cut_text

// Filter early to save other filters from processing text that might otherwise not be shown
add_filter('the_content', 'c2c_hide_or_cut_text', 1);

// Thanks to Owen Winkler (http://www.asymptomatic.com) and Ryan Boren (http://boren.nu) for code 
// 	pointers on inserting buttons into QuickLinks bar
add_filter('admin_footer', 'c2c_add_cuthideshow_buttons');

function c2c_add_cuthideshow_buttons() {
        if(strpos($_SERVER['REQUEST_URI'], 'post.php')) {
		
		// Change this next line if you want... an array of the buttons you want on quicktags button bar
		$buttons = array('cut', 'hide', 'show');

?>
<script language="JavaScript" type="text/javascript"><!--
function js_add_cuthideshow_buttons () {
	var edspell = document.getElementById("ed_spell");
	if (edspell == null) return;
	var edcut = document.getElementById("ed_cut");
	if (edcut != null) return;
<?php
$j = 0;
if (in_array('cut', $buttons)) {
?>
	edButtons[edButtons.length] =
	new edButton('ed_cut'
	,'cut'
	,'<!--cut-->'
	,'<!--/cut-->'
	,''
	);
<?php
	$j++;
}
if (in_array('hide', $buttons)) {
?>
	edButtons[edButtons.length] =
	new edButton('ed_hide'
	,'hide'
	,'<!--hide-->'
	,'<!--/hide-->'
	,''
	);
<?php
	$j++;
}
if (in_array('show', $buttons)) {
?>
	edButtons[edButtons.length] =
	new edButton('ed_show'
	,'show'
	,'<!--show-->'
	,'<!--/show-->'
	,''
	);
<?php
	$j++;
}
?>
	n = edButtons.length - <?php echo $j; ?>;
	for (i = n; i < edButtons.length; i++) {
		edShowButton(edButtons[i], i);
		var newbutton = document.getElementById(edButtons[i].id);
		edspell.parentNode.insertBefore(newbutton, edspell);
	}
	return;
}        
js_add_cuthideshow_buttons();

//--></script>
<?php
	}
}
?>