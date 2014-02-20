<?php
  /*
  Plugin Name: Feed List
  Plugin URI: http://rawlinson.us/blog/?p=212
  Description: Displays any ATOM or RSS feed in your blog.
  Author: Bill Rawlinson
  Author URI: http://blog.rawlinson.us
  Version: 2.02B

	
	DESCRIPTION:
		 This plugin fetches RSS or ATOM feeds from the url you provide and displays them on your blog. It can be used to manage "hot links" sections or anything else you can grab via an RSS or ATOM feed.

  The plugin also supports wordpress filters by letting you embed a feed into your post.

  Finally, it also provides a "Feed" management interface within your wordpress admin console so you can add feeds to your sidebar (or elsewhere) without having to reedit your template.

	INSPIRATION:
  The initial idea for this plugin came from the del.icio.us plugin that can be found at http://chrismetcalf.net. - Secondary inspiration for the ATOM integration comes from James Lewis at http://jameslewis.com - I had been thinking about doing it and he did it which pushed me to make the integration.

	LICENSE:
		This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License (GPL) as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.


	CREDITS:

		Incorporated many changes submitted by James Lewis at http://jameslewis.com


	POTENTIAL ISSUES:
		May not handle internationalization very well.  Has seen very limited testing with non UTF-8 encoding.
  */


// the magpie stuff built into wordpress generates errors when normalizing atom to rss feed fields so lets
// suppress those.
error_reporting(E_ERROR);

