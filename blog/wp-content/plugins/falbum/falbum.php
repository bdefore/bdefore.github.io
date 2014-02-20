<?php
/*
Copyright (c) 2005
Released under the GPL license
http://www.gnu.org/licenses/gpl.txt

This file is part of WordPress.
WordPress is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

$falbum_options = get_option('falbum_options');

define ('FALBUM_VERSION', '0.5.6');

define ('FALBUM_THUMBNAIL_SIZE', $falbum_options['falbum_tsize']); //Size of the thumbnail you want to appear in the album thumbnail page ('s' = 75px x 75px, 't' = 100px x 75px, 'm' = 240px x 180px)
define ('FALBUM_SHOW_PRIVATE', $falbum_options['falbum_show_private']); //Whether or not to show your "private" Flickr photos
define ('FALBUM_WP_USER_LEVEL', $falbum_options['falbum_wp_user_level']); //If showing private, user level that can see them

define ('FALBUM_USE_FRIENDLY_URLS', $falbum_options['falbum_friendly_urls']); //Whether or not to use friendly URLs
define ('FALBUM_URL_ROOT', $falbum_options['falbum_url_root']); //URL to use as the root for all navigational links

define ('FALBUM_ALBUMS_PER_PAGE', $falbum_options['falbum_albums_per_page']); //How many albums to show on a page (0 for no paging)
define ('FALBUM_PHOTOS_PER_PAGE', $falbum_options['falbum_photos_per_page']); //How many photos to show on a page (0 for no paging)
define ('FALBUM_NUMBER_RECENT', $falbum_options['falbum_number_recent']);
define ('FALBUM_MAX_PHOTO_WIDTH', $falbum_options['falbum_max_photo_width']);
define ('FALBUM_DISPLAY_DROPSHADOW', $falbum_options['falbum_display_dropshadows']);
define ('FALBUM_DISPLAY_SIZES', $falbum_options['falbum_display_sizes']);
define ('FALBUM_DISPLAY_EXIF', $falbum_options['falbum_display_exif']);

define ('FALBUM_CACHE_EXPIRE_SHORT', 3600); //How many seconds to wait between refreshing cache (default = 3600 seconds - hour)
define ('FALBUM_CACHE_EXPIRE_LONG', 604800); //How many seconds to wait between refreshing cache (default = 604800 seconds - 1 week)

define ('FALBUM_API_KEY', 'e746ede606c9ebb66ef79605ec834c07');
define ('FALBUM_SECRET', '46d7a532dd766c9e');

define ('FALBUM_TOKEN', $falbum_options['falbum_token']);
define ('FALBUM_NSID', $falbum_options['falbum_nsid']);

/* The main function - called in falbum-wp.php, and can be called in any WP template. */
function fa_show_photos ($album = null, $photo = null, $page = 0, $tags = null, $show = null)
{
	$output = '';

	$continue = true;

	if (!is_null($show)){

		if ($show == 'tags') {
			$output = fa_showTags();
			$continue = false;

		} elseif ($show == 'recent') {
			$tags = '';
		}

	}

	if ($continue) {
		// Show list of albums/photosets (none have been selected yet)
		if (is_null($album) && is_null($tags) && is_null($photo)) {
			$output = fa_showAlbums($page);
		}
		// Show list of photos in the selected album/photoset
		elseif (!is_null($album) && is_null($photo)) {
			$output = fa_showAlbumThumbnails($album, $page);
		}
		// Show list of photos of the selected tags
		elseif (!is_null($tags) && is_null($photo))	{
			$output = fa_showTagsThumbnails($tags, $page);
		}
		// Show the selected photo in the slected album/photoset
		elseif ((!is_null($album) || !is_null($tags)) && !is_null($photo)) {
			$output = fa_showPhoto($album, $tags, $photo, $page);
		}
	}
	echo "\n<!-- Start of FAlbum -->\n<div id='falbum' class='falbum'>\n$output</div>\n<!-- End of Falbum -->\n";
}

/* Shows list of all albums - this is the first thing you see */
function fa_showAlbums($page = 1) {
	if ($page == '') {
		$page = 1;
	}

	list($output, $expired) = fa_getCacheData("showAlbums-$page");

	if (!isset($output) || $expired) {

		$output = '';

		$xpath = fa_callFlickr('flickr.photosets.getList','user_id='.FALBUM_NSID);
		if (is_object($xpath)) {

			$count = 0;
			$output2 = '';

			if ($page == 1 && FALBUM_NUMBER_RECENT != 0) {

				$xpath2 = fa_callFlickr('flickr.photos.search','user_id='.FALBUM_NSID.'&per_page=1&sort=date-taken-desc');

				if (is_object($xpath2)) {
					$result = $xpath2->match('/rsp/photos/photo');
					$countResult = count($result);

					for ($i = 0; $i < $countResult; $i++) {
						$server = $xpath2->getData($result[$i].'/@server');
						$secret = $xpath2->getData($result[$i].'/@secret');
						$photo_id = $xpath2->getData($result[$i].'/@id');
						$thumbnail = "http://static.flickr.com/{$server}/{$photo_id}_{$secret}_".FALBUM_THUMBNAIL_SIZE.".jpg"; // Build URL to thumbnail

						$output2 .= "<div class='falbum-album'>\n";
						$output2 .= '<div class="falbum-tn-border-'.FALBUM_THUMBNAIL_SIZE.'">';
						$output2 .= "<div class='falbum-thumbnail'>";
						$output2 .= "<a href='".fa_createURL("show/recent/")."'>";
						$output2 .= "<img src='$thumbnail' alt='' /></a>";
						$output2 .= "</div>\n"; // falbum-thumbnail
						$output2 .= "</div>\n"; // falbum-tn-border
						$output2 .= "<h3 class='falbum-title'>";
						$output2 .= "<a href='".fa_createURL('show/recent/')."' title='".__('View all recent photos', FALBUM_DOMAIN)."'>";
						$output2 .= __('Recent Photos', FALBUM_DOMAIN)."</a> / <a href='".fa_createURL('show/tags/')."'>".__('Tags', FALBUM_DOMAIN)."</a>\n";
						$output2 .= "</h3>\n";
						$output2 .= "<div class='falbum-meta'></div>\n";
						$output2 .= "<div class='falbum-album-description'>".__('See the most recent photos posted, regardless of which photo set they belong to.')."</div>\n";

						$output2 .= "</div>\n"; // falbum-album
					}

				} else {
					$output .= "<p class='fa_error'>Error: $xpath2<br />Section: fa_showAlbums -> \$xpath2</p>";
				}
			}

			if (FALBUM_NUMBER_RECENT != 0) {
				$count++;
			}

			$result = $xpath->match('/rsp/photosets/photoset');
			$countResult = count($result);

			$photo_title_array = array();
			for ($i = 0; $i < $countResult; $i++) {

				if ((FALBUM_ALBUMS_PER_PAGE == 0)
				|| (($count >= ($page - 1) * FALBUM_ALBUMS_PER_PAGE) && ($count < $page * FALBUM_ALBUMS_PER_PAGE))) {

					$photos = $xpath->getData($result[$i].'/@photos');

					if($photos != 0) {
						$id = $xpath->getData($result[$i].'/@id');
						$server = $xpath->getData($result[$i].'/@server');
						$primary = $xpath->getData($result[$i].'/@primary');
						$secret = $xpath->getData($result[$i].'/@secret');
						$title = fa_unhtmlentities($xpath->getData($result[$i].'/title'));
						$description = fa_unhtmlentities($xpath->getData($result[$i].'/description'));

						if (!in_array($title, $photo_title_array)) {
							$link_title = $title;
							$photo_title_array[$id] = $title;

						} else {
							$dup_count = 1;
							while (in_array($title . '-' . $dup_count, $photo_title_array)) {
								$dup_count++;
							}
							$link_title = $title . '-' . $dup_count;
							$photo_title_array[$id] = $link_title;
						}

						$thumbnail = "http://static.flickr.com/{$server}/{$primary}_{$secret}_".FALBUM_THUMBNAIL_SIZE.".jpg"; // Build URL to small square thumbnail

						$output2 .= "<div class='falbum-album'>\n";
						$output2 .= '<div class="falbum-tn-border-'.FALBUM_THUMBNAIL_SIZE.'">';
						$output2 .= "<div class='falbum-thumbnail'>";
						$output2 .= "<a href='";
						if (FALBUM_USE_FRIENDLY_URLS == 'true') {
							$output2 .= fa_createURL('album/'.sanitize_title($link_title).'/');
						} else {
							$output2 .= fa_createURL("album/$id");
						}
						$output2 .= "' title='$title'>";
						$output2 .= "<img src='$thumbnail' alt='' />";
						$output2 .= "</a>\n";

						$output2 .= "</div>\n"; // falbum-thumbnail
						$output2 .= "</div>\n"; // falbum-tn-border

						$output2 .= "<h3 class='falbum-title'>";
						if (FALBUM_USE_FRIENDLY_URLS == 'true') {
							$output2 .= "<a href='".fa_createURL('album/'.sanitize_title($link_title))."/' title='".strtr(__('View all pictures in #title#', FALBUM_DOMAIN),array("#title#"=>$title));
						} else {
							$output2 .= "<a href='".fa_createURL("album/$id")."' title='".strtr(__('View all pictures in #title#', FALBUM_DOMAIN),array("#title#"=>$title));
						}

						$output2 .= "'>$title</a>";
						$output2 .= "</h3>\n";
						$output2 .= "<div class='falbum-meta'>".strtr(__('This photoset has #num_photots# pictures', FALBUM_DOMAIN),array("#num_photots#"=>$photos))."</div>\n";
						$output2 .= "<div class='falbum-album-description'>$description</div>\n";
						$output2 .= "</div>\n"; // falbum-album

					} else {
						$count--;
					}
				}
				$count++;
			}

			if (FALBUM_ALBUMS_PER_PAGE != 0) {
				$pages = ceil($count / FALBUM_ALBUMS_PER_PAGE);
				if ($pages > 1) {
					$output .= fa_buildPaging($page, $pages, 'page/','top');
				}
			}

			$output .= $output2;

			if (FALBUM_ALBUMS_PER_PAGE != 0) {
				if ($pages > 1) {
					$output .= fa_buildPaging($page, $pages, 'page/','bottom');
				}
			}

			fa_setCacheData("showAlbums-$page",$output);
		} else {
			$output .= "<p class='fa_error'>Error: $xpath<br />Section: fa_showAlbums -> \$xpath</p>";
		}
	}

	return $output;
}

