<?php
/*
Plugin Name: Twitter widget
Description: Sidebar widget to display your Twitter timeline. The widget now allows you to choose whether or not to display your twitter username in front of your updates. 
Author: Sarah Isaacson
Version: 1.3beta
Plugin URI: http://www.velvet.id.au/twitter-wordpress-sidebar-widget/
Author URI: http://velvet.id.au
License:  Creative Commons Attribution-Noncommercial-Share Alike 2.5
Warranties: None
Last Modified: 28th May 2007 (Western Australian Time +8 UTC)
*/
/* Credits and thanks: I learned lots from the Automattic Inc del.icio.us widget plugin. Thanks guys */
/* And obviously, the good folks at Obvious Corp need to be thanked for creating Twitter */ 
/* Thank you also to Lionel of Rarsh.com for providing the fix for the NaN bug in IE after Twitter changed something in their code recently - 7th May 2007 */
/* 28th May 2007
  Updated after twitter changed their badge code to use the format 
  /statuses/user_timeline/userid.json?callback=twitterCallback&?callback=twitterCallback&count=5
  from http://twitter.com/t/status/user_timeline/userid?callback=twitterCallback&amp;count=6&amp;named_obj
*/

/*
This work is licensed under the Creative Commons Attribution-Noncommercial-Share Alike 2.5 License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/2.5/ or send a letter to Creative Commons, 543 Howard Street, 5th Floor, San Francisco, California, 94105, USA.

There's probably a lot of mistakes and errors and I have no idea how this is for accessibility. My coding skills aren't strong and everything that is in this plugin has been learned mostly through trial and error. I apologise if this breaks stuff for you, but I welcome suggestions for improvements. Personally I'm astounded that this works at all on even my weblog. I hope that it works for you. I will try to fix things if I can, but I really am not a strong coder, so you may have to wait until someone who knows more than I do can fix it. 
Cheers and beers,
Sarah
*/

