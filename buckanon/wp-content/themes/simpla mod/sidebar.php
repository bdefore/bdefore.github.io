<div id="sidebar">
	<div class="entrytitle">
		<h2>Springboard</h2> 
		<h3>Recent notables.</h3>
	</div>
<?php feedList("http://feeds.delicious.com/rss/bdefore/buckanon?count=100",0,true,false,'<div class="entry"><p>','</p></div>',' ',false ); ?>
	<div class="entrytitle">
		<h2>Musicboard</h2> 
		<h3>What's in my ear:</h3>
	</div>
<?php 	wpaudioscrobbler(); ?>
	<div class="entrytitle">
		<h2>Twitterboard</h2> 
		<h3>Dispatches from the front.</h3>
	</div>
<?php twitterFeed(); ?>
	<p>
</div>
