<div id="sidebar">
<div class="sidelist">
<h2>Suche</h2>
<ul>
<li>
<?php include (TEMPLATEPATH . '/searchform.php'); ?>
</li>
</ul>
</div>

<div class="sidelist">
<ul>
<?php wp_list_pages('title_li=<h2>Seiten</h2>'); ?>
</ul>
</div>

<div class="sidelist">
<h2>Archiv</h2>
<ul>
<?php wp_get_archives('type=monthly'); ?>
</ul>
</div>

<div class="sidelist">
<ul>
<?php wp_list_categories('title_li=<h2>Kategorien</h2>'); ?>
</ul>
</div>

<div class="sidelist">
<ul>
<?php wp_list_bookmarks(); ?>
</ul>
</div>


<div class="sidelist">
<h2>Meta</h2>
<ul>
<?php wp_register(); ?>
<li><?php wp_loginout(); ?></li>
<li><a href="http://wordpress-deutschland.org/" title="WordPress Deutschland">WordPress Deutschland</a></li>
</ul>
</div>

</div><!--#### Ende Sidebar ###-->