/* Shows Thumbnails of all photos in selected album */
function fa_showAlbumThumbnails($album, $page = 1) {
	if ($page == '') {
		$page = 1;
	}

	list($output, $expired) = fa_getCacheData("showAlbumThumbnails-$album-$page");
	if (!isset($output) || $expired) {

		$output =  '';

		$top_level_xpath = fa_callFlickr('flickr.photosets.getList','user_id='.FALBUM_NSID);

		if (!is_object($top_level_xpath)) { echo "<p class='fa_error'>Error: $top_level_xpath<br />Section: fa_showAlbumThumbnails -> \$top_level_xpath</p>"; return; }

		if (FALBUM_USE_FRIENDLY_URLS == 'true') {
			$album_array = fa_get_album_id($top_level_xpath);
			while($album_title = current($album_array)) {
				if (sanitize_title($album_title) == $album) {
					$album_id = key($album_array);
					break;
				}
				next($album_array);
			}
		} else {
			$album_id = $album;
		}

		$xpath = fa_callFlickr('flickr.photosets.getPhotos','photoset_id='.$album_id);
		if(is_object($xpath)) {
			$title = $top_level_xpath->getData("//photoset[@id='$album_id']/title");

			$output .= "<h3 class='falbum-title'><a href='".fa_createURL()."'>".__(Photos, FALBUM_DOMAIN)."</a> &raquo; {$title}</h3>\n";
			$output .= "<div class='falbum-meta'>";
			$output .= "<div class='falbum-slideshowlink'><a href='#'  onclick=\"window.open('http://www.flickr.com/slideShow/index.gne?set_id={$album_id}','slideShowWin','width=500,height=500,top=150,left=70,scrollbars=no, status=no, resizable=no')\">".__('View as a slide show', FALBUM_DOMAIN)."</a></div>\n";
			$output .= "</div>\n";

			$result = $xpath->match('/rsp/photoset/photo');
			$countResult = count($result);

			if (FALBUM_PHOTOS_PER_PAGE != 0) {
				$pages = ceil($countResult / FALBUM_PHOTOS_PER_PAGE);

				if ($pages > 1 ) {
					if (FALBUM_USE_FRIENDLY_URLS == 'true') {
						$output .= fa_buildPaging($page, $pages, 'album/'.$album.'/page/','top');
					} else {
						$output .= fa_buildPaging($page, $pages, "album/$album/page/",'top');
					}
				}
			}

			$photo_title_array = array();

			$count = 0;
			for ($i = 0; $i < $countResult; $i++) {

				$photo_id = $xpath->getData($result[$i].'/@id');
				$photo_title = $xpath->getData($result[$i].'/@title');

				if (!in_array($photo_title, $photo_title_array)){
					$photo_title_array[$photo_id] = $photo_title;
				} else {
					$dup_count = 1;
					while (in_array($photo_title . '-' . $dup_count, $photo_title_array)) {
						$dup_count++;
					}
					$photo_title = $photo_title . '-' . $dup_count;
					$photo_title_array[$photo_id] = $photo_title;
				}

				if ((FALBUM_PHOTOS_PER_PAGE == 0) || (($count >= ($page - 1) * FALBUM_PHOTOS_PER_PAGE) && ($count < ($page * FALBUM_PHOTOS_PER_PAGE)))) {
					$server = $xpath->getData($result[$i].'/@server');
					$secret = $xpath->getData($result[$i].'/@secret');

					$output .= '<div class="falbum-tn-border-'.FALBUM_THUMBNAIL_SIZE.'">';
					$output .= "<div class='falbum-thumbnail'>";

					$thumbnail = "http://static.flickr.com/{$server}/{$photo_id}_{$secret}_".FALBUM_THUMBNAIL_SIZE.".jpg"; // Build URL to thumbnail
					if (FALBUM_PHOTOS_PER_PAGE != 0) {
						if (FALBUM_USE_FRIENDLY_URLS == 'true') {
							$output .= "<a href='".fa_createURL('album/'.$album."/page/$page/photo/".sanitize_title($photo_title).'/')."'>";
						} else {
							$output .= "<a href='".fa_createURL("album/$album/page/$page/photo/$photo_id")."'>";
						}
					} else {
						if (FALBUM_USE_FRIENDLY_URLS == 'true') {
							$output .= "<a href='".fa_createURL('album/'.$album.'/photo/'.sanitize_title($photo_title).'/')."'>";
						} else {
							$output .= "<a href='".fa_createURL("album/$album/photo/$photo_id")."'>";
						}
					}
					$output .= "<img src='$thumbnail' alt=\"".htmlentities($photo_title)."\" title=\"".htmlentities($photo_title)."\" />";
					$output .= "</a></div></div>";
				}
				$count++;
			}

			if (FALBUM_PHOTOS_PER_PAGE != 0 && $pages > 1) {
				if (FALBUM_USE_FRIENDLY_URLS == 'true') {
					$output .= fa_buildPaging($page, $pages, 'album/'.$album.'/page/','bottom');
				} else {
					$output .= fa_buildPaging($page, $pages, "album/$album/page/",'bottom');
				}
			}
		} else {
			$output .= "<p class='fa_error'>Error: $xpath<br />Section: fa_showAlbumThumbnails -> \$xpath</p>";
		}

		fa_setCacheData("showAlbumThumbnails-$album-$page",$output);
	}
	return $output;
}