// Add the twitter widget to plugin loading
function widget_twitterer_init() {

        // Check for the sidebar widget functions
                if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
        return;

                // This is the widget's configuration form - will save the options too.
                        function widget_twitterer_control() {
                $options = $newoptions = get_option('widget_twitterer');
                    if ( $_POST['twitterer-submit'] ) {
                          $newoptions['title'] = strip_tags(stripslashes($_POST['twitterer-title']));
                          $newoptions['tuser'] = strip_tags(stripslashes($_POST['twitterer-tuser']));
                          $newoptions['count'] = (int) $_POST['twitterer-count'];
                //          $newoptions['tuserid'] = (int) $_POST['twitterer-tuserid'];
                          $newoptions['ttimetext'] = strip_tags(stripslashes($_POST['twitterer-ttimetext']));
                          $newoptions['tshowname'] = isset($_POST['twitterer-showname']);
                // title is the widget title to apear in the sidebar, tuser is the twitter user's screen name, count is the number of entries to display and tuserid is the numeric twitter user ID
                      }
                      if ( $options != $newoptions ) {
                            $options = $newoptions;
                            update_option('widget_twitterer', $options);
                      }
                       
                 // This is so your plugin will work if you don't set any options
                 // I suggest you change them unless you find my twitters regarding this plugin particularly interesting.
                      if ( $newoptions['tuser'] == null ) {
                      $options['tuser'] = 'wptwitter';
                      update_option('widget_twitterer', $options);
                      }
//                      if ( $newoptions['tuserid'] == null ) {
//                      $options['tuserid'] = ((int) '32103');
//                      $options['title'] = 'WP Twitter Widget News';
//                      $options['ttimetext'] = 'This happened';
//                      update_option('widget_twitterer', $options);
//                      }
                      if ( $newoptions['count'] == null ) {
                      $options['count'] = ((int) '1');
                      $options['title'] = 'WP Twitter Widget News';
                      $options['ttimetext'] = 'This happened';
                      update_option('widget_twitterer', $options);
                      }

                // For the show name options
                      $tshowname = $options['tshowname'] ? 'checked="checked"' : '';

              ?>
<?php // I've kept the styling from the del.icio.us plugin to keep things consistent in the widget options user interface
?>
<div style="text-align:right">
     <label for="twitterer-title" style="line-height:32px;display:block;"><?php _e('Widget title:', 'widgets'); ?> <input type="text" id="twitterer-title" name="twitterer-title" value="<?php echo wp_specialchars($options['title'], true); ?>" /></label>    
     <label for="twitterer-tuser" style="line-height:32px;display:block;"><?php _e('Twitter username:', 'widgets'); ?> <input type="text" id="twitterer-tuser" name="twitterer-tuser" value="<?php echo wp_specialchars($options['tuser'], true); ?>" /></label>
<!--     <label for="twitterer-tuserid" style="line-height:32px;display:block;"><?php _e('Twitter user ID:', 'widgets'); ?> <input type="text" id="twitterer-tuserid" name="twitterer-tuserid" value="<?php echo wp_specialchars($options['tuserid'], true); ?>" /></label> -->
     <label for="twitterer-count" style="line-height:32px;display:block;"><?php _e('Number of updates:', 'widgets'); ?> <input type="text" id="twitterer-count" name="twitterer-count" value="<?php echo $options['count']; ?>" /></label>
     <label for="twitterer-ttimetext" style="line-height:32px;display:block;"><?php _e('Text before time:', 'widgets'); ?> <input type="text" id="twitterer-ttimetext" name="twitterer-ttimetext" value="<?php echo wp_specialchars($options['ttimetext'], true); ?>" /></label>    
     <label for="twitterer-showname" style="line-height:32px;display:block;"><?php _e('Show Twitter Name', 'widgets'); ?> <input class="checkbox" type="checkbox" id="twitterer-showname" name="twitterer-showname" <?php echo $tshowname; ?> /></label>
     <input type="hidden" name="twitterer-submit" id="twitterer-submit" value="1" />
     </div>

<?php
         }
          // This shows the widget on the sidebar
         function widget_twitterer($args) {
                 extract($args);
                
                 $defaults = array('count' => 5, 'title' => 'Latest Twitters', 'tuser' => 'wptwitter');
//                 $defaults = array('count' => 5, 'title' => 'Latest Twitters', 'tuser' => 'wptwitter', 'tuserid' => '32103');
                 $options = (array) get_option('widget_twitterer');
                 foreach ( $defaults as $key => $value )
                         if ( !isset($options[$key]) )
                         $options[$key] = $defaults[$key];
?>
<?php   //set variables refer to later
                         $twitter_url = 'http://twitter.com/';
                         $my_twitter_feed_url = $twitter_url . 'statuses/user_timeline/' . ($options['tuser']);
//                         $my_twitter_feed_url = $twitter_url . 't/status/user_timeline/' . ((int) $options['tuserid']);
                         $my_twitter_feed_url.= '.json?callback=twitterCallback&amp;count=' . ((int) $options['count']) .  '&amp;named_obj';
                         $my_twitter = $twitter_url . $options['tuser'] . '/';
                         $setcount =  ($options['count']); 

        // This is to take care of extra spaces if the time text happens to be blank
                      if ( $options['ttimetext'] == null ) {
                      $timetext = (' ');
                      }
                      else {  $timetext = ' ' . $options['ttimetext'] . ' '; }

         // options for showing username 
         $sn = $options['tshowname'] ? '1' : '0';

 ?>

 <!-- start good stuff --> 
<?php // The next two bits are using functions of the Automattic sidebar widget plugin
?>
<?php echo $before_widget;  ?>
<?php echo $before_title . "<a href='" . $my_twitter . "' title='My Twitter page at Twitter.com'>{$options['title']}</a>" . $after_title; ?>

<?php // fetches the now customised twitter feed url 
?>

<script type="text/javascript" src="<?php echo $my_twitter_feed_url; ?>"></script>

<div id="twitter-box" style="overflow: hidden; /* to hide the overflow of long urls */"> </div>
 <script type="text/javascript"><!--
   
  function relative_created_at(time_value) {  // thanks to Lionel of rarsh.com for pointing out that Twitter changed their code, and this is the fix which will work in IE
     var created_at_time = Date.parse(time_value.replace(" +0000",""));
     var relative_time = ( arguments.length > 1 ) ? arguments[1] : new Date();
     var wordy_time = parseInt(( relative_time.getTime() - created_at_time ) / 1000) + (relative_time.getTimezoneOffset()*60);

       if ( wordy_time < 59 ) {
         return 'less than a minute ago';
         } 
       else if ( wordy_time < 89) {
         return 'about a minute ago';
         } 
       else if ( wordy_time < 3000 ) {         // < 50 minutes ago
         return ( parseInt( wordy_time / 60 )).toString() + ' minutes ago';
         } 
       else if ( wordy_time < 5340 ) {         // < 89 minutes ago
         return 'about an hour ago';
         } 
       else if ( wordy_time < 9000 ) {          // < 150 minutes ago
         return 'a couple of hours ago';  
         }
       else if ( wordy_time < 82800 ) {         // < 23 hours ago
         return 'about ' + ( parseInt( wordy_time / 3600 )).toString() + ' hours ago';
         } 
       else if ( wordy_time < 129600 ) {       //  < 36 hours
         return 'a day ago';
         }
       else if ( wordy_time < 172800 ) {       // < 48 hours
         return 'almost 2 days ago';
         }
       else {
         return ( parseInt(wordy_time / 86400)).toString() + ' days ago';
         }
    }

   var ul = document.createElement('ul');
   for (var i=0; i < <?php echo $setcount ; ?>; i++) {
     var post = Twitter.posts[i]; 
     var li = document.createElement('li');
     var showTwitterName = <?php echo $sn; ?>;
       if ( showTwitterName == 1 ) {
          li.appendChild(document.createTextNode(post.user.name + ' '));
          }
     li.appendChild(document.createTextNode(post.text));
     li.appendChild(document.createTextNode('<?php echo $timetext; ?>')); 
     var a = document.createElement('a');
     a.setAttribute('href', '<?php echo $my_twitter; ?>' + 'statuses/' + post.id);
     a.setAttribute('title', 'Permalink to this twitter (id ' + post.id + ') at Twitter.com');
     a.appendChild(document.createTextNode(relative_created_at(post.created_at)));
     li.appendChild(a); 
     ul.appendChild(li);
     }
  ul.setAttribute('id', 'twitter-list');
  document.getElementById('twitter-box').appendChild(ul);
-->
</script>


<!-- end experiment good stuff -->
<?php echo $after_widget; ?>
<?php
        }

        // Tell the sidebar about the Twitter widget and its control
        register_sidebar_widget(array('Twitters', 'widgets'), 'widget_twitterer');
        register_widget_control(array('Twitters', 'widgets'), 'widget_twitterer_control');

}

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
add_action('widgets_init', 'widget_twitterer_init');

?>
