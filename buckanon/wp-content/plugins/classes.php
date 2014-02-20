<?php

class wpaudioscrobbler {

    var $username;
	var $emptymsg;
	var $cache_expire;
	var $rt;
	var $urls;

	function wpaudioscrobbler(){

		$settings = get_option('wpaudioscrobbler_settings');
		$this->username = $settings['username'];
		$this->cache_expire = $settings['cache_expire'];

		$this->rt = array();
		$this->rt['emptymsg'] = $settings['emptymsg_rt'];
		$this->rt['template'] = $settings['template_rt'];
		$this->rt['date'] = $settings['date_rt'];
		$this->rt['limit'] = $settings['limit_rt'];

		$this->urls = array();
		$this->urls['recent_tracks'] = "http://ws.audioscrobbler.com/1.0/user/$this->username/recenttracks.xml";
		$this->urls['weekly_artists'] = "http://ws.audioscrobbler.com/1.0/user/$this->username/weeklyartistchart.xml";

	} /* end function: wpaudioscrobbler */

	function get_info($function){
		return $this->$function();
 	} /* end function: get_info */

	function recent_tracks(){

		$tree = $this->getTree($this->urls['recent_tracks']);
		if (!isset($tree['RECENTTRACKS']['TRACK'][0]))
		    return $this->rt['emptymsg'];
		else{
			$list = '';
			$now = time();
			$tz_diff = date('Z', $tree['RECENTTRACKS']['TRACK'][0]['DATE']['ATTRIBUTES']['UTS']) - (get_option('gmt_offset')*60*60);
			foreach ($tree['RECENTTRACKS']['TRACK'] as $i => $track){
				if ($i >= $this->rt['limit'])break;
				$minutes_ago = round( ($now - $track['DATE']['ATTRIBUTES']['UTS']) / 60 );
				$temp = str_replace('%artist%', $track['ARTIST']['VALUE'], $this->rt['template']);
				$temp = str_replace('%title%', $track['NAME']['VALUE'], $temp);
				$temp = str_replace('%date%', date($this->rt['date'], $track['DATE']['ATTRIBUTES']['UTS'] + $tz_diff), $temp);
				$temp = str_replace('%minutes_ago%', $minutes_ago, $temp);
				$temp = str_replace('%time_ago%', $this->get_time_ago($minutes_ago), $temp);				
				$temp = (empty($track['NAME']['VALUE'])) ? str_replace('%link%', '#', $temp) : str_replace('%link%', $track['URL']['VALUE'], $temp) ;
				$list .= $temp."\n";
			}
			return $this->xhtml_safe($list);
  		} //end else

	} /* end function: recent_tracks */

	function weekly_artists(){
		$tree = $this->getTree($this->urls['weekly_artists']);
		if (!isset($tree['WEEKLYARTISTCHART']['ARTIST'][0]))
		    return $this->rt['emptymsg'];
		else {
			$list = '';
			foreach ($tree['WEEKLYARTISTCHART']['ARTIST'] as $i => $artist){
				if ($i >= $this->rt['limit'])break;
				$minutes_ago = 0;
				$temp = str_replace('%artist%', $artist['PLAYCOUNT']['VALUE'] . ': ' . $artist['NAME']['VALUE'], $this->rt['template']);
				$list .= $temp."\n";
			}
			return $this->xhtml_safe($list);
		} // end else
		
	} /* end function: weekly_artists */


	function xhtml_safe($string){
		return str_replace('&', '&amp;', $string);
	} /* end function: xhtml_safe */

