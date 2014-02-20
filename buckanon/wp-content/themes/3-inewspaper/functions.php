<?php
if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));

function kubrick_head() {
	$head = "<style type='text/css'>\n<!--";
	$output = '';
	if ( kubrick_header_image() ) {
		$url =  kubrick_header_image_url() ;
		$output .= "#header { background: url('$url') no-repeat bottom center; }\n";
	}
	if ( false !== ( $color = kubrick_header_color() ) ) {
		$output .= "#headerimg h1 a, #headerimg h1 a:visited, #headerimg .description { color: $color; }\n";
	}
	if ( false !== ( $display = kubrick_header_display() ) ) {
		$output .= "#headerimg { display: $display }\n";
	}
	$foot = "--></style>\n";
	if ( '' != $output )
		echo $head . $output . $foot;
}

add_action('wp_head', 'kubrick_head');

function kubrick_header_image() {
	return apply_filters('kubrick_header_image', get_option('kubrick_header_image'));
}

function kubrick_upper_color() {
	if (strpos($url = kubrick_header_image_url(), 'header-img.php?') !== false) {
		parse_str(substr($url, strpos($url, '?') + 1), $q);
		return $q['upper'];
	} else
		return '69aee7';
}

function kubrick_lower_color() {
	if (strpos($url = kubrick_header_image_url(), 'header-img.php?') !== false) {
		parse_str(substr($url, strpos($url, '?') + 1), $q);
		return $q['lower'];
	} else
		return '4180b6';
}

function kubrick_header_image_url() {
	if ( $image = kubrick_header_image() )
		$url = get_template_directory_uri() . '/images/' . $image;
	else
		$url = get_template_directory_uri() . '/images/kubrickheader.jpg';

	return $url;
}

function kubrick_header_color() {
	return apply_filters('kubrick_header_color', get_option('kubrick_header_color'));
}

function kubrick_header_color_string() {
	$color = kubrick_header_color();
	if ( false === $color )
		return 'white';

	return $color;
}

function kubrick_header_display() {
	return apply_filters('kubrick_header_display', get_option('kubrick_header_display'));
}

function kubrick_header_display_string() {
	$display = kubrick_header_display();
	return $display ? $display : 'inline';
}

add_action('admin_menu', 'kubrick_add_theme_page');

function kubrick_add_theme_page() {
	if ( $_GET['page'] == basename(__FILE__) ) {
		if ( 'save' == $_REQUEST['action'] ) {
			check_admin_referer('kubrick-header');
			if ( isset($_REQUEST['njform']) ) {
				if ( isset($_REQUEST['defaults']) ) {
					delete_option('kubrick_header_image');
					delete_option('kubrick_header_color');
					delete_option('kubrick_header_display');
				} else {
					if ( '' == $_REQUEST['njfontcolor'] )
						delete_option('kubrick_header_color');
					else {
						$fontcolor = preg_replace('/^.*(#[0-9a-fA-F]{6})?.*$/', '$1', $_REQUEST['njfontcolor']);
						update_option('kubrick_header_color', $fontcolor);
					}
					if ( preg_match('/[0-9A-F]{6}|[0-9A-F]{3}/i', $_REQUEST['njuppercolor'], $uc) && preg_match('/[0-9A-F]{6}|[0-9A-F]{3}/i', $_REQUEST['njlowercolor'], $lc) ) {
						$uc = ( strlen($uc[0]) == 3 ) ? $uc[0]{0}.$uc[0]{0}.$uc[0]{1}.$uc[0]{1}.$uc[0]{2}.$uc[0]{2} : $uc[0];
						$lc = ( strlen($lc[0]) == 3 ) ? $lc[0]{0}.$lc[0]{0}.$lc[0]{1}.$lc[0]{1}.$lc[0]{2}.$lc[0]{2} : $lc[0];
						update_option('kubrick_header_image', "header-img.php?upper=$uc&lower=$lc");
					}

					if ( isset($_REQUEST['toggledisplay']) ) {
						if ( false === get_option('kubrick_header_display') )
							update_option('kubrick_header_display', 'none');
						else
							delete_option('kubrick_header_display');
					}
				}
			} else {

				if ( isset($_REQUEST['headerimage']) ) {
					check_admin_referer('kubrick-header');
					if ( '' == $_REQUEST['headerimage'] )
						delete_option('kubrick_header_image');
					else {
						$headerimage = preg_replace('/^.*?(header-img.php\?upper=[0-9a-fA-F]{6}&lower=[0-9a-fA-F]{6})?.*$/', '$1', $_REQUEST['headerimage']);
						update_option('kubrick_header_image', $headerimage);
					}
				}

				if ( isset($_REQUEST['fontcolor']) ) {
					check_admin_referer('kubrick-header');
					if ( '' == $_REQUEST['fontcolor'] )
						delete_option('kubrick_header_color');
					else {
						$fontcolor = preg_replace('/^.*?(#[0-9a-fA-F]{6})?.*$/', '$1', $_REQUEST['fontcolor']);
						update_option('kubrick_header_color', $fontcolor);
					}
				}

				if ( isset($_REQUEST['fontdisplay']) ) {
					check_admin_referer('kubrick-header');
					if ( '' == $_REQUEST['fontdisplay'] || 'inline' == $_REQUEST['fontdisplay'] )
						delete_option('kubrick_header_display');
					else
						update_option('kubrick_header_display', 'none');
				}
			}
			//print_r($_REQUEST);
			wp_redirect("themes.php?page=functions.php&saved=true");
			die;
		}
		add_action('admin_head', 'kubrick_theme_page_head');
	}
	add_theme_page(__('Customize Header'), __('Header Image and Color'), 'edit_themes', basename(__FILE__), 'kubrick_theme_page');
}

