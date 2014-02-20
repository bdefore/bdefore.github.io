<?php if (function_exists('get_flickrRSS')) { ?>
<div class="tooltitle">Flickr <a style="text-decoration: none; font-size: 8pt;" href="<?php echo $flickr_url; ?>"><?php _e("(more...)", "squible"); ?></a></div>
<?php get_flickrRSS($flickr_userid, "userid", $numpics, small, "", "", ""); ?><br /><br />
<?php } ?>