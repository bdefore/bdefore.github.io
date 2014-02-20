<?php
/*
Plugin Name: Preserve Code Formatting
Version: 2.0
Plugin URI: http://coffee2code.com/wp-plugins/preserve-code-formatting
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Preserve formatting of code for display by preventing its modification by WordPress and other plugins while retaining original whitespace and characters.

NOTE: Use of the visual text editor will pose problems as it can mangle your intent in terms of <code> tags.  I do not
offer any support for those who have the visual editor active.

Notes:

Basically, you can just paste code into `<code>`, `<pre>`, and/or other tags you additionally specify and 
this plugin will:
* Prevent WordPress from HTML-encoding text (i.e. single- and double-quotes will not become curly; "--" and "---" 
will not become en dash and em dash, respectively; "..." will not become a horizontal ellipsis, etc)
* Prevent most other plugins from modifying preserved code
* Optionally preserve whitespace (in a variety of methods)
* Optionally preserve code added in comments

Keep these things in mind:
* ALL embedded HTML tags and HTML entities will be rendered as text to browsers, appearing exactly as you wrote 
them (including any <br />).
* By default this plugin filters 'the_content' (post content), 'the_excerpt' (post excerpt), and
'get_comment_text (comment content)'.

Example:
A post containing this within <code></code>:
$wpdb->query("
        INSERT INTO $tablepostmeta
        (post_id,meta_key,meta_value)
        VALUES ('$post_id','link','$extended')
");

Would, with this plugin enabled, look in a browser pretty much how it does above, instead of like:
$wpdb->query("
INSERT INTO $tablepostmeta
(post_id,meta_key,meta_value)
VALUES ('$post_id','link','$extended')
");

Compatible with WordPress 2.0+, 2,1+, 2.2+, 2.3+, and 2.5.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

Installation:

1. Download the file http://coffee2code.com/wp-plugins/preserve-code-formatting.zip and unzip it into your 
/wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Go to the Options -> Code Formatting (or in WP 2.5: Settings -> Code Formatting) admin options page.
Optionally customize the options.

*/

