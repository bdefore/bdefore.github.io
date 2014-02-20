<?php
include("squible_options.php");
include("elements.php");
?>

<div id="midpanel">

<table style="" width="100%" cellpadding="0" cellspacing="0"><tr><td valign="top" style='width:50%'>
<div style="padding: 10px; padding-bottom: 0px;">

<?php
include($include_midleft1);
include($include_midleft2);
include($include_midleft3);
include($include_midleft4);
?>

</div>
</td><td valign="top">
<div style="padding: 10px; padding-bottom: 0px;">

<?php
include($include_midright1);
include($include_midright2);
include($include_midright3);
include($include_midright4);
?>

</div>
</td></tr></table>
<br />

<div id="lowpanel">

<table width="100%" cellpadding="0" cellspacing="0"><tr><td valign="top" style='width:50%'>
<div style="padding: 10px;">

<?php
include($include_botleft_1);
include($include_botleft_2);
include($include_botleft_3);
include($include_botleft_4);
 ?>

<br /><br /><br />
<div class="tooltitle"><?php _e("Meta", "squible"); ?></div>
<div style="padding-top: 2px;">
<a title="RDF" href="<?php bloginfo('rdf_url'); ?>">RDF</a> | <a title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>">RSS</a> | <a title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>">Atom 0.3</a> | <a href="http://validator.w3.org/check?uri=referer">XHTML</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <?php wp_register('',''); ?>
</div>

</div>

</td><td valign="top">
<div style="padding: 10px;">

<?php
include($include_botright_1);
include($include_botright_2);
include($include_botright_3);
include($include_botright_4);
?>

</div>
<br />

</td></tr></table>
</div>

<?php get_footer(); ?>
