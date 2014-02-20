<?php
require_once (ABSPATH . WPINC . '/class-snoopy.php');

$current = 'Sca4ered "Polynikes"';
function k2info($show='') {
global $current;
	switch($show) {
	case 'version' :
    	$info = 'Beta Two '. $current;
    	break;
    case 'scheme' :
    	$info = bloginfo('template_url') . '/styles/' . get_option('k2scheme');
    	break;
    }
    echo $info;
}

function k2update() {
	if ( !empty($_POST) ) {
		if ( isset($_POST['k2scheme_file']) ) {
			$k2scheme_file = $_POST['k2scheme_file'];
			update_option('k2scheme', $k2scheme_file, '','');
		}
		if ( isset($_POST['livesearch']) ) {
			$search = $_POST['livesearch'];
			update_option('k2livesearch', $search, '','');
		}
		if ( isset($_POST['livecommenting']) ) {
			$commenting = $_POST['livecommenting'];
			update_option('k2livecommenting', $commenting, '','');
		}
		if ( isset($_POST['widthtype']) ) {
			$widthtype = $_POST['widthtype'];
			update_option('k2widthtype', $widthtype, '','');
		}
		if ( isset($_POST['asides_text']) ) {
			$asides_text = $_POST['asides_text'];
			update_option('k2asidescategory', $asides_text, '','');
		}
		if ( isset($_POST['asidesposition']) ) {
			$asidesposition = $_POST['asidesposition'];
			update_option('k2asidesposition', $asidesposition, '','');
		}
		if ( isset($_POST['asidesnumber']) ) {
			$asidesnumber = $_POST['asidesnumber'];
			update_option('k2asidesnumber', $asidesnumber, '','');
		}
		if ( isset($_POST['about_text']) ) {
			$about = $_POST['about_text'];
			update_option('k2aboutblurp', $about, '','');
		}
		if ( isset($_POST['deliciousname']) ) {
			$name = $_POST['deliciousname'];
			update_option('k2deliciousname', $name, '','');
		}
		if ( isset($_POST['archives']) ) {
			$add = $_POST['archives'];
			update_option('k2archives', $add, '','');
			create_archive();
		} else {
		// thanks to Michael Hampton, http://www.ioerror.us/ for the assist
			$remove = '';
			update_option('k2archives', $remove, '','');
			delete_archive();
		}

		if ( isset($_POST['configela']) ) {
			if (!setup_archive()) unset($_POST['configela']);
		}
	}
}

function create_archive() {
global $wpdb, $user_ID;
get_currentuserinfo();
	$check = $wpdb->query("SELECT * from $wpdb->posts WHERE post_title = 'Archives'");
		if(!$check) {
	$message = "Do not edit this page";
	$title_message = 'Archives';
	$content = apply_filters('content_save_pre', $message);
	$post_title = apply_filters('title_save_pre', $title_message);
	$now = current_time('mysql');
	$now_gmt = current_time('mysql', 1);
	$post_author = $user_ID;
	$id_result = $wpdb->get_row("SHOW TABLE STATUS LIKE '$wpdb->posts'");
	$post_ID = $id_result->Auto_increment;
	$post_name = sanitize_title($post_title, $post_ID);
	$ping_status = get_option('default_ping_status');
	$comment_status = get_option('default_comment_status');
	
	$postquery ="INSERT INTO $wpdb->posts
			(ID, post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt,  post_status, comment_status, ping_status, post_password, post_name, to_ping, post_modified, post_modified_gmt, post_parent, menu_order)
			VALUES
			('$post_ID', '$post_author', '$now', '$now_gmt', '$content', '$post_title', '', 'static', '$comment_status', '$ping_status', '', '$post_name', '', '$now', '$now_gmt', '', '')";
	$result = $wpdb->query($postquery);
	$metaquery = "INSERT INTO $wpdb->postmeta(meta_id, post_id, meta_key, meta_value) VALUES('', '$post_ID', '_wp_page_template', 'page-archives.php')";
	$result2 = $wpdb->query($metaquery);
	}
}

function delete_archive() {
global $wpdb;
	$check = $wpdb->query("SELECT * from $wpdb->posts WHERE post_title = 'Archives'");
		if($check) {
	$burninate = $wpdb->query("DELETE from $wpdb->posts WHERE post_title = 'Archives' and post_status = 'static'");
	$result = $wpdb->query($burninate);
	}
}

