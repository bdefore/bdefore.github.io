
<!-- begin sidebar -->
<div id="right">
	<!--
		<div id="author">
			Here is a section you can use to briefly talk about yourself or your site. Uncomment and delete this line to use.
			<h3><?php _e('The Author'); ?></h3>
			<p>Your description here.</p>
		</div>
		
		<div class="line"></div>
	-->
		<div id="links">
		
		
		<h3><?php _e('Categories'); ?></h3>
				<ul>
					<?php wp_list_cats(); ?>
				</ul>	
		<div class="line"></div>

		<div id="pages">
			<h3><?php _e('Projects'); ?></h3>
				<ul>
					<?php wp_list_pages('title_li='); ?>
				</ul>
		</div>
			
		
		<div class="line"></div>
		
		<h3><?php _e('Associates'); ?></h3>
			<ul>
				<?php get_links('-1', '<li>', '</li>', '', 0, 'name', 0, 0, -1, 0); ?>
			</ul>
				
		<div class="line"></div>
		
		<h3><?php _e('Archives'); ?></h3>
			<ul>
		 		<?php wp_get_archives('type=monthly'); ?>
 			</ul>
				
			<?php if (function_exists('wp_theme_switcher')) { ?>
			<div class="line"></div>

			<h3><?php _e('The Themes' ); ?></h3>
			<div class="themes">
				<?php wp_theme_switcher(); ?>
			</div>
			<?php } ?>
		</div>
		
<!--	FeedList plugin -->
<!--
		<div class="line"></div>
			<ol>
	
			<?php 
	/*
				feedList(array("rss_feed_url"=>"http://weblogs.macromedia.com/mxna/xml/rss.cfm?query=bySmartCategory&languages=1&smartCategoryId=4&smartCategoryKey=D03946CE-BE57-8C18-7F13A6688166DAA8",
	
								"num_items"=>10,
	
								"show_description"=>false,
	
								"random"=>false,
	
								"sort"=>"asc","new_window"=>true)); 
	
	*/
	?>
	
			</ol>
			
-->	

<!--	FAlbum plugin -->

<!-- <div class="line"></div>
 <?php
 //echo fa_show_recent(5);
 ?> 
	-->
	<div class="line"></div>
		<div id="flashcontent"></div>
		<script type="text/javascript">
			// <![CDATA[
			var fo = new FlashObject("/swf/mxna.swf", "mxnaSwf", "220", "500", "7", "#ffffff", false, "high");
			fo.addParam("menu",				"false");
			fo.addParam("wmode",			"transparent");
			fo.write("flashcontent");
			// ]]>
		</script>
		
</div>

<!-- end sidebar -->