function kubrick_theme_page_head() {
?>
<script type="text/javascript" src="../wp-includes/js/colorpicker.js"></script>
<script type='text/javascript'>
// <![CDATA[
	function pickColor(color) {
		ColorPicker_targetInput.value = color;
		kUpdate(ColorPicker_targetInput.id);
	}
	function PopupWindow_populate(contents) {
		contents += '<br /><p style="text-align:center;margin-top:0px;"><input type="button" value="<?php echo attribute_escape(__('Close Color Picker')); ?>" onclick="cp.hidePopup(\'prettyplease\')"></input></p>';
		this.contents = contents;
		this.populated = false;
	}
	function PopupWindow_hidePopup(magicword) {
		if ( magicword != 'prettyplease' )
			return false;
		if (this.divName != null) {
			if (this.use_gebi) {
				document.getElementById(this.divName).style.visibility = "hidden";
			}
			else if (this.use_css) {
				document.all[this.divName].style.visibility = "hidden";
			}
			else if (this.use_layers) {
				document.layers[this.divName].visibility = "hidden";
			}
		}
		else {
			if (this.popupWindow && !this.popupWindow.closed) {
				this.popupWindow.close();
				this.popupWindow = null;
			}
		}
		return false;
	}
	function colorSelect(t,p) {
		if ( cp.p == p && document.getElementById(cp.divName).style.visibility != "hidden" )
			cp.hidePopup('prettyplease');
		else {
			cp.p = p;
			cp.select(t,p);
		}
	}
	function PopupWindow_setSize(width,height) {
		this.width = 162;
		this.height = 210;
	}

	var cp = new ColorPicker();
	function advUpdate(val, obj) {
		document.getElementById(obj).value = val;
		kUpdate(obj);
	}
	function kUpdate(oid) {
		if ( 'uppercolor' == oid || 'lowercolor' == oid ) {
			uc = document.getElementById('uppercolor').value.replace('#', '');
			lc = document.getElementById('lowercolor').value.replace('#', '');
			hi = document.getElementById('headerimage');
			hi.value = 'header-img.php?upper='+uc+'&lower='+lc;
			document.getElementById('header').style.background = 'url("<?php echo get_template_directory_uri(); ?>/images/'+hi.value+'") center no-repeat';
			document.getElementById('advuppercolor').value = '#'+uc;
			document.getElementById('advlowercolor').value = '#'+lc;
		}
		if ( 'fontcolor' == oid ) {
			document.getElementById('header').style.color = document.getElementById('fontcolor').value;
			document.getElementById('advfontcolor').value = document.getElementById('fontcolor').value;
		}
		if ( 'fontdisplay' == oid ) {
			document.getElementById('headerimg').style.display = document.getElementById('fontdisplay').value;
		}
	}
	function toggleDisplay() {
		td = document.getElementById('fontdisplay');
		td.value = ( td.value == 'none' ) ? 'inline' : 'none';
		kUpdate('fontdisplay');
	}
	function toggleAdvanced() {
		a = document.getElementById('jsAdvanced');
		if ( a.style.display == 'none' )
			a.style.display = 'block';
		else
			a.style.display = 'none';
	}
	function kDefaults() {
		document.getElementById('headerimage').value = '';
		document.getElementById('advuppercolor').value = document.getElementById('uppercolor').value = '#69aee7';
		document.getElementById('advlowercolor').value = document.getElementById('lowercolor').value = '#4180b6';
		document.getElementById('header').style.background = 'url("<?php echo get_template_directory_uri(); ?>/images/kubrickheader.jpg") center no-repeat';
		document.getElementById('header').style.color = '#FFFFFF';
		document.getElementById('advfontcolor').value = document.getElementById('fontcolor').value = '';
		document.getElementById('fontdisplay').value = 'inline';
		document.getElementById('headerimg').style.display = document.getElementById('fontdisplay').value;
	}
	function kRevert() {
		document.getElementById('headerimage').value = '<?php echo js_escape(kubrick_header_image()); ?>';
		document.getElementById('advuppercolor').value = document.getElementById('uppercolor').value = '#<?php echo js_escape(kubrick_upper_color()); ?>';
		document.getElementById('advlowercolor').value = document.getElementById('lowercolor').value = '#<?php echo js_escape(kubrick_lower_color()); ?>';
		document.getElementById('header').style.background = 'url("<?php echo js_escape(kubrick_header_image_url()); ?>") center no-repeat';
		document.getElementById('header').style.color = '';
		document.getElementById('advfontcolor').value = document.getElementById('fontcolor').value = '<?php echo js_escape(kubrick_header_color_string()); ?>';
		document.getElementById('fontdisplay').value = '<?php echo js_escape(kubrick_header_display_string()); ?>';
		document.getElementById('headerimg').style.display = document.getElementById('fontdisplay').value;
	}
	function kInit() {
		document.getElementById('jsForm').style.display = 'block';
		document.getElementById('nonJsForm').style.display = 'none';
	}
	addLoadEvent(kInit);
// ]]>
</script>
<style type='text/css'>
	#headwrap {
		text-align: center;
	}
	#kubrick-header {
		font-size: 80%;
	}
	#kubrick-header .hibrowser {
		width: 780px;
		height: 260px;
		overflow: scroll;
	}
	#kubrick-header #hitarget {
		display: none;
	}
	#kubrick-header #header h1 {
		font-family: 'Trebuchet MS', 'Lucida Grande', Verdana, Arial, Sans-Serif;
		font-weight: bold;
		font-size: 4em;
		text-align: center;
		padding-top: 70px;
		margin: 0;
	}

	#kubrick-header #header .description {
		font-family: 'Lucida Grande', Verdana, Arial, Sans-Serif;
		font-size: 1.2em;
		text-align: center;
	}
	#kubrick-header #header {
		text-decoration: none;
		color: <?php echo kubrick_header_color_string(); ?>;
		padding: 0;
		margin: 0;
		height: 200px;
		text-align: center;
		background: url('<?php echo kubrick_header_image_url(); ?>') center no-repeat;
	}
	#kubrick-header #headerimg {
		margin: 0;
		height: 200px;
		width: 100%;
		display: <?php echo kubrick_header_display_string(); ?>;
	}
	#jsForm {
		display: none;
		text-align: center;
	}
	#jsForm input.submit, #jsForm input.button, #jsAdvanced input.button {
		padding: 0px;
		margin: 0px;
	}
	#advanced {
		text-align: center;
		width: 620px;
	}
	html>body #advanced {
		text-align: center;
		position: relative;
		left: 50%;
		margin-left: -380px;
	}
	#jsAdvanced {
		text-align: right;
	}
	#nonJsForm {
		position: relative;
		text-align: left;
		margin-left: -370px;
		left: 50%;
	}
	#nonJsForm label {
		padding-top: 6px;
		padding-right: 5px;
		float: left;
		width: 100px;
		text-align: right;
	}
	.defbutton {
		font-weight: bold;
	}
	.zerosize {
		width: 0px;
		height: 0px;
		overflow: hidden;
	}
	#colorPickerDiv a, #colorPickerDiv a:hover {
		padding: 1px;
		text-decoration: none;
		border-bottom: 0px;
	}
