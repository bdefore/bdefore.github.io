=== Kimili Flash Embed ===
Contributors: Kimili
Tags: flash, flex, swf, swfobject, javascript
Requires at least: 2.6
Tested up to: 2.7
Stable tag: 2.0.1
Donate Link: http://kimili.com/donate

Provides a WordPress interface for SWFObject, the best way to embed Flash content on any site.

== Description ==

Kimili Flash Embed is a plugin for Wordpress that allows you to easily place Flash movies on your site. Built upon [SWFObject](http://blog.deconcept.com/swfobject) javascript code, it is standards compliant, search engine friendly, highly flexible and full featured, as well as easy to use.

Kimili Flash Embed utilizes SWFObject 2.1, is fully compatible with Wordpress 2.x and plays well with most other plugins.

== Installation ==

Installing the plugin is as easy as unzipping and uploading the entire kimili-flash-embed folder into your /wp-content/plugins directory and activating the plugin. You can deactivate and delete any old versions of KFE you may have.

== Screenshots ==

1. The Kimili Flash Embed Tag Generator, as rendered by by Wordpress 2.7.

== Basic Usage ==

Once the plugin is installed and activated, you can add Flash content to your pages using a tag like this in your articles:

> `[kml_flashembed movie="filename.swf" height="150" width="300" /]`

When you use the Rich Text Editor in either Visual or HTML modes, you should see a button on the right end of the toolbar with a Flash player logo in the visual mode or a button that reads "Kimili Flash Embed" in HTML mode. Click it, and you will be presented with the Kimili Flash Embed Tag Generator which presents you with all possible embedding options. Set the options according to your needs and click the "Generate" button to drop a KFE tag in your editor.

The only required attributes in a KFE tag are movie, height, and width. See the the following sections all available attributes and advanced usage.

== Available Attributes ==

All of the available attributes for the KFE tag should be lowercase and double quoted. They are:

There only one attribute required in a KFE tag: *movie*. All of the available attributes for the KFE tag should be lowercase and double quoted. They are:

**MOVIE** (required)
The path and file name of the Flash movie you want to display.

**ALLOWFULLSCREEN**
(`true`|`false`) Enables full-screen mode. The default value is false if this attribute is omitted. You must have version 9,0,28,0 or greater of Flash Player installed to use full-screen mode.

**ALLOWNETWORKING**
(`all`|`internal`|`none`) Controls a SWF file's access to network functionality. The default value is 'all' if this attribute is omitted.

**ALLOWSCRIPTACCESS**
(`always`|`never`|`sameDomain`) Controls the ability to perform outbound scripting from within a Flash SWF. The default value is 'always' if this attribute is omitted.

**ALTTEXT** (_very_ deprecated)
The text you want to display if the required Flash player is not found. I strongly recommend that you "nest alternative content in your KFE tags":#altContent in favor of using this attribute.

**BASE**
( . or base directory or URL) - Specifies the base directory or URL used to resolve all relative path statements in the Flash Player movie. This attribute is helpful when your Flash Player movies are kept in a different directory from your other files.

**BGCOLOR**
(`#RRGGBB`, hexadecimal RGB value) - Specifies the background color of the Flash movie.

**DEVICEFONT**
Specifies whether static text objects that the Device Font option has not been selected for will be drawn using device fonts anyway, if the necessary fonts are available from the operating system.

**FID**
Use this attribute to give your movie a unique id on the page for scripting purposes. If omitted, a random ID is assigned to your movie.

**FVARS**
Pass variables (name/value pairs) into your movie with this attribute. You can pass in as few or as many variables as you want, separating name/value pairs with a semicolon. Syntax is as follows:

* `fvars=" name = value ; name = value "`

In addition to hard coded values, you can also pass in arbitrary Javascript or PHP code, like such:

* _Javascript_ - `href = ${document.location.href;}`
* _PHP_ - `date = ?{date('F j, Y');}`

These can be strung together in any order inside the fvars attribute:

* `fvars=" href = ${document.location.href;} ; date = ?{date('F j, Y');} ; name = Johnny Bravo "`

**FVERSION**
You can specify what version of the Flash player is required to play your movie. If you omit this attribute, the value set in the plugin options will be used.

**HEIGHT**
The height of the Flash movie. You can specify in pixels using just a number or percentage. If you omit this attribute, the value set in the plugin options will be used.

**LOOP**
(`true`|`false`) - Specifies whether the movie repeats indefinitely or stops when it reaches the last frame. The default value is true if this attribute is omitted.

**MENU**
 (`true`) displays the full menu, allowing the user a variety of options to enhance or control playback.
 (`false`) displays a menu that contains only the Settings option and the About Flash option.

**PLAY**
(`true`|`false`) - Specifies whether the movie begins playing immediately on loading in the browser. The default is true.

**PUBLISHMETHOD**
 (`static`) - Embed Flash content and alternative content using standards compliant markup and use unobtrusive JavaScript to resolve the issues that markup alone cannot solve.
 (`dynamic`) - Create alternative content using standards compliant markup and embed Flash content with unobtrusive JavaScript.

If you omit this attribute, the value set in the plugin options will be used.

**QUALITY**
(`low`|`high`|`autolow`|`autohigh`|`best`) - Specifies the playback quality of the Flash movie.

**SCALE**
(`showall`|`noborder`|`exactfit`) - Dictates how the movie fills in the specified target area.

**SEAMLESSTABBING**
(`true`|`false`) Specifies whether users are allowed to use the Tab key to move keyboard focus out of a Flash movie and into the surrounding HTML (or the browser, if there is nothing focusable in the HTML following the Flash movie). The default value is true if this attribute is omitted.

**SWLIVECONNECT**
(`true`|`false`) Specifies whether the browser should start Java when loading the Flash Player for the first time. The default value is false if this attribute is omitted. If you use JavaScript and Flash on the same page, Java must be running for the FSCommand to work.

**TARGET**
When setting `publishmethod` to `dynamic`, this is the ID of an element on your page that you want your Flash movie to display within. If you don't set this attribute, a random target ID will be generated. _Will be ignored if `publishmethod` is `static`._

**TARGETCLASS**
This is the class name of the element on your page that you want your Flash movie to display within - helpful for CSS Styling. If you omit this attribute, the value set in the plugin options will be used.

**USEEXPRESSINSTALL**
(`true`|`false`) Use this if you want to invoke the Flash Player "Express Install":#expressinstall functionality. This gives users the option to easily update their Flash Player if it doesn't meet the required version without leaving your site.

**WIDTH**
The width of the Flash movie. You can specify in pixels using just a number or percentage. If you omit this attribute, the value set in the plugin options will be used.

**WMODE**
(window, opaque, transparent) - Sets the Window Mode property of the Flash movie for transparency, layering, and positioning in the browser.

You can find out more about Flash player attributes at [Adobe's Knowledge Base](http://www.adobe.com/go/tn_12701)

== Using Flash Player Express Install ==

If you want to give visitors to your site the option to upgrade their Flash Player to the latest version as quickly and seamlessly as possible, you can use the Flash Player’s Express Install functionality.

= General Notes =

Your SWF files need to be a minimum of 214px wide by 137px high so the entire upgrade dialog can be seen by the user if the Express Install is triggered. Furthermore, if your Express-Install-enabled SWF is not at least that size, the Express Install function will automatically fail.

It may also be a good idea to only place one SWF with Express Install functionality on each page. This way users won’t be greeted with multiple upgrade dialog boxes and be forced to choose one.  Onto the specifics:

= Specifics =

Define the minimum flash player version required by your .SWF using the fversion attribute:

> `fversion="9.0.115"`

Add the useexpressinstall attribute to your `[kml_flashembed /]` tag, like this:

> `useexpressinstall="true"`
	
In the end, your KFE tag should look something like this:

> `[kml_flashembed movie="filename.swf" height="300" width="300" fversion="9" useexpressinstall="true"  /]`
	
That is all you need in order to invoke the Express Install functionality.  In the case of the above KFE tag, if a user arrives at your site with either a Flash Player 6, 7, or 8 installed, they will be alerted that they need a more recent version of the Flash Player and be given the option to upgrade it without leaving your site.

== Defining Alternate Content for a Flash Movie ==

With KFE 2.0, it is now _much_ easier to specify alternative content which gets displayed when your Flash doesn't get rendered. This could happen if a user doesn't have a recent enough Flash player installed or lacks the Flash player altogether, such as on an iPhone. Another reason to specify alternative content is for search engine optimization, or SEO. Most search engines aren't very good at indexing content in Flash movies, if they can do it at all (Google can, but only with content that has been hard coded into a SWF--dynamic content in a SWF doesn't get indexed). In these cases it's best to specify some alternative content for your SWF.

To define alternative content for a SWF, you can now nest arbitrary HTML inside a KFE tag and it will be treated as alternate content for that SWF. The Tag Generator does this for you automatically. Properly nested alternative content looks like this:

	[kml_flashembed movie="/my/great/movie.swf" width="400" height="300"]

		<!-- Begin Alternate Content -->

		<p>
			<a href="http://adobe.com/go/getflashplayer">
				<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
			</a>
		</p>

		<!-- End Alternate Content -->

	[/kml_flashembed]
	
== Configuration Options ==	

New to KFE 2.0 are configuration options which allow you to customize how the plugin behaves as well as set certain defaults for the Tag Generator and KFE tags that omit some parameters. In Wordpress, you can find the options by navigating to _Settings &rarr; Kimili Flash Embed_. You'll find the following options available to you:

**SWF Filename**
This is the default SWF filename which is populated in the **Flash (.swf)** field in the Tag Generator. This is especially useful if you use a common player to play FLVs, for instance. You can specify a URL with an absolute file path (i.e. @/flies/flash/player.swf@). Default is *untitled.swf*.

**Element Class Name**
This is the CSS class name applied to your rendered Flash movies. If you omit this attribute from a KFE tag, whatever value you have set here will be applied to the tag. Default is _flashmovie_.

**Publish Method**
Specifies the default [publishing method](http://code.google.com/p/swfobject/wiki/documentation#Should_I_use_the_static_or_dynamic_publishing_method?) for your SWF. If you omit this attribute from a KFE tag, whatever value you have set here will be applied to the tag. Default is _static_

**Dimensions (width&times;height)**
Specifies the default width and height for your SWFs. If you omit either of these attributes from a KFE tag, whatever value you have set here will be applied to the tag. Default is _400&times;300_

**Flash Version**
Specifies the default Flash player version (Major.Minor.Release format) required to display your SWFs. If you omit this attribute from a KFE tag, whatever value you have set here will be applied to the tag. Default is _8.0.0_

**Alternate Content**
Specifies the default "alternative content":#altContent to display when your SWF isn't rendered. Default is a "Get Flash Player" badge linked to Adobe's Flash Player download page.

**Create a reference to SWFObject.js?**
Decide whether or not you want to create a reference to SWFObject 2.1 in the HTML of your page templates. It is useful to turn this off if you already have SWFObject being referenced elsewhere in your code. Note that SWFObject 2.x is NOT compatible with SWFObject 1.x! *KFE 2 requires SWFObject 2.x*. Default is _true_

**Where do you want to reference SWFObject.js from?**
If you choose to create a reference to SWFObject 2.1, you have 2 options in terms of where you reference it from. **Internal** creates a link to the copy of SWFObject bundled with the plugin. **Google Ajax Library** creates a link to the copy of SWFObject hosted in the [Google's Hosted Ajax Library](http://code.google.com/p/swfobject/wiki/hosted_library). The advantage of this is that the Javascript gets served with correct cache headers and it saves you a bit of bandwidth on your server. Default is _Google Ajax Library_

== Backwards Compatibility Gotchas ==

KFE 2.0 is _mostly_ backwards compatible with KFE 1.x, so if you've been using the 1.x version and just creating basic KFE tags, you should be able to upgrade with no issues whatsoever. There are, however, some attributes which were available in 1.x which are no longer available in 2.0. This is due primarily to changes in SWFObject itself when it went to 2.0, unless otherwise noted. The 1.x attributes which are no longer supported are:

* `detectkey`
* `noscript` - This is now handled by defining "alternate content":#altContent properly.
* `redirecturl`
* `xiredirecturl`

One other minor backwards compatibility issue for you has to do with the format of KFE tags. In very early versions of this plugin, you defined KFE tags using angle brackets - `<kml_flashembed ... />`. When problems arose with using angle bracket tags in Wordpress' Rich Text Editor, I introduced the familiar square bracket variety - `[kml_flashembed ... /]`. Up to and including KFE 1.4.3, both versions of the tag formatting were supported. However, in order to simplify things in the KFE 2.0 code, the old angle bracket tags are no longer supported. If you have any posts which use the old formatting, you'll have to go back and update them to square brackets in order to continue rendering those Flash movies correctly.

== Frequently Asked Questions ==

So I can maintain them in one place, please see the Kimili Flash Embed FAQs at the [Kimili Flash Embed Home](http://kimili.com/plugins/kml_flashembed#faqs)

== Version History ==

> **Note:** Because this plugin has been around for a while and numerous older versions exist, yet version 1.4 is the first version to actually be included in the Wordpress Plugin Repository, any older versions are NOT available here.  If you'd like to download an older version, you can do so at the [Kimili Flash Embed for Wordpress Home Page](http://kimili.com/plugins/kml_flashembed/wp).

= Version 2.0 =

* Complete rewrite utilizing SWFObject 2.1
* Fixes an incompatibility with the Rich Text Editor in Wordpress 2.7

= Version 1.4.3 =

* Fixed a bug with how fvars are output in RSS feeds.

= Version 1.4.2 =

* Fixed a problem with URL file-access on some server configurations.

= Version 1.4.1 =

* Fixed an incompatibility with other plugins that were using the buttonsnap.php library
* Updated Toolbar buttons to work with TinyMCE 3, used in Wordpress 3

= Version 1.4 =

* Added "allowFullScreen" attribute for full support of Flash Player 9
* Fixed a bug when specifying percentage heights and widths.

= Version 1.3.1 =

* Fixed movie ID (fid attribute) handling.
           
= Version 1.3 =

* Updated SWFObject Javascript to latest codebase (SWFObject 1.5)
* Simplified Express Install handling to reflect changes in SWFObject 1.5
* Cleaned up code, declaring undeclared variables.

= Version 1.2 =

* Improved compatibility with other plugins.
* Removed the need to turn off GZIP compression.
* Added Flash movies to RSS feeds.
* Added a "targetclass" attribute to define a class name for the element which the SWF will be rendered within.
* Fixed a problem with invalid HTML rendering.
* Fixed a problem when passing in URLs with query strings in the FVARS attribute.
* Added Rich Text Editor Toolbar button to quickly insert KFE tags in a post.
* Updated SWFObject Javascript to latest codebase (SWFObject 1.4.4)

= Version 1.1 =

* Much improved compatibility with other WordPress plugins and themes. (yay!)
* Simplified embedding multiple instances of the same SWF.
* Removed FlashObject code from RSS feeds, allowing for feed validation.
* Updated JS to latest codebase. (FlashObject 1.3d)

= Version 1.0 =

* Updated JS to latest codebase. (FlashObject 1.3, released 1/17/06)
* Modified JS to support old browsers.
* Added ability to pass arbitrary Javascript and PHP values to SWF. 
* Includes Express Install functionality.

= Version 0.3.1 =

* Fixed a bug that prevented the Javascript from displaying properly on some servers

= Version 0.3 =

* Fixed a bug that prevented the Flash movie from displaying properly on archive pages.
* Updated Flash Object Javascript to include NS4 compatibility.

= Version 0.2 =

* Eliminated the need to install and link to a separate JavaScript file
* Initialized some previously uninitialized variables, cleaning things up a bit
* Fixed a bug that prevented fvars from being passed to the flash
* Dealt with a strange WP behaivior that was keeping the code from validating (See URL above for more info)