if(!function_exists('getLinkListSettings')){

	function getLinkListSettings(){

	
		
		/* 
			CONFIGURATION SETTINGS 
			----------------------

			lastRSSPath 		relative path to the lastRSS.php file.  By default
								we assume it is in the wp-content directory - not in the plugins subdirectory.

			cacheTimeout		how long should your cache file live in seconds?  By default it is 21600 or 6 hours.
								most sites prefer you use caching so please make sure you do!

			connectionTimeout	how long should I try to connect the feed provider before I give up, default is 15 seconds


			rss feed.
								for instance, espn.com's feed includes CDATA in the link title.  You have three options for processing CDATA.
									*	content		get CDATA content (without CDATA tag).  THis is the DEFAULT SETTING

									*	nochange	don't make any changes (get CDATA content including CDATA tag); this will result in
													the cdata not being displayed on the page do to the format of the CDATA tags; but it 
													will be in the page's source

									*	strip		completely strip CDATA information - this just gets rid of it so it wont be displayed
													or in the pages source code.  NOT RECOMMENDED

			showRssLinkListJS	TRUE by default and will include a small block of JS in your header.  If it is false the JS will not be 						included. If you want the $new_window = 'true' option to use the JS then this must also be true.  Otherwise 					both true and simple will hardcode the target="_blank" into the new window links
		*/


		/* DEFINE THE SETTINGS -- EDIT AS YOU NEED */
	
		$cacheTimeout = 1;		// 21600 sec is 6 hours.
		$connectionTimeout = 3;		// 15 seconds is default
		$showRSSLinkListJS = true;

		

		/* build an array out of the settings and send them back; don't edit this part */
		$settings = array (	'cacheTimeout' => $cacheTimeout,
							'connectionTimeout' => $connectionTimeout,
							'showRSSLinkListJS' => $showRSSLinkListJS
		);

		return $settings;
	}


	function getLinkListDefaults(){
		/* 
			DEFAULT FEED SETTINGS; only apply to calls to _rssLinkList and not rssLinkList.
			----------------------
			
			rss_feed_url		the url to get a feed from. 

			num_items			how many items to display; default is 15. If you want to show all items, set to 0

			show_description	true or false - should we show the item's description.  By default this is true.

			random				true or false - should we show  random selection of items? By default this is false.
								obviously, if num_items=0 this will have no effect.

			before				what should we print before each item? By default this is an <li> or opening html tag for a
								list item.
			
			after				what should we print after each item? By default this is an </li> or closing html tag for a
								list item.

			description_seperator
								what do we put between an item and it's description?  By default it is a hyphen.

			encoding			true or false.  set to true if you see wierd square like characters in your page output.  This helps,
								but does not totally solve internationalization issues.

			sort				one of three options telling us how to sort your items.

									*	none	dont sort them at all, just leave them in the order they are in.  DEFAULT SETTING

									*	asc		sort alphabetically by the title of the item

									*	desc	sort in reverse alphabetical order by the title of the item.

			new_window			true or false or simple.  set to true if you want the links to open in a new window target="_blank" 
								using "true" adds the target in a standards complaint way.  Using simple will add it in a simple manner
								that bypasses javascript but will not validate as xhtml strict.

			
			ignore_cache		use only under special circumstances such as testing a feed.  Setting to true will get you banned from
								some feed providers if you fetch too often!  If you provide a number (instead of true or false) it will
								use that value (in seconds) as the cache timeout setting..
								

			debug				NO LONGER AVAILALBE 
						

		*/

		$rss_feed_url    = 'http://del.icio.us/rss';
		$num_items    = 15;                         
		$show_description = true;                   
		$random  = false;                           
		$before = '<li>';                           
		$after = '</li>';                           
		$description_seperator = ' - ';             
		$encoding = false;                          
		$sort = 'none';                             
		$new_window = false;                        
		$ignore_cache = false;                              


		$defaults = array(	'rss_feed_url'    => $rss_feed_url,
							'num_items'    => $num_items,
							'show_description' => $show_description,
							'random'  => $random,
							'before' => $before,
							'after' => $after,
							'description_seperator' => $description_seperator,
							'encoding' => $encoding,
							'sort' => $sort,
						    'new_window' => $new_window,
							'ignore_cache' => $ignore_cache
					 );

		return $defaults;
	}


/*********************************************
	DONT EDIT BELOW THIS LINE
*********************************************/


	// Module wide settings and includes here ------------------------------
  
  	 // admin-functions.php for get_home_path() only
	require_once(dirname(__FILE__) .'/../../wp-admin/admin-functions.php') ; 

	// get the magpie libary
	require_once(dirname(__FILE__) .'/../../wp-includes/rss-functions.php') ; 

	$settings=getLinkListSettings() ;
  
  	
  	// ------------------------------------------------------------------------
  




// USER API - this is the only method you ever need to call from within your templates
  function feedList($rss_feed_url = "http://del.icio.us/rss", 
                      $num_items = 15,
                      $show_description = true,
                      $random = false,
                      $before = "<li>",
                      $after = "</li>",
                      $description_seperator = " - ",
					  $encoding	= false,
					  $sort = 'none',
					  $new_window = false,
					  $ignore_cache = false) {


	if(is_array($rss_feed_url)) {
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
				$params['ignore_cache']);	

	
	} else {
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
			  $ignore_cache);	
	}

  }


  function rssLinkListBuilder($rss_feed_url = "http://del.icio.us/rss", 
                      $num_items = 15,
                      $show_description = true,
                      $random = false,
                      $before = "<li>",
                      $after = "</li>",
                      $description_seperator = " - ",
					  $encoding	= false,
					  $sort = 'none',
					  $new_window = false,
					  $ignore_cache = true) {



	  $settings = getLinkListSettings();
	
	// Magpie's cache is on by default, only off if turned off.
	if($ignore_cache){
		if(is_numeric($ignore_cache)){
			define('MAGPIE_CACHE_AGE', $ignore_cache);
		} else {
			define('MAGPIE_CACHE_ON', false) ; 
		}
	}
	else{
		define('MAGPIE_CACHE_AGE', $settings["cacheTimeout"]) ; 
	}

	define('MAGPIE_DEBUG',true);
	define('MAGPIE_CACHE_ON', false); 

	
	define('MAGPIE_FETCH_TIME_OUT', $settings["connectionTimeout"]);

	$rssList = '';
	
	// make sure no wierdly escaped & are in the feed path - this is really
	// needed when the "Filter" is used as wordpress auto escapes the & with &#038;
	// i don't know if I have to worry about other characters at the moment
	$rss_feed_url = str_replace("&#038;","&",$rss_feed_url);

	if ($rs = fetch_rss($rss_feed_url)) {
					
		// here we can work with RSS fields
		$items = $rs->items;

		if(count($items)){


			if($random) {
			  // We want a random selection, so lets shuffle it
			  shuffle($items);
			}

			// Slice off the number of items that we want
			if($num_items > 0){
				$items = array_slice($items, 0, $num_items);
			}


			/**********************
				Now that we have potentially randomized and cut down our list
				we will sort the remainders if we need to
			***********************/
			// make sure we are not getting messed up just because
			// someone typed in caps.
			$sort = strtolower($sort);
			if(($sort == 'asc' || $sort == 'desc') && count($items)){
				//Order alpha by title
				foreach($items as $item) {
					$sortBy[] = $item['title'];
				}

				//Make titles lowercase
				//otherwise capitals will come before lowercase
				$sortByLower = array_map('strtolower', $sortBy);
				
				if($sort == 'asc'){
					array_multisort($sortByLower, SORT_ASC, SORT_STRING, $items);			
				} else if ($sort == 'desc'){
					array_multisort($sortByLower, SORT_DESC, SORT_STRING, $items);			
				}
			}


			  // explicitly set this because $new_window could be "simple"
			  $target = '';
			  if($new_window == true && $settings["showRSSLinkListJS"]) { 
				$target=' rel="external" '; 		
			  } 
			  else if($new_window == true || $new_window == 'simple'){
				  $target=' target="_blank" ';
			  }


			// If the cache directory should be writeable but isn't, add an entry as a message
			
			if ($cache_error) {
				array_unshift($items, array('title'=>'Fix your cache directory', 'description'=>'Fix your cache directory', 'summary'=>'Fix your cache directory')) ;
			}		
			
			// Loop through the items and build the output list
			
			foreach ($items as $item ) {

			  // Link title is the text shown in the list
			  $thisLink = '';
			  $linkTitle = '';
			  $thisTitle = $item['title'];
			  if($encoding){
				$thisTitle = utf8_encode($thisTitle);
			  }


			  // clean out the desription value
			  $thisDescription = '';
			  
			  // now set the Description and linkTitle (attribute of the anchor tag)
			  if(isset($item['content']['encoded']) || isset($item['description'])) {

				if(isset($item['content']['encoded'])) {
					$thisDescription = $item['content']['encoded'];
				}else{
					$thisDescription = $item['description'];
				}

				if($encoding){
					$thisDescription = utf8_encode($thisDescription);
				}

				$linkTitle=$thisDescription ; 
				$linkTitle=strip_tags($linkTitle) ; 
				$linkTitle=str_replace(array("\n", "\t", '"'), array('', '', "'"), $linkTitle) ; 
				$linkTitle=substr($linkTitle,0,300) ; 

				if(strlen(trim($thisDescription))) {
					$thisDescription = $description_seperator . $thisDescription;
				}
				  
			  } 

			  // only build the hyperlink if a link is provided..
			  if(strlen(trim($item['link'])) && strlen(trim($thisTitle))){
				  $thisLink = '<span class="rssLinkListItemTitle"><a href="'.$item['link'].'"' . $target .' title="'.$linkTitle.'">'.$thisTitle.'</a></span>'; 
			  } elseif (strlen(trim($item['link'])) && $show_description) {
				  // if we don't have a title but we do have a description we want to show.. link the description
				  $thisLink = '<span class="rssLinkListItemTitle"><a href="'.$item['link'].'"' . $target .'><span class="rssLinkListItemDesc">'.$thisDescription.'</span></a></span>';
				  $thisDescription = '';
			  } else {
				  $thisLink = '<span class="rssLinkListItemTitle">' . $thisTitle . '</span>';
			  }

			  if($show_description) {
				$rssList .= $before . $thisLink . $thisDescription . $after . "\n";
			  } else {
				$rssList .= $before . $thisLink . $after . "\n";
			  }
			}
		} else {
			$rssList .= '<a href="#" title="No Items Found there may be a problem with the feed at: '. $rss_feed_url.'">Empty List</a>';
		}

	}
	else {
		$rssList .= 'requested list not available';
	}

    return $rssList;

  }


	