</style>
<?php
}

function kubrick_theme_page() {
	if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.').'</strong></p></div>';
?>
<div class='wrap'>
	<div id="kubrick-header">
	<h2><?php _e('Header Image and Color'); ?></h2>
		<div id="headwrap">
			<div id="header">
				<div id="headerimg">
					<h1><?php bloginfo('name'); ?></h1>
					<div class="description"><?php bloginfo('description'); ?></div>
				</div>
			</div>
		</div>
		<br />
		<div id="nonJsForm">
			<form method="post" action="">
				<?php wp_nonce_field('kubrick-header'); ?>
				<div class="zerosize"><input type="submit" name="defaultsubmit" value="<?php echo attribute_escape(__('Save')); ?>" /></div>
					<label for="njfontcolor"><?php _e('Font Color:'); ?></label><input type="text" name="njfontcolor" id="njfontcolor" value="<?php echo attribute_escape(kubrick_header_color()); ?>" /> <?php printf(__('Any CSS color (%s or %s or %s)'), '<code>red</code>', '<code>#FF0000</code>', '<code>rgb(255, 0, 0)</code>'); ?><br />
					<label for="njuppercolor"><?php _e('Upper Color:'); ?></label><input type="text" name="njuppercolor" id="njuppercolor" value="#<?php echo attribute_escape(kubrick_upper_color()); ?>" /> <?php printf(__('HEX only (%s or %s)'), '<code>#FF0000</code>', '<code>#F00</code>'); ?><br />
				<label for="njlowercolor"><?php _e('Lower Color:'); ?></label><input type="text" name="njlowercolor" id="njlowercolor" value="#<?php echo attribute_escape(kubrick_lower_color()); ?>" /> <?php printf(__('HEX only (%s or %s)'), '<code>#FF0000</code>', '<code>#F00</code>'); ?><br />
				<input type="hidden" name="hi" id="hi" value="<?php echo attribute_escape(kubrick_header_image()); ?>" />
				<input type="submit" name="toggledisplay" id="toggledisplay" value="<?php echo attribute_escape(__('Toggle Text')); ?>" />
				<input type="submit" name="defaults" value="<?php echo attribute_escape(__('Use Defaults')); ?>" />
				<input type="submit" class="defbutton" name="submitform" value="&nbsp;&nbsp;<?php _e('Save'); ?>&nbsp;&nbsp;" />
				<input type="hidden" name="action" value="save" />
				<input type="hidden" name="njform" value="true" />
			</form>
		</div>
		<div id="jsForm">
			<form style="display:inline;" method="post" name="hicolor" id="hicolor" action="<?php echo attribute_escape($_SERVER['REQUEST_URI']); ?>">
				<?php wp_nonce_field('kubrick-header'); ?>
	<input type="button" onclick="tgt=document.getElementById('fontcolor');colorSelect(tgt,'pick1');return false;" name="pick1" id="pick1" value="<?php echo attribute_escape(__('Font Color')); ?>"></input>
		<input type="button" onclick="tgt=document.getElementById('uppercolor');colorSelect(tgt,'pick2');return false;" name="pick2" id="pick2" value="<?php echo attribute_escape(__('Upper Color')); ?>"></input>
		<input type="button" onclick="tgt=document.getElementById('lowercolor');colorSelect(tgt,'pick3');return false;" name="pick3" id="pick3" value="<?php echo attribute_escape(__('Lower Color')); ?>"></input>
				<input type="button" name="revert" value="<?php echo attribute_escape(__('Revert')); ?>" onclick="kRevert()" />
				<input type="button" value="<?php echo attribute_escape(__('Advanced')); ?>" onclick="toggleAdvanced()" />
				<input type="hidden" name="action" value="save" />
				<input type="hidden" name="fontdisplay" id="fontdisplay" value="<?php echo attribute_escape(kubrick_header_display()); ?>" />
				<input type="hidden" name="fontcolor" id="fontcolor" value="<?php echo attribute_escape(kubrick_header_color()); ?>" />
				<input type="hidden" name="uppercolor" id="uppercolor" value="<?php echo attribute_escape(kubrick_upper_color()); ?>" />
				<input type="hidden" name="lowercolor" id="lowercolor" value="<?php echo attribute_escape(kubrick_lower_color()); ?>" />
				<input type="hidden" name="headerimage" id="headerimage" value="<?php echo attribute_escape(kubrick_header_image()); ?>" />
				<p class="submit"><input type="submit" name="submitform" class="defbutton" value="<?php echo attribute_escape(__('Update Header &raquo;')); ?>" onclick="cp.hidePopup('prettyplease')" /></p>
			</form>
			<div id="colorPickerDiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;visibility:hidden;"> </div>
			<div id="advanced">
				<form id="jsAdvanced" style="display:none;" action="">
					<?php wp_nonce_field('kubrick-header'); ?>
					<label for="advfontcolor"><?php _e('Font Color (CSS):'); ?> </label><input type="text" id="advfontcolor" onchange="advUpdate(this.value, 'fontcolor')" value="<?php echo attribute_escape(kubrick_header_color()); ?>" /><br />
					<label for="advuppercolor"><?php _e('Upper Color (HEX):');?> </label><input type="text" id="advuppercolor" onchange="advUpdate(this.value, 'uppercolor')" value="#<?php echo attribute_escape(kubrick_upper_color()); ?>" /><br />
					<label for="advlowercolor"><?php _e('Lower Color (HEX):'); ?> </label><input type="text" id="advlowercolor" onchange="advUpdate(this.value, 'lowercolor')" value="#<?php echo attribute_escape(kubrick_lower_color()); ?>" /><br />
					<input type="button" name="default" value="<?php echo attribute_escape(__('Select Default Colors')); ?>" onclick="kDefaults()" /><br />
					<input type="button" onclick="toggleDisplay();return false;" name="pick" id="pick" value="<?php echo attribute_escape(__('Toggle Text Display')); ?>"></input><br />
				</form>
			</div>
		</div>
	</div>
</div>
<?php }
// Recent Comments
function mw_recent_comments(
	$no_comments = 8,
	$show_pass_post = false,
	$title_length = 50, 	// max title length (auto shorten)
	$author_length = 30,	// max author length (auto shorten)
	$wordwrap_length = 50, //  adds a blank if word is longer than this number of chars
	$type = 'all', 	// Comments, trackbacks, or both?
	$format = '<li>%date%: <a href="%permalink%" title="%title%">%title%</a> (von %author_full%)</li>',
	$date_format = 'd.m.y, H:i',
	$none_found = '<li>No Comments</li>',	// None found
	$type_text_pingback = 'Pingback von',
	$type_text_trackback = 'Trackback von',
	$type_text_comment = 'von'

	) {

	//Language...
	$mwlang_anonymous = 'Anonymous'; // Anonymous
	$mwlang_authorurl_title_before = 'Website of &lsaquo;';
	$mwlang_authorurl_title_after = '&rsaquo; Visit';


    global $wpdb;

    $request = "SELECT ID, comment_ID, comment_content, comment_author, comment_author_url, comment_date, post_title, comment_type
				FROM $wpdb->comments LEFT JOIN $wpdb->posts ON $wpdb->posts.ID=$wpdb->comments.comment_post_ID
				WHERE post_status IN ('publish','static')";

	switch($type) {
		case 'all':
			// add nothing
			break;
		case 'comment_only':
			//
			$request .= "AND $wpdb->comments.comment_type='' ";
			break;
		case 'trackback_only':
			$request .= "AND ( $wpdb->comments.comment_type='trackback' OR $wpdb->comments.comment_type='pingback' ) ";
			break;
	 default:
 		//
			break;

	}

	if (!$show_pass_post) $request .= "AND post_password ='' ";

	$request .= "AND comment_approved = '1' ORDER BY comment_ID DESC LIMIT $no_comments";

	$comments = $wpdb->get_results($request);
    $output = '';
	if ($comments) {
    	foreach ($comments as $comment) {

			// Permalink to post/comment
			$loop_res['permalink'] = get_permalink($comment->ID). '#comment-' . $comment->comment_ID;

			// Title of the post
			$loop_res['post_title'] = stripslashes($comment->post_title);
			$loop_res['post_title'] = wordwrap($loop_res['post_title'], $wordwrap_length, ' ' , 1);

			if (strlen($loop_res['post_title']) >= $title_length) {
				$loop_res['post_title'] = substr($loop_res['post_title'], 0, $title_length) . '&#8230;';
			}

			// Author's name only
        	$loop_res['author_name'] = stripslashes($comment->comment_author);
			$loop_res['author_name'] = wordwrap($loop_res['author_name'], $wordwrap_length, ' ' , 1);

			if ($loop_res['author_name'] == '') $loop_res['author_name'] = $mwlang_anonymous;
			if (strlen($loop_res['author_name']) >= $author_length) {
				$loop_res['author_name'] = substr($loop_res['author_name'], 0, $author_length) . '&#8230;';
			}

			// Full author (link, name)
			$author_url = $comment->comment_author_url;
			if (empty($author_url)) {
				$loop_res['author_full'] = $loop_res['author_name'];
			} else {
				$loop_res['author_full'] = '<a href="' . $author_url . '" title="' . $mwlang_authorurl_title_before . $loop_res['author_name'] . $mwlang_authorurl_title_after . '">' . $loop_res['author_name'] . '</a>';
			}

/*
			// Comment excerpt
			$comment_excerpt = strip_tags($comment->comment_content);
			$comment_excerpt = stripslashes($comment_excerpt);
			if (strlen($comment_excerpt) >= $comment_length) {
				$comment_excerpt = substr($comment_excerpt, 0, $comment_length) . '...';
			}

*/

			// Comment type
			if ( $comment->comment_type == 'pingback' ) {
				$loop_res['comment_type'] = $type_text_pingback;
			} elseif ( $comment->comment_type == 'trackback' ) {
				$loop_res['comment_type'] = $type_text_trackback;
			} else {
				$loop_res['comment_type'] = $type_text_comment;
			}

			// Date of comment
			$loop_res['comment_date'] = mysql2date($date_format, $comment->comment_date);

			// Output element
			$element_loop = str_replace('%permalink%', $loop_res['permalink'], $format);
			$element_loop = str_replace('%title%', $loop_res['post_title'], $element_loop);
			$element_loop = str_replace('%author_name%', $loop_res['author_name'], $element_loop);
			$element_loop = str_replace('%author_full%', $loop_res['author_full'], $element_loop);
			$element_loop = str_replace('%date%', $loop_res['comment_date'], $element_loop);
			$element_loop = str_replace('%type%', $loop_res['comment_type'], $element_loop);


			$output .= $element_loop . "\n";


		} //foreach

		$output = convert_smilies($output);

	} else {
		$output .= $none_found;
    }

    echo $output;
}
?>
<?php
/*
Plugin Name: Breadcrumb Navigation XT
Plugin URI: http://mtekk.weblogs.us/code/breadcrumb-nav-xt/
Description: Adds a breadcrumb navigation showing the visitor&#39;s path to their current location. For details on how to use this plugin visit <a href="http://mtekk.weblogs.us/code/breadcrumb-nav-xt/">Breadcrumb Nav XT</a>. 
Version: 1.10.1
Author: John Havlik/Michael Woehrer
Author URI: http://mtekk.weblogs.us/
*/
$bcn_version = "1.10.1";
/*	----------------------------------------------------------------------------
         ____________________________________________________
        |                                                    |
        |             Breadcrumb Navigation XT               |
        |                  Michael Woehrer                   |
        |                    John Havlik                     |
        |____________________________________________________|

	Copyright 2006-2007 Michael Woehrer (michael dot woehrer at gmail dot com)
	Portions Copyright 2007 John Havlik (mtekkmonkey at gmail dot com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    --------------------------------------------------------------------------*/
