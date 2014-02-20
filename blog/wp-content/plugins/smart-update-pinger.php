<?

/*
	Plugin Name: Smart Update Pinger
	Plugin URI: http://www.daven.se/english/wp-plugins.html
	Description: Replaces the built-in ping/notify functionality. Pings only when publishing new posts, not when editing.
	Author: Christian Davén
	Version: 1.2
	Author URI: http://www.daven.se/
*/

# set to true to log behaviour
# (make sure the script has writing privileges in the blog main directory first)
define("SUP_DEBUG", false);

# adds an options page to the options menu
function SUP_add_options_page()
{
	if(function_exists('add_options_page'))
		add_options_page('Smart Update Pinger', 'Update Pinger', 5, basename(__FILE__), 'SUP_show_options_page');
}

# shows the options page
function SUP_show_options_page()
{
	$ping = get_option('SUP_ping');
	$uris = get_option('ping_sites');

	if(isset($_POST['submit']))
	{
		if(SUP_DEBUG)
			addtolog("saving settings");

		$uris = $_POST['uris'];

		$ping = 0;
		if($_POST['ping'] == 1)
			$ping = 1;

		update_option("SUP_ping", $ping);
		update_option("ping_sites", $uris);

		echo '<div class="updated"><p><strong>Options saved.</strong></p></div>';
	}

	$checked = '';
	if($ping == 1)
		$checked = 'checked="checked"';

	echo

	'<div class="wrap">
	<h2>URIs to Ping</h2>
	<p>The following services will automatically be pinged/notified when you publish new posts or drafts. <strong>Not</strong> when you edit previously published posts, as WordPress does by default.</p>
	<p><strong>NB:</strong> this list is synchronized with the <a href="options-writing.php">original update services list</a>.</p>
	<form method="post">
	<p>Separate multiple service URIs with line breaks:<br />
	<textarea name="uris" cols="60" rows="10">'.$uris.'</textarea></p>
	<p><input type="checkbox" id="ping_checkbox" name="ping" value="1" '.$checked.'" /> <label for="ping_checkbox">Ping services</label></p>
	<p class="submit"><input type="submit" name="submit" value="Save Settings" /></p></form></div>';
}


# the actual pinging
function SUP_ping($id)
{
	global $wpdb;

	if(get_option('SUP_ping') == 1
	and get_option('ping_sites') != "")
	{
		# fetches data directly from database; the function "get_post" is cached,
		# and using it here will get the post as is was before the last save
		$row = mysql_fetch_array(mysql_query(
			"SELECT post_date,post_modified
			FROM $wpdb->posts
			WHERE
				id=$id"));

		# if time when created equals time when modified it is a new post,
		# otherwise the author has edited/modified it
		if($row["post_date"] == $row["post_modified"])
		{
			if(SUP_DEBUG)
				addtolog("pinging (new post)");

			# uses the built-in generic_ping function from functions.php
			generic_ping();
		}
		elseif(SUP_DEBUG)
			addtolog("NOT pinging (edit)");
	}
	elseif(SUP_DEBUG)
		addtolog("NOT pinging (disabled)");
}

# when publishing a draft the post_date and post_modified will not be equal,
# but we should ping nonetheless.
# the above SUP_ping function will be called after this one,
# but the post will then be considered an edit and there will not be duplicate pings.
function SUP_ping_draft($id)
{
	if(get_option('SUP_ping') == 1
	and get_option('ping_sites') != "")
	{
		if(SUP_DEBUG)
			addtolog("pinging (draft)");

		# uses the built-in generic_ping function from functions.php
		generic_ping();
	}
	elseif(SUP_DEBUG)
		addtolog("NOT pinging (disabled)");
}

# for debugging
function addtolog($line)
{
	$fh = @fopen(ABSPATH."smart-update-pinger.log", "a");
	@fwrite($fh, strftime("%D %T")."\t$line\n");
	@fclose($fh);
}



# adds some hooks

# shows the options in the administration panel
add_action('admin_menu', 'SUP_add_options_page');
# calls SUP_ping whenever a post is published
add_action('publish_post', 'SUP_ping');
# calls SUP_ping_draft when changing the status from private/draft to published
add_action('private_to_published', 'SUP_ping_draft');

# stops the built-in pinging
remove_action('publish_post', 'generic_ping');


# activates pinging if setting doesn't exist in database yet
# (before the user has changed the settings the first time)
if(get_option('SUP_ping') === false)
{
	update_option("SUP_ping", 1);

	if(SUP_DEBUG)
		addtolog("enabling ping by default");
}

?>
