<?php get_header(); ?>

<div class="primary" style="float: right; padding: 20px 20px 0 10px;
	margin: 0 5% 0 0px; ">

	<?php include (TEMPLATEPATH . '/theloop.php'); ?>

</div>


<?php include("sidebarr.php"); ?>


<?php /* Show Asides only on the frontpage */ if (!is_paged() && is_home()) { ?>
	<?php include("panel.php"); ?>
	<?php } ?>
<?php get_footer(); ?>