function setup_archive() {
	global $wpdb;

	if (file_exists(ABSPATH . 'wp-content/plugins/UltimateTagWarrior/ultimate-tag-warrior-core.php') && in_array('UltimateTagWarrior/ultimate-tag-warrior.php', get_option('active_plugins'))) {
		$menu_order="chrono,tags,cats";
	} else {
		$menu_order="chrono,cats";
	}

	$initSettings = array(

	// we always set the character set from the blog settings
		'newest_first' => 0,
		'num_entries' => 1,
		'num_entries_tagged' => 0,
		'num_comments' => 1,
		'fade' => 1,
		'hide_pingbacks_and_trackbacks' => 1,
		'use_default_style' => 1,
		'paged_posts' => 1,
		'selected_text' => '',
		'selected_class' => 'selected',
		'comment_text' => '<span>%</span>',
		'number_text' => '<span>%</span>',
		'number_text_tagged' => '(%)',
		'closed_comment_text' => '<span>%</span>',
		'day_format' => 'jS',
		'error_class' => 'alert',
	// allow truncating of titles
		'truncate_title_length' => 0,
		'truncate_cat_length' => 25,
		'truncate_title_text' => '&#8230;',
		'truncate_title_at_space' => 1,
		'abbreviated_month' => 1,
		'tag_soup_cut' => 0,
		'tag_soup_X' => 0,
	// paged posts related stuff
		'paged_post_num' => 15,
		'paged_post_next' => '&laquo; previous 15 posts',
		'paged_post_prev' => 'next 15 posts &raquo;',
	// default text for the tab buttons
		'menu_order' => $menu_order,
		'menu_month' => 'Chronology',
		'menu_cat' => 'Taxonomy',
		'menu_tag' => 'Folksonomy',
		'before_child' => '&nbsp;&nbsp;&nbsp;',
		'after_child' => '',
		'loading_content' => '<img src="'.get_bloginfo('template_url').'/images/spinner.gif" class="elaload" alt="Spinner" />',
		'idle_content' => '',
		'excluded_categories' => '0');

	if (function_exists('af_ela_set_config')) {
		$ret = af_ela_set_config($initSettings);
	}

	return $ret;
}

// if we can't find k2 installed lets go ahead and install all the options that run K2.  This should run only one more time for all our existing users, then they will just be getting the upgrade function if it exists.

if (!get_option('k2installed')) {
add_option('k2installed', $current, 'This options simply tells me if K2 has been installed before', $autoload);
add_option('k2aboutblurp', 'This is the about text', 'Allows you to write a small blurp about you and your blog, which will be put on the frontpage', $autoload);
add_option('k2asidescategory', '0', 'A category which will be treated differently from other categories', $autoload);
add_option('k2asidesposition', '0', 'Whether to use inline or sidebar asides', $autoload);
add_option('k2livesearch', 'live', "If you don't trust JavaScript and Ajax, you can turn off LiveSearch. Otherwise I suggest you leave it on", $autoload); // (live & classic)
add_option('k2asidesnumber', '3', 'The number of Asides to show in the Sidebar. Default is 3.', $autoload);
add_option('k2widthtype', 'flexible', "Determines whether to use flexible or fixed width.", $autoload); // (flexible & fixed)
add_option('k2deliciousname', '', 'Makes use of Alexander Malovs Delicious plugin to show the delicious links on the sidebar.', $autoload);
add_option('k2archives', '', 'Set whether K2 has a Live Archive page', $autoload);
add_option('k2scheme', '', 'Choose the Scheme you want K2 to use', $autoload);
add_option('k2livecomments', '0', "If you don't trust JavaScript and Ajax, you can turn off Live Commenting. Otherwise I suggest you leave it on", $autoload);
}

// Here we handle upgrading our users with new options and such.  If k2installed is in the DB but the version they are running is lower than our current version, trigger this event.

	elseif (get_option('k2installed') < $current) {
	/* Do something! */
	//add_option('k2upgrade-test', 'this is the text', 'Just testing', $autoload);
}

// Let's add the options page.
add_action ('admin_menu', 'k2menu');

$k2loc = '../themes/' . basename(dirname($file)); 

function k2menu() {
	add_submenu_page('themes.php', 'K2x Options', 'K2x Options', 5, $k2loc . 'functions.php', 'menu');
}

