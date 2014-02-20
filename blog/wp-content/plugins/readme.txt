  Plugin Name: Feed List
  Plugin URL: http://rawlinson.us/blog/?p=212
  Description: Allows you to display lists of links from an rss 
or atom feed on your blog.
  Author: Bill Rawlinson
  Author URI: http://blog.rawlinson.us
  Version: 2.01b



/* CHANGE LOG 
	DATE					MODIFICATION						
		AUTHOR
=================================================================
==========================
	12 October 2005			Initial Version						
		Bill Rawlinson - released version 2.0B
							rewrite of rssLinkedList  NOTE a 
major change - the caching is handled 
by Wordpress now so you don't need a 
cache directory.


	06 Nov 2005			Simplified Interface and Rewrite Docs

	15 Nov 2005			Fixed some bugs 

	01 Dec 2005			Fixed a bug where the description wasn't being shown for atom feeds
						and cleaned up the description display code
*/



  DESCRIPTION:
	This plugin fetches RSS or ATOM feeds from the url you 
provide and displays them on your blog. It can be used to 
manage "hot links" sections or anything else you can grab via 
an RSS or ATOM feed.

	The plugin also supports wordpress filters by letting you 
embed a feed into your post.

	Finally, it also provides a "Feed" management interface 
within your wordpress admin console so you can add feeds to 
your sidebar (or elsewhere) without having to reedit your 
template.


  INSPIRATION:
	The initial idea for this plugin came from the del.icio.us 
plugin that can be found at http://chrismetcalf.net. -

	Secondary inspiration for the ATOM integration comes from 
James Lewis at http://jameslewis.com - I had been thinking 
about doing it and he did it which pushed me to make the 
integration.
  
  LICENSE:
	This program is free software; you can redistribute it and/or 
modify it under the terms of the GNU General Public License 
(GPL) as published by the Free Software Foundation; either 
version 2 of the License, or (at your option) any later 
version.


  POTENTIAL ISSUES:
	May not handle internationalization very well.  Has seen very 
limited testing with non UTF-8 encoding.


  REQUIREMENTS:
	* WordPress 1.5 or greater (http://www.wordpress.org)

	INSTALLATION:

	1.) Place the plugin (feedlist.php) in your 
wp-content/plugins/ directory.

	2.) Edit feedlist.php and fill out the values in the 
CONFIGURATION section.

	3.) Enable the feedList plugin in the "Plugins" section of 
your WordPress administration panel.


  UPGRADING:
	1.) jot down your configuration information in feedList.php 

	2.) Overwrite your feedlist.php file

	3.) Update your feedlist configuration information with that 
data you wrote down in step 1

	4.) enjoy


  USAGE:
	From anywhere in your WordPress template, call the function 
"feedList(...)", which takes the following parameters (all 
parameters have default values) you can pass in either a named array of parameters or
pass the parameters in order as follows:


	* rss_feed_url (default: "http://del.icio.us/rss") - The URL of the Del.icio.us RSS or ATOM Feed.  Still named rss_feed_url for backwards compatability but will work with ATOM feeds
	* num_items (default: 15) - The number of items to display
	* show_description (default: true) - Whether or not to display the "description" field
	* random (default: false) - Whether or not to randomize the items
	* before (default: "<li>") - Tag placed before the item	* after (default: "</li>") - Tag placed after the item	* description_seperator (default: " - ") - Between the link and the item
	* encoding (default: false) - Change to true if you are reading in a ISO-8859-1 formatted file.  Basically, if you see a bunch of question marks (?) in your titles set this to true and see if it fixes the problem.
	* sort (default: "none") - takes one of three values; none, asc, desc
			none - doesn't sort and leaves your existing code as is
			asc	 - sorts the results in alphabetic order (by title)
			desc - sorts in reverse alphabetic order (by title)
	* new_window (default: false) - Whether to open the links in a new window or not.
			true - opens links in new window using javascript to attach the "target" attribute to each link in the list	and is thus xhtml strict compliant
			false - opens the links in the current window  (DEFAULT)
			simple - opens links in new window and hardcodes the target="_blank" into the link. NOT xhtml strict compliant this option exists so that you can use it without javascript.  If you also don't want to include the javascript	in your header file update the global setting in rssLinkList.php $showRSSLinkListJS and set it to false.

				
	FILTER USAGE

		* basic:
			<!--rss:[URL]--> 

			NOTE if you aren't using named parameters with the fitler then ONLY provide the url after the rss: or else it won't work.  Left as rss: for backwards compatability but will work with ATOM feeds as well.

		* NAMED PARAMETERS
			<!--rss:rss_feed_url:=http://del.icio.us/rss/finalcut/wishlist,num_items:=5,random:=true-->

			NOTE when using the filter and named parameters ALL parameters including the URL must be named. Also note that if you are providing different HTML for the before or after parameter you must escape it.  For instance if you want before='<li>' then you must pass before='&lt;li&gt;'  

			Finally note the whole thing must be on ONE line.  No line breaks or else it won't work.




  EXAMPLES:

	NAMED PARAMETER EXAMPLE -- PREFERRED METHOD
		<ol>
		<?php 
			feedList(array("rss_feed_url"=>"http://www.auf-der-hoehe.de/index.php?id=23&type=333&feed_id=71&no_cache=1",
							"num_items"=>10,
							"show_description"=>false,
							"random"=>true,
							"sort"=>"asc","new_window"=>true)); 
		?>
		</ol>

	BASIC
		<ol>
		 <?php 
			feedList("http://del.icio.us/rss/finalcut"); 
		 ?>
		</ol>

		due to the fact that rssLinkList wraps each item with an <li> tag pair by default you need to provide the <ol> or <ul> wrappers	around the function call.


	COMBINING LISTS:

		You can also combine rss calls into one html list simply by wrapping multiple rssLinkList function calls in one set of html list tags.  Notice I only specify the first parameter here.  All parameters have defaults so the only one you really need to provide is the URL.

		<ol>
		 <?php 
			feedList("http://del.icio.us/rss/finalcut"); 
			feedList("http://www.43things.com/rss/uber/author?username=FinalCut");
		 ?>
		</ol>

		since the function, feedList, by default wraps each rss item in <li> tags you will end up with one long list of items to display.

	ENCODING EXAMPLE:

		<ol>
		<?php feedList("http://www.auf-der-hoehe.de/index.php?id=23&type=333&feed_id=71&no_cache=1",10,false,true,"<li>","</li>","-",true); ?>
		</ol>



  NOTE:
	Remember, if you don't want your items to be displayed as an html list - you need to override the default parameters of "before" and "after" in the function call.