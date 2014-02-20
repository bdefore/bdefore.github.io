<?php
	/*
	Welcome, and thanks for using the Squible Wordpress theme. This is the
	configuration file for the theme. You can control options like whether
	or not to use the builtin plugins we've included with the theme, your
	about text and other various options.
	*/
	
	// Version number - don't change!
	$squible_version="Alpha 2.5";
		
	// Element Positioning - Beta!
	// Use this to define whereabouts certain elements sit on the page.
	// Valid keywords are: about, flickr, recentcomments, populartags, search, previousposts, asides
	// Leave a value blank ("") to disable display in that slot.
	
	$midpanel_left_1 = "about";
	$midpanel_left_2 = "recentcomments";
	$midpanel_left_3 = "";
	$midpanel_left_4 = "";
	
	$midpanel_right_1 = "flickr";
	$midpanel_right_2 = "populartags";
	$midpanel_right_3 = "search";
	$midpanel_right_4 = "";
	
	$bottompanel_left_1 = "previousposts";
	$bottompanel_left_2 = "";
	$bottompanel_left_3 = "";
	$bottompanel_left_4 = "";
	
	$bottompanel_right_1 = "asides";
	$bottompanel_right_2 = "";
	$bottompanel_right_3 = "";
	$bottompanel_right_4 = "";

	// This is the text for the about section on the home page,
	// edit to your liking.
	$aboutme="Hello. Welcome to bcdef.org<br />";

	// This determines whether or not you would like to use the plugins 
	// that are included with the squible theme. Use 1 for yes and 0
	// for no. We would recommend using this unless you know for sure
	// that you have all the supported plugins already installed.
	$builtin_plugins=1;

	// This controls the amount of characters that gets displayed on
	// the top post on the home page.
	$limitchars=450;
	// HTML tags to allow in the text of the top post on the home
	// page (all are stripped by default).
	$allowed_html="<br>";

	// Flickr Integration
	// This is your flickr user id, if you don't know what your flickr
	// user id is, you can find it by going here:
	// http://eightface.com/code/idgettr/
	$flickr_userid="87971625@N00";
	// This is the URL for your pictures on flickr
	$flickr_url="http://www.flickr.com/photos/photanical/";
	// The number of flickr thumbs to show;
	$numpics=4;

	// When users add a new tag to one of your posts, you will be emailed.
	// $tagemail is the email address where you would like those emails to
	// go. 
	$tagemail = "you@youremail.com";

	// This is the category used for asides.
	$asides_cat=2;
	// Number of asides to show
	$asidesnum=5;

	// The number of recently commented posts to show
	$show_recent_comments=10;

	// Popular tags options
	$minfont = 7;
        $maxfont = 14;
        $fontunit = "pt";
        $category_ids_to_exclude = "";
	$numberoftags=50;

	//Show author 1 for yes, 0 for no
	$show_author=0;

	//Use ajax comments !!!!!NOT COMPATIBLE WITH SPAM KARMA!!!!!
	$use_ajax=1;
?>
