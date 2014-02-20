<div class="tooltitle"><?php _e('Popular Tags', 'squible'); ?></div>
<div class="poptags">
<p><?php
if (function_exists('UTW_ShowWeightedTagSetAlphabetical')) {
	UTW_ShowWeightedTagSetAlphabetical("coloredsizedtagcloud", '', 25);
} else {
	popular_tags($minfont, $maxfont, $fontunit, $category_ids_to_exclude, $numberoftags); 
}
?></p>
</div>