/////////////////////////////////
//The class starts here
/////////////////////////////////
class breadcrumb_navigation_xt {

/*==============================================================================
    					=== VARIABLES ===
  ============================================================================*/
	
	var $opt;	// array containing the options
	
/*==============================================================================
    					=== CONSTRUCTOR ===
  ============================================================================*/
	function breadcrumb_navigation_xt() {
		$this->opt = array(
			// Assuming your Wordpress URL is http://www.site.com:
			//  a) If you have a standard WP installation (www.site.com displays latest 10 posts)
			//     then use FALSE
			//  b) If you have a static front page by using a WordPress page on http://www.site.com and
			//     your blog is available at http://www.site.com/blog/, then use TRUE
			'static_frontpage' => 'false',

				//*** only used if 'static_frontpage' => true
					// Relative URL for your blog's address that is used for the Weblog link. 
					// Use it if your blog is available at http://www.site.com/myweblog/, 
					// and at http://www.site.com/ a Wordpress page is being displayed:
					// In this case apply '/myweblog/'.
						'url_blog' => '',
					// Display HOME? If set to false, HOME is not being displayed. 
						'home_display' => 'true',
					// URL for the home link
						'url_home' => get_settings('home'),
					// Apply a link to HOME? If set to false, only plain text is being displayed.
						'home_link' => 'true',
					// Text displayed for the home link, if you don't want to call it home then just change this.
					// Also, it is being checked if the current page title = this variable. If yes, only the Home link is being displayed,
					// but not a weird "Home / Home" breadcrumb.	
						'title_home' => 'Home',
			// Text displayed for the weblog. If "'static_frontpage' => false", you
			// might want to change this value to "Home" 
				'title_blog' => 'Home',
			// Separator that is placed between each item in the breadcrumb navigation, but not placed before
			// the first and not after the last element. You also can use images here,
			// e.g. '<img src="separator.gif" title="separator" width="10" height="8" />'
				'separator' => ' | ',
			// Prefix for a search page
				'search_prefix' => 'Search results for &#39;',
			// Suffix for a search page
				'search_suffix' => '&#39;',
			// Prefix for a author page
				'author_prefix' => 'Posts by ',
			// Suffix for a author page
				'author_suffix' => '',
			// Prefix for an attachment post
				'attachment_prefix' => 'Attachment: ',
			// Suffix for an attachment post
				'attachment_suffix' => '',
			// Name format to display for author (e.g., nickname, first_name, last_name, display_name)
				'author_display' => 'display_name',
			// Prefix for a single blog article.
				'singleblogpost_prefix' => '',
			// Suffix for a single blog article.
				'singleblogpost_suffix' => '',
			// Prefix for a page.
				'page_prefix' => '',
			// Suffix for a page.
				'page_suffix' => '',
			// The prefix that is used for mouseover link (e.g.: "Browse to: Archive")
				'urltitle_prefix' => 'Browse to: ',
			// The suffix that is used for mouseover link
				'urltitle_suffix' => '',
			// Prefix for categories.
				'archive_category_prefix' => '',
			// Suffix for categories.
				'archive_category_suffix' => '',
			// Prefix for archive by year/month/day
				'archive_date_prefix' => 'Archive for ',
			// Suffix for archive by year/month/day
				'archive_date_suffix' => '',
			// Archive date format (e.g., ISO (yy/mm/dd), US (mm/dd/yy), EU (dd/mm/yy))
				'archive_date_format' => 'EU',
			// Prefix for tags.
				'archive_tag_prefix' => 'Archive by tag &#39;',
			// Suffix for tags.
				'archive_tag_suffix' => '&#39;',
			// Text displayed for a 404 error page, , only being used if 'use404' => true
				'title_404' => '404',
			// Display current item as link?
				'link_current_item' => 'false',
			// URL title of current item, only being used if 'link_current_item' => true
				'current_item_urltitle' => 'Link of current page (click to refresh)',
			// Style or prefix being applied as prefix to current item. E.g. <span class="bc_current">
				'current_item_style_prefix' => '',
			// Style or prefix being applied as suffix to current item. E.g. </span>
				'current_item_style_suffix' => '',
			// Maximum number of characters of post title to be displayed? 0 means no limit.
				'posttitle_maxlen' => 0,
			// Display category when displaying single blog post
				'singleblogpost_category_display' => 'true',
			// Maximum number of categories to display in the breadcrumb. O means no limit.
				'singleblogpost_category_maxdisp' => 0,
			// Prefix for single blog post category, only being used if 'singleblogpost_category_display' => true
				'singleblogpost_category_prefix' => '',
			// Suffix for single blog post category, only being used if 'singleblogpost_category_display' => true
				'singleblogpost_category_suffix' => '',
 
		);		
	} // END function breadcrumb (constructor)

/*==============================================================================
    				=== DISPLAY BREADCRUMB ===
  ============================================================================*/
	function display() {
		global $wpdb, $post, $wp_query;
		//Initilize runlenght variable
		$bcn_runlength = 0;
		////////////////////////////////////////////////////////////////////////
		// Needed links
		////////////////////////////////////////////////////////////////////////
		/* -------- HOME LINK -------- */
		$bcn_homelink = '';
		if ( ($this->opt['static_frontpage'] === 'true') AND ($this->opt['home_display'] === 'true')) {		// Hide HOME if it is disabled in the options
			if ($this->opt['home_link'] === 'true') {			// Link home or just display text
				$bcn_homelink = '<a href="' . $this->opt['url_home'] . '" title="' . $this->opt['urltitle_prefix'] . $this->opt['title_home'] . $this->opt['urltitle_suffix'] . '">' . $this->opt['title_home'] . '</a>';
			} else {
				$bcn_homelink = $this->opt['title_home'];			
			}
		}
	
		/* -------- BLOG LINK -------- */
		$bcn_bloglink = '<a href="' . get_bloginfo('url') . $this->opt['url_blog'] . '" title="' . $this->opt['urltitle_prefix'] . $this->opt['title_blog'] . $this->opt['urltitle_suffix'] . '">' . $this->opt['title_blog'] . '</a>';

		/* -------- CURRENT ITEM -------- */
		$curitem_urlprefix = '';
		$curitem_urlsuffix = '';
		if ($this->opt['link_current_item'] === 'true') {
			$curitem_urlprefix = '<a title="' . $this->opt['current_item_urltitle'] . '" href="' . $_SERVER['REQUEST_URI'] . '">';
			$curitem_urlsuffix = '</a>';
		}
		
		////////////////////////////////////////////////////////////////////////
		// Get the different types
		////////////////////////////////////////////////////////////////////////
		if ( is_search() ) 								$swg_type = 'search';		// Search
		elseif ( is_page() ) 							$swg_type = 'page';			// Page
		elseif ( is_attachment())						$swg_type = 'attachment';	// Attachments
		elseif ( is_single() )							$swg_type = 'singlepost';	// Single post page
		elseif ( is_author() )							$swg_type = 'author';		// Author page
		elseif ( is_archive() && is_category() )		$swg_type = 'categories';	// Weblog Categories
		elseif ( is_archive() && is_date() )			$swg_type = 'date';			// Weblog Date Archive
		elseif ( is_archive() && !is_date() && !is_category() )		$swg_type = 'tag';	// Weblog Tag Archive
		elseif ( is_404() )								$swg_type = '404';			// 404
		else											$swg_type = 'else';			// Everything else (should be weblog article list only)
	
	
		/* *************************************************
			Here we set the initial array $result_array. We use this for being able
			to apply styles, anchors etc. to each element.
			Default is set to false.
		************************************************* */
		$result_array = array(
			'middle' => false,	// The part between "Home" and the last element of the breadcrumb.
			'last' => array(	// The last element of the breadcrumb
					'prefix' => false,	// prefix
					'title' => false,	// text
					'suffix' => false	// suffix
				  ) 
			);
	
	
		switch ($swg_type) {
	
		case 'page':
			////////////////////////////////////////////////////////////////////
			// Get Pages
			////////////////////////////////////////////////////////////////////
			$bcn_pagetitle = trim(wp_title('', false));	// page title, we do not use "$post->post_title" since it could display
														// 	wrong title if theme uses more than one LOOP.
			$bcn_theparentid = $post->post_parent;	// id of the parent page
			
			$bcn_loopcount = 0;	// counter for the array
			while( 0 != $bcn_theparentid ) {
				// Get the row of the parent's page;
				// 	*** Regarding performance this is not a perfect solution since this query is inside a loop ! ***
				//		However, the number of queries is reduced to the number of parents.
				$mylink = $wpdb->get_row("SELECT post_title, post_parent FROM $wpdb->posts WHERE ID = '$bcn_theparentid;'");
	
				// Title of parent into array incl. current permalink (via $bcn_theparentid, 
				// since we set this variable below we can use it here as current id!)
				$bcn_titlearray[$bcn_loopcount] = '<a href="' . get_permalink($bcn_theparentid) . '" title="' . $this->opt['urltitle_prefix'] . $mylink->post_title . $this->opt['urltitle_suffix'] . '">' . $mylink->post_title . '</a>';
	
				// New parent ID of parent
				$bcn_theparentid = $mylink->post_parent;
	
				$bcn_loopcount++;	
			}	// while
	
			if (is_array($bcn_titlearray)) {
				// Reverse the array since it is in a reverse order 
				$bcn_titlearray = array_reverse($bcn_titlearray);
		
				// Prepare the output by looping thru the array. We use $sep for not adding the separator before the first element
				$count = 0;
				foreach ($bcn_titlearray as $val) {
					$sep = '';
					if (0 != $count)
						$sep = $this->opt['separator'];

					$page_result = $page_result . $sep . $val;
					
					$count++;
				}
			}

			// Result			
			// If we have a front page named 'Home' (or similar), we do not want to display the Breadcrumb like this: Home / Home
			// Therefore do not display the Home Link if such certain page is being displayed.
			if( strtolower($bcn_pagetitle) != strtolower($this->opt['title_home']) ) {  // Check if we are not on home
				if ($page_result != '') $result_array['middle'] = $page_result;
				$result_array['last']['prefix'] = $this->opt['page_prefix'];
				$result_array['last']['title'] = $bcn_pagetitle;
				$result_array['last']['suffix'] = $this->opt['page_suffix'];
			}
	
			break; // end of case 'page'
	
		case 'attachment':
			////////////////////////////////////////////////////////////////////
			// Get attachment page
			////////////////////////////////////////////////////////////////////
			
			// Blog link and parent page
			$bcn_theparentid = $post->post_parent;
			$bcn_parenttitle = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = '$bcn_theparentid;'");
			$bcn_parent = '<a title="' . $this->opt['urltitle_prefix'] .
				$bcn_parenttitle->post_title . $this->opt['urltitle_suffix'] .
				'" href="' . get_permalink($bcn_theparentid) . '">' .
				$bcn_parenttitle->post_title . '</a>';
			$result_array['middle'] = $bcn_bloglink . $this->opt['separator'] . $bcn_parent;
			// Attachment prefix text
			$result_array['last']['prefix'] = $this->opt['attachment_prefix'];
			// Get attachment name
			$result_array['last']['title'] = trim(wp_title('', false));
			// Attachment suffix text
			$result_array['last']['suffix'] = $this->opt['attachment_suffix'];
			break; // end of case 'attachment'
			
		case 'search':
			////////////////////////////////////////////////////////////////////
			// Get search
			////////////////////////////////////////////////////////////////////
			
			//Get the search prefix
			$result_array['last']['prefix'] = $this->opt['search_prefix'];
			//Get the searched text
			Global $s;
			$result_array['last']['title'] = wp_specialchars($s, 1);
			//Get the search suffix
			$result_array['last']['suffix'] = $this->opt['search_suffix'];
			break; // end of case 'search'
			
		case 'author':
			////////////////////////////////////////////////////////////////////
			// Get author page
			////////////////////////////////////////////////////////////////////
			
			// Blog link
			$result_array['middle'] = $bcn_bloglink;
			// Author text
			$result_array['last']['prefix'] = $this->opt['author_prefix'];
			// Get the Author name, note it is an array
			$curauth = (get_query_var('author_name')) ? get_userdatabylogin(get_query_var('author_name')) : get_userdata(get_query_var('author'));
			// Get the Author display type
			$authdisp = $this->opt['author_display'];
			// Make sure user picks only safe values
			if ($authdisp == 'nickname' || $authdisp == 'nickname' || $authdisp == 'first_name' || $authdisp == 'last_name' || $authdisp == 'display_name')
			{
				$result_array['last']['title'] = $curauth->$authdisp;
			}
			$result_array['last']['suffix'] = $this->opt['author_suffix'];
			break; // end of case 'author'
			
		case 'singlepost':
			////////////////////////////////////////////////////////////////////
			// Get single blog post
			////////////////////////////////////////////////////////////////////

			$bcn_pagetitle = trim(wp_title('', false));	// page title, we do not use "$post->post_title" since it could display
														// 	wrong title if theme uses more than one LOOP.

			$result_array['middle'] = $bcn_bloglink;
			
			// Add category
			if($this->opt['singleblogpost_category_display'] === 'true') {
				// Apply limit to category if set
				if($this->opt['singleblogpost_category_maxdisp'] > 0)
				{
					$category_temp = explode(",", get_the_category_list(', '));
					$category_list = $category_temp[0];
					for($i=1; $i < $this->opt['singleblogpost_category_maxdisp']; $i++)
					{
						// Only go through if there is a category, else exit loop
						if($category_temp[$i]) {$category_list .= "," . $category_temp[$i];}
						else {$i = $this->opt['singleblogpost_category_maxdisp'] + 2;}
					}
				}
				else
				{
					$category_list = get_the_category_list(', ');
				}
				$category_temp = explode(",", get_the_category_list(', '));
				$result_array['middle'] .= $this->opt['separator'] . $this->opt['singleblogpost_category_prefix'] . $category_list . $this->opt['singleblogpost_category_suffix'];
			}
			
			$result_array['last']['prefix'] = $this->opt['singleblogpost_prefix'];

			// Restrict the length of the title... 
			$bcn_post_title = $bcn_pagetitle;
			if ( ($this->opt['posttitle_maxlen'] >= 1) and ( strlen($bcn_post_title) > $this->opt['posttitle_maxlen']) )  
				$bcn_post_title = substr($bcn_post_title, 0, $this->opt['posttitle_maxlen']-1) . '...';
			$result_array['last']['title'] = $bcn_post_title;

			$result_array['last']['suffix'] = $this->opt['singleblogpost_suffix'];

			break;
	
		case 'categories':
			////////////////////////////////////////////////////////////////////
			// Get Category and Parent Categories
			////////////////////////////////////////////////////////////////////
			$result_array['middle'] = $bcn_bloglink;
			$object = $wp_query->get_queried_object();

			// Get parents of current category
			$parent_id  = $object->category_parent;
			$cat_breadcrumbs = '';
			while ($parent_id) {
				$category   = get_category($parent_id);
				$cat_breadcrumbs = '<a href="' . get_category_link($category->cat_ID) . '" title="' . $this->opt['urltitle_prefix'] . $category->cat_name . $this->opt['urltitle_suffix'] . '">' . $category->cat_name . '</a>' . $this->opt['separator'] . $cat_breadcrumbs;
				$parent_id  = $category->category_parent;
			}

			$result_array['last']['prefix'] = $this->opt['archive_category_prefix'];
			$result_array['last']['prefix'] .= $cat_breadcrumbs;
			// Current Category 
			$result_array['last']['title'] = $object->name;
			$result_array['last']['suffix'] = $this->opt['archive_category_suffix'];
			break;

	
		case 'date':
			////////////////////////////////////////////////////////////////////
			// Get Blog Date Archive
			////////////////////////////////////////////////////////////////////

			$result_array['middle'] = $bcn_bloglink;
			if(is_day())
			{
				// -- Archive by day
				if($this->opt['archive_date_format'] == 'US')
				{
					$result_array['last']['prefix'] = $this->opt['archive_date_prefix'];
					$result_array['last']['title'] = '<a title="Browse to the ' .
						get_the_time('F') . ' ' . get_the_time('Y') . ' archive" href="' .
						get_year_link(get_the_time('Y')) . get_the_time('m') . '">' .
						get_the_time('F') . '</a>' . ' ' . get_the_time('d') . ', ' .
						' <a title="Browse to the ' . get_the_time('Y') . ' archive" href="' .
						get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>';
					$result_array['last']['suffix'] = $this->opt['archive_date_suffix'];
				}
				else if($this->opt['archive_date_format'] == 'ISO')
				{
					$result_array['last']['prefix'] = $this->opt['archive_date_prefix'];
					$result_array['last']['title'] = ' <a title="Browse to the ' .
						get_the_time('Y') . ' archive" href="' . get_year_link(get_the_time('Y')) .
						'">' . get_the_time('Y') . '</a> <a title="Browse to the ' .
						get_the_time('F') . ' ' . get_the_time('Y') . ' archive" href="' .
						get_year_link(get_the_time('Y')) . get_the_time('m') . '">' .
						get_the_time('F') . '</a>' . ' ' . get_the_time('d');
					$result_array['last']['suffix'] = $this->opt['archive_date_suffix'];
				}
				else
				{
					$result_array['last']['prefix'] = $this->opt['archive_date_prefix'];
					$result_array['last']['title'] = get_the_time('d') . ' ' .
						'<a title="Browse to the ' . get_the_time('F') . ' ' . get_the_time('Y') .
						' archive" href="' . get_year_link(get_the_time('Y')) . get_the_time('m') .
						'">' . get_the_time('F') . '</a>' . ' <a title="Browse to the ' . 
						get_the_time('Y') . ' archive" href="' . get_year_link(get_the_time('Y')) .
						'">' . get_the_time('Y') . '</a>';
					$result_array['last']['suffix'] = $this->opt['archive_date_suffix'];
				}
			}
			else if(is_month())
			{
				// -- Archive by month
				$result_array['last']['prefix'] = $this->opt['archive_date_prefix'];
				$result_array['last']['title'] = get_the_time('F') . ' ' . '<a title="Browse to the ' . 
					get_the_time('Y') . ' archive" href="' . get_year_link(get_the_time('Y')) . '">' . 
					get_the_time('Y') . '</a>';
				$result_array['last']['suffix'] = $this->opt['archive_date_suffix'];
			}
			else if(is_year())
			{
				// -- Archive by year
				$result_array['last']['prefix'] = $this->opt['archive_date_prefix'];
				$result_array['last']['title'] = get_the_time('Y');
				$result_array['last']['suffix'] = $this->opt['archive_date_suffix'];
			}
			break;
	
		case '404':
			////////////////////////////////////////////////////////////////////
			// Get 404 error page
			////////////////////////////////////////////////////////////////////

			$result_array['last']['title'] = $this->opt['title_404'];
		
			break;
	
		case 'tag':
			/////////////////////////////////////////////
			// Get Blog Tag Archive
			/////////////////////////////////////////////
			$object = $wp_query->get_queried_object();
			$result_array['middle'] = $bcn_bloglink;
			$result_array['last']['prefix'] = $this->opt['archive_tag_prefix'];
			$result_array['last']['title'] = $object->name;
			$result_array['last']['suffix'] = $this->opt['archive_tag_suffix'];
	
			break; 	
	

		case 'else':
			////////////////////////////////////////////////////////////////////
			// Get weblog article list (which is very often the front page of the blog)
			////////////////////////////////////////////////////////////////////
		
			$result_array['last']['title'] = $this->opt['title_blog'];

		} // switch

		////////////////////////////////////////////////////////////////////////////
		// Echo the result
		////////////////////////////////////////////////////////////////////////////
		// MIDDLE PART
		//		The first separator between HOME and the first entry
		$first_sep = '';	// display first separator only if HOME is disabled in the options AND it is a static front page
		if ( ($this->opt['static_frontpage'] === 'true') AND ($this->opt['home_display'] === 'true') ) {
			$first_sep = $this->opt['separator'];
		}
		//	get middle part and add separator(s)
		$middle_part = '';		
		if ($result_array['middle'] === false) {
			// there is no middle part...
		
			if ($result_array['last']['title'] === false)
				$first_sep = ''; // we are on home.

		} else {
			// There is a middle part...
			$middle_part = $result_array['middle'] . $this->opt['separator'];
		}

		// LAST PART
		$last_part = '';
		if ($result_array['last']['prefix'] !== false)
			$last_part .= $result_array['last']['prefix'];

		if ($result_array['last']['title'] !== false)
			$last_part .= $curitem_urlprefix . $result_array['last']['title'] . $curitem_urlsuffix;

		if ($result_array['last']['suffix'] !== false)
			$last_part .= $result_array['last']['suffix'];
		// Slight hack here
		global $bcn_version;
		// ECHO
		$result = "\n" . "<!-- Breadcrumb, generated by Breadcrumb NavXT " . $bcn_version . " - http://mtekk.weblogs.us/code -->" . "\n"; // Please do not remove this line.

		if ($this->opt['static_frontpage'] === 'false') {
			if ( ($swg_type === 'page') or ($swg_type === 'search') or ($swg_type === '404') ) {
				$result .= $bcn_bloglink . $this->opt['separator'];
			}
		}

		$result .= $bcn_homelink . $first_sep . $middle_part . $this->opt['current_item_style_prefix'] . $last_part . $this->opt['current_item_style_suffix'] . "\n";
		echo $result;

	} // END function display


} // END class breadcrumb_navigation_xt
//Serge's PHP4 patch
/////////////////////////////////
//Hook for the admin interface
/////////////////////////////////