	/*************************/
	/**** cache functions ****/
	/*************************/
	function getTree($requestURL){

		$tree = get_option('wpaudioscrobbler_cache');

		if((time() - get_option('wpaudioscrobbler_cache_ts')) > $this->cache_expire){

			if(function_exists('curl_init')){
					$curl_handle = curl_init();
					curl_setopt($curl_handle,CURLOPT_URL,$requestURL);
					curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,1);
					curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,10);
					$audiocrobblerdata = curl_exec($curl_handle);
					curl_close($curl_handle);
			}else{
					$fp = fopen($requestURL,'r');
					while (!feof($fp))
					$audiocrobblerdata .= fread($fp, 4000);
					fclose($fp);
			}
			$parser = new wpas_XMLParser($audiocrobblerdata, 'raw', 1);
			$tree = $parser->getTree();
			update_option('wpaudioscrobbler_cache_ts', time());
			update_option('wpaudioscrobbler_cache', $tree);
		}
		return $tree;
	} /* end function: getTree */

	function get_time_ago($min){

		$time = array();

		if ($min > 1440){
			$time['days'] = (int) ($min / 1440);
			$min -= ($time['days'] * 1440);
		}else{
			if ($min >= 60){
				$time['hours'] = (int) ($min / 60);
				$min -= ($time['hours'] * 60);
			}
		  	$time['minutes'] = $min;
		}//end else days

		if(isset($time['days']))
		    $str = 'over '.($time['days']*24).' hours';
		elseif (isset($time['hours']) && $time['hours'] >= 2)
		    $str = 'over '.$time['hours'].' hours';
        elseif (isset($time['hours'])){
            $str = ($time['hours'] == 1) ? '1 hour' : $time['hours'].' hours';
            if ($time['minutes'] > 0)
                $str .= ($time['minutes'] == 1) ? ' and 1 minute' : ' and '.$time['minutes'].' minutes';
  		}else{
            if ($time['minutes'] != 0)
                $str = ($time['minutes'] == 1) ? '1 minute' : $time['minutes'].' minutes';
			else
			    $str = 'less than a minute';
		}

		return $str;

 	} /* end fucntion: get_time_ago */

} /* end class: wpaudioscrobbler */


	/*
	// Based on code found online at:
	// http://php.net/manual/en/function.xml-parse-into-struct.php
	//
	// Created By: Eric Pollmann
	// Released into public domain September 2003
	// http://eric.pollmann.net/work/public_domain/
	//
	*/
	class wpas_XMLParser {
		var $data;		// Input XML data buffer
		var $vals;		// Struct created by xml_parse_into_struct
		var $collapse_dups;	// If there is only one tag of a given name,
					//   shall we store as scalar or array?
		var $index_numeric;	// Index tags by numeric position, not name.
					//   useful for ordered XML like CallXML.

		// Read in XML on object creation.
		// We can take raw XML data, a stream, a filename, or a url.
		function wpas_XMLParser($data_source, $data_source_type='raw', $collapse_dups=0, $index_numeric=0) {
			$this->collapse_dups = $collapse_dups;
			$this->index_numeric = $index_numeric;
			$this->data = '';
			if ($data_source_type == 'raw')
				$this->data = $data_source;

			elseif ($data_source_type == 'stream') {
				while (!feof($data_source))
					$this->data .= fread($data_source, 1000);

			// try filename, then if that fails...
			} elseif (file_exists($data_source))
				$this->data = implode('', file($data_source));

			// try url
			else {
				$fp = fopen($data_source,'r');
				while (!feof($fp))
					$this->data .= fread($fp, 1000);
				fclose($fp);
			}
		}

		// Parse the XML file into a verbose, flat array struct.
		// Then, coerce that into a simple nested array.
		function getTree() {
			$parser = xml_parser_create('UTF-8');
			xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
			xml_parse_into_struct($parser, $this->data, $vals, $index);
			xml_parser_free($parser);

			$i = -1;
			return $this->getchildren($vals, $i);
		}

		// internal function: build a node of the tree
		function buildtag($thisvals, $vals, &$i, $type) {
            $tag = array();

			if (isset($thisvals['attributes']))
				$tag['ATTRIBUTES'] = $thisvals['attributes'];

			// complete tag, just return it for storage in array
			if ($type === 'complete')
				$tag['VALUE'] = $thisvals['value'];

			// open tag, recurse
			else
				$tag = array_merge($tag, $this->getchildren($vals, $i));

			return $tag;
		}

		// internal function: build an nested array representing children
		function getchildren($vals, &$i) {
			$children = array();     // Contains node data

			// Node has CDATA before it's children
	                if ($i > -1 && isset($vals[$i]['value']))
				$children['VALUE'] = $vals[$i]['value'];

			// Loop through children, until hit close tag or run out of tags
			while (++$i < count($vals)) {

				$type = $vals[$i]['type'];

				// 'cdata':	Node has CDATA after one of it's children
				// 		(Add to cdata found before in this case)
				if ($type === 'cdata')
					$children['VALUE'] .= $vals[$i]['value'];

				// 'complete':	At end of current branch
				// 'open':	Node has children, recurse
				elseif ($type === 'complete' || $type === 'open') {
					$tag = $this->buildtag($vals[$i], $vals, $i, $type);
					if ($this->index_numeric) {
						$tag['TAG'] = $vals[$i]['tag'];
						$children[] = $tag;
					} else
						$children[$vals[$i]['tag']][] = $tag;
				}

				// 'close:	End of node, return collected data
				//		Do not increment $i or nodes disappear!
				elseif ($type === 'close')
					break;
			}
			if ($this->collapse_dups)
				foreach($children as $key => $value)
					if (is_array($value) && (count($value) == 1))
						$children[$key] = $value[0];
			return $children;
		}
	} /* end class: wpas_XMLParser */

?>
