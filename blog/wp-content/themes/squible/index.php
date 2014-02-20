<?php get_header(); ?>
<?php include("squible_options.php"); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php if (in_category($asides_cat) && !$_GET['tag']) {continue;} ?>

<?php $the_date = the_date('','','', false);?>

<div id="toppanel">

<div class="postintro">
<div class="clearfix">
<?php if (!$_GET['tag']) { ?>
	<?php include("niftybox.php") ?>
<?php } ?>
	 <h2 class="storytitle" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><span style="display:inline; color:#ccc; font-weight: normal;"> <?php the_time('F j, Y'); ?></span></h2>
<?php if ($show_author) { ?>
<div class="author"><small><?php the_author('namefl'); ?></small></div>
<?php } ?>

	<?php if (function_exists('the_content_limit')) { ?>
		<?php the_content_limit($limitchars, ""); ?>
	<?php } else { ?>
		<?php the_content(__("Continue reading '", "squible") . the_title('', '', false) . "'"); ?>
	<?php } ?>

</div></div>

<?php if (!$_GET['tag']) break; ?>
<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.', 'squible'); ?></p>
<?php endif; ?>

</div>

<?php 
if (!$_GET['tag']) {
	include("inc.php");
} else {
	get_footer();
}
?>
