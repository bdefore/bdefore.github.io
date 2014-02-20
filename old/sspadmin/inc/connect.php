<?php require_once 'conf.php'; ?>
<?php
	$versionNumber = '1.3';
	
	$connection = @mysql_connect($host, $user, $pass) or die('<p>Error connecting to MySQL Database. If you are installing SSPAdmin, return to start.php and check your server name. If you have already installed SSPAdmin, your MySQL Server may be down. Contact your host.</p>');
	
	mysql_select_db($db);
	
	// Nothing to see here..
	$itbl = $pre.'images';
	$atbl = $pre.'albums';
	$utbl = $pre.'usrs';
	$dtbl = $pre.'dynamic';
	$dltbl = $pre.'dynamic_links';

	function mysqlclean($array, $index, $maxlength, $connection)
   {
     if (isset($array["{$index}"]))
     {
        $input = substr($array["{$index}"], 0, $maxlength);
        $input = mysql_real_escape_string($input, $connection);
        return ($input);
     }
     return NULL;
   }
   
	function getCount($s)
	{
		global $atbl, $itbl;
		if ($s == 'album')
			$t = $atbl.' WHERE active = 1';
		else
			$t = $itbl;
			
		$query = "SELECT id FROM $t";
		
		$result = mysql_query($query);
		
		echo(mysql_num_rows($result));
		
		if ($s == 'album')
		{
			$query = "SELECT id FROM $atbl WHERE active = '0'";
		
			$result = mysql_query($query);
		
			if (mysql_num_rows($result) != 0)
				echo ' ('.mysql_num_rows($result).' Inactive)';
		}
	}
	
	function getAlbumCount($aid)
	{
		global $itbl;
		$query = "SELECT id FROM $itbl WHERE active = '1' AND aid = $aid";
		
		$result = mysql_query($query);
		
		echo '('.mysql_num_rows($result).' Active Photos';
		
		$query = "SELECT id FROM $itbl WHERE active = '0' and aid = $aid";
		
		$result = mysql_query($query);
		
		if (mysql_num_rows($result) != 0)
			echo ', '.mysql_num_rows($result).' Inactive';
			
		echo')';
	}
	
	function writeImageBox($aid,$p,$s)
	{
		global $itbl;
		if (!$s) $tag = ' AND active = 1';
		$query = "SELECT * FROM $itbl WHERE aid=$aid{$tag} ORDER BY seq";
		$result = mysql_query($query);
		
		if (!$s)
		{
			echo '<div id="pic-spread-full">';
			echo '<ul id="boxes">';
		} else {
			echo '<div id="pic-spread">';
		}
		while ($row = mysql_fetch_array($result))
		{
		if ($s)
		{
			$f = 'javascript:editPic('.$row['id'].','.$aid.')';
			if ( ereg("swf", $row['src']) || ereg("flv", $row['src']) )
			{
				$mid = '<div id="'.$row['id'].'" class="boxy"><a href="'.$f.'">'.$row['src'].'</a></div>';
			} 
			else
			{
				$mid = '<a href="'.$f.'"><img id="'.$row['id'].'" src="albums/'.$p.'/lg/'.$row['src'].'" height="75" /></a>';
			}
			echo $mid;
		}
		else
		{
			if ( ereg("swf", $row['src']) || ereg("flv", $row['src']) )
			{
				echo('<li id="'.$row['id'].'"><div>'.$row['src'].'</div></li>');
			} 
			else
			{
				echo('<li id="'.$row['id'].'"><img src="albums/'.$p.'/lg/'.$row['src'].'" height="75" /></li>');
			}
		}
		}
		if (!$s)
		{
			echo '</ul><div style="clear: left;"><br/></div>';
		}
		echo '</div>';
	}
	
	function albumsList ()
	{
		global $atbl, $adminDir;
		$query = "SELECT * FROM $atbl ORDER BY displayOrder";
		$result = mysql_query($query);
		
		if (mysql_num_rows($result) != 0)
		{
			while ($row = mysql_fetch_array($result))
			{
				$aid = $row['id'];
				$aName = $row['name'];
				$aDes = $row['description'];
				$p = $row['path'];
				$tn = $row['tn'];
				$st = $row['active'];
				$start = $row['startHere'];
				$atn = str_replace(($adminDir.'/'), '', $row['aTn']);
				
				if ($st == 1)
				{
					$st = 'Active';
					$l = 'Make Inactive';
					$o = 0;
				}
				else
				{
					$st = 'Inactive';
					$l = 'Make Active';
					$o = 1;
				}
				
				if ($atn != '')
					$atn = "<img src=\"$atn\" class=\"album-thumb\" />";
				
				if ($st == 'Active')
				{
				if ($start == 1)
					$start = 'Slideshow Starts with This Album (<a href="swap-start-album.php?aid='.$aid.'&act=2">Disable</a>)';
				else
					$start = '<a href="swap-start-album.php?aid='.$aid.'&act=1">Make this the <strong>startHere</strong> album</a>';
				}
				else
				{
					$start = 'Album must be active to be the <strong>startHere</strong> album';

				}
				
				echo '<div class="album-wrap"><h3 class="album-header">'.$aName.'</strong><small> ';
				getAlbumCount($aid);
				echo '</small></h3>'.$atn;
				echo '<ul class="album-sub"><li>Album ID for Linking: <strong>'.$aid.'</strong></li><li class="lock">Currently '.$st.' (<a href="swap-activation.php?aid='.$aid.'&opt='.$o.'">'.$l.'</a>)</li>';
				echo '<li class="start">'.$start.'</li>';
				echo '<li class="config"><a href="edit-album.php?aid='.$aid.'">Edit Album Metadata</a></li><li class="img"><a href="edit-album-images.php?aid='.$aid.'">Edit Album Images</li><li class="img"><a href="edit-album-order.php?aid='.$aid.'">Edit Image Order</li><li class="img"><a href="generate-thumbs.php?aid='.$aid.'">Generate Thumbnails</li><li class="search"><a href="add-album-up-single.php?aid='.$aid.'">Upload a New Image to this Gallery</a></li><li class="search"><a href="album-rescan.php?aid='.$aid.'">Rescan for New Images</a></li><li class="delete"><a href="javascript:albumDeleteConfirm('.$aid.')">Delete this Album</a> (Cannot Be Undone)</li></ul></div>';
			}
		}
		else
		{
		echo '<p>No Albums to Edit. <a href="add-album.php">Click here</a> to add an album.</p>';
		}
}

function dynAlbumsList ()
	{
		global $dtbl, $adminDir;
		$query = "SELECT * FROM $dtbl";
		$result = mysql_query($query);
		
		if (mysql_num_rows($result) != 0)
		{
			echo '<ul class="album-sub">';
			while ($row = mysql_fetch_array($result))
			{
				$did = $row['id'];
				$aName = $row['name'];
				
				echo '<li><a href="edit-dynamic-single.php?did='.$did.'">'.$aName.'</a> (ID for linking: <strong>'.$did.'</strong>) <a href="javascript:dynAlbumDeleteConfirm('.$did.')">Delete</a></li>';
			}
			echo '</ul>';
		}
		else
		{
		echo '<p><em>No dynamic Galleries Yet. Add One Above.</em></p>';
		}
}

function albumsListOrder ()
	{
		global $atbl, $adminDir;
		$query = "SELECT * FROM $atbl WHERE Active = '1' ORDER BY displayOrder";
		$result = mysql_query($query);
		
		if (mysql_num_rows($result) != 0)
		{
			echo '<ul id="albums">';
			while ($row = mysql_fetch_array($result))
			{
				$aid = $row['id'];
				$aName = $row['name'];
				echo '<li id="'.$aid.'">'.$aName.'</li>';
			}
			echo '</ul>';
			
		}
		else
		{
			echo '<p>No Active Albums.</p>';
		}
	}
	
function dynAlbumsListOrder ($did)
	{
		global $atbl, $dltbl;
		$query = "SELECT * FROM $dltbl WHERE did='$did' ORDER BY display";
		$result = mysql_query($query);
		
		if (mysql_num_rows($result) != 0)
		{
			echo '<ul id="albums">';
			while ($row = mysql_fetch_array($result))
			{
				$aid = $row['aid'];
				$q = "SELECT name FROM $atbl WHERE id=$aid";
				$r = mysql_query($q);
	
	
				while ($rw = mysql_fetch_array($r))
				{
					$aName = $rw['name'];
				}
				echo '<li id="'.$aid.'">'.$aName.'</li>';
			}
			echo '</ul>';
			
		}
		else
		{
			echo '<p>No Active Albums.</p>';
		}
	}
?>