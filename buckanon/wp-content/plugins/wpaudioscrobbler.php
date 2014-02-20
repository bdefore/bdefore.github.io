<?php
/*
Plugin Name: WP Audioscrobbler
Plugin URI: http://sevennine.net/projects/wp-audioscrobbler/
Description: A way to list your most recently played tracks from your audioscrobbler/last.fm account.
Version: 0.35 Beta
License: GPL
Author: Marc Hodges
Author URI: http://sevennine.net
*/
	function wpaudioscrobbler_settings_panel() {

		$wpas_setting = get_option('wpaudioscrobbler_settings');
		$wpas_ver = '0.35b';

		if ($wpas_setting['version'] != $wpas_ver){
            $wpas_setting['version'] = $wpas_ver;
            update_option('wpaudioscrobbler_settings', $wpas_setting);
            $wpas_setting = get_option('wpaudioscrobbler_settings');
		}

		//if form was submitted
		if(isset($_POST['submitted'])){

			if (isset($_POST['clear_cache']))
			    update_option('wpaudioscrobbler_cache_ts', 0);

			$new_settings = array(
								'version' => $_POST['version'],
				                'username' => $_POST['username'],
				                'cache_expire' => $_POST['cache_expire'],
				                'emptymsg_rt' => stripslashes($_POST['emptymsg_rt']),
				                'date_rt' => stripslashes($_POST['date_rt']),
				                'template_rt' => stripslashes($_POST['template_rt']),
				                'limit_rt' => $_POST['limit_rt'],
								);

			if ($new_settings != $wpas_setting){
				update_option('wpaudioscrobbler_settings', $new_settings);
				$wpas_setting = $new_settings;
			}

  		}//end if submitted

  		//if seeing the plugin admin for the first time
		if (empty($wpas_setting)){
	        $wpas_setting_default = array(
						'version' => $wpas_ver,
	                    'username' => '',
	                    'cache_expire' => '900',
	                    'emptymsg_rt' => "<p>I haven't been listening to any music lately.</p>",
	                    'date_rt' => 'D M j, Y @ H:i:s',
	                    'template_rt' => '<p><a href="%link%">%artist% - %title%</a><br />played on: %date% (%minutes_ago% minutes ago)</p>',
	                    'limit_rt' => '5',
						);
			update_option('wpaudioscrobbler_settings', $wpas_setting_default);
			$wpas_setting = $wpas_setting_default;
	 	}//end if settings available

		//so people can view " in the form
		function html_entities($s){
			//$s = str_replace('&', '&amp;', $s);
			$s = str_replace('"', '&quot;', $s);
			//$s = str_replace("'", '&prime;', $s);
			return $s;
		}

		?>
		<div class="wrap">
			<h2>WP Audioscrobbler Settings</h2>

			<form name="wpas-settings" action="<?php echo $_SERVER[PHP_SELF];?>?page=wpaudioscrobbler.php" method="post">
			<input type="hidden" name="version" value="<?php echo html_entities($wpas_setting['version']); ?>" />
			<table width="100%" cellspacing="2" cellpadding="5" class="editform" summary="WP Audioscrobbler Settings">
			<tr valign="top">
				<th scope="row" width="33%"><label for="username">Username:</label></th>
				<td><input name="username" type="text" size="40" value="<?php echo html_entities($wpas_setting['username']); ?>" class="code"/>
				<br/>Your last.fm username.
				<br /><code>http://www.last.fm/user/username/</code></td>
			</tr>
			</table>

			<fieldset>
			<legend>Cache Settings</legend>
			<table width="100%" cellspacing="2" cellpadding="5" class="editform" summary="WP Audioscrobbler Settings">
			<tr valign="top">
				<th scope="row" width="33%"><label for="cache_expire">Cache Expire:</label></th>
				<td><input name="cache_expire" type="text" size="10" value="<?php echo html_entities($wpas_setting['cache_expire']); ?>" class="code"/> seconds
				<br/>How often your recent tracks list will get updated, it is recomended that you don't set it lower then 10-15 minutes so that <a href="http://www.audioscrobbler.net">audioscrobbler</a> isn't overwelmed with requests.
				<br/>example: <code>900</code> seconds = 15 minutes</td>
			</tr>
			<tr valign="top">
				<th scope="row" width="33%"><label for="clear_cache">Reset Cache:</label></th>
				<td><input name="clear_cache" type="checkbox" />
				<br/>Do you want to reset the stored data?</td>
			</tr>
			</table>
			</fieldset>

			<br />

			<fieldset>
			<legend>Track Listing</legend>
			<table width="100%" cellspacing="2" cellpadding="5" class="editform" summary="WP Audioscrobbler Settings">
			<tr valign="top">
				<th scope="row" width="33%"><label for="emptymsg_rt">No Tracks Message:</label></th>
				<td><input name="emptymsg_rt" type="text" size="30" value="<?php echo html_entities($wpas_setting['emptymsg_rt']); ?>" class="code"/>
				<br/>This is the message that will appear when there are no recently played tracks listed on last.fm.</td>
			</tr>
			<tr valign="top">
				<th scope="row" width="33%"><label for="limit_rt">Number of Tracks:</label></th>
				<td><select name="limit_rt"><?php
					for($i=1;$i<=20;$i++){
						$selected = ($wpas_setting['limit_rt'] == $i) ? '  selected': '';
	                    _e("<option value=\"$i\"$selected> $i </option>\n");
					}// end for
					?></select>
				<br/>The number of tracks/songs that will be displayed.</td>
			</tr>
			<tr valign="top">
				<th scope="row" width="33%"><label for="date_rt">Date Format:</label></th>
				<td><input name="date_rt" type="text" size="30" value="<?php echo html_entities($wpas_setting['date_rt']); ?>" class="code"/>
				<br/>The format of the date when <code>%date%</code> is called, uses the PHP <a href="http://www.php.net/date/">date function</a>.</td>
			</tr>
			<tr valign="top">
				<th scope="row" width="33%"><label for="template_rt">Display Template:</label></th>
				<td><input name="template_rt" type="text" size="60" value="<?php echo html_entities($wpas_setting['template_rt']); ?>" class="code"/>
				<br/>The layout for each track in the list.
				<br/>example: <code>&lt;p&gt;&lt;a href="%link%"&gt;%artist% - %title%&lt;/a&gt;&lt;br /&gt;played on: %date% (%minutes_ago% minutes ago)&lt;/p&gt;</code>
				<br/>Available tags:
				<br/><code>%artist%</code> -> the artist
				<br/><code>%title%</code> -> the song title
				<br/><code>%date%</code> -> when the track was played
				<br/><code>%minutes_ago%</code> -> how many minutes ago the track was played
				<br/><code>%time_ago%</code> -> how many hours/minutes ago the track played, if over 24 hours will say 'over 24 hours', if over 2 hours, will say 'over x hours' and if less than 2 hours, will say how many hours/minutes ago
				<br/><code>%link%</code> -> the URL to the track's page on <a href="http://www.last.fm">last.fm</a>, if no URL exists, will return a '#'</td>
			</tr>
			</table>

			</fieldset>
			<p class="submit"><input type="hidden" name="submitted" /><input type="submit" name="Submit" value="<?php _e($rev_action);?> Update Settings &raquo;" /></p>
			</form>
		</div> <!-- wrap -->

		<?php
	} /* end function: wpaudioscrobbler_settings_menu */

	function wpaudioscrobbler($get='recent_tracks'){
	  	$wpas = new wpaudioscrobbler();
		echo $wpas->get_info($get);
		unset($wpas);
	}

	function wpaudioscrobbler_require(){
        require_once(dirname(__FILE__).'/classes.php');
 	}

 	function wpaudioscrobbler_admin_menu(){
	   add_submenu_page('plugins.php', 'WP Audioscrobbler', 'WP Audioscrobbler', 8, basename(__FILE__), 'wpaudioscrobbler_settings_panel');
	}

    add_action('init', 'wpaudioscrobbler_require');
    add_action('admin_menu', 'wpaudioscrobbler_admin_menu');


?>
