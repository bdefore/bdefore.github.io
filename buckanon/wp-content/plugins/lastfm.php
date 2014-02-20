<?
/*
	Plugin Name: Last.fm Sidebar
	Plugin URI: none
	Description: Show music you've been listening to
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

	function musicFeed() {
		$num_items = 12;
	
		$url = "http://ws.audioscrobbler.com/1.0/user/bdefore/weeklyartistchart.xml";

	// Snoopy is an HTTP client in PHP
	$client = new Snoopy();
	@$client->fetch($url);

		$resp = $client;
		
		if($resp)
		{
			$items = $resp->items;
			echo $resp;
			echo $items;
			echo count($items);
/*
				foreach ($resp as $key => $value) {
					if($value) {
						echo '=== ' . $key . ' --- ' . $value;
					}
					echo '<br />';
				}
*/
			$xml = '<asdf>afefefe</asdf>';
			echo $xml;
			echo simplexml_load_string($xml);
			$dump = simplexml_load_string($resp['results']);
			

			if (count($items))
			{
				// Slice off the number of items that we want:
				$items = array_slice($items, 0, $num_items);

				echo '<div class="entry"><p>';
				foreach ($items as $item) {
					if($item) {
						echo $item['name'] . ' - ' . $item['playcount'];
					}
					echo '<br />';
				}
				echo '</p></div>';
			}
		}
	}