/* Shows thumbnails for all Recent and Tag thumbnails */
function fa_showTagsThumbnails($tags, $page = 1) {

	if ($page == '') {
		$page = 1;
	}

	list($output, $expired) = fa_getCacheData("showTagsThumbnails-$tags-$page");
	if (!isset($output) || $expired) {

		$output = '';

		if ($tags == '') {
			// Get recent photos
			if (FALBUM_NUMBER_RECENT == -1) {
				$xpath = fa_callFlickr('flickr.photos.search','user_id='.FALBUM_NSID.'&sort=date-taken-desc&per_page='.FALBUM_PHOTOS_PER_PAGE.'&page='.$page);
			} else {
				//BUG: There's a bug here in that if you specify the number of photos you want to limit the recent to, the pagination goes wrong
				$xpath = fa_callFlickr('flickr.photos.search','user_id='.FALBUM_NSID.'&sort=date-taken-desc&per_page='.FALBUM_NUMBER_RECENT.'&page=1');
			}
		} else {
			$xpath = fa_callFlickr('flickr.photos.search','user_id='.FALBUM_NSID.'&tags='.$tags.'&tag_mode=all&per_page='.FALBUM_PHOTOS_PER_PAGE.'&page='.$page);
		}

		if (is_object($xpath)) {

			$result = $xpath->match('/rsp/photos/photo');
			$countResult = count($result);

			if ((FALBUM_PHOTOS_PER_PAGE != 0) && (FALBUM_NUMBER_RECENT != -1)) {
				$pages = ceil(FALBUM_NUMBER_RECENT / FALBUM_PHOTOS_PER_PAGE);
			} else {
				//$pages = ceil($countResult / FALBUM_PHOTOS_PER_PAGE);
				$pages = $xpath->getData("/rsp/photos/@pages");
			}

			$output .= "<h3 class='falbum-title'><a href='".fa_createURL()."'>".__(Photos, FALBUM_DOMAIN)."</a> &raquo; ";

			if ($tags == '') {
				$output .= __('Recent Photos', FALBUM_DOMAIN);
			} else {
				$output .= "<a href='".fa_createURL('show/tags')."'>".__('Tags', FALBUM_DOMAIN)."</a>: $tags";
			}
			$output .= "</h3>\n";
			$output .= "<div class='falbum-description'></div>\n";


			if ($tags == '') {
				if (FALBUM_PHOTOS_PER_PAGE > 0) {
					$urlPrefix = 'show/recent/page/';
				} else {
					$urlPrefix = 'show/recent/';
				}
			} else {
				if (FALBUM_PHOTOS_PER_PAGE > 0) {
					$urlPrefix = "tags/$tags/page/";

				} else {
					$urlPrefix = "tags/$tags/";
				}
			}

			if ($pages > 1) {
				$output .= fa_buildPaging($page, $pages, $urlPrefix,'top');
			}

			$photo_title_array = array();

			for ($i = 0; $i < $countResult; $i++) {
				//for ($i = 0; $i < FALBUM_NUMBER_RECENT; $i++) {
				$server = $xpath->getData($result[$i].'/@server');
				$secret = $xpath->getData($result[$i].'/@secret');
				$photo_id = $xpath->getData($result[$i].'/@id');
				$photo_title = sanitize_title($xpath->getData($result[$i].'/@title'));

				if (!in_array($photo_title, $photo_title_array)){
					$photo_title_array[$photo_id] = $photo_title;
				} else {
					$dup_count = 1;
					while (in_array($photo_title . '-' . $dup_count, $photo_title_array)) {
						$dup_count++;
					}
					$photo_title = $photo_title . '-' . $dup_count;
					$photo_title_array[$photo_id] = $photo_title;
				}

				if ((FALBUM_PHOTOS_PER_PAGE == 0)
				|| FALBUM_NUMBER_RECENT == -1
				|| (($count >= ($page - 1) * FALBUM_PHOTOS_PER_PAGE) && ($count < ($page * FALBUM_PHOTOS_PER_PAGE)))) {
					$thumbnail = "http://static.flickr.com/{$server}/{$photo_id}_{$secret}_".FALBUM_THUMBNAIL_SIZE.".jpg"; // Build URL to thumbnail

					$output .= '<div class="falbum-tn-border-'.FALBUM_THUMBNAIL_SIZE.'">';
					$output .= "<div class='falbum-thumbnail'>";

					if ($tags == '') {
						if (FALBUM_USE_FRIENDLY_URLS == 'true') {
							$output .= "<a href='".fa_createURL("show/recent/page/$page/photo/$photo_title")."/'>";
						} else {
							$output .= "<a href='".fa_createURL("show/recent/page/$page/photo/$photo_id")."'>";
						}
					} else {
						if (FALBUM_PHOTOS_PER_PAGE > 0) {
							if (FALBUM_USE_FRIENDLY_URLS == 'true') {
								$output .= "<a href='".fa_createURL("tags/$tags/page/$page/photo/$photo_title")."/'>";
							} else {
								$output .= "<a href='".fa_createURL("tags/$tags/page/$page/photo/$photo_id")."'>";
							}
						} else {
							if (FALBUM_USE_FRIENDLY_URLS == 'true') {
								$output .= "<a href='".fa_createURL("tags/$tags/photo/$photo_title")."/'>";
							} else {
								$output .= "<a href='".fa_createURL("tags/$tags/page/$page/photo/$photo_id")."'>";
							}
						}
					}

					$output .= "<img src='$thumbnail' alt=\"".htmlentities($photo_title)."\" title=\"".htmlentities($photo_title)."\" />";

					$output .= "</a>";
					$output .= "</div>";
					$output .= "</div>";
				}
				$count++;
			}

			if (FALBUM_PHOTOS_PER_PAGE != 0 && $pages > 1) {
				$output .= fa_buildPaging($page, $pages, $urlPrefix,'bottom');
			}
		} else {
			$output .= "<p class='fa_error'>Error: $xpath<br />Section: fa_showTagsThumbnails -> \$xpath</p>";
		}

		fa_setCacheData("showTagsThumbnails-$tags-$page",$output);
	}
	return $output;
}

