=== Preserve Code Formatting ===
Contributors: coffee2code
Donate link: http://coffee2code.com
Tags: code, formatting, post body, content, display
Requires at least: 2.0.2
Tested up to: 2.3.2
Stable tag: trunk
Version: 2.0

Preserve formatting of code for display by preventing its modification by WordPress and other plugins while retaining original whitespace and characters.

== Description ==

Preserve formatting of code for display by preventing its modification by WordPress and other plugins while retaining original whitespace and characters.

NOTE: Use of the visual text editor will pose problems as it can mangle your intent in terms of `<code>` tags.  I do not offer any support for those who have the visual editor active.

Notes:

Basically, you can just paste code into `<code>`, `<pre>`, and/or other tags you additionally specify and 
this plugin will:
* Prevent WordPress from HTML-encoding text (i.e. single- and double-quotes will not become curly; "--" and "---" will not become en dash and em dash, respectively; "..." will not become a horizontal ellipsis, etc)
* Prevent most other plugins from modifying preserved code
* Optionally preserve whitespace (in a variety of methods)
* Optionally preserve code added in comments

Keep these things in mind:
* ALL embedded HTML tags and HTML entities will be rendered as text to browsers, appearing exactly as you wrote them (including any `<br />`).
* By default this plugin filters 'the_content' (post content), 'the_excerpt' (post excerpt), and 'get_comment_text (comment content)'.

Example:
A post containing this within `<code></code>`:
`$wpdb->query("
        INSERT INTO $tablepostmeta
        (post_id,meta_key,meta_value)
        VALUES ('$post_id','link','$extended')
");`

Would, with this plugin enabled, look in a browser pretty much how it does above, instead of like:
`$wpdb->query(&#8212;
INSERT INTO $tablepostmeta
(post_id,meta_key,meta_value)
VALUES ('$post_id','link','$extended')
&#8213;);`

== Installation ==

1. Unzip `preserve-code-formatting.zip` inside the `/wp-content/plugins/` directory, or upload `preserve-code-formatting.php` to `/wp-content/plugins/`
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Go to the `Options` -> `Code Formatting` (or in WP 2.5: `Settings` -> `Code Formatting`) admin options page.  Optionally customize the options.

== Frequently Asked Questions ==

= Why does my code still display all funky?  (by the way, I'm using the visual editor) =

The visual editor has a tendency to screw up some of your intent, especially when you are attempting to include raw code.  This plugin does not make any claims about working when you create posts with the visual editor enabled.

== Screenshots ==

1. A screenshot of the plugin's admin options page.