//*****************
//INLINE PAGE FILTER METHODS
//*****************
	function rssLinkListFilter($text) {

		return preg_replace_callback("/<!--rss:(.*)-->/", "rssMatcher", $text);
	}

	if(function_exists('add_filter')){
		add_filter('the_content', 'rssLinkListFilter');
	}


	function rssMatcher($matches) {
		// get the settings passed in
		$filterSetting = explode(",",$matches[1]);
		$params = array ('rss_feed_url' => $matches[1]);




		// determine if we have more than just a url
		/* loop over the array and break each element up into a sub array like:
				subArray[0] = key
				subArray[1] = value
			*/

		if(count($filterSetting) > 1){
			foreach ($filterSetting as $setting ) {
				$setting = explode(":=",$setting);
				$keyVal = $setting[0];
				$valVal = $setting[1];
				if($valVal == 'true' || $valVal == '1'){
					$valVal = true;
				} elseif ($valVal =='false' || $valVal == '0'){
					$valVal = false;
				}
				// make sure before and after tags are no longer escaped
				$valVal = html_entity_decode($valVal);

				$params[$keyVal] = $valVal;
			}
		} else {
			// handle the origional default settings for when the filter was first added to the plugin

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
		}


		$params = pc_assign_defaults($params);

	//return print_r($params);

		return rssLinkListBuilder($params['rss_feed_url'],$params['num_items'],$params['show_description'],$params['random'],$params['before'],$params['after'],$params['description_seperator'],$params['encoding'],$params['sort'],$params['new_window'],$params['ignore_cache']);

	}