//Minimum required user level to get to the admin interface
$breadcrumb_nav_xt_admin_req = 8;
@include(dirname(__FILE__)."/breadcrumb-navigation-xt-admin.php");
?>
<?php
/*
Plugin Name: Ping/Track/Comment Count
Plugin URI: http://txfx.net/code/wordpress/ping-track-comment-count/
Version: 1.1
Description: Provides unctions that return or display the number of trackbacks, pingbacks, comments or combined pings recieved by a given post.  Other authors: Chris J. Davis, Scott "Skippy" Merrill
Author: Mark Jaquith
Author URI: http://markjaquith.com/
*/

/*

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA	 02110-1301, USA.

*/

function get_comment_type_count($type='all', $post_id = 0) {
	global $cjd_comment_count_cache, $id, $post;
	if ( !$post_id )
		$post_id = $post->ID;
	if ( !$post_id )
		return;

	if ( !isset($cjd_comment_count_cache[$post_id]) ) {
		$p = get_post($post_id);
		$p = array($p);
		update_comment_type_cache($p);
	}

	if ( $type == 'pingback' || $type == 'trackback' || $type == 'comment' )
		return $cjd_comment_count_cache[$post_id][$type];
	elseif ( $type == 'ping' )
		return $cjd_comment_count_cache[$post_id]['pingback'] + $cjd_comment_count_cache[$post_id]['trackback'];
	else
		return array_sum((array) $cjd_comment_count_cache[$post_id]);

}

