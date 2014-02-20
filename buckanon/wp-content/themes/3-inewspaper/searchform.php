<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div><input type="text" value="<?php if ($_GET['s'] == NULL) { echo "Enter search here..."; } else {?><?php the_search_query(); ?><?php }?>"  onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" name="s" id="s" />
<input type="submit" id="searchsubmit" value="Search" />
</div>
</form>