//*****************
//DEPRECATED METHODS
//*****************
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
					  $ignore_cache = false) {
	
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
			  $ignore_cache);


  }

  function _rssLinkList($params){
	/* this interface was created to support NAMED parameters */
		$params = pc_assign_defaults($params);
		feedList($params);

  }



//****************
// UTILITY METHODS
//****************
	function pc_assign_defaults($array) {
		$defaults = getLinkListDefaults();
		$a = array( );
		foreach ($defaults as $d => $v) {
			$a[$d] = isset($array[$d]) ? $array[$d] : $v;
		}

		return $a;
	}

	// provides a XHTML 4.0 standards compliant method of opening new windows
	function rssLinkList_JS(){

		$jsstring = '<script type="text/javascript"><!--

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
		';


		echo $jsstring;
	}


	$settings = getLinkListSettings();

	if(function_exists('add_action') && $settings["showRSSLinkListJS"]){
		add_action('wp_head', 'rssLinkList_JS');
	}


// this has been added because sometimes the MAGPIE that comes with Wordpress calls an error
// method that isn't defined.  This resolves that problem  DO NOT ERASE THIS.
	function error ($errormsg, $lvl=E_USER_WARNING) {
		// append PHP's error message if track_errors enabled
		if ( isset($php_errormsg) ) {
			$errormsg .= " ($php_errormsg)";
		}
		if ( MAGPIE_DEBUG ) {
			trigger_error( $errormsg, $lvl);
		}
		else {
			error_log( $errormsg, 0);
		}
	}





//****************
// ADMIN MANAGER METHODS
//****************
	 
	function feedListPrint() {
	 
		$b=get_option('rss_sidebar') ;
		
		$sep=false ;
		
		foreach($b as $name=>$url) {
			
			if ($sep)
				echo '<br>' ;
			else 	
				$sep=true ;
			
			echo '<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$name.'</b>'  ; 
		
			echo '<ul>' ;
			
			_rssLinkList(array('rss_feed_url'=>$url, show_description=>false)) ;

			echo '</ul>' ;

		}
		
	}




	function feedList_manage(){
		echo ("TODO: displaylist of current feeds, and provide ability to edit, add, and delete feeds");
	}


}







// sidebar link administrative console
// initially created by: 
//		Author: James Lewis
//		Author URI: http://jameslewis.com

if (function_exists('is_plugin_page')) {
	if (is_plugin_page()) {

		feedList_manage();
	
	} else if(!function_exists('feedList_menu')){
		function feedList_menu() {
		add_management_page('Imported Feeds', 'Feeds', 9, 'feedList.php');
	        }
		add_action('admin_menu', 'feedList_menu');
	}
} else {

	// prevent recursion
	global $feedList_foo;
	if (isset($feedList_foo)) { return; }
	$feedList_foo = "bar";

	if (isset($_POST['admin'])) {
		$admin = $_POST['admin'];
	} elseif (isset($_GET['admin'])) {
		$admin = $_GET['admin'];
	} else {
		$admin = 'manage';
	}
	// make sure no one's trying something sneaky
	if (! function_exists('get_currentuserinfo')) {
		require_once ('../../wp-config.php');
	}
	get_currentuserinfo();
	if ($user_level < 9) {
		die ('Cheeky monkey!');
	}

}

?>