function menu() {
	load_plugin_textdomain('k2options');
	//this begins the admin page
?>


<?php if (isset($_POST['configela'])) : ?>
	<div class="updated">
		<p><?php _e('ELA Options for K2 have been set'); ?></p>
	</div>
<?php endif; ?>


<?php if (isset($_POST['Submit'])) : ?>
	<div class="updated">
		<p><?php _e('K2x Options have been updated'); ?></p>
	</div>
<?php endif; ?>


<div class="wrap">


<h2><?php _e('K2x Options'); ?></h2>
<form name="dofollow" action="" method="post">
  <input type="hidden" name="action" value="<?php k2update(); ?>" />
  <input type="hidden" name="page_options" value="'dofollow_timeout'" />
  <table width="700px" cellspacing="2" cellpadding="5" class="editform">
  <tr valign="top">
		<th scope="row"><?php echo __('K2 Scheme'); ?></th>
		<td>
			<label for="k2scheme_file">

			<?php
			global $wpdb;
			$name = get_option('k2scheme');
			if ($name != '') {
				$scheme_title = $name;
			} else {
				$scheme_title ='change me';
			}
			?>

			<select name="k2scheme_file" id="k2scheme_file" style="width: 300px;">
			<option value="<?php echo get_option('k2scheme'); ?>"><?php echo$scheme_title; ?></option>
			<option value="-----">----</option>
			<option value="">No Scheme</option>

			<?php
			$scheme_dir = @ dir(ABSPATH . '/wp-content/themes/' . get_template() . '/styles');

			if ($scheme_dir) {
				while(($file = $scheme_dir->read()) !== false) {
			  		if (!preg_match('|^\.+$|', $file) && preg_match('|\.css$|', $file)) 
						$scheme_files[] = $file;
					}
				}
				if ($scheme_dir || $scheme_files) {
					foreach($scheme_files as $scheme_file) {
				 	echo '<option value="' . $scheme_file . '">' . $scheme_file . '</option>';
				}
				echo '</select>';
			} 
			?>
			
			<p><b>Choose Sca4ered.css</b></p>
		</td>
		</tr>
 		<tr valign="top">
		<th scope="row" width="20%"><?php echo __('Live Search'); ?></th>
		<td>
			<input name="livesearch" id="classic-search" type="radio" value="0" <?php checked('0', get_option('k2livesearch')); ?> /> 
			<label for="classic"><?php _e('Enable Livesearch (default)') ?></label><br />
			<input name="livesearch" id="live-search" type="radio" value="1" <?php checked('1', get_option('k2livesearch')); ?> /> 
			<label for="live"><?php _e('Disable Livesearch') ?></label>
			<p><small>Livesearch is a javascript powered search-as-you-type solution. <a href="http://blog.bitflux.ch/wiki/LiveSearch">Would you like to know more?</a>.</small></p>
		</td>
		</tr>
<tr valign="top">
		<th scope="row" width="20%"><?php echo __('AJAX Commenting'); ?></th>
		<td>
			<input name="livecommenting" id="classic-commenting" type="radio" value="0" <?php checked('0', get_option('k2livecommenting')); ?> /> 
			<label for="classic-commenting"><?php _e('Enable AJAX Commenting (default)') ?></label><br />
			<input name="livecommenting" id="live-commenting" type="radio" value="1" <?php checked('1', get_option('k2livecommenting')); ?> /> 
			<label for="live-commenting"><?php _e('Disable AJAX Commenting') ?></label>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row"><?php echo __('Width Type'); ?></th>
		<td>
			<input name="widthtype" id="fixed" type="radio" value="1" <?php checked('1', get_option('k2widthtype')); ?> /> 
			<label for="fixed"><?php _e('Fixed Width (default)') ?></label><br />
			<input name="widthtype" id="flexible" type="radio" value="0" <?php checked('0', get_option('k2widthtype')); ?> /> 
			<label for="flexible"><?php _e('Flexible Width') ?></label>
			<p>
			<small>Flexible Width has the K2 page adjust itself according to the width of the browser, up to a maximum and down to a minimum width, so as to not entirely break readability at very high resolutions. Fixed width fixes K2's page width at 780px.<br /><em><strong>Beta Note:</strong> Doesn't work in Internet Explorer as far as I know. Suggestions are welcome.</em></small>
			</p>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row"><?php echo __('Asides'); ?></th>
		<td>
			<input name="asidesposition" id="primary-asides" type="radio" value="0" <?php checked('0', get_option('k2asidesposition')); ?> /> 
			<label for="primary-asides"><?php _e('Inline Asides') ?></label><br />
			<input name="asidesposition" id="secondary-asides" type="radio" value="1" <?php checked('1', get_option('k2asidesposition')); ?> /> 
			<label for="secondary-asides"><?php _e('Sidebar Asides') ?></label>
			<p><small>Determines whether Asides (if they are active) are shown inline or on the sidebar. <br /></small><b>Sca4ered was built around sidebar asides.</b></p>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row"><?php echo __('Asides Category'); ?></th>
		<td>
			<label for="asides_text">
			<?php
			global $wpdb;
			$id = get_option('k2asidescategory');
			if ($id != 0) {
			$asides_title = $wpdb->get_var("SELECT cat_name from $wpdb->categories WHERE cat_ID = $id");
			} else {
				$asides_title='No Asides';
				}
			$asides_cats = $wpdb->get_results("SELECT * from $wpdb->categories");
			?>
			<select name="asides_text" id="asides_text" style="width: 300px;">
			<option value="<?php echo get_option('k2asidescategory'); ?>"><?php echo $asides_title; ?></option>
			<option value="-----">----</option>
			<option value="0">No Asides</option>
			<?php
			foreach ($asides_cats as $cat) {
			echo '<option value="' . $cat->cat_ID . '">' . $cat->cat_name . '</option>';
            }
            ?>
			</select>
			<p><small>Just select a category and it will be displayed using <a href="http://photomatt.net/2004/05/19/asides/">Matt's Asides Technique</a></small></p>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row"><?php echo __('Asides Number'); ?></th>
		<td>
			<input name="asidesnumber" id="asidesnumber" type="text" value="<?php echo get_option('k2asidesnumber'); ?>" size="2" /> 
			<p><small>Set the number of Asides to show in the Sidebar. Defaults to 3.</small></p>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row"><?php echo __('About Text'); ?></th>
		<td>
			<label for="about_text">
			<textarea name="about_text" style="width: 98%;" rows="5" id="about_text"><?php echo stripslashes(get_option('k2aboutblurp')); ?></textarea>
			<p><small>Enter a blurp about yourself here, and it will show up on the frontpage. Deleting the content disables the about blurp.</small></p>
		</td>
		</tr>
		<?php if (function_exists('delicious')) { ?> 
		<tr valign="top">
		<th scope="row"><?php echo __('Delicious User Name'); ?></th>
		<td>
			<label for="deliciousname"><?php echo __('Delicious User Name'); ?></label>
			<input name="deliciousname" style="width: 300px;" id="deliciousname" value="<?php echo get_option('k2deliciousname'); ?>">
			<p><small>Enter your delicious username here, to make use of <a href="http://www.w-a-s-a-b-i.com/archives/2004/10/15/delisious-cached/">Alexander Malov's del.icio.us plugin</a></small></p>
		</td>
		</tr>
		<?php } ?>
		<tr valign="top">
		<th scope="row"><?php echo __('Archives Page'); ?></th>
		<td>
			<input name="archives" id="add-archive" type="checkbox" value="add_archive" <?php checked('add_archive', get_option('k2archives')); ?> />
			<label for="add-archives"><?php _e('Enable the K2 Archives page') ?></label>
			<p><small>Enabling this checkbox will create an Archives Page, which will show up in your blog menu as the first page.</small></p>
		</td>
		</tr>

		<?php if (function_exists('af_ela_set_config')) { ?>
		<tr valign="top">
			<th scope="row"><?php echo __('Set Extended Live Archive'); ?></th>
			
			<td>
				<input name="configela" id="configela" type="submit" value="<?php _e('Setup ELA for K2 Archives page') ?>" />
				<p><small>Set the options of Arnaud's brilliant <a href="http://www.sonsofskadi.net/index.php/extended-live-archive/" title="Find out more about ELA">Extended Live Archives</a> for K2.</small></p>
			</td>
		</tr>	
		<?php } ?>

		</table>

	
	<p class="submit"><input type="submit" name="Submit" value="<?php _e('Update Options') ?> &raquo;" /></p>

</form>
</div>

<div class="wrap">
	<p style="text-align: center;">Help to be had at the <a href="http://www.flickr.com/groups/binarybonsai/discuss/" title="K2 support forums">K2 Support Forums</a> or in the <a href="http://binarybonsai.com/wordpress/k2/features-and-plugins/" title="Documentation">K2 Documentation</a>.</p>
</div>

<?php } // this ends the admin page ?>
