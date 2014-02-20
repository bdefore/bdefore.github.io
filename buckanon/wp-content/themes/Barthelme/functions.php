<?php
/*
File Name: Wordpress Theme Toolkit
Version: 1.0
Author: Ozh
Author URI: http://planetOzh.com/
*/
/************************************************************************************
 * THEME USERS : Don't touch anything !! Or don't ask the theme author for support (:-0
 ************************************************************************************/
include(dirname(__FILE__).'/themetoolkit.php');

/************************************************************************************
 * FUNCTION ARRAY
 ************************************************************************************/
themetoolkit(
	'barthelme', 
	array(
	'separ1' => 'Typography {separator}',
	'bodyfontsize' => 'Base Font Size ## The base font size globally affects all font sizes throughout your blog. This can be in any unit (e.g., px, pt, em), but I suggest using a percentage (%). Default is 75%.<br/><em>Format: <strong>Xy</strong> where X = a number and y = its units.</em>',
	'bodyfontfamily' => 'Base Font Family {radio|arial, helvetica, sans-serif|<span style="font-family:arial, helvetica, sans-serif !important;font-weight:bold;">Arial</span> (Helvetica, sans serif)|"courier new", courier, monospace|<span style="font-family:courier new, courier, monospace !important;font-weight:bold;">Courier New</span> (Courier, monospace)|georgia, times, serif|<span style="font-family:georgia, times, serif !important;font-weight:bold;">Georgia</span> (Times, serif)|"lucida console", monaco, monospace|<span style="font-family:lucida console, monaco, monospace !important;font-weight:bold;">Lucida Console</span> (Monaco, monospace)|"lucida sans unicode", lucida grande, sans-serif|<span style="font-family:lucida sans unicode, lucida grande !important;font-weight:bold;">Lucida Sans Unicode</span> (Lucida Grande, sans serif)|tahoma, geneva, sans-serif|<span style="font-family:tahoma, geneva, sans-serif !important;font-weight:bold;">Tahoma</span> (Geneva, sans serif)|"times new roman", times, serif|<span style="font-family:times new roman, times, serif !important;font-weight:bold;">Times New Roman</span> (Times, serif)|"trebuchet ms", helvetica, sans-serif|<span style="font-family:trebuchet ms, helvetica, sans-serif !important;font-weight:bold;">Trebuchet MS</span> (Helvetica, sans serif)|verdana, geneva, sans-serif|<span style="font-family:verdana, geneva, sans-serif !important;font-weight:bold;">Verdana</span> (Geneva, sans serif)} ## The base font family sets the font for all elements except headers (see below). A fall-back font and the font family are in parentheses. Default is Arial.',
	'headerfontfamily' => 'Header Font Family {radio|arial, helvetica, sans-serif|<span style="font-family:arial, helvetica, sans-serif !important;font-weight:bold;">Arial</span> (Helvetica, sans serif)|"courier new", courier, monospace|<span style="font-family:courier new, courier, monospace !important;font-weight:bold;">Courier New</span> (Courier, monospace)|georgia, times, serif|<span style="font-family:georgia, times, serif !important;font-weight:bold;">Georgia</span> (Times, serif)|"lucida console", monaco, monospace|<span style="font-family:lucida console, monaco, monospace !important;font-weight:bold;">Lucida Console</span> (Monaco, monospace)|"lucida sans unicode", lucida grande, sans-serif|<span style="font-family:lucida sans unicode, lucida grande !important;font-weight:bold;">Lucida Sans Unicode</span> (Lucida Grande, sans serif)|tahoma, geneva, sans-serif|<span style="font-family:tahoma, geneva, sans-serif !important;font-weight:bold;">Tahoma</span> (Geneva, sans serif)|"times new roman", times, serif|<span style="font-family:times new roman, times, serif !important;font-weight:bold;">Times New Roman</span> (Times, serif)|"trebuchet ms", helvetica, sans-serif|<span style="font-family:trebuchet ms, helvetica, sans-serif !important;font-weight:bold;">Trebuchet MS</span> (Helvetica, sans serif)|verdana, geneva, sans-serif|<span style="font-family:verdana, geneva, sans-serif !important;font-weight:bold;">Verdana</span> (Geneva, sans serif)} ## This selects the font for headings (h1, h2, h3, etc.) and other elements throughout your blog. A fall-back font and the font family are in parentheses. Default is Georgia.',
	'postentryalignment' => 'Post Text Alignment {radio|justify|Justified|left|Left aligned ("Ragged right")|right|Right aligned ("Ragged left")} ## Choose one for the text alignment of the post body text. Default is left aligned.',
	'separ2' => 'Layout {separator}',
	'wrapperwidth' => 'Layout Width ## Set the overall width of content in the browser window. This can be in any unit (e.g., px, pt, em), but I suggest using % for a fluid layout. Default is 90%.<br/><em>Format: <strong>Xy</strong> where X = a number and y = its units.</em>',
	'separ3' => 'Content {separator}',
	'sidebaraddin' => 'Sidebar Add-in {checkbox|showsidebaraddin|yes|Display the add-in sidebar content} ## If checked, the sidebar content below will appear in the sidebar throughout the blog, except on single post pages. Default is unchecked.<br/><em><strong>Note to Widgets users:</strong> If you are actively using the Widgets plugin, then the sidebar add-in text will not appear.</em>',
	'sidebartext' => 'Sidebar Add-in Content {textarea|10|55} ## Add/edit content for the sidebar section. This text must be parsed in HTML tags. You can use HTML, but beware of special characters: i.e., &amp; = <code>&amp;amp;</code>. Remember that this text <em>will not appear</em> unless "Sidebar Add-in" is checked above. Default is Lorem ipsum&hellip; .',
	'footeraddin' => 'Footer Add-in {checkbox|showfooteraddin|yes|Display the add-in footer text} ## If checked, the footer text below will appear in the footer throughout the blog. Default is unchecked.',
	'footertext' => 'Footer Add-in Text {textarea|10|55} ## Add/edit content for the footer. This text is placed within <code>&lt;p&gt;...&lt;/p&gt;</code> tags. Beware of special characters: i.e., &amp; = <code>&amp;amp;</code>. Remember that this text <em>will not appear</em> unless "Footer Add-in" is checked above. Default is Lorem ipsum&hellip; .',
	'debug' => 'debug',
	),
	__FILE__
);

