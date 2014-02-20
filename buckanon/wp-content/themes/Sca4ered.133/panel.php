<br style="clear: both" /><br />
<center><div id="panel">

<center><?php if ((function_exists('get_flickrRSS')) && is_home() && !(is_paged())) { ?> 
	
	
			<?php get_flickrRSS(); ?>
	
	<?php } ?>
	

</center>


<table style="" width="100%" cellpadding="0" cellspacing="0"><tr><td valign="top" style='width:33%'>
<div style="padding: 5px; padding-bottom: 0px;">



	<?php if ((function_exists('blc_latest_comments')) && is_home()) { ?> 
	<div class="sb-comments"><h2><?php _e('Activity'); ?>    <a  href="<?php bloginfo('comments_rss2_url'); ?>" title="RSS Feed for all Comments"  ><img style="border: none;" src="<?php bloginfo('template_directory'); ?>/images/feed.png" alt="RSS" /></a></h2>
		
		
			<?php blc_latest_comments('5','10','false'); ?>
	
	</div>

	<?php } ?>
	

	</td>


<td valign="top" style='width:33%'>
<div style="padding: 5px; padding-bottom: 0px;">


	<?php if ((function_exists('UTW_ShowWeightedTagSet')) && is_home()) { ?> 
	
	<div class="sb-latest"><h2><?php _e('Tagtail'); ?></h2>
	
	<?php UTW_ShowWeightedTagSet('weightedlongtailvertical','',15) ?>
			
	</div><?php } ?>
</td>
<td valign="top" style='width:33%'>

<div style="padding: 5px; padding-bottom: 0px;">








</div>
<?php if ((function_exists('delicious')) && is_home() && !(is_paged()) ) { $k2deliciousname = get_option('k2deliciousname'); ?> 
	<div class="sb-delicious"><h2><a href="http://del.icio.us/<?php echo $k2deliciousname; ?>" title="My del.icio.us links library">Del.icio.us</a></h2>
		<span class="metalink"><a href="http://del.icio.us/rss/<?php echo $k2deliciousname; ?>" title="RSS Feed for del.icio.us links" class="feedlink"><img src="<?php bloginfo('template_directory'); ?>/images/feed.png" alt="RSS" /></a></span>
		<div>
			<?php delicious($k2deliciousname); ?>
		</div>
	</div>
	<?php } ?>

<br /><br />

</td></tr></table>
</div></div></center>