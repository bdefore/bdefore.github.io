<?php

/*
	Plugin Name: Feed List Old
	Plugin URI: http://rawlinson.us/blog/?p=212
	Description: Displays any ATOM or RSS feed in your blog.
	Author: Bill Rawlinson
	Author URI: http://blog.rawlinson.us/
	Version: 2.1.2


	DESCRIPTION:
		This plugin fetches RSS or ATOM feeds from the url you provide and
		displays them on your blog. It can be used to manage "hot links" 
		sections or anything else you can grab via an RSS or ATOM feed.

		The plugin also supports wordpress filters by letting you embed a feed into your post.

		Finally, it also provides a "Feed" management interface within 
		your wordpress admin console so you can add feeds to your 
		sidebar (or elsewhere) without having to reedit your template.

	INSPIRATION:
		The initial idea for this plugin came from the del.icio.us 
		plugin that can be found at http://chrismetcalf.net/ - Secondary 
		inspiration for the ATOM integration comes from James Lewis at 
		http://JamesLewis.com - I had been thinking about doing it and 
		he did it which pushed me to make the integration.

	USAGE:
		Upload the plugin, activate it and add this line to the template:
			feedList(array('Key' => 'Value', 'Key' => 'Value'));
		Note that you need to use a named array; the keys are listed
		under the "DEFAULT FEED SETTINGS" heading in this file.

	LICENSE:
		This program is free software; you can redistribute it and/or 
		modify it under the terms of the GNU General Public License 
		(GPL) as published by the Free Software Foundation; either 
		version 2 of the License, or (at your option) any later version.

	CREDITS:
		Incorporated many changes submitted by:
		- 2.0: James Lewis, http://JamesLewis.com/
		- 2.1: Pallieter Koopmans, http://Pallieter.org/

	POTENTIAL ISSUES:
		May not handle internationalization very well.
		Has seen very limited testing with non UTF-8 encoding.
*/


	// The magpie stuff built into WordPress generates errors when normalizing atom to rss feed fields so lets suppress those:
	//error_reporting(E_ERROR);

	if(!function_exists('getLinkListSettings'))
	{
		function getLinkListSettings()
		{
			/*
				CONFIGURATION SETTINGS
				----------------------

				cacheTimeout		how long should your cache file live in seconds?  By default it is 21600 or 6 hours.
							most sites prefer you use caching so please make sure you do!

				connectionTimeout	how long should I try to connect the feed provider before I give up, default is 15 seconds


				showRssLinkListJS	TRUE by default and will include a small block of JS in your header.  If it is false the JS will not be
							included. If you want the $new_window = 'true' option to use the JS then this must also be true.
							Otherwise both true and simple will hardcode the target="_blank" into the new window links
			*/

			// DEFINE THE SETTINGS -- EDIT AS YOU NEED:
			$feedListDebug = false; // To debug this script during programming (true/false).

			$cacheTimeout = 60;		// 21600 sec is 6 hours.
			$connectionTimeout = 15;	// 15 seconds is default
			$showRSSLinkListJS = true;
			
			$Language = 'en_US'; // Choose your language (from the available languages below,in the translations):
			
			
			$Translations = array(); // Please send in your suggestions/translations:

				// English:
				$Translations['en_US'] = array();
				$Translations['en_US']['ReadMore']		= 'Read more...';

				// Dutch:
				$Translations['nl_NL'] = array();
				$Translations['nl_NL']['ReadMore']		= '[lees verder]';

			
			$feedListFile = '/feeds.txt'; // IF you are going to use the random feedlist generator make sure this holds the correct name for your feed file:

			// Build an array out of the settings and send them back:
			$settings = array (	'feedListDebug' => $feedListDebug,
						'cacheTimeout' => $cacheTimeout,
						'connectionTimeout' => $connectionTimeout,
						'showRSSLinkListJS' => $showRSSLinkListJS,
						'language' => $Language,
						'translations' => $Translations,
						'feedListFile' => $feedListFile
			);

			return $settings;
		}

		function getLinkListDefaults()
		{
			/*

DEFAULT FEED SETTINGS (only apply to calls to _rssLinkList and not rssLinkList):

rss_feed_url			the url to get a feed from.

num_items			how many items to display; default is 15. If you want to show all items, set to 0.

show_description		true or false - should we show the item's description.  By default this is true.

random				true or false - should we show  random selection of items? By default this is false. Obviously, if num_items=0 this will have no effect.

before				what should we print before each item? By default this is an <li> or opening html tag for a list item.

after				what should we print after each item? By default this is an </li> or closing html tag for a list item.

description_seperator		what do we put between an item and it's description?  By default it is a hyphen.

encoding			true or false. set to true if you see wierd square like characters in your page output. This helps, but does not totally solve internationalization issues.

sort				one of three options telling us how to sort your items.
						*	none	dont sort them at all, just leave them in the order they are in. DEFAULT SETTING
						*	asc		sort alphabetically by the title of the item
						*	desc	sort in reverse alphabetical order by the title of the item.

new_window			true or false or simple. set to true if you want the links to open in a new window target="_blank"
					using "true" adds the target in a standards complaint way. Using simple will add it in a simple manner
					that bypasses javascript but will not validate as xhtml strict.


ignore_cache			use only under special circumstances such as testing a feed. Setting to true will get you banned from
					some feed providers if you fetch too often! If you provide a number (instead of true or false) it will
					use that value (in seconds) as the cache timeout setting..

suppress_link			true or false don't make the title of the feed a link.
						* false - keep the link DEFAULT SETTING
						* true - remove the link

show_date			true or false, display the last time/date the feed was refreshed
						* false - don't show the date DEFAULT SETTING
						* true - show the date

additional_fields		a tilde delmited list of additional fields you want displayed from the feed. Default is a blank string.

max_characters			The maximum number of characters to return. If you want to show everything, set to 0 (default).

max_char_wordbreak		Prevent breaking up words? true/false (if true: we cut on the last space before max_characters. Hint: add "..." to the beginning of the "after" param.

*/

		// Default settings:
		$rss_feed_url = 'http://del.icio.us/rss/bdefore';
		$num_items = 15;
		$show_description = true;
		$random = false;
		$before = '<div class="entrybody">';
		$after = '</div>';
		$description_seperator = ' - ';
		$encoding = false;
		$sort = 'none';
		$new_window = false;
		$ignore_cache = false;
		$suppress_link = false;
		$show_date = false;
		$additional_fields = '';
		$max_characters = 0;
		$max_char_wordbreak = true;
		// Convert the plain-text variables into the array we use internally:
		$defaults = array(	'rss_feed_url' => $rss_feed_url,
					'num_items' => $num_items,
					'show_description' => $show_description,
					'random' => $random,
					'before' => $before,
					'after' => $after,
					'description_seperator' => $description_seperator,
					'encoding' => $encoding,
					'sort' => $sort,
					'new_window' => $new_window,
					'ignore_cache' => $ignore_cache,
					'suppress_link' => $suppress_link,
					'show_date' => $show_date,
					'additional_fields' => $additional_fields,
					'max_characters' => $max_characters,
					'max_char_wordbreak' => $max_char_wordbreak
		);
		return $defaults;
	}


/*********************************************
	DONT EDIT BELOW THIS LINE
 *********************************************/

	// Module wide settings and includes here ------------------------------

	// admin-functions.php for get_home_path() only
	require_once(dirname(__FILE__).'/../../wp-admin/admin-functions.php');

	// get the magpie libary
if (file_exists(dirname(__FILE__).'/../../wp-includes/rss.php')) {
	require_once(dirname(__FILE__).'/../../wp-includes/rss.php');
} else {
	require_once(dirname(__FILE__).'/../../wp-includes/rss-functions.php');
}

	$settings = getLinkListSettings();

  	// ------------------------------------------------------------------------

	// USER API - this is the only method you ever need to call from within your templates:
	function feedList(	$rss_feed_url = "http://del.icio.us/rss",
				$num_items = 15,
				$show_description = true,
				$random = false,
				$before = "<li>",
				$after = "</li>",
				$description_seperator = " - ",
				$encoding	= false,
				$sort = 'none',
				$new_window = false,
				$ignore_cache = false,
				$suppress_link = false,
				$show_date=false,
				$additional_fields = '',
				$max_characters = 0,
				$max_char_wordbreak = true)
	{
		if (is_array($rss_feed_url))
		{
			$params = pc_assign_defaults($rss_feed_url);
			echo rssLinkListBuilder($params['rss_feed_url'],
						$params['num_items'],
						$params['show_description'],
						$params['random'],
						$params['before'],
						$params['after'],
						$params['description_seperator'],
						$params['encoding'],
						$params['sort'],
						$params['new_window'],
						$params['ignore_cache'],
						$params['suppress_link'],
						$params['show_date'],
						$params['additional_fields'],
						$params['max_characters'],
						$params['max_char_wordbreak']);
		}
		else
		{
			echo rssLinkListBuilder($rss_feed_url,
						$num_items,
						$show_description,
						$random,
						$before,
						$after,
						$description_seperator,
						$encoding,
						$sort,
						$new_window,
						$ignore_cache,
						$suppress_link,
						$show_date,
						$additional_fields,
						$max_characters,
						$max_char_wordbreak);
		}

	}

	function rssLinkListBuilder(	$rss_feed_url = "http://del.icio.us/rss",
					$num_items = 15,
					$show_description = true,
					$random = false,
					$before = "<li>",
					$after = "</li>",
					$description_seperator = " - ",
					$encoding	= false,
					$sort = 'none',
					$new_window = false,
					$ignore_cache = true,
					$suppress_link = false,
					$show_date=false,
					$additional_fields = '',
					$max_characters = 0,
					$max_char_wordbreak = true)
	{
		
		$BeforeArrayCrawler = 0;
		$settings = getLinkListSettings();

		// Magpie's cache is on by default, only off if turned off:
		//define('MAGPIE_CACHE_ON', true);

		if ($ignore_cache)
		{
			if (is_numeric($ignore_cache))
			{
				define('MAGPIE_CACHE_AGE', $ignore_cache);
			}
			else
			{
				define('MAGPIE_CACHE_ON', false);
			}
		}
		else
		{
			define('MAGPIE_CACHE_AGE', $settings["cacheTimeout"]);
		}
		define('MAGPIE_DEBUG', false);
		define('MAGPIE_FETCH_TIME_OUT', $settings["connectionTimeout"]);

		$rssList = '';

		// Make sure no wierdly escaped & are in the feed path - this is really
		// needed when the "Filter" is used as WordPress auto escapes the & with &#038;
		// I don't know if I have to worry about other characters at the moment:
		$rss_feed_url = str_replace('&#038;', '&', $rss_feed_url);

		if ($rs = fetch_rss($rss_feed_url))
		{
			// Here we can work with RSS fields:
			if ($settings["feedListDebug"])
			{
				echo '<pre>';
				print_r($rs);
				echo '</pre>';
			}
			$items = $rs->items;

			if (count($items))
			{
				if ($random)
				{
					// We want a random selection, so lets shuffle it
					shuffle($items);
				}
				// Slice off the number of items that we want:
				if ($num_items > 0)
				{
					$items = array_slice($items, 0, $num_items);
				}

				/**********************
					Now that we have potentially randomized and cut down our list
					we will sort the remainders if we need to
				***********************/
				// Make sure we are not getting messed up just because someone typed in caps:
				$sort = strtolower($sort);
				if (($sort == 'asc' || $sort == 'desc') && count($items))
				{
					// Order alpha by title:
					foreach($items as $item)
					{
						$sortBy[] = $item['title'];
					}

					// Make titles lowercase (otherwise capitals will come before lowercase):
					$sortByLower = array_map('strtolower', $sortBy);

					if($sort == 'asc')
					{
						array_multisort($sortByLower, SORT_ASC, SORT_STRING, $items);
					}
					elseif ($sort == 'desc')
					{
						array_multisort($sortByLower, SORT_DESC, SORT_STRING, $items);
					}
				}

				// Explicitly set this because $new_window could be "simple":
				$target = '';
				if($new_window == true && $settings["showRSSLinkListJS"])
				{
					$target=' rel="external" ';
				}
				elseif ($new_window == true || $new_window == 'simple')
				{
					$target=' target="_blank" ';
				}

				// If the cache directory should be writeable but isn't, add an entry as a message:
				if ($cache_error)
				{
					array_unshift($items, array('title'=>'Fix your cache directory', 'description'=>'Fix your cache directory', 'summary'=>'Fix your cache directory'));
				}

				// Loop through the items and build the output list:
				foreach ($items as $item)
				{
					// Link title is the text shown in the list:
					$thisLink = '';
					$linkTitle = '';
					$thisDescription = '';
					$thisTitle = $item['title'];
					if ($encoding)
					{
						// $thisTitle = mb_convert_encoding($thisTitle, 'HTML-ENTITIES', "UTF-8");
						$thisTitle = htmlentities(utf8_decode($thisTitle));
					}
	
					// Now set the Description (main text) and linkTitle (attribute of the anchor tag):
					if (isset($item['content']['encoded']) || isset($item['description']))
					{
						if (isset($item['description']))
						{
							$thisDescription = $item['description'];
						}
						elseif (isset($item['content']['encoded']))
						{
							$thisDescription = $item['content']['encoded'];
						}
						
						// Handle max_characters and max_char_wordbreak before the htmlentities makes it more complicated:
						if (!empty($max_characters) && is_numeric($max_characters))
						{
							$thisDescription = substr($thisDescription, 0, $max_characters);

							// If true, we cut on the last space:
							if (!empty($max_char_wordbreak))
							{
								$max_char_pos = strrpos($thisDescription, ' ');
								if ($max_char_pos > 0)
								{
									$thisDescription = substr($thisDescription, 0, $max_char_pos);
								}
							} 

						} else if ($encoding) { // we only convert back to HTML if you didn't cut the line thus possibly breaking the HTML
							$thisDescription = htmlentities(utf8_decode($thisDescription));
						}

						$linkTitle = $thisDescription;
						$linkTitle = strip_tags($linkTitle);
						$linkTitle = str_replace(array("\n", "\t", '"'), array('', '', "'"), $linkTitle);
						$linkTitle = substr($linkTitle, 0, 300);
	
						if (strlen(trim($thisDescription)))
						{
							$thisDescription = $description_seperator.$thisDescription;
						}
					}
					$thisDescription = str_replace("&","&amp;",$thisDescription);
	
					// Only build the hyperlink if a link is provided..and we are not told to suppress the link:
					if (!$suppress_link && strlen(trim($item['link'])) && strlen(trim($thisTitle)))
					{
						$thisLink = '<span class="rssLinkListItemTitle"><a href="'.htmlentities(utf8_decode($item['link'])).'"' . $target .' title="'.$linkTitle.'">'.$thisTitle.'</a></span>';
					}
					elseif (strlen(trim($item['link'])) && $show_description)
					{
						// If we don't have a title but we do have a description we want to show.. link the description
						$thisLink = '<span class="rssLinkListItemTitle"><a href="'.htmlentities(utf8_decode($item['link'])).'"' . $target .'><span class="rssLinkListItemDesc">'.$thisDescription.'</span></a></span>';
						$thisDescription = '';
					}
					else
					{
						$thisLink = '<span class="rssLinkListItemTitle">' . $thisTitle . '</span>';
					}

					$thisLink = str_replace("&","&amp;",$thisLink);

					// Determine if any extra data should be shown:
					$extraData = '';
					if (strlen($additional_fields))
					{
						// Magpie converts all key names to lowercase so we do too:
						$additional_fields = strtolower($additional_fields);

						// Get each additional field:
						$addFields = explode('~', $additional_fields);

						foreach ($addFields as $addField)
						{
							// Determine if the field was a nested field:
							$fieldDef = explode('.', $addField);
							$thisNode = $item;
							foreach($fieldDef as $fieldName)
							{
								// Check to see if the fieldName has a COLON in it, if so then we are referencing an array:
								$thisField = explode(':', $fieldName);
								$fieldName = $thisField[0];

								$thisNode = $thisNode[$fieldName];
								if (count($thisField) == 2)
								{
									$fieldName = $thisField[1];
									$thisNode = $thisNode[$fieldName];
								}
							}
							if (is_string($thisNode) && isset($thisNode))
							{
								$extraData .= '<div class="feedExtra'.str_replace(".","",$addField).'">' . $thisNode . '</div>';
							}
						}
					}
					$extraData = str_replace("&","&amp;",$extraData);

					
					if ($show_description){
						$rssList .= $before.$thisLink.$thisDescription.$extraData;
					}else{
						$rssList .= $before.$thisLink.$extraData;
					}

					 if (!empty($max_characters)){
						$rssList .= '<div class="ReadMoreLink"><a href="'.htmlentities(utf8_decode($item['link'])).'">'.$settings["translations"][$settings["language"]]['ReadMore'].'</a> &nbsp; </div>';
					 }

					$rssList .= $after;

				}
			}
			else
			{
				$rssList .= $before . '<a href="#" title="No Items Found there may be a problem with the feed at: '. $rss_feed_url.'">Empty List</a>' . $after;
			}

			if($show_date)
			{
				$rssList = '<div class="feedDate">updated: '.fl_tz_convert($rs->last_modified,0,Date('I')).'</div>'.$rssList;
			}
		}
		else
		{
			$rssList .= 'requested list not available';
		}
		return $rssList;
	}

		//*****************
		//INLINE PAGE FILTER METHODS
		//*****************
		function rssLinkListFilter($text)
		{

			return preg_replace_callback("/<!--rss:(.*)-->/", "rssMatcher", $text);
		}

		if (function_exists('add_filter'))
		{
			add_filter('the_content', 'rssLinkListFilter');
		}

		function rssMatcher($matches)
		{

			$settings = getLinkListSettings();

			// get the settings passed in
			$filterSetting = explode(",",$matches[1]);
			$params = array ('rss_feed_url' => $matches[1]);

			// determine if we have more than just a url
			/* loop over the array and break each element up into a sub array like:
					subArray[0] = key
					subArray[1] = value
				*/

			if (count($filterSetting) > 1)
			{
				foreach ($filterSetting as $setting )
				{
					$setting = explode(":=",$setting);
					$keyVal = $setting[0];
					$valVal = $setting[1];
					if($valVal == 'true' || $valVal == '1'){
						$valVal = true;
					}
					elseif ($valVal =='false' || $valVal == '0')
					{
						$valVal = false;
					}
					// Make sure before and after tags are no longer escaped:
					$valVal = html_entity_decode($valVal);

					$params[$keyVal] = $valVal;
				}
			}
			else
			{
				// Handle the origional default settings for when the filter was first added to the plugin:
				$params['num_items'] = 0;
				$params['show_description'] = false;
				$params['random'] = false;
				$params['before'] = '<li>';
				$params['after'] = '</li>';
				$params['description_seperator'] = ' - ';
				$params['encoding'] = false;
				$params['sort'] = 'asc';
				$params['new_window'] = false;
				$params['ignore_cache'] = false;
				$params['suppress_link'] = false;
				$params['show_date'] = false;
				$params['additional_fields'] = '';
				$params['max_characters'] = 0;
				$params['max_char_wordbreak'] = true;
			}

			$params = pc_assign_defaults($params);

			if ($settings["feedListDebug"])
			{
				return print_r($params);
			}

			return rssLinkListBuilder($params['rss_feed_url'], $params['num_items'], $params['show_description'], $params['random'], $params['before'], $params['after'], $params['description_seperator'], $params['encoding'], $params['sort'], $params['new_window'], $params['ignore_cache'], $params['suppress_link'], $params['show_date'], $params['additional_fields'], $params['max_characters'], $params['max_char_wordbreak']);

		}

  function rssLinkList($rss_feed_url = "http://del.icio.us/rss", 
                      $num_items = 15,
                      $show_description = true,
                      $random = false,
                      $before = "<li>",
                      $after = "</li>",
                      $description_seperator = " - ",
					  $encoding	= false,
					  $sort = 'none',
					  $new_window = false,
					  $ignore_cache = false,
					  $suppress_link = false,
					  $show_date=false,
					  $additional_fields = '',
					  $max_characters=0,
					  $max_char_wordbreak=true) {
	
	// display the final list
		feedList($rss_feed_url, 
			  $num_items,
			  $show_description,
			  $random,
			  $before,
			  $after,
			  $description_seperator,
			  $encoding,
			  $sort,
			  $new_window,
			  $ignore_cache,
			  $suppress_link,
			  $show_date,
			  $additional_fields,
			  $max_characters,
			  $max_char_wordbreak);


  }
		function _rssLinkList($params)
		{
			// This interface was created to support NAMED parameters:
			$params = pc_assign_defaults($params);
			feedList($params);
		}

		//****************
		// UTILITY METHODS
		//****************
		function fl_tz_convert($datetime,$tz_from,$tz_to,$format='d M Y h:ia T')
		{
			return date($format, strtotime($datetime)+(3600*($tz_to - $tz_from)));
		}

		function pc_assign_defaults($array)
		{
			$defaults = getLinkListDefaults();
			$a = array( );
			foreach ($defaults as $d => $v)
			{
				$a[$d] = isset($array[$d]) ? $array[$d] : $v;
			}
			return $a;
		}

		// Provides a XHTML 4.0 standards compliant method of opening new windows:
		function rssLinkList_JS()
		{
			$jsstring = ('<script type="text/javascript"><!--

			function addEvent(elm, evType, fn, useCapture)
			// addEvent and removeEvent
			// cross-browser event handling for IE5+,  NS6 and Mozilla
			// By Scott Andrew
			{
			  if (elm.addEventListener){
				elm.addEventListener(evType, fn, useCapture);
				return true;
			  } else if (elm.attachEvent){
				var r = elm.attachEvent("on"+evType, fn);
				return r;
			  } else {
				alert("Handler could not be removed");
			  }
			}
			function externalLinks() {
			 if (!document.getElementsByTagName) return;
			 var anchors = document.getElementsByTagName("a");
			 for (var i=0; i<anchors.length; i++) {
			   var anchor = anchors[i];
			   if (anchor.getAttribute("href") && anchor.getAttribute("rel") == "external")
					anchor.setAttribute("target","_blank");
			 }
			}

			addEvent(window, "load", externalLinks);
			//-->
			</script>
			');
			echo $jsstring;
		}

		$settings = getLinkListSettings();

		if (function_exists('add_action') && $settings['showRSSLinkListJS'])
		{
			add_action('wp_head', 'rssLinkList_JS');
		}

		function randomFeedList($args = '')
		{

			$settings = getLinkListSettings();

			parse_str($args, $r);
			if (!isset($r['file']))
				$r['file'] = dirname(__FILE__).$settings["feedListFile"];
			if (!isset($r['feedsToShow']))
				$r['feedsToShow'] = 5;
			if (!isset($r['itemsPerFeed']))
				$r['itemsPerFeed'] = 1;
			if (!isset($r['show_description']))
				$r['show_description'] = false;
			else
			{
				if($r['show_description'] == 'false')
				{
					$r['show_description'] = false;
				}
				else
				{
					$r['show_description'] = true;
				}
			}

			if (!isset($r['randomItemsPerFeed']))
			{
				$r['randomItemsPerFeed'] = true;
			}
			else
			{
				if ($r['randomItemsPerFeed'] == 'false')
					$r['randomItemsPerFeed'] = false;
				else
					$r['randomItemsPerFeed'] = true;
			}
			if (!isset($r['beforeItem']) )
				$r['beforeItem'] = '<li>';
			if (!isset($r['afterItem']) )
				$r['afterItem'] = '</li>';
			if (!isset($r['description_seperator']) )
				$r['description_seperator'] = '-';
			if (!isset($r['encoding']) )
				$r['encoding'] = 'false';
			else
			{
				if($r['encoding'] == 'false')
					$r['encoding'] = false;
				else
					$r['encoding'] = true;
			}
			if (!isset($r['sort']) )
				$r['sort'] = 'none';
			if (!isset($r['new_window']) )
				$r['new_window'] = false;
			else {
				if($r['new_window'] == 'false')
					$r['new_window'] = false;
				else
					$r['new_window'] = true;
			}
			if (!isset($r['ignore_cache']) )
				$r['ignore_cache'] = false;
			else
			{
				if($r['ignore_cache'] == 'false'){
					$r['ignore_cache'] = false;
				} else if($r['ignore_cache'] == 'true') {
					$r['ignore_cache'] = true;
				} else {
					$r['ignore_cache'] = intval($r['ignore_cache']);
				}
			}
			if (!isset($r['suppress_link']) )
				$r['suppress_link'] = false;
			else
			{
				if($r['suppress_link'] == 'false')
					$r['suppress_link'] = false;
				else
					$r['suppress_link'] = true;
			}
			if (!isset($r['show_date']))
			{
				$r['show_date'] = false;
			}
			else
			{
				if($r['show_date'] == 'false')
					$r['show_date'] = false;
				else
					$r['show_date'] = true;
			}
			if (!isset($r['additional_fields']) )
				$r['additional_fields'] = '';
			if (!isset($r['max_characters']) )
				$r['max_characters'] = 0;
			if (!isset($r['max_char_wordbreak']) ){
				$r['max_char_wordbreak'] = true;
			} else {
				if($r['max_char_wordbreak'] == 'false')
					$r['max_char_wordbreak'] = false;
				else
					$r['max_char_wordbreak'] = true;
			}

			// Seed the random number generator:
			srand((double)microtime()*1000000);

			/*	load the $feedListFile  contents into an array, using the --NEXT-- text as
				a delimeter between feeds and a tilde (~) between URL and TITLE
			*/
			$arry_txt = preg_split("/--NEXT--/", join('', file($r['file'])));
			if ($arry_txt.count)
			{
				// Randomize the array:
				shuffle($arry_txt);
				// Make sure we are set to show something:
				if ($r['feedsToShow'] < 1){
					$r['feedsToShow'] = 1;
				}
				// Make sure we aren't trying to show too many items:
				if ($r['feedsToShow'] > sizeof($arry_txt)){
					$r['feedsToShow'] = sizeof($arry_txt);
				}
				// If we actually have some stuff to show then lets show it:
				if ($r['feedsToShow'] > 0 )
				{
					// get the feed~titlecombination
					for ($i=0; $i<$r['feedsToShow']; $i++)
					{
						$thisFeed = $arry_txt[$i];
						// now build an 2 element array from the feed~title string so it is easy to parse
						$feedAndTitle = preg_split("/~/", $thisFeed);
						//grab the feed
						$feed = trim($feedAndTitle[0]);
						//grab the title
						$title = trim($feedAndTitle[1]);
						echo '<div class="randomFeed">';
						if (strlen($title))
						{
							echo $title.':<br />';
						}
						feedList($feed, $r['itemsPerFeed'], $r['show_description'], $r['randomItemsPerFeed'], $r['beforeItem'], $r['afterItem'], $r['description_seperator'], $r['encoding'], $r['sort'], $r['new_window'], $r['ignore_cache'], $r['suppress_link'], $r['show_date'], $r['additional_fields'], $r['max_characters'], $r['max_char_wordbreak']);
						echo '</div>';
					}
				}
			}
			else
			{
				echo 'the specified file, '.$r['file'].' was not found or is empty.';
			}

		}

		// This has been added because sometimes the MAGPIE that comes with WordPress calls an error
		// method that isn't defined. This resolves that problem. DO NOT ERASE THIS.
		if(!function_exists('error')){
			function error($errormsg, $lvl=E_USER_WARNING)
			{
				// Append PHP's error message if track_errors enabled:
				if (isset($php_errormsg))
				{
					$errormsg .= " ($php_errormsg)";
				}
				if (MAGPIE_DEBUG)
				{
					trigger_error($errormsg, $lvl);
				}
				else
				{
					error_log($errormsg, 0);
				}
			}
		}
		/*********************
		 ADMIN MANAGER METHODS
		 *********************/
		function feedListPrint()
		{
			$b = get_option('rss_sidebar');
			$sep = false;
			foreach($b as $name=>$url)
			{
				if ($sep)
				{
					echo '<br />';
				}
				else
				{
					$sep = true;
				}
				echo '<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$name.'</b>';
				echo '<ul>';
				_rssLinkList(array('rss_feed_url'=>$url, show_description=>false));
				echo '</ul>';
			}
		}

		function feedList_manage()
		{
			echo '<p>TODO: display the list of current feeds, and provide ability to edit, add, and delete feeds.</p>';
		}

		function feedList_install()
		{
			// Include upgrade-functions for maybe_create_table:
			if (!function_exists('maybe_create_table'))
			{
				require_once(ABSPATH.'/wp-admin/upgrade-functions.php');
			}

			global $table_prefix;

			$feedList_feedTable = $table_prefix . "feedList";
			$feedList_feedTable_sql = "CREATE TABLE " . $feedList_feedTable . "( id int(11) NOT NULL auto_increment, url varchar(250) NOT NULL, num_items integer default 15, show_description tinyint(1) default 1, random tinyint(1) default 0, before_item varchar(10) default '<li>', after_item varchar(10) default '</li>', separator_symbol varchar(5) default '-', encoding tinyint(1) default 0, sort varchar(4) default 'none', new_window varchar(6) default 'false', ignore_cache tinyint(1), show_date tinyint(1) default 0, PRIMARY KEY (id) )";

			$feedList_catTable = $table_prefix . "feedListCats";
			$feedList_catTable_sql = "CREATE TABLE " . $feedList_catTable . "(id int(11) NOT NULL auto_increment,title varchar(100) NOT NULL,keyword varchar(25) NOT NULL,PRIMARY KEY (id) )";

			$feedList_catFeedTable = $table_prefix . "feedListCatFeeds";
			$feedList_catFeedTable_sql = "CREATE TABLE " . $feedList_catTable . "(feedid int(11) NOT NULL, catid int(11) NOT NULL,PRIMARY KEY (feedid,catid) )";

			// Create Foreign Keys:
			$feedList_fkFeed = "FK_" . $table_prefix . "feedListFID";
			$feedList_fkFeed_sql = "ALTER TABLE ".$feedList_catFeedTable." ADD CONSTRAINT ". $feedList_fkFeed . " FOREIGN KEY ".$feedList_fkFeed ." (`feedid`) REFERENCES ".$feedList_feedTable ." (`id`) ON DELETE CASCADE ON UPDATE RESTRICT";

			$feedList_fkCat = "FK_" . $table_prefix . "feedListCID";
			$feedList_fkCat_sql = "ALTER TABLE ".$feedList_catFeedTable." ADD CONSTRAINT ". $feedList_fkCat . " FOREIGN KEY ".$feedList_fkCat ." (`catid`) REFERENCES ".$feedList_catFeedTable ." (`id`) ON DELETE CASCADE ON UPDATE RESTRICT";
		}
	}

	if (function_exists('is_plugin_page'))
	{
		if (is_plugin_page())
		{
			feedList_manage();
		}
		elseif (!function_exists('feedList_menu'))
		{
			function feedList_menu()
			{
				add_management_page('Imported Feeds', 'Feeds', 9, 'feedlist.php');
			}
			add_action('admin_menu', 'feedList_menu');
		}
	}
	else
	{
		// Prevent recursion:
		global $feedList_foo;
		if (isset($feedList_foo)) { return; }
		$feedList_foo = "bar";

		if (isset($_POST['admin']))
		{
			$admin = $_POST['admin'];
		}
		elseif (isset($_GET['admin']))
		{
			$admin = $_GET['admin'];
		}
		else
		{
			$admin = 'manage';
		}
		// Make sure no one's trying something sneaky:
		if (!function_exists('get_currentuserinfo'))
		{
			require_once('../../wp-config.php');
		}
		get_currentuserinfo();
		if ($user_level < 9)
		{
			die('Cheeky monkey.');
		}
	}

?>