/*
Copyright (c) 2004-2008 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

if ( !class_exists('PreserveCodeFormatting') ) :

class PreserveCodeFormatting {
	var $admin_options_name = 'c2c_preserve_code_formatting';
	var $nonce_field = 'update-preserve_code_formatting';
	var $show_admin = true;	// Change this to false if you don't want the plugin's admin page shown.
	var $config = array();
	var $options = array(); // Don't use this directly

	function PreserveCodeFormatting() {
		$this->config = array(
			// input can be 'checkbox', 'text', 'hidden', or 'none'
			'preserve_tags' => array('input' => 'text', 'default' => array('code', 'pre'), 'datatype' => 'array',
				'label' => 'Tags that will have their contents preserved',
				'help' => 'Space and/or comma-separated list of values.'),
			'preserve_in_comments' => array('input' => 'checkbox', 'default' => true,
				'label' => 'Preserve code in comments?',
				'help' => 'Preserve code posted by visitors in comments?'),
			'wrap_multiline_code_in_pre' => array('input' => 'checkbox', 'default' => true,
				'label' => 'Wrap multiline code in <code>&lt;pre></code> tag?',
				'help' => '&lt;pre> helps to preserve whitespace'),
			'use_nbsp_for_spaces' => array('input' => 'checkbox', 'default' => true,
				'label' => 'Use <code>&amp;nbsp;</code> for spaces?',
				'help' => 'Not necessary if you are wrapping code in <code>&lt;pre></code> or you use CSS to define whitespace:pre; for code tags.'),
			'nl2br' => array('input' => 'checkbox', 'default' => false,
				'label' => 'Convert newlines to <code>&lt;br/></code>?',
				'help' => 'Depending on your CSS styling, you may need this.  Otherwise, code may appear double-spaced.')
		);

		$options = $this->get_options();

		add_action('admin_menu', array(&$this, 'admin_menu'));

		add_filter('the_content', array(&$this, 'preserve_preprocess'), 2);
		add_filter('the_content', array(&$this, 'preserve_postprocess_and_preserve'), 100);
		add_filter('content_save_pre', array(&$this, 'preserve_preprocess'), 2);
		add_filter('content_save_pre', array(&$this, 'preserve_postprocess'), 100);

		add_filter('the_excerpt', array(&$this, 'preserve_preprocess'), 2);
		add_filter('the_excerpt', array(&$this, 'preserve_postprocess_and_preserve'), 100);
		add_filter('excerpt_save_pre', array(&$this, 'preserve_preprocess'), 2);
		add_filter('excerpt_save_pre', array(&$this, 'preserve_postprocess'), 100);

		// Comment out these next lines if you don't want to allow preserve code formatting for comments.
		if ($options['preserve_in_comments']) {
			add_filter('comment_text', array(&$this, 'preserve_preprocess'), 2);
			add_filter('comment_text', array(&$this, 'preserve_postprocess_and_preserve'), 100);
			add_filter('pre_comment_content', array(&$this, 'preserve_preprocess'), 2);
			add_filter('pre_comment_content', array(&$this, 'preserve_postprocess'), 100);
		}
	}

	function install() {
		$this->options = $this->get_options();
		update_option($this->admin_options_name, $this->options);
	}

	function admin_menu() {
		if ($this->show_admin)
			add_options_page('Preserve Code Formatting', 'Code Formatting', 9, basename(__FILE__), array(&$this, 'options_page'));
	}

	function get_options() {
		if (!empty($this->options)) return $this->options;
		// Derive options from the config
		$options = array();
		foreach (array_keys($this->config) as $opt) {
			$options[$opt] = $this->config[$opt]['default'];
		}
        $existing_options = get_option($this->admin_options_name);
        if (!empty($existing_options)) {
            foreach ($existing_options as $key => $value)
                $options[$key] = $value;
        }
		$this->options = $options;
        return $options;
	}

	function options_page() {
		$options = $this->get_options();
		// See if user has submitted form
		if ( isset($_POST['submitted']) ) {
			check_admin_referer($this->nonce_field);

			foreach (array_keys($options) AS $opt) {
				$options[$opt] = $_POST[$opt];
				if (($this->config[$opt]['input'] == 'checkbox') && !$options[$opt])
					$options[$opt] = 0;
				if ($this->config[$opt]['datatype'] == 'array')
					$options[$opt] = explode(',', str_replace(array(', ', ' ', ','), ',', $options[$opt]));
			}
			// Remember to put all the other options into the array or they'll get lost!
			update_option($this->admin_options_name, $options);

			echo "<div class='updated'><p>Plugin settings saved.</p></div>";
		}

		$action_url = $_SERVER[PHP_SELF] . '?page=' . basename(__FILE__);

		echo <<<END
		<div class='wrap'>
			<h2>Preserve Code Formatting Plugin Options</h2>
			<p>Preserve formatting for text within &lt;code> and &lt;pre> tags (other tags can be defined as well).
			Helps to preserve code indentation, multiple spaces, prevents WP's fancification of text (ie. ensures 
			quotes don't become curly, etc).</p>

			<p>NOTE: Use of the visual text editor will pose problems as it can mangle your intent in terms of &lt;code> tags.  
			I do not offer any support for those who have the visual editor active.</p>
			
			<form name="preserve_code_formatting" action="$action_url" method="post">	
END;
				wp_nonce_field($this->nonce_field);
		echo '<fieldset class="option"><table width="100%" cellspacing="2" cellpadding="5" class="optiontable editform">';
				foreach (array_keys($options) as $opt) {
					$input = $this->config[$opt]['input'];
					if ($input == 'none') continue;
					$label = $this->config[$opt]['label'];
					$value = $options[$opt];
					if (($input == 'checkbox') && ($value == 1)) {
						$checked = 'checked=checked ';
						$value = 1;
					} else {
						$checked = '';
					};
					if ($this->config[$opt]['datatype'] == 'array')
						$value = implode(', ', $value);
					echo "<tr valign='top'><th width='50%' scope='row'>$label : </th>";
					echo "<td><input name='$opt' type='$input' id='$opt' value='$value' $checked/>";
					if ($this->config[$opt]['help']) {
						echo "<br /><span style='color:#777; font-size:x-small;'>";
						echo $this->config[$opt]['help'];
						echo "</span>";
					}
					echo "</td></tr>";
				}
		echo <<<END
			</table>
			</fieldset>
			<input type="hidden" name="submitted" value="1" />
			<div class="submit"><input type="submit" name="Submit" value="Update Options &raquo;" /></div>
		</form>
			</div>
END;
		echo <<<END
		<div class='wrap' style="text-align:center; color:#888;">This plugin brought to you by <a href="http://coffee2code.com" title="coffee2code.com">Scott Reilly, aka coffee2code</a>.<br /><span style="font-size:x-small;"><a href="http://coffee2code.com/donate">Did you find this plugin useful?</a></span></div>
END;
	}

	function prep_code( $text ) {
		$options = $this->get_options();

		$text = preg_replace("/(\r\n|\n|\r)/", "\n", $text);
		$text = preg_replace("/\n\n+/", "\n\n", $text);
		$text = str_replace(array("&#36&;", "&#39&;"), array("$", "'"), $text);
		$text = htmlspecialchars($text, ENT_QUOTES);
		$text = str_replace("\t", '  ', $text);
		if ($options['use_nbsp_for_spaces'])  $text = str_replace('  ', '&nbsp;&nbsp;', $text);
		if ($options['nl2br']) $text = nl2br($text);
		return $text;
	} //end prep_code()

	function preserve_code_formatting( $text ) {
		$text = str_replace(array('$', "'"), array('&#36&;', '&#39&;'), stripslashes_deep($text));
		$text = $this->prep_code($text);
		$text = str_replace(array('&#36&;', '&#39&;', '&lt; ?php'), array('$', "'", '&lt;?php'), $text);
		return $text;
	} //end preserve_code_formatting()

	function preserve_preprocess( $content ) {
		$options = $this->get_options();
		$preserve_tags = $options['preserve_tags'];
		$result = '';
		foreach ($preserve_tags as $tag) {
			if (!empty($result)) {
				$content = $result;
				$result = '';
			}
			$codes = preg_split("/(<{$tag}[^>]*>.*<\\/{$tag}>)/Us", $content, -1, PREG_SPLIT_DELIM_CAPTURE);
			foreach ($codes as $code) {
				if (preg_match("/^<{$tag}[^>]*>(.*)<\\/{$tag}>/Us", $code, $match))
					$code = "[[{$tag}]]" . base64_encode(stripslashes($match[1])) . "[[/{$tag}]]";
				$result .= $code;
			}
		}
		return $result;
	} //end preserve_preprocess()

	function preserve_postprocess( $content, $preserve = false ) {
		global $wpdb;
		$options = $this->get_options();
		$preserve_tags = $options['preserve_tags'];
		$wrap_multiline_code_in_pre = $options['wrap_multiline_code_in_pre'];
		$result = '';
		foreach ($preserve_tags as $tag) {
			if (!empty($result)) {
				$content = $result;
				$result = '';
			}
			$codes = preg_split("/(\\[\\[{$tag}\\]\\].*\\[\\[\\/{$tag}\\]\\])/Us", $content, -1, PREG_SPLIT_DELIM_CAPTURE);
			foreach ($codes as $code) {
				if (preg_match("/\\[\\[{$tag}\\]\\](.*)\\[\\[\\/{$tag}\\]\\]/Us", $code, $match)) {
					$data = base64_decode($match[1]);
					if ($preserve) $data = $this->preserve_code_formatting($data);
					else $data = $wpdb->escape($data);
					$code = "<$tag>$data</$tag>";
					if ( $preserve && $wrap_multiline_code_in_pre && preg_match("/\n/", $data) )
						$code = '<pre>' . $code . '</pre>';
				}
				$result .= $code;
			}
		}
		return $result;
	} //end preserve_postprocess()

	function preserve_postprocess_and_preserve( $content ) {
		return $this->preserve_postprocess($content, true);
	}

} // end PreserveCodeFormatting

endif; // end if !class_exists()
if ( class_exists('PreserveCodeFormatting') ) :
	// Get the ball rolling
	$preserve_code_formatting = new PreserveCodeFormatting();
	// Actions and filters
	if (isset($preserve_code_formatting)) {
		register_activation_hook( __FILE__, array(&$preserve_code_formatting, 'install') );
	}
endif;

?>