/* Shows the selected photo */
function fa_showPhoto($album, $tags, $photo, $page = 1) {

	if ($page == '') {
		$page = 1;
	}

	$in_photo = $photo;
	$in_album = $album;


	list($output, $expired) = fa_getCacheData("showPhoto-$in_album-$tags-$in_photo-$page");

	if (!isset($output) || $expired) {

		$output =  '';

		// Get Prev and Next Photos

		if (!is_null($album)) {
			$url_prefix = "album/$album";
			$album_array = fa_get_album_id();
			if (!is_array($album_array)) { echo "Error: Array broken"; return; }
			if (FALBUM_USE_FRIENDLY_URLS == 'true') {
				while($album_array_title = current($album_array)) {
					if (sanitize_title($album_array_title) == $album) {
						$album = key($album_array);
						$album_title = $album_array_title;
					}
					next($album_array);
				}
			} else {
				$album_title = $album_array[$album];
			}

			$top_level_xpath = fa_callFlickr('flickr.photosets.getPhotos','photoset_id='.$album);
			if (!is_object($top_level_xpath)) { echo "<p class='fa_error'>Error: $top_level_xpath<br />Section: fa_showPhoto -> \$top_level_xpath</p>"; return; }
			$result = $top_level_xpath->match('/rsp/photoset/photo');

			// get photo id from title if using friendly URLs
			if (FALBUM_USE_FRIENDLY_URLS == 'true') {

				$photo_title_array = array();

				for ($i=0;$i<count($result);$i++) {
					$photo_id = $top_level_xpath->getData($result[$i].'/@id');
					$photo_title = sanitize_title($top_level_xpath->getData($result[$i].'/@title'));

					if (!in_array($photo_title, $photo_title_array)){
						$photo_title_array[$photo_id] = $photo_title;
					} else {
						$dup_count = 1;
						while (in_array($photo_title . '-' . $dup_count, $photo_title_array)) {
							$dup_count++;
						}
						$photo_title = $photo_title . '-' . $dup_count;
						$photo_title_array[$photo_id] = $photo_title;
					}
				}
				$photo = array_search($photo,$photo_title_array);
			}

		} else {
			if ($tags == '') {
				$url_prefix = 'show/recent';
				$album_title = __('Recent Photos', FALBUM_DOMAIN);
				$top_level_xpath = fa_callFlickr('flickr.photos.search','user_id='.FALBUM_NSID.'&sort=date-taken-desc&per_page='.FALBUM_PHOTOS_PER_PAGE.'&page='.$page);
			} else {
				$url_prefix = "tags/$tags";
				$album_title = __('Tags', FALBUM_DOMAIN);
				$album_title = $tags;
				$top_level_xpath = fa_callFlickr('flickr.photos.search','user_id='.FALBUM_NSID.'&tags='.$tags.'&tag_mode=all&per_page='.FALBUM_PHOTOS_PER_PAGE.'&page='.$page);
			}
			if (!is_object($top_level_xpath)) { echo "<p class='fa_error'>Error: $top_level_xpath<br />Section: fa_showPhoto -> Tags/Recent \$top_level_xpath</p>"; return; }
			$result = $top_level_xpath->match('/rsp/photos/photo');
			/* get photo id frm title if using friendly URLS*/
			if (FALBUM_USE_FRIENDLY_URLS == 'true') {
				$photo_title_array = array();
				for ($i=0;$i<count($result);$i++) {
					$photo_id = $top_level_xpath->getData($result[$i].'/@id');
					$photo_title = sanitize_title($top_level_xpath->getData($result[$i].'/@title'));

					if (!in_array($photo_title, $photo_title_array)){
						$photo_title_array[$photo_id] = $photo_title;
					} else {
						$dup_count = 1;
						while (in_array($photo_title . '-' . $dup_count, $photo_title_array)) {
							$dup_count++;
						}
						$photo_title = $photo_title . '-' . $dup_count;
						$photo_title_array[$photo_id] = $photo_title;
					}
				}
				$photo = array_search($photo,$photo_title_array);
			}

			$total_pages = $top_level_xpath->getData('/rsp/photos/@pages');
			$total_photos = $top_level_xpath->getData('/rsp/photos/@total');
		}


		if (!$top_level_xpath->getNode('/rsp/err'))	{
			$prev = $tmp_prev = $next = $photo;
			$prevPage = $nextPage = $page;

			$control = 1;

			$photo_title_array = array();

			$countResult = count($result);

			for ($i = 0; $i < $countResult; $i++) {
				$photo_id = $top_level_xpath->getData($result[$i].'/@id');
				$photo_title = $top_level_xpath->getData($result[$i].'/@title');
				$secret = $top_level_xpath->getData($result[$i].'/@secret');
				$server = $top_level_xpath->getData($result[$i].'/@server');

				if (!in_array($photo_title, $photo_title_array)){
					$photo_title_array[$photo_id] = $photo_title;
				} else {
					$dup_count = 1;
					while (in_array($photo_title . '-' . $dup_count, $photo_title_array)) {
						$dup_count++;
					}
					$photo_title = $photo_title . '-' . $dup_count;
					$photo_title_array[$photo_id] = $photo_title;
				}

				if ($control == 0) {
					// Selected photo was the last one, so this one is the next one
					$next = $photo_id; // Set ID of the next photo
					$next_title = $photo_title;
					$next_sec = $secret; // Set ID of the next photo
					$next_server = $server; // Set ID of the next photo
					break; // Break out of the foreach loop
				}

				if ($photo_id == $photo) {

					// This is the selected photo
					$prev = $tmp_prev; // Set ID of the previous photo
					$prev_title = $tmp_prev_title;
					$control--; // Decrement control variable to tell next iteration of loop that the selected photo was found

					if (is_null($album)) {

						if ($i == 0 && $page > 1) {
							$findPrev = true;
						}

						if (($i == ($countResult - 1)) && ($page < $total_pages)) {
							$findNext = true;
						}

					} else {
						if (FALBUM_PHOTOS_PER_PAGE > 0) {
							$pages = ($countResult / FALBUM_PHOTOS_PER_PAGE);

							if ($page > 1 && ($i % FALBUM_PHOTOS_PER_PAGE) == 0 ) {
								$prevPage = $prevPage - 1;
							}

							if ($page < $pages && (($i + 1) % FALBUM_PHOTOS_PER_PAGE) == 0) {
								$nextPage = $nextPage + 1;
							}
						} else {
							$pages = $prevPage = $nextPage = 1;
						}
					}

				}
				$tmp_prev = $photo_id; // Keep the last photo in a temporary variable in case next photo is the selected on
				$tmp_prev_title = $photo_title;
			}

			if ($findPrev) {
				$prevPage = $prevPage - 1;

				if ($tags == '') {
					$url_prefix = 'show/recent';
					$top_level_xpath = fa_callFlickr('flickr.photos.search','user_id='.FALBUM_NSID.'&sort=date-taken-desc&per_page='.FALBUM_PHOTOS_PER_PAGE.'&page='.$prevPage);
				}else {
					$url_prefix = "tags/$tags";
					$top_level_xpath = fa_callFlickr('flickr.photos.search','user_id='.FALBUM_NSID.'&tags='.$tags.'&tag_mode=all&per_page='.FALBUM_PHOTOS_PER_PAGE.'&page='.$prevPage);
				}
				if (!is_object($top_level_xpath)) { echo "<p class='fa_error'>Error: $top_level_xpath<br />Section: fa_showPhoto -> findPrev \$top_level_xpath</p>"; return; }
				$result = $top_level_xpath->match('/rsp/photos/photo');
				$countResult = count($result);

				$photo_title_array = array();
				for ($i = 0; $i < $countResult; $i++) {
					$photo_id = $top_level_xpath->getData($result[$i].'/@id');
					$photo_title = $top_level_xpath->getData($result[$i].'/@title');
					if (!in_array($photo_title, $photo_title_array)){
						$photo_title_array[$photo_id] = $photo_title;
					} else {
						$dup_count = 1;
						while (in_array($photo_title . '-' . $dup_count, $photo_title_array)) {
							$dup_count++;
						}
						$photo_title = $photo_title . '-' . $dup_count;
						$photo_title_array[$photo_id] = $photo_title;
					}
				}
				$prev = $photo_id; // Set ID of the next photo
				$prev_title = $photo_title;
			}

			if ($findNext) {

				$nextPage = $nextPage + 1;

				if ($tags == '') {
					$url_prefix = 'show/recent';
					$top_level_xpath = fa_callFlickr('flickr.photos.search','user_id='.FALBUM_NSID.'&sort=date-taken-desc&per_page='.FALBUM_PHOTOS_PER_PAGE.'&page='.$nextPage);
				}else {
					$url_prefix = "tags/$tags";
					$top_level_xpath = fa_callFlickr('flickr.photos.search','user_id='.FALBUM_NSID.'&tags='.$tags.'&tag_mode=all&per_page='.FALBUM_PHOTOS_PER_PAGE.'&page='.$nextPage);
				}
				if (!is_object($top_level_xpath)) { echo "<p class='fa_error'>Error: $top_level_xpath<br />Section: fa_showPhoto -> findNext \$top_level_xpath</p>"; return; }
				$result = $top_level_xpath->match('/rsp/photos/photo');

				$photo_id = $top_level_xpath->getData($result[0].'/@id');
				$photo_title = $top_level_xpath->getData($result[0].'/@title');
				$secret = $top_level_xpath->getData($result[0].'/@secret');
				$server = $top_level_xpath->getData($result[0].'/@server');
				$next = $photo_id; // Set ID of the next photo
				$next_title = $photo_title;
				$next_sec = $secret; // Set ID of the next photo
				$next_server = $server; // Set ID of the next photo
			}
		}

		// Set Next and Prev in navigation to either photo ID or sanitized title, depending on
		// whether FALBUM_USE_FRIENDLY_URLS == "true|false"

		if (FALBUM_USE_FRIENDLY_URLS == 'true') {
			$nav_next = sanitize_title($next_title);
			$nav_prev = sanitize_title($prev_title);
		} else {
			$nav_next = $next;
			$nav_prev = $prev;
		}

		// Display Photo
		$xpath = fa_callFlickr('flickr.photos.getInfo','photo_id='.$photo);
		if (is_object($xpath)) {

			$xpath_sizes = fa_callFlickr('flickr.photos.getSizes','photo_id='.$photo);
			if (!is_object($xpath_sizes)) { echo "<p class='fa_error'>Error: $xpath_sizes<br />Section: fa_showPhoto -> \$xpath_sizes</p>"; return; }
			if ($xpath_sizes->match("/rsp/sizes/size[@label='Square']/@width")) {
				$orig_w_sq = $xpath_sizes->getData("/rsp/sizes/size[@label='Square']/@width");
				$orig_h_sq = $xpath_sizes->getData("/rsp/sizes/size[@label='Square']/@height");
			}
			if ($xpath_sizes->match("/rsp/sizes/size[@label='Thumbnail']/@width")) {
				$orig_w_t = $xpath_sizes->getData("/rsp/sizes/size[@label='Thumbnail']/@width");
				$orig_h_t = $xpath_sizes->getData("/rsp/sizes/size[@label='Thumbnail']/@height");
			}
			if ($xpath_sizes->match("/rsp/sizes/size[@label='Small']/@width")) {
				$orig_w_s = $xpath_sizes->getData("/rsp/sizes/size[@label='Small']/@width");
				$orig_h_s = $xpath_sizes->getData("/rsp/sizes/size[@label='Small']/@height");
			}
			if ($xpath_sizes->match("/rsp/sizes/size[@label='Medium']/@width")) {
				$orig_w_m = $xpath_sizes->getData("/rsp/sizes/size[@label='Medium']/@width");
				$orig_h_m = $xpath_sizes->getData("/rsp/sizes/size[@label='Medium']/@height");
			}
			if ($xpath_sizes->match("/rsp/sizes/size[@label='Large']/@width")) {
				$orig_w_l = $xpath_sizes->getData("/rsp/sizes/size[@label='Large']/@width");
				$orig_h_l = $xpath_sizes->getData("/rsp/sizes/size[@label='Large']/@height");
			}
			if ($xpath_sizes->match("/rsp/sizes/size[@label='Original']/@width")) {
				$orig_w_o = $xpath_sizes->getData("/rsp/sizes/size[@label='Original']/@width");
				$orig_h_o = $xpath_sizes->getData("/rsp/sizes/size[@label='Original']/@height");
			}

			$server = $xpath->getData('/rsp/photo/@server');
			$secret = $xpath->getData('/rsp/photo/@secret');
			$photo_id = $xpath->getData('/rsp/photo/@id');
			$title = fa_unhtmlentities($xpath->getData('/rsp/photo/title'));
			$date_taken = $xpath->getData('/rsp/photo/dates/@taken');
			$description =  fa_unhtmlentities(nl2br($xpath->getData('/rsp/photo/description')));
			$comments =  $xpath->getData('/rsp/photo/comments');

			$imagex = "http://static.flickr.com/{$server}/{$photo}_{$secret}";
			$image = $imagex.".jpg"; // Build URL to medium size image
			$original = $imagex."_o.jpg"; // Build URL to original size image

			$next_image = "http://static.flickr.com/{$next_server}/{$next}_{$next_sec}.jpg"; // Build URL to medium size image

			$output .= "<h3 class='falbum-title'><a href='".fa_createURL()."'>".__('Photos', FALBUM_DOMAIN)."</a> &raquo; ";
			if (FALBUM_PHOTOS_PER_PAGE != 0) {
				$output .= "<a href='".fa_createURL("$url_prefix/page/{$page}/")."'>".__($album_title, FALBUM_DOMAIN)."</a>";
			} else {
				$output .= "<a href='".fa_createURL("$url_prefix/")."'>".__($album_title, FALBUM_DOMAIN)."</a>";
			}
			$output .= " &raquo; {$title}</h3>\n";

			//Date Taken
			$date_taken = (date(__('M j, Y - g:i A', FALBUM_DOMAIN), strtotime($date_taken)));
			$output .= "<div class='falbum-date-taken'>".strtr(__('Taken on: #date_taken#', FALBUM_DOMAIN),array("#date_taken#"=>$date_taken))."</div>\n";


			//Tags
			$result = $xpath->match('/rsp/photo/tags/tag');
			$countResult = count($result);
			if ($countResult > 0) {
				$output .= "<div class='falbum-tags'><a href='".fa_createURL('show/tags')."'>".__('Tags', FALBUM_DOMAIN)."</a>: ";
				for ($i = 0; $i < $countResult; $i++) {
					$value = $xpath->getData($result[$i]);
					$output .= "<a href='".fa_createURL("tags/{$value}/")."'>{$value}</a>";
					if ($i < $countResult - 1) {
						$output .= ", ";
					}
				}
				$output .= "</div>\n"; //close falbum-tags
			}

			//Notes
			$result = $xpath->match('/rsp/photo/notes/note');
			$notes_countResult = count($result);
			if ($notes_countResult > 0) {
				if (FALBUM_MAX_PHOTO_WIDTH > 0 && FALBUM_MAX_PHOTO_WIDTH < $orig_w_m) {
					$scale = FALBUM_MAX_PHOTO_WIDTH / $orig_w_m; // Notes are relative to Medium Size
				} else {
					$scale = 1;
				}
				$output .= "<map id='imgmap'>\n";
				for ($i = 0; $i < $notes_countResult; $i++) {
					$value = nl2br($xpath->getData($result[$i]));
					$x = 5 + $xpath->getData($result[$i].'/@x') * $scale;
					$y = 5 + $xpath->getData($result[$i].'/@y') * $scale;
					$w = $xpath->getData($result[$i].'/@w') * $scale;
					$h = $xpath->getData($result[$i].'/@h') * $scale;
					$output .= "<area alt='' title=\"".htmlentities($value)."\" nohref='nohref' shape='rect' coords='{$x},{$y},".($x + $w - 1).",".($y + $h - 1)."' />\n";
				}
				$output .= "</map>\n";
			}

			//Photo
			$output .= "<div class='falbum-photo-block'>";
			$output .= "<div class='falbum-photo'>";

			// Click on image for next
			if ($next != $photo) {
				if (FALBUM_PHOTOS_PER_PAGE > 0) {
					$output .= "<a href='".fa_createURL("$url_prefix/page/$nextPage/photo/$nav_next/")."' title='".__('Click to view next image', FALBUM_DOMAIN)."'>\n";
				} else {
					$output .= "<a href='".fa_createURL("$url_prefix/photo/$nav_next/")."' title='".__('Click to view next image', FALBUM_DOMAIN)."'>\n";
				}
			} else {
				if (FALBUM_PHOTOS_PER_PAGE > 0) {
					$output .= "<a href='".fa_createURL("$url_prefix/page/$page/")."' title='".__('Click to return to album', FALBUM_DOMAIN)."'>\n";
				} else {
					$output .= "<a href='".fa_createURL("$url_prefix/")."' title='".__('Click to return to album', FALBUM_DOMAIN)."'>\n";
				}
			}
			$output .= "<img src='$image' alt=''";
			if ($notes_countResult > 0) {
				$output .= " usemap='imgmap' ";
			}
			$output .= "id='flickr-photo' class='annotated' ";
			$output .= "width='";
			if (FALBUM_MAX_PHOTO_WIDTH != '0' && FALBUM_MAX_PHOTO_WIDTH < $orig_w_m) {
				$output .= FALBUM_MAX_PHOTO_WIDTH;
			} else {
				$output .= $orig_w_m;
			}
			$output .= "'";
			$output .= "/></a>\n";
			$output .= "</div>"; // falbum-photo


			//Description
			//$output .= "<div class=\"falbum-description\">\n";
			//$output .= "<p>{$description}</p>\n";
			//$output .= "</div>\n"; // falbum-description

			// Navigation
			$output .= "<div class='falbum-nav'>";
			if (FALBUM_PHOTOS_PER_PAGE != 0) {
				if (($prev != $photo) && ($next != $photo)) {
					// Show both previous and next navigation
					$output .= fa_getButton('pageprev',fa_createURL("$url_prefix/page/$prevPage/photo/$nav_prev/"),"&laquo; ".__('Previous', FALBUM_DOMAIN),__('Previous Photo', FALBUM_DOMAIN),1);
					$output .= '&nbsp;&nbsp;';
					$output .= fa_getButton('return',fa_createURL("$url_prefix/page/$page/"),__('Album Index', FALBUM_DOMAIN),__('Return to album index', FALBUM_DOMAIN),1);
					$output .= '&nbsp;&nbsp;';
					$output .= fa_getButton('pagenext',fa_createURL("$url_prefix/page/$nextPage/photo/$nav_next/"),"&nbsp;&nbsp; ".__('Next', FALBUM_DOMAIN)." &raquo;&nbsp;&nbsp;",__('Next Photo', FALBUM_DOMAIN),1);
				} elseif (($prev != $photo) && ($next == $photo)) {
					// Show only previous navigation
					$output .= fa_getButton('pageprev',fa_createURL("$url_prefix/page/$prevPage/photo/$nav_prev/"),"&laquo; ".__('Previous', FALBUM_DOMAIN),__('Previous Photo', FALBUM_DOMAIN),1);
					$output .= '&nbsp;&nbsp;';
					$output .= fa_getButton('return',fa_createURL("$url_prefix/page/$page/"),__('Album Index', FALBUM_DOMAIN),__('Return to album index', FALBUM_DOMAIN),1);

				} elseif (($prev == $photo) && ($next != $photo)) {
					// Show only next navigation
					$output .= fa_getButton('return',fa_createURL("$url_prefix/page/$page/"),__('Album Index', FALBUM_DOMAIN),__('Return to album index', FALBUM_DOMAIN),1);
					$output .= '&nbsp;&nbsp;';
					$output .= fa_getButton('pagenext',fa_createURL("$url_prefix/page/$nextPage/photo/$nav_next/"),"&nbsp;&nbsp; ".__('Next', FALBUM_DOMAIN)." &raquo;&nbsp;&nbsp;",__('Next Photo', FALBUM_DOMAIN),1);

				} else {
					$output .= fa_getButton('return',fa_createURL("$url_prefix/page/$page/"),__('Album Index', FALBUM_DOMAIN),__('Return to album index', FALBUM_DOMAIN),1);
				}
			} else {
				if (($prev != $photo) && ($next != $photo)) {
					// Show both previous and next navigation
					$output .= fa_getButton('pageprev',fa_createURL("$url_prefix/photo/$nav_prev/"),"&laquo; ".__('Previous', FALBUM_DOMAIN),__('Previous Photo', FALBUM_DOMAIN),1);
					$output .= '&nbsp;&nbsp;';
					$output .= fa_getButton('return',fa_createURL("$url_prefix/"),__('Album Index', FALBUM_DOMAIN),__('Return to album index', FALBUM_DOMAIN),1);
					$output .= '&nbsp;&nbsp;';
					$output .= fa_getButton('pagenext',fa_createURL("$url_prefix/photo/$nav_next/"),"&nbsp;&nbsp; ".__('Next', FALBUM_DOMAIN)." &raquo;&nbsp;&nbsp;",__('Next Photo', FALBUM_DOMAIN),1);
				} elseif (($prev != $photo) && ($next == $photo)) {
					// Show only previous navigation
					$output .= fa_getButton('pageprev',fa_createURL("$url_prefix/photo/$nav_prev/"),"&laquo; ".__('Previous', FALBUM_DOMAIN),__('Previous Photo', FALBUM_DOMAIN),1);
					$output .= '&nbsp;&nbsp;';
					$output .= fa_getButton('return',fa_createURL("$url_prefix/"),__('Album Index', FALBUM_DOMAIN),__('Return to album index', FALBUM_DOMAIN),1);

				} elseif (($prev == $photo) && ($next != $photo)) {
					// Show only next navigation
					$output .= fa_getButton('return',fa_createURL("$url_prefix/"),__('Album Index', FALBUM_DOMAIN),__('Return to album index', FALBUM_DOMAIN),1);
					$output .= '&nbsp;&nbsp;';
					$output .= fa_getButton('pagenext',fa_createURL("$url_prefix/photo/$nav_next/"),"&nbsp;&nbsp; ".__('Next', FALBUM_DOMAIN)." &raquo;&nbsp;&nbsp;",__('Next Photo', FALBUM_DOMAIN),1);

				} else {
					$output .= fa_getButton('return',fa_createURL("$url_prefix/"),__('Album Index', FALBUM_DOMAIN),__('Return to album index', FALBUM_DOMAIN),1);
				}
			}
			$output .= "</div>"; // falbum-nav
			$output .= "</div>"; // falbum-photo-block

			//Description
			$output .= "<div class='falbum-description'>\n";
			$output .= "<p>{$description}</p>\n";
			$output .= "</div>\n"; // falbum-description

			//Meta Information
			$output .= "<div class='falbum-meta'>\n";

			//Original
			//$output .= "<p>".__('View the', FALBUM_DOMAIN)." <a href='$original' title='{$title}'>".__('original photo', FALBUM_DOMAIN);
			//if (isset($orig_w_o)) {
			//	$output .= " ({$orig_w_o}x{$orig_h_o})";
			//}
			//$output .= "</a></p>\n";

			//Sizes

			if (strtolower(FALBUM_DISPLAY_SIZES) == 'true' ) {

				$output .= "<div class='falbum-photoSizesBlock'>".__('Available Sizes', FALBUM_DOMAIN).": ";

				if (isset($orig_w_sq)) {
					$output .= "<a href='{$imagex}_s.jpg' class='falbum-photoSizes' title='".__('Square', FALBUM_DOMAIN)." ({$orig_w_sq}x{$orig_h_sq})'>".__('SQ', FALBUM_DOMAIN)."</a>";
				}
				if (isset($orig_w_t)) {
					$output .= "<a href='{$imagex}_t.jpg' class='falbum-photoSizes' title='".__('Thumbnail', FALBUM_DOMAIN)." ({$orig_w_t}x{$orig_h_t})'>".__('T', FALBUM_DOMAIN)."</a>";
				}
				if (isset($orig_w_s)) {
					$output .= "<a href='{$imagex}_m.jpg' class='falbum-photoSizes' title='".__('Small', FALBUM_DOMAIN)." ({$orig_w_s}x{$orig_h_s})'>".__('S', FALBUM_DOMAIN)."</a>";
				}
				if (isset($orig_w_m)) {
					$output .= "<a href='{$imagex}.jpg' class='falbum-photoSizes' title='".__('Medium', FALBUM_DOMAIN)." ({$orig_w_m}x{$orig_h_m})'>".__('M', FALBUM_DOMAIN)."</a>";
				}
				if (isset($orig_w_l)) {
					$output .= "<a href='{$imagex}_b.jpg' class='falbum-photoSizes' title='".__('Large', FALBUM_DOMAIN)." ({$orig_w_l}x{$orig_h_l})'>".__('L', FALBUM_DOMAIN)."</a>";
				}
				if (isset($orig_w_o)) {
					$output .= "<a href='{$imagex}_o.jpg' class='falbum-photoSizes' title='".__('Original', FALBUM_DOMAIN)." ({$orig_w_o}x{$orig_h_o})'>".__('O', FALBUM_DOMAIN)."</a>";
				}
				$output .= '</div>'; //photosizesblock

			}


			// Flickr / Comments
			$output .= "<p><a href='http://www.flickr.com/photos/".FALBUM_NSID."/$photo'>".
			__('See this photo on Flickr', FALBUM_DOMAIN)."&nbsp;";
			// Comments
			if ($comments == 1) {
				$output .= strtr(__('(#num_comments# comment)', FALBUM_DOMAIN),array("#num_comments#"=>$comments));
			} else {
				$output .=  strtr(__('(#num_comments# comments)', FALBUM_DOMAIN),array("#num_comments#"=>$comments));
			}
			$output .= "</a></p>\n";

			if (strtolower(FALBUM_DISPLAY_EXIF) == 'true' ) {
				//Exif
				$remote_url = get_settings('siteurl')."/wp-content/plugins/falbum/falbum-remote.php";
				$output .= "<div id='exif' class='falbum-exif'><a href=\"javascript:showExif('{$photo_id}','{$secret}','{$remote_url}');\">".__('Show Exif Data', FALBUM_DOMAIN)."</a></div>";
			}

			$output .= "</div>\n"; // falbum-meta

			// Script
			$output .= "<script type='text/javascript'>\n";
			$output .= "//<!--\n";
			$output .= "falbum_prefetch('$next_image');\n";
			$output .= "//-->\n";
			$output .= "</script>\n";

		} else {
			$output .= "<p class='fa_error'>Error: $xpath<br />Section: fa_showPhoto -> \$xpath</p>";
		}

		fa_setCacheData("showPhoto-$in_album-$tags-$in_photo-$page",$output);
	}
	return $output;
}