function comment_type_count($type = 'all', $post_id = 0) {
		echo get_comment_type_count($type, $post_id);
}


function update_comment_type_cache(&$queried_posts) {
	global $cjd_comment_count_cache, $wpdb;

	if ( !$queried_posts )
		return $queried_posts;


	foreach ( (array) $queried_posts as $post )
		if ( !isset($cjd_comment_count_cache[$post->ID]) )
			$post_id_list[] = $post->ID;

	if ( $post_id_list ) {
		$post_id_list = implode(',', $post_id_list);

		foreach ( array('', 'pingback', 'trackback') as $type ) {
			$counts = $wpdb->get_results("SELECT ID, COUNT( comment_ID ) AS ccount
			FROM $wpdb->posts
			LEFT JOIN $wpdb->comments ON ( comment_post_ID = ID AND comment_approved = '1' AND comment_type='$type' )
			WHERE post_status = 'publish' AND ID IN ($post_id_list)
			GROUP BY ID");

			if ( $counts ) {
				if ( '' == $type )
					$type = 'comment';
				foreach ( $counts as $count )
					$cjd_comment_count_cache[$count->ID][$type] = $count->ccount;
			}
		}
	}
	return $queried_posts;
}

add_filter('the_posts', 'update_comment_type_cache');
?>