/************************************************************************************
 * FUNCTION CALLS
 ************************************************************************************/
function barthelme_bodyfontsize() {
	global $barthelme;
	if ( $barthelme->option['bodyfontsize'] ) {
		print 'body { font-size: ';
		print $barthelme->option['bodyfontsize'];
		print "; }\n";
	}
}
function barthelme_bodyfontfamily() {
	global $barthelme;
	if ( $barthelme->option['bodyfontfamily'] ) {
		print 'body { font-family: ';
		print $barthelme->option['bodyfontfamily'];
		print "; }\n";
	}
}
function barthelme_headerfontfamily() {
	global $barthelme;
	if ( $barthelme->option['headerfontfamily'] ) {
		print 'div.post-header, h2.post-title, p.post-date-single, h2.post-title-single, div.post-entry h1, div.post-entry h2, div.post-entry h3, div.post-entry h4, div.post-entry h5, div.post-entry h6, div.post-entry blockquote, div.post-footer, h3#comment-count, h4#comment-header, div#comments ol li p.comment-metadata, h4#respond { font-family: ';
		print $barthelme->option['headerfontfamily'];
		print "; }\n";
	}
}
function barthelme_postentryalignment() {
	global $barthelme;
	if ( $barthelme->option['postentryalignment'] ) {
		print 'div.post-entry p { text-align: ';
		print $barthelme->option['postentryalignment'];
		print "; }\n";
	}
}
function barthelme_wrapperwidth() {
	global $barthelme;
	if ( $barthelme->option['wrapperwidth'] ) {
		print 'div#wrapper { width: ';
		print $barthelme->option['wrapperwidth'];
		print "; }\n";
	}
}
function barthelme_sidebartext() {
	global $barthelme;
	if ( $barthelme->option['showsidebaraddin'] == 'yes' ) {
		print $barthelme->option['sidebartext'];
	}
}
function barthelme_footertext() {
	global $barthelme;
	if ( $barthelme->option['showfooteraddin'] == 'yes' ) {
		print $barthelme->option['footertext'];
	}
}

/************************************************************************************
 * FUNCTION DEFAULTS
 ************************************************************************************/
if ( !$barthelme->is_installed() ) {

	$set_defaults['bodyfontsize'] = '75%';
	$set_defaults['bodyfontfamily'] = 'arial, helvetica, sans-serif';
	$set_defaults['headerfontfamily'] = 'georgia, times, serif';
	$set_defaults['postentryalignment'] = 'left';
	$set_defaults['wrapperwidth'] = '90%';
	$set_defaults['sidebartext'] = '<li><h2>More About</h2><p>Lorem ipusm text here can be customized in the <em>Presentation > blog.txt Themes Options</em> menu. You can also select within the options to exclude this section completely. <em>Most</em> XHTML <strong>tags</strong> will <span style="text-decoration:underline;">work</span>.</p></li>';
	$set_defaults['footertext'] = 'Lorem ipsum text here can be customized in the <em>Presentation > blog.txt Themes Options</em> menu. Inline (non-block) XHTML <strong>elements</strong> will <span style="text-decoration:underline;">work</span>.';
	$result = $barthelme->store_options($set_defaults) ;
}

/************************************************************************************
 * CALL FOR WIDGETS PLUGIN, V.1
 ************************************************************************************/
if( function_exists('register_sidebar') ) {
	register_sidebar( array (
		'name' => 'Main Sidebar',
	) );
}
function widget_mytheme_search() {
?>
<li id="search">
	<h2><label for="s">Search</label></h2>
	<form id="searchform" method="get" action="<?php bloginfo('home'); ?>/">
		<div>
			<input id="s" name="s" type="text" value="<?php echo wp_specialchars($s, 1); ?>" tabindex="1" size="10" />
			<br/>
			<input id="searchsubmit" name="searchsubmit" type="submit" value="Find" tabindex="2" />
		</div>
	</form> 
</li>
<?php
}
if ( function_exists('register_sidebar_widget') )
    register_sidebar_widget(__('Search'), 'widget_mytheme_search');
?>