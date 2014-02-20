<?php
/* ---------------------------------------------------------------------------
Title: FlickrSSP 1.0
Author: Brian Sweeting (http://www.sweeting.net/)

FlickrSSP allows you to use Flickr (http://www.flickr.com) and SlideshowPro 
(http://www.slideshowpro.net) to display all of your Flickr photosets on 
your own website instead of just your recent photos via the RSS feed.
--------------------------------------------------------------------------- */

// The user_id (nsid) used to access Flickr
// You can get this by viewing the source of any Flickr page, once you are 
// logged in, it looks something like global_nsid = '11111111111@X11';)

//$flickrssp_config['user_id'] = '35116660@N00';	// bdefore
$flickrssp_config['user_id'] = $_GET['id'];	// krimzen

// The api key used to access Flickr
// You can request one of these at: http://www.flickr.com/services/api/key.gne
$flickrssp_config['api_key'] = '7f0ef5320c47a765408fcb56d6e72845';

// Your url to Flickr
// Get this by clicking the "Yours" link while you are logged into Flickr
$flickrssp_config['url'] = 'http://www.flickr.com/photos/' . $_GET['user'];

// Path and filename for the cached xml file
$flickrssp_config['xml'] = 'images.xml';

// How often should the update the xml file in seconds 
// (86400 seconds = 1 day)
$flickrssp_config['cache_period'] = 10;




/* -----------------------------------------------
Do not edit below this point
----------------------------------------------- */
include('inc/flickrssp.class.php');
$flickrssp = new flickrssp($flickrssp_config);
?>
