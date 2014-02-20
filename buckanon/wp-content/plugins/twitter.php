<?
/*
	Plugin Name: Twitter List
	Plugin URI: none
	Description: Show tweets
	Author: Buck DeFore
	Author URI: none
	Version: 0.1

*/
	// get the magpie libary
	if (file_exists(dirname(__FILE__).'/../../wp-includes/rss.php')) {
		require_once(dirname(__FILE__).'/../../wp-includes/rss.php');
	} else {
		require_once(dirname(__FILE__).'/../../wp-includes/rss-functions.php');
	}

	function twitterFeed() {
		$_num_items = 7;
	
		if ($_rs = fetch_rss("http://twitter.com/statuses/friends_timeline/7547492.rss"))
		{
			$_items = $_rs->items;

			if (count($_items))
			{
				// Slice off the number of items that we want:
				$_items = array_slice($_items, 0, $_num_items);

				foreach ($_items as $_item) {
					if($_item) {
						echo '<div class="entry"><p>';
						list($_author,$_tweet) = split(': ',$_item['description']);
						echo $_tweet;
						echo '</p></div>';
					}
				}
			}
		}
	}
?>