/* Shows all the tag cloud */
function fa_showTags() {

	list($output, $expired) = fa_getCacheData('showTags');

	if (!isset($output) || $expired) {

		$output = '';

		//

		$xpath = fa_callFlickr('flickr.tags.getListUserPopular','count=500&user_id='.FALBUM_NSID);

		if (is_object($xpath)) {
			$result = $xpath->match('/rsp/who/tags/tag');
			$countResult = count($result);

			$count = 0;

			$output .= "<h3 class='falbum-title'><a href='".fa_createURL()."'>".__(Photos, FALBUM_DOMAIN)."</a> &raquo; ";

			$output .= __('Tags', FALBUM_DOMAIN);

			$output .= "</h3>\n";


			$output .= "<div class='falbum-description'></div>\n";

			$output .= "<div class='falbum-cloud'>";

			$maxcount = 0;
			for ($i = 0; $i < $countResult; $i++) {
				$tagcount = $xpath->getData($result[$i].'/@count');
				if ($tagcount > $maxcount) {
					$maxcount = $tagcount;
				}
			}

			for ($i = 0; $i < $countResult; $i++) {

				$tagcount = $xpath->getData($result[$i].'/@count');
				$tag = $xpath->getData($result[$i]);

				if ($tagcount <= ($maxcount * .1) ) { $tagclass = 'falbum-tag1';
				} elseif ($tagcount <= ($maxcount * .2) ) {$tagclass = 'falbum-tag2';
				} elseif ($tagcount <= ($maxcount * .3) ) {$tagclass = 'falbum-tag3';
				} elseif ($tagcount <= ($maxcount * .5) ) {$tagclass = 'falbum-tag4';
				} elseif ($tagcount <= ($maxcount * .7) ) {$tagclass = 'falbum-tag5';
				} elseif ($tagcount <= ($maxcount * .8) ) {$tagclass = 'falbum-tag6';
				} else {$tagclass = 'falbum-tag7';}

				$output .= "<a href='".fa_createURL("tags/$tag")."' class='".$tagclass."' title='".$tagcount." ".__('photos', FALBUM_DOMAIN)."'>".$tag."</a>";
				$output .= " &nbsp; ";
			}

			$output .= "</div>";

			fa_setCacheData('showTags',$output);
		} else {
			$output .= "<p class='fa_error'>Error: $xpath<br />Section: fa_showTags -> \$xpath</p>";
		}
	}
	return $output;
}

/* Gets EXIF data for the selected photo */
function fa_getExif($photo_id, $secret) {

	list($output, $expired) = fa_getCacheData("getExif-$photo_id-$secret");
	if (!isset($output) || $expired) {

		$output = '';

		$exif_xpath = fa_callFlickr('flickr.photos.getExif','photo_id='.$photo_id.'&secret='.$secret, FALBUM_CACHE_EXPIRE_LONG);
		if (!is_object($exif_xpath)) { echo "<p class='fa_error'>Error: $exif_xpath<br />Section: fa_getExif \$exif_xpath</p>"; return; }
		$result = $exif_xpath->match('//EXIF');
		$countResult = count($result);

		$output .= "<table>";
		for ($i = 0; $i < $countResult; $i++) {
			$label = $exif_xpath->getData($result[$i].'/@label');
			$raw = $exif_xpath->getData($result[$i].'/raw');

			$output .= "<tr ";

			if ($i % 2 == 0) {
				$output .= "class='even'";
			} else {
				$output .= "class='odd'";
			}

			$output .= "><td>$label</td><td>";
			$r1 = $exif_xpath->match($result[$i].'/clean');
			if (count($r1) > 0) {
				$output .= $exif_xpath->getData($result[$i].'/clean');
			}else{
				$output .= $raw;
			}

			$output .= "</td></tr>";
		}
		$output .= "</table>";
		fa_setCacheData("getExif-$photo_id-$secret",$output);
	}
	return $output;
}

/* Function to show recent photos - commonly used in the sidebar */
function fa_show_recent($num = 5, $style = 0, $size = FALBUM_THUMBNAIL_SIZE) {

	list($output, $expired) = fa_getCacheData("show_recent-$num-$style-$size");

	if (!isset($output) || $expired) {

		$output = '';

		$xpath = fa_callFlickr('flickr.photos.search','user_id='.FALBUM_NSID.'&per_page='.$num.'&sort=date-taken-desc');

		if (is_object($xpath)) {

			if ($style == 0) { $output .= "<ul class='falbum-recent'>\n"; } else { $output .= "<div class='falbum-recent'>\n"; }

			$result = $xpath->match('/rsp/photos/photo');
			$countResult = count($result);

			for ($i = 0; $i < $countResult; $i++) {
				$server = $xpath->getData($result[$i].'/@server');
				$secret = $xpath->getData($result[$i].'/@secret');
				$photo_id = $xpath->getData($result[$i].'/@id');
				$photo_title = $xpath->getData($result[$i].'/@title');

				if (FALBUM_USE_FRIENDLY_URLS == 'true') {
					$photo_link = sanitize_title($photo_title);
				} else {
					$photo_link = $photo_id;
				}
				if ($style == 0) { $output .= "<li>\n"; } else { $output .= "<div class='falbum-thumbnail'>"; }

				$thumbnail = "http://static.flickr.com/{$server}/{$photo_id}_{$secret}_".$size.".jpg"; // Build URL to thumbnail

				$output .= "<a href='".fa_createURL("show/recent/photo/$photo_link/")."'>";

				$output .= "<img src='$thumbnail' alt=\"".htmlentities($photo_title)."\" title=\"".htmlentities($photo_title)."\" />";
				$output .= "</a>\n";

				if ($style == 0) { $output .= "</li>\n"; } else { $output .= "</div>\n"; }
			}
			if ($style == 0) { $output .= "</ul>\n"; } else { $output .= "</div>\n"; }
		} else {
			$output .= "<p class='fa_error'>Error: $xpath<br />Section: fa_showRecent -> \$xpath</p>";
		}
		fa_setCacheData("show_recent-$num-$style",$output);
	}
	return $output;
}

/* Function to show a random selection of photos - commonly used in the sidebar */
function fa_showRandom($num = 5, $tags='', $style = 0, $size = FALBUM_THUMBNAIL_SIZE) {

	$output = '';
	$page = 1;

	if ($tags == '') {
		$xpath = fa_callFlickr('flickr.photos.search','user_id='.FALBUM_NSID.'&sort=date-taken-desc&per_page='.FALBUM_PHOTOS_PER_PAGE);
	} else {
		$xpath = fa_callFlickr('flickr.photos.search','user_id='.FALBUM_NSID.'&tags='.$tags.'&tag_mode=all&per_page='.FALBUM_PHOTOS_PER_PAGE.'&page='.$page);
	}

	if (is_object($xpath)) {

		$totalPages = $xpath->getData('/rsp/photos/@pages');

		if ($style == 0) { $output .= "<ul class='falbum-random'>\n"; } else { $output .= "<div class='falbum-random'>\n"; }

		for ($j = 0; $j < $num; $j++) {
			$page = mt_rand(1, $totalPages);
			if ($tags == '') {
				$xpath = fa_callFlickr('flickr.photos.search','user_id='.FALBUM_NSID.'&sort=date-taken-desc&per_page='.FALBUM_PHOTOS_PER_PAGE.'&page='.$page);
			} else {
				$xpath = fa_callFlickr('flickr.photos.search','user_id='.FALBUM_NSID.'&tags='.$tags.'&tag_mode=all&per_page='.FALBUM_PHOTOS_PER_PAGE.'&page='.$page);
			}

			if (is_object($xpath)) {
				$result = $xpath->match('/rsp/photos/photo');
				$countResult = count($result);

				$randPhoto = mt_rand(0, $countResult - 1);

				$server = $xpath->getData($result[$randPhoto].'/@server');
				$secret = $xpath->getData($result[$randPhoto].'/@secret');
				$photo_id = $xpath->getData($result[$randPhoto].'/@id');
				$photo_title = $xpath->getData($result[$randPhoto].'/@title');

				if (FALBUM_USE_FRIENDLY_URLS == 'true') {
					$photo_link = sanitize_title($photo_title);
				} else {
					$photo_link = $photo_id;
				}

				if ($style == 0 ) { $output .= "<li>\n"; } else { $output .= "<div class='falbum-thumbnail'>"; }

				$thumbnail = "http://static.flickr.com/{$server}/{$photo_id}_{$secret}_".$size.".jpg"; // Build URL to thumbnail

				if ($tags != '') {
					$output .= "<a href='".fa_createURL("tags/$tags/page/$page/photo/$photo_link/")."'>";
				} else {
					$output .= "<a href='".fa_createURL("show/recent/page/$page/photo/$photo_link/")."'>";
				}

				$output .= "<img src='$thumbnail' alt=\"".htmlentities($photo_title)."\" title=\"".htmlentities($photo_title)."\" class='falbum-recent-thumbnail' />";
				$output .= "</a>\n";
				if ($style == 0) { $output .= "</li>\n"; } else { $output .= "</div>\n"; }
			} else {
				$output .= "<p class='fa_error'>Error: $xpath<br />Section: fa_showRandom -> \$xpath</p>";
			}
		}
		if ($style == 0) { $output .= "</ul>\n"; } else { $output .= "</div>\n"; }
	} else {
		$output .= "<p class='fa_error'>Error: $xpath<br />Section: fa_showRandom -> \$xpath</p>";
	}
	return $output;
}

/* Function that actually makes the flickr calls */
function fa_callFlickr($method, $parms='', $cache_option = FALBUM_CACHE_EXPIRE_SHORT) {

	if (fa_show_private() == 'true' ) {
		$np = 'method='.$method.'&api_key='.FALBUM_API_KEY.'&auth_token='.FALBUM_TOKEN;

		if ($parms != '') {
			$np .= '&'.$parms;
		}

		$p = explode('&', $np);
		sort($p);
		$m = FALBUM_SECRET;
		foreach ($p as $key => $val) {
			$m .= str_replace('=','',$val);
		}

		//echo "<pre>{$m}</pre>";

		$url = 'http://www.flickr.com/services/rest/?'.implode('&',$p);
		$url .= '&api_sig='.md5($m);

	} else {

		$np = 'method='.$method.'&api_key='.FALBUM_API_KEY;
		if ($parms != '') {
			$np .= '&'.$parms;
		}
		$url = 'http://www.flickr.com/services/rest/?'.$np;
	}

	$resp = fa_fopen_url($url, $cache_option); // Do the Flickr API call

	$xpath = fa_parseXPath($resp);

	return $xpath;
}

/* Gets info from Cache Table */
function fa_getCacheData($key) {
	global $wpdb, $table_prefix;

	$fa_table = $table_prefix . 'falbum_cache';

	$data = null;
	$expired = null;

	if (fa_show_private() == 'true') {
		$key .= '-private';
	}

	//get existing data from db
	$output = $wpdb->get_row("SELECT data, (UNIX_TIMESTAMP(expires) - UNIX_TIMESTAMP()) expires FROM ".$fa_table." WHERE ID='" . md5($key) . "'");

	if (isset($output)) {
		$data = unserialize(stripslashes($output->data));
		$expires_value = $output->expires;

		if ($expires_value < 0) {
			$expired = true;
		}else {
			$expired = false;
		}
	}

	//echo '<pre>get - key -'.$key.'</pre>';
	//echo '<pre>$data-'.isset($data).'</pre>';
	//echo '<pre>$expired-'.$expires_value.'</pre>';

	return array($data, $expired);
}

/* Function to store the data in the cache table */
function fa_setCacheData($key, $data, $cache_option = FALBUM_CACHE_EXPIRE_SHORT) {
	global $wpdb, $table_prefix;

	$fa_table = $table_prefix . 'falbum_cache';

	if (fa_show_private() == 'true') {
		$key .= '-private';
	}

	//echo '<pre>set - key -'.$key.'</pre>';
	$wpdb->query("REPLACE INTO $fa_table SET ID='" . md5($key) . "', data='" . addslashes(serialize($data)) . "', expires=DATE_ADD(NOW(), INTERVAL $cache_option SECOND)");
}

/* Function that opens the URLS - uses libcurl by default, else falls back to fsockopen */
function fa_fopen_url($url, $cache_option = FALBUM_CACHE_EXPIRE_SHORT, $fsocket_timeout = 120) {

	list($data, $expired) = fa_getCacheData($url);

	if (!isset($data) || $expired) {

		$urlParts = parse_url($url);
		$host = $urlParts['host'];
		$port = (isset($urlParts['port'])) ? $urlParts['port'] : 80;

		if (!extension_loaded('curl')) {
			/* Use fsockopen */
			//echo '<pre>fsockopen-'.htmlentities($url).'</pre>';

			if( !$fp = @fsockopen( $host, $port, $errno, $errstr, $fsocket_timeout )) {
				$data = __('fsockopen:Flickr server not responding', FALBUM_DOMAIN);
			} else {

				if( !fputs( $fp, "GET $url HTTP/1.0\r\nHost:$host\r\n\r\n" )) {
					$data = __('fsockopen:Unable to send request', FALBUM_DOMAIN);
				}

				$ndata = null;
				stream_set_timeout($fp, $fsocket_timeout);
				$status = socket_get_status($fp);
				while( !feof($fp) && !$status['timed_out'])
				{
					$ndata .= fgets ($fp,8192);
					$status = socket_get_status($fp);
				}
				fclose ($fp);

				// strip headers
				$sData = split("\r\n\r\n", $ndata, 2);
				$ndata = $sData[1];
			}
		} else {
			/* Use curl */
			//echo '<pre>curl-'.htmlentities($url).'</pre>';

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_PORT, $port);
			curl_setopt($ch, CURLOPT_TIMEOUT, $fsocket_timeout);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$ndata = curl_exec($ch);
			$error = curl_error($ch);
			curl_close($ch);
		}

		//echo '<pre>$ndata-'.htmlentities($ndata).'</pre>';

		$pos = strrpos($ndata, '<rsp stat="ok">');

		if ($pos !== false) {
			$data = $ndata;
			fa_setCacheData($url,$data,$cache_option);
		} else {
			$data = __("libcurl:Connection Error: $error", FALBUM_DOMAIN);;
		}
	}
	return $data;
}

/* Creates the URLs used in Falbum */
function fa_createURL($parms = '') {
	if ($parms != '') {
		$element = explode('/', $parms);
		for ($x=1; $x<count($element); $x++) {
			$element[$x] = urlencode($element[$x]);
		}
		if (strtolower(FALBUM_USE_FRIENDLY_URLS) == 'false')  {
			$parms = '?'.$element[0].'='.$element[1].'&'.$element[2].'='.$element[3].'&'.$element[4].'='.$element[5].'&'.$element[6].'='.$element[7];
			$parms = str_replace('&=','',$parms);
		} else {
			$parms = implode('/', $element);
		}
	}
	return htmlspecialchars(FALBUM_URL_ROOT."$parms");
}

/* Function that parses the XML results from the Flickr API (based on torsten@jserver.de's fa_parseXPath function found at http://www.php.net/manual/en/ref.xml.php) */
function fa_parseXPath ($data) {

	$pos = strpos($data, 'xml');
	if ($pos === false) {

		$xpath = $data;

	} else {

		require_once('XPath.class.php');

		$xmlOptions = array(XML_OPTION_CASE_FOLDING => TRUE, XML_OPTION_SKIP_WHITE => TRUE);
		$xPath =& new XPath(FALSE, $xmlOptions);
		//$xPath->bDebugXmlParse = TRUE;

		if ($xPath->importFromString($data))  {

			$status = $xPath->getData('/rsp/@stat');
									
			if ($status != "ok") {
				$msg = $xPath->getData('/rsp/err/@msg');
				$code = $xPath->getData('/rsp/err/@code');
				$xPath = "$status: $code - $msg";
			}

		} else {
			$xPath = "Failed to parse response from Flickr";
		}
	}

	return $xPath;
}

/* Function that builds the album pages */
function fa_buildPaging($page, $pages, $urlPrefix, $pos) {

	$sAlbHeader.="<div class='falbum-navigationBar' id='pages-$pos'>".__('Page:', FALBUM_DOMAIN)."&nbsp;";

	if ($page > 1 && $pages > 1) {
		$title=strtr(__('Go to previous page (#page#)', FALBUM_DOMAIN),array("#page#"=>$page-1));
		$sAlbHeader.= fa_getButton('pageprev-',fa_createURL($urlPrefix.($page-1)),__('Previous', FALBUM_DOMAIN),$title,0,'_self',true,$pos);
	}

	for ($i=1; $i<=$pages; $i++) {
		// We display 1 ... 14 15 16 17 18 ... 29 when there are too many pages
		if ($pages>10) {

			$mn=$page-4;
			$mx=$page+4;

			if ($i<=$mn) {
				if ($i==2)
				$sAlbHeader.="<span class='pagedots'>&nbsp;&hellip;&nbsp;</span>";
				if ($i!=1)
				continue;
			}
			if ($i>=$mx) {
				if ($i==$pages - 1)
				$sAlbHeader.="<span class='pagedots'>&nbsp;&hellip;&nbsp;</span>";
				if ($i!=$pages)
				continue;
			}
		}
		$id="page$i";
		if ($i==$page) {
			$id='curpage';
		}

		$sAlbHeader.= fa_getButton($id,fa_createURL($urlPrefix.$i),$i,'',($i?0:1),'_self',true,$pos);
	}
	if ($page < $pages) {
		$title=strtr(__('Go to next page (#page#)', FALBUM_DOMAIN),array("#page#"=>$page+1));
		$sAlbHeader.= fa_getButton('pagenext',fa_createURL($urlPrefix.($page+1)),__('Next', FALBUM_DOMAIN),$title,1,'_self',true,$pos);
	}
	$sAlbHeader.="</div>\n\n";

	return $sAlbHeader;
}

/* Build pretty navigation buttons */
function fa_getButton($id, $href, $text, $title, $nSpacer, $target='_self', $bCallCustom=true, $pos='')
{
	// Begin toolbar/end toolbar stuff
	if (substr($id,0,1)=='#')
	return '';

	$class='buttonLink';
	if ($id=='curpage')	{
		$class='curPageLink';
	}	else if (preg_match('/^page[0-9]+$/',$id)) {
		$class='otherPageLink';
	}

	$x='';

	if ($nSpacer==1)
	$space='&nbsp;';
	if ($nSpacer==2)
	$space='&nbsp;&nbsp;&nbsp;';

	if (!empty($space))
	$x.="<span id='space_{$id}_{$pos}' class='buttonspace'>$space</span>";

	if (!empty($href))
	$x.="<a class='$class' href='$href' id='$id-$pos' title='$title'>$text</a>";
	else
	$x.="<span class='disabledButtonLink' id='$id-$pos' >$text</span>";
	return $x;
}

/* Removes all HTML entities - commonly used for the descriptions */
function fa_unhtmlentities($string)
{
	// replace numeric entities
	$string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
	$string = preg_replace('~&#([0-9]+);~e', 'chr(\\1)', $string);
	// replace literal entities
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	return strtr($string, $trans_tbl);
}

/* Get album ID from the album title */
function fa_get_album_id($top_level_xpath = 0) {
	if ($top_level_xpath == 0) {
		$top_level_xpath = fa_callFlickr('flickr.photosets.getList','user_id='.FALBUM_NSID);
	}
	$album_id_array = array();
	$photosets = $top_level_xpath->match('/rsp/photosets/photoset');
	for ($i=0;$i<count($photosets);$i++) {
		$album_id = $top_level_xpath->getData($photosets[$i].'/@id');
		$album_title = $top_level_xpath->getData($photosets[$i].'/title');

		if (!in_array($album_title, $album_id_array)){
			$album_id_array[$album_id] = $album_title;
		} else {
			$count = 1;
			while (in_array($album_title . '-' . $count, $album_id_array)) {
				$count++;
			}
			$album_id_array[$album_id] = $album_title . '-' . $count;
		}
	}
	return $album_id_array;
}

/* Outputs a true or false variable for showing private photos based on the registered user level */
function fa_show_private() {
	global $user_level;
	get_currentuserinfo();
	$PrivateAlbumChoice = false;

	$u_level = $user_level;
	if (!isset($u_level)) {
		$u_level = 0;
	}

	if ($u_level >= FALBUM_WP_USER_LEVEL) {
		if (strtolower(FALBUM_SHOW_PRIVATE) == 'true'){
			$PrivateAlbumChoice = true;
		}
	}
	return $PrivateAlbumChoice;
}

?>
