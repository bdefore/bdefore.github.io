<?php

/*
Plugin Name: FAlbum
Version: 0.5.6
Plugin URI: http://www.randombyte.net/
Description: A plugin for displaying your <a href="http://www.flickr.com/">Flickr</a> photosets and photos in a gallery format on your Wordpress site.
Author: Elijah Cornell
Author URI: http://www.randombyte.net/

Change log:
0.5.6 - Change Flickr urls to new struture
        Fixed issue for .httaccess not being updated every time
0.5.5 - Fixed issue when album title contained non-ascii charactors
Fixed issue with viewing albums in non-friendly url mode
Fixed error when returning EXIF data
Fixed issue with slideshow no showing
Fixed issue where the same XPath object was being created twice per request
0.5.4 - Code clean up / CSS clean up (K2 theme support)
Fixed a caching issue during authentication
Fixed a couple paging issues cause be new friendly urls
Lowered WP FAlbum Options page to level 8
Moved WP FAlbum Options page to WP Options tab
Cleaner error messages
0.5.3 - Fix friendly url bug - if multiple album/photo names where the same
Fix added check to use curl if available
Revalidated to XHTML 1.0 Strict
0.5.2 - Friendly url improvments
Private photos @ set WP user levels
Added option to disable or limit the number of recent photos
Fixed minor page nubering issue
Fixed issue on photo page when many tags were shown and not properly wrapping
Added fa_showRandom method
0.5.1 - Fixed php tag issue on admin page (missing text on admin page)
Fix friendly url / .htaccess issue
Photoset/tag name now shows in breadcrum instead of "Index"
0.5 - 	Added tag cloud / Dutch localization
0.4.4 - Tag search fix / German localization
0.4.3 - XML errors, html in titles, descriptions, and notes fixes
0.4.2 -	Friendly URL fixes, XML error fixes
0.4.1 -	Localization clean up, added option to disable dropshadows
0.4 -	Localization, many bug fixes
0.3 -	Switched to use Flickr new auth api
0.2 -	Added Admin page
Switched caching to be stored in the database
0.1 - 	Init Release

Copyright (c) 2005
Released under the GPL license
http://www.gnu.org/licenses/gpl.txt

This file is part of WordPress.
WordPress is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

include_once (ABSPATH.'wp-includes/streams.php');
include_once (ABSPATH.'wp-includes/gettext.php');

require_once ('falbum.php');

define('FALBUM_DOMAIN', '/falbum/lang/falbum');

load_plugin_textdomain(FALBUM_DOMAIN);

// plugin menu
function falbum_add_pages() {
	if (function_exists('add_options_page')) {
		add_submenu_page('options-general.php', 'FAlbum', 'FAlbum', 8, basename(__FILE__), 'falbum_options_page');
	}
}

function falbum_init() {
	global $wpdb, $table_prefix, $user_level;
	$fa_table = $table_prefix."falbum_cache";
	get_currentuserinfo();
	if ($user_level < 8) {
		return;
	}

	if ($wpdb->get_var("show tables like '$fa_table'") != $fa_table) {
		$sql = "CREATE TABLE ".$fa_table." (
					ID varchar(40) PRIMARY KEY,
					data text,
					expires datetime
					)";
		require_once (ABSPATH.'wp-admin/upgrade-functions.php');
		dbDelta($sql);
	}
}

function falbum_options_page() {

	global $is_apache, $wpdb, $table_prefix;
	$fa_table = $table_prefix."falbum_cache";
	$falbum_options = get_option('falbum_options');

	$ver = $falbum_options['falbum_version'];
	if ($ver != FALBUM_VERSION) {
		falbum_init();
	}

	// Setup htaccess 
	$urlinfo = parse_url(get_settings('siteurl'));
	$path = $urlinfo['path'];

	$furl = trailingslashit($falbum_options['falbum_url_root']);
	if ($furl {
		0 }
	== "/") {
		$furl = substr($furl, 1);
	}
	if (strpos('/'.$furl, $path.'/') === false) {
		$home_path = parse_url("/");
		$home_path = $home_path['path'];
		$root2 = str_replace($_SERVER["PHP_SELF"], '', $_SERVER["SCRIPT_FILENAME"]);
		$home_path = trailingslashit($root2.$home_path);
	} else {
		$furl = str_replace($path.'/', '', '/'.$furl);
		$home_path = get_home_path();
	}
	if ($furl {
		0 }
	== "/") {
		$furl = substr($furl, 1);
	}
	if ((!file_exists($home_path.'.htaccess') && is_writable($home_path)) || is_writable($home_path.'.htaccess')) {
		$writable = true;
	} else {
		$writable = false;
	}

	$rewriteRule = "<IfModule mod_rewrite.c>\n"."RewriteEngine On\n"."RewriteRule ^".$furl."?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?$ ".$path."/wp-content/plugins/falbum/falbum-wp.php?$1=$2&$3=$4&$5=$6&$7=$8 [QSA,L]\n"."</IfModule>";

	//echo '<pre>$path-'.$path.'/'.'</pre>';
	//echo '<pre>$furl-'.'/'.$furl.'</pre>';
	//echo '<pre>1-'.strpos('/'.$furl, $path.'/').'</pre>';
	//echo '<pre>$furl-'.$furl.'</pre>';
	//echo '<pre>'.$rewriteRule.'</pre>';

	// posting logic
	if (isset ($_POST['Submit'])) {

		$falbum_options['falbum_tsize'] = $_POST['falbum_tsize'];
		$falbum_options['falbum_show_private'] = $_POST['falbum_show_private'];
		$falbum_options['falbum_friendly_urls'] = $_POST['falbum_friendly_urls'];
		$falbum_options['falbum_url_root'] = $_POST['falbum_url_root'];
		$falbum_options['falbum_albums_per_page'] = $_POST['falbum_albums_per_page'];
		$falbum_options['falbum_photos_per_page'] = $_POST['falbum_photos_per_page'];
		$falbum_options['falbum_max_photo_width'] = $_POST['falbum_max_photo_width'];
		$falbum_options['falbum_display_dropshadows'] = $_POST['falbum_display_dropshadows'];
		$falbum_options['falbum_display_sizes'] = $_POST['falbum_display_sizes'];
		$falbum_options['falbum_display_exif'] = $_POST['falbum_display_exif'];
		$falbum_options['falbum_wp_user_level'] = $_POST['falbum_wp_user_level'];
		$falbum_options['falbum_number_recent'] = $_POST['falbum_number_recent'];

		$furl = $falbum_options['falbum_url_root'];
		$pos = strpos($furl, '/');
		if ($furl {
			0 }
		!= "/") {
			$furl = '/'.$furl;
		}
		$pos = strpos($furl, '.php');
		if ($pos === false) {
			$furl = trailingslashit($furl);
		}

		$falbum_options['falbum_url_root'] = $furl;

		update_option('falbum_options', $falbum_options);

		$updateMessage .= __('Options saved', FALBUM_DOMAIN)."<br /><br />";

		if ($falbum_options['falbum_friendly_urls'] == 'true') {

			if ($is_apache) {

				$urlinfo = parse_url(get_settings('siteurl'));
				$path = $urlinfo['path'];

				$furl = trailingslashit($falbum_options['falbum_url_root']);
				if ($furl {
					0 }
				== "/") {
					$furl = substr($furl, 1);
				}

				//echo '<pre>$path-'.$path.'/'.'</pre>';
				//echo '<pre>$furl-'.'/'.$furl.'</pre>';
				//echo '<pre>1-'.strpos('/'.$furl, $path.'/').'</pre>';

				$pos = strpos('/'.$furl, $path.'/');

				if ($path != '/' && strpos('/'.$furl, $path.'/') === false) {
					//use root .htaccess file
					//echo '<pre>root</pre>';
					$home_path = parse_url("/");
					$home_path = $home_path['path'];
					$root2 = str_replace($_SERVER["PHP_SELF"], '', $_SERVER["SCRIPT_FILENAME"]);
					$home_path = trailingslashit($root2.$home_path);
				} else {
					//use wp .htaccess file
					//echo '<pre>wp</pre>';
					if (strlen($path) > 1) {
						$furl = str_replace($path.'/', '', '/'.$furl);
					}
					$home_path = get_home_path();
				}
				if ((!file_exists($home_path.'.htaccess') && is_writable($home_path)) || is_writable($home_path.'.htaccess')) {
					$writable = true;
				} else {
					$writable = false;
				}
				if ($furl {
					0 }
				== "/") {
					$furl = substr($furl, 1);
				}

				$rewriteRule = "<IfModule mod_rewrite.c>\n"."RewriteEngine On\n"."RewriteRule ^".$furl."?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?$ ".$path."/wp-content/plugins/falbum/falbum-wp.php?$1=$2&$3=$4&$5=$6&$7=$8 [QSA,L]\n"."</IfModule>";

				//echo '<pre>'.$rewriteRule.'</pre>';

				if ($writable) {
					$rules = explode("\n", $rewriteRule);
					falbum_insert_with_markers($home_path.'.htaccess', 'FAlbum', $rules);
					$updateMessage .= __('Mod rewrite rules updated', FALBUM_DOMAIN)."<br /><br />";
				}
			}
			$wpdb->query("DELETE from ".$fa_table."");
			$updateMessage .= __('Cache cleared', FALBUM_DOMAIN)."<br />";
		} else {
			if ($writable) {
				falbum_insert_with_markers($home_path.'.htaccess', 'FAlbum', explode("\n", ""));
			}
		}
	}

	if (isset ($_POST['ClearToken'])) {
		$falbum_options['falbum_token'] = null;
		update_option('falbum_options', $falbum_options);
		$updateMessage .= __('Flickr authorization reset', FALBUM_DOMAIN)."<br />";
	}

	if (isset ($_POST['ClearCache'])) {
		$wpdb->query("DELETE from ".$fa_table."");
		$updateMessage .= __('Cache cleared', FALBUM_DOMAIN)."<br />";
	}

	if (isset ($_POST['GetToken'])) {
		$frob2 = $_POST['frob'];
		$url = 'http://flickr.com/services/rest/?method=flickr.auth.getToken&api_key='.FALBUM_API_KEY.'&frob='.$frob2;
		$parms = 'api_key'.FALBUM_API_KEY.'frob'.$frob2.'methodflickr.auth.getToken';
		$url = $url.'&api_sig='.md5(FALBUM_SECRET.$parms);

		//echo '<pre>'.htmlentities($url).'</pre>';

		$resp = fa_fopen_url($url, 0);
		$xpath = fa_parseXPath($resp);

		//echo '<pre>'.htmlentities($resp).'</pre>';
		//echo '<pre>'.htmlentities($status).'</pre>';

		if (is_a($xpath, 'XPath')) {
			//echo '<pre>'.htmlentities($resp).'</pre>';
			$token = $xpath->getData("/rsp/auth/token");
			$nsid = $xpath->getData("/rsp/auth/user/@nsid");

			$falbum_options['falbum_token'] = $token;
			$falbum_options['falbum_nsid'] = $nsid;

			update_option('falbum_options', $falbum_options);

			$updateMessage .= __('Successfully set token', FALBUM_DOMAIN)."<br />";
		} else {
			$updateMessage .= __('You have not Authorized Falbum. Please perform Step 1.', FALBUM_DOMAIN);
			$updateMessage .= "<br /><br />Flickr message: $xpath";
		}
	}

	if (isset ($updateMessage)) {
?> <div class="updated"><p><strong><?php echo $updateMessage?></strong></p></div> <?php

	}

	//Init Settings
	if (!isset ($falbum_options['falbum_tsize']) || $falbum_options['falbum_tsize'] == "") {
		$falbum_options['falbum_tsize'] = "s";
	}
	if (!isset ($falbum_options['falbum_show_private']) || $falbum_options['falbum_show_private'] == "") {
		$falbum_options['falbum_show_private'] = "false";
	}
	if (!isset ($falbum_options['falbum_friendly_urls']) || $falbum_options['falbum_friendly_urls'] == "") {
		$falbum_options['falbum_friendly_urls'] = "false";
	}
	if (!isset ($falbum_options['falbum_url_root']) || $falbum_options['falbum_url_root'] == "") {
		$falbum_options['falbum_url_root'] = $path."/wp-content/plugins/falbum/falbum-wp.php";
	}
	if (!isset ($falbum_options['falbum_albums_per_page']) || $falbum_options['falbum_albums_per_page'] == "") {
		$falbum_options['falbum_albums_per_page'] = "10";
	}
	if (!isset ($falbum_options['falbum_photos_per_page']) || $falbum_options['falbum_photos_per_page'] == "") {
		$falbum_options['falbum_photos_per_page'] = "45";
	}
	if (!isset ($falbum_options['falbum_max_photo_width']) || $falbum_options['falbum_max_photo_width'] == "") {
		$falbum_options['falbum_max_photo_width'] = "500";
	}
	if (!isset ($falbum_options['falbum_display_dropshadows']) || $falbum_options['falbum_display_dropshadows'] == "") {
		$falbum_options['falbum_display_dropshadows'] = "-nods";
	}
	if (!isset ($falbum_options['falbum_display_sizes']) || $falbum_options['falbum_display_sizes'] == "") {
		$falbum_options['falbum_display_sizes'] = "false";
	}
	if (!isset ($falbum_options['falbum_display_exif']) || $falbum_options['falbum_display_exif'] == "") {
		$falbum_options['falbum_display_exif'] = "false";
	}
	if (!isset ($falbum_options['falbum_wp_user_level']) || $falbum_options['falbum_wp_user_level'] == "") {
		$falbum_options['falbum_wp_user_level'] = "10";
	}
	if (!isset ($falbum_options['falbum_number_recent']) || $falbum_options['falbum_number_recent'] == "") {
		$falbum_options['falbum_number_recent'] = "-1";
	}
?>


<div class="wrap">
<?php

	//echo '<pre>data-'.htmlentities($falbum_options['falbum_token']).'</pre>';
	//echo '<pre>data-'.htmlentities($falbum_options['falbum_nsid']).'</pre>';
?>

  <h2><?php _e('FAlbum Options', FALBUM_DOMAIN);?></h2>
    <form method=post action="<?php echo $_SERVER['PHP_SELF']; ?>?page=falbum-plugin.php">
        <input type="hidden" name="update" value="true">
                       
        <?php if (!isset($falbum_options['falbum_token']) || $falbum_options['falbum_token'] == '' ) { ?>

       <fieldset class="options">
       <legend><?php _e('Initial Setup', FALBUM_DOMAIN);?></legend>
        
               <?php


	$url = 'http://flickr.com/services/rest/?method=flickr.auth.getFrob&api_key='.FALBUM_API_KEY;
	$parms = 'api_key'.FALBUM_API_KEY.'methodflickr.auth.getFrob';
	$url = $url.'&api_sig='.md5(FALBUM_SECRET.$parms);
	//echo '<pre>$url-'.htmlentities($url).'</pre>';

	$resp = fa_fopen_url($url, 0);
	$xpath = fa_parseXPath($resp);

	//echo "Status: $status";
	//echo '<pre>$resp-'.htmlentities($resp).'</pre>';

	if (is_a($xpath, 'XPath')) {

		$frob = $xpath->getData("/rsp/frob");

		//echo '<pre>$frob-'.htmlentities($frob).'</pre>';

		$link = 'http://flickr.com/services/auth/?api_key='.FALBUM_API_KEY.'&frob='.$frob.'&perms=read';
		$parms = 'api_key'.FALBUM_API_KEY.'frob'.$frob.'permsread';
		$link .= '&api_sig='.md5(FALBUM_SECRET.$parms);
?>
       
		       <input type="hidden" name="frob" value="<?php echo $frob?>">
		       <p>	      
		       <?php _e('Please complete the following step to allow FAlbum to access your Flickr photos.', FALBUM_DOMAIN);?>
		       </p>
       
		       <p>
		       <?php _e('Step 1:', FALBUM_DOMAIN);?> <a href="<?php echo $link?>" target="_blank"><?php _e('Authorize FAlbum with access your Flickr account', FALBUM_DOMAIN);?></a>
		       </p>
       	       	
       	 
		       <p>
		       <?php _e('Step 2:', FALBUM_DOMAIN);?> <input type="submit" name="GetToken" value="<?php _e('Get Authentication Token', FALBUM_DOMAIN);?>" />
		       </p>
	       <?php

	} else {
		echo "<p>Error: $xpath </p>";
	}
?>

       	
                       
      </fieldset>
      
      	<?php } else { ?>
      
      
		<fieldset class="options">
		<legend><?php _e('FAlbum Admin', FALBUM_DOMAIN);?></legend>
         
		<p>
		<input type="submit" name="ClearCache" value="<?php _e('Clear Cache', FALBUM_DOMAIN);?>" />
		&nbsp;&nbsp;&nbsp;
         
		<?php if (isset($falbum_options['falbum_token'])) { ?>
			<input type="submit" name="ClearToken" value="<?php _e('Reset Flickr Authorization', FALBUM_DOMAIN);?>" />
		<?php } ?>
         
		</p>
		</fieldset>
       
		<hr />
       
		<fieldset class="options">
		<legend><?php _e('FAlbum Configuration', FALBUM_DOMAIN);?></legend>
		<table width="100%" cellspacing="2" cellpadding="5" class="editform">

            	<tr valign="top">
            	<th width="33%" scope="row"><?php _e('Thumbnail Size', FALBUM_DOMAIN);?>:</th>
                    <td>
		    
                    <select name="falbum_tsize">
                    <option value="s"<?php if ($falbum_options['falbum_tsize'] == 's') { ?> selected="selected"<?php } ?> ><?php _e('Square', FALBUM_DOMAIN);?> (75px x 75px)</option>
                    <option value="t"<?php if ($falbum_options['falbum_tsize'] == 't') { ?> selected="selected"<?php } ?> ><?php _e('Thumbnail', FALBUM_DOMAIN);?> (100px x 75px)</option>
                    <option value="m"<?php if ($falbum_options['falbum_tsize'] == 'm') { ?> selected="selected"<?php } ?> ><?php _e('Small', FALBUM_DOMAIN);?> (240px x 180px)</option>
                    </select><br />
                    <?php _e('Size of the thumbnail you want to appear in the album thumbnail page', FALBUM_DOMAIN);?><br /></td>
                </tr>
                
                <tr valign="top">
                    <th width="33%" scope="row"><?php _e('Albums Per Page', FALBUM_DOMAIN);?>:</th>
                    <td><input type="text" name="falbum_albums_per_page" size="3" value="<?php echo $falbum_options['falbum_albums_per_page'] ?>"/><br />
                   <?php _e('How many albums to show on a page (0 for no paging)', FALBUM_DOMAIN);?></td>
                </tr>
                <tr valign="top">
                    <th width="33%" scope="row"><?php _e('Photos Per Page', FALBUM_DOMAIN);?>:</th>
                    <td><input type="text" name="falbum_photos_per_page" size="3" value="<?php echo $falbum_options['falbum_photos_per_page'] ?>"/><br />
                   <?php _e('How many photos to show on a page (0 for no paging)', FALBUM_DOMAIN);?></td>
                </tr>
                <tr valign="top">
                    <th width="33%" scope="row"><?php _e('Recent Images', FALBUM_DOMAIN);?>:</th>
                    <td><input type="text" name="falbum_number_recent" size="3" value="<?php echo $falbum_options['falbum_number_recent'] ?>"/><br />
                   <?php _e('How many of the most recent photos to show (0 for no recent images / -1 to show all available images)', FALBUM_DOMAIN);?></td>
                </tr>
				
		<tr valign="top">
                    <th width="33%" scope="row"><?php _e('Max Photo Width', FALBUM_DOMAIN);?>:</th>
                    <td><input type="text" name="falbum_max_photo_width" size="3" value="<?php echo $falbum_options['falbum_max_photo_width'] ?>"/><br />
                   <?php _e('Maximum photo width in pixels (0 for resizing).  The default size of the images returned from Flickr is 500 pixels.', FALBUM_DOMAIN);?></td>
                </tr>  
                
                 <tr valign="top">
                    <th width="33%" scope="row"><?php _e('Display Drop Shadows', FALBUM_DOMAIN);?>:</th>
                    <td>
                    <select name="falbum_display_dropshadows">
                    <option value="-ds"<?php if ($falbum_options['falbum_display_dropshadows'] == '-ds') { ?> selected="selected"<?php } ?> ><?php _e('true', FALBUM_DOMAIN);?></option>
                    <option value="-nods"<?php if ($falbum_options['falbum_display_dropshadows'] == '-nods') { ?> selected="selected"<?php } ?> ><?php _e('false', FALBUM_DOMAIN);?></option>
                    </select>
                    <br />
                    <?php _e('Whether or not to show drop shadows under photos', FALBUM_DOMAIN);?></td>
                </tr>
               
                <tr valign="top">
                    <th width="33%" scope="row"><?php _e('Display Photo Sizes', FALBUM_DOMAIN);?>:</th>
                    <td>
                    <select name="falbum_display_sizes">
                    <option value="true"<?php if ($falbum_options['falbum_display_sizes'] == 'true') { ?> selected="selected"<?php } ?> ><?php _e('true', FALBUM_DOMAIN);?></option>
                    <option value="false"<?php if ($falbum_options['falbum_display_sizes'] == 'false') { ?> selected="selected"<?php } ?> ><?php _e('false', FALBUM_DOMAIN);?></option>
                    </select>
                    <br />
                    <?php _e('Whether or not to show photo sizes links', FALBUM_DOMAIN);?></td>
                </tr>
                
                <tr valign="top">
                    <th width="33%" scope="row"><?php _e('Display EXIF Data', FALBUM_DOMAIN);?>:</th>
                    <td>
                    <select name="falbum_display_exif">
                   <option value="true"<?php if ($falbum_options['falbum_display_exif'] == 'true') { ?> selected="selected"<?php } ?> ><?php _e('true', FALBUM_DOMAIN);?></option>
                    <option value="false"<?php if ($falbum_options['falbum_display_exif'] == 'false') { ?> selected="selected"<?php } ?> ><?php _e('false', FALBUM_DOMAIN);?></option>
                     </select>
                    <br />
                    <?php _e('Whether or not to show EXIF link', FALBUM_DOMAIN);?></td>
                </tr>
             
                <tr valign="top">
                    <th width="33%" scope="row"><?php _e('Show Private', FALBUM_DOMAIN);?>:</th>
                    <td>
                    <select name="falbum_show_private">
                    <option value="true"<?php if ($falbum_options['falbum_show_private'] == 'true') { ?> selected="selected"<?php } ?> ><?php _e('true', FALBUM_DOMAIN);?></option>
                    <option value="false"<?php if ($falbum_options['falbum_show_private'] == 'false') { ?> selected="selected"<?php } ?> ><?php _e('false', FALBUM_DOMAIN);?></option>
                    </select>
                    <br />
                    <?php _e('Whether or not to show your "private" Flickr photos', FALBUM_DOMAIN);?></td>
                </tr>
                
                <tr valign="top">
                    <th width="33%" scope="row"><?php _e('WordPress User Level', FALBUM_DOMAIN);?>:</th>
                    <td><input type="text" name="falbum_wp_user_level" size="3" value="<?php echo $falbum_options['falbum_wp_user_level'] ?>"/>
                    <br />
                    <?php _e('Set the Wordpress user level that is allowed to view "private" Flickr photos if "Show Private" is true. <br /> (0 to allow anonymous users)', FALBUM_DOMAIN);?></td>
                </tr>
                
                <tr valign="top">
                    <th width="33%" scope="row"><?php _e('Use Friendly URLS', FALBUM_DOMAIN);?>:</th>
                    <td>
                    <select name="falbum_friendly_urls">
                    <option value="true"<?php if ($falbum_options['falbum_friendly_urls'] == 'true') { ?> selected="selected"<?php } ?> ><?php _e('true', FALBUM_DOMAIN);?></option>
                    <option value="false"<?php if ($falbum_options['falbum_friendly_urls'] == 'false') { ?> selected="selected"<?php } ?> ><?php _e('false', FALBUM_DOMAIN);?></option>
                    </select>
                    <br />
                    <?php _e('Set to true if you want to use "friendly" URLs (requires mod_rewrite), false otherwise', FALBUM_DOMAIN);?>
                </tr>
                <tr valign="top">	
                    <th width="33%" scope="row"><?php _e('URL Root', FALBUM_DOMAIN);?>:</th>
                    <td><input type="text" name="falbum_url_root" size="60" value="<?php echo $falbum_options['falbum_url_root'] ?>"/><br />
                   <?php

	_e('URL to use as the root for all navigational links.<br /><strong>NOTE:</strong>It is important that you specify something here, for example:<br />
	If friendly URLs is <strong>enabled</strong> use - /photos/<br /> 
	If friendly URLs is <strong>disabled</strong> use - ', FALBUM_DOMAIN);
	echo $path."/wp-content/plugins/falbum/falbum-wp.php"
?>
				   </td>
                </tr>
                 <tr valign="top">
                    <th width="33%" scope="row"><?php

?>

</th>
                    <td>
                    <?php if ( !$writable && $is_apache) { ?>
  <p><?php _e('If your', FALBUM_DOMAIN);?><code><?php echo $home_path?>.htaccess</code><?php _e('file was <a href="http://codex.wordpress.org/Make_a_Directory_Writable">writable</a> we could do this automatically, but it isn\'t so these are the mod_rewrite rules you should have in your <code>.htaccess</code> file. Click in the field and press <kbd>CTRL + a</kbd> to select all.') ?></p>
  <p><textarea rows="5" style="width: 98%;" name="rules"><?php echo $rewriteRule; ?>
  </textarea></p><?php } ?>    
					</td>
                </tr>        
				                                 
		</table>
     
		<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Update Options', FALBUM_DOMAIN);?> &raquo;" />
		</p>
       
		</fieldset>
   
		</form>
		</div>

		<?php
 }
}

// function for outputting header information
//
function falbum_header() {
	$hHead = "<meta name='generator' content='FAlbum ".FALBUM_VERSION."' />\n";

	$tdir = get_template_directory();
	$tdir_uri = get_template_directory_uri();
	if (file_exists($tdir."/falbum.css.php")) {
		$hHead .= "<link rel='stylesheet' href='".$tdir_uri."/falbum.css.php' type='text/css' />\n";
	} else
		if (file_exists($tdir."/falbum.css")) {
			$hHead .= "<link rel='stylesheet' href='".$tdir_uri."/falbum.css' type='text/css' />\n";
		} else {
			$hHead .= "<link rel='stylesheet' href='".get_settings('siteurl')."/wp-content/plugins/falbum/falbum.css.php' type='text/css' />\n";
		}

	print ($hHead);
}

//Temp Method - needed to fix WordPress issue
function falbum_insert_with_markers($filename, $marker, $insertion) {
	if (!file_exists($filename) || is_writeable($filename)) {
		if (!file_exists($filename)) {
			$markerdata = '';
		} else {
			$markerdata = explode("\n", implode('', file($filename)));
		}

		$f = fopen($filename, 'w');
		$foundit = false;
		if ($markerdata) {
			$state = true;
			foreach ($markerdata as $markerline) {
				if (strstr($markerline, "# BEGIN {$marker}"))
					$state = false;
				if ($state)
					fwrite($f, "{$markerline}\n");
				if (strstr($markerline, "# END {$marker}")) {
					fwrite($f, "# BEGIN {$marker}\n");
					if (is_array($insertion))
						foreach ($insertion as $insertline)
							fwrite($f, "{$insertline}\n");
					fwrite($f, "# END {$marker}\n");
					$state = true;
					$foundit = true;
				}
			}
		}
		if (!$foundit) {
			fwrite($f, "\n# BEGIN {$marker}\n");
			foreach ($insertion as $insertline)
				fwrite($f, "{$insertline}\n");
			fwrite($f, "# END {$marker}\n");
		}
		fclose($f);
		return true;
	} else {
		return false;
	}
}
//

// output styles to the <head> section of the page
add_action('wp_head', 'falbum_header');
add_action('admin_menu', 'falbum_add_pages');
?>
