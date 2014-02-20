<?php

include_once("id3v2.php");

$mp3 = new id3v2();

// Print opening XML tags
echo('<?xml version="1.0" ?>' . "\n");
echo('  <player showDisplay="yes" showPlaylist="no">' . "\n");

// Set the directory which contains the MP3 files. Don't forget the trailing slash!
// "./" is the directory this PHP file lives in.
$dir = './';

$handle = opendir($dir);
// Loop through all the files found in this directory
while (false !== ($file = readdir($handle))) {
        // Ignore any types of subdirectories and process files with ".mp3" extension only
        if (!is_dir($file) && eregi("(.mp3)$", $file)) {
				$filesize = filesize($dir.$file);
                // Extract ID3 information from current file
                $mp3->GetInfo($dir.$file);

                // Build the <song> XML tag for the current song,
                // preferably using ID3v2 information, otherwise ID3v1 data if available.

                echo '    <song ';
				
			echo 'path="';
//			print $dir.$file;
			print $file;
			echo '"';				
				
                // Step 1: the "artist" attribute:
                echo 'artist="';
                if (!empty($mp3->id3v2Info[TP1][info][0][Value]))
                    echo $mp3->id3v2Info[TP1][info][0][Value] . '"';
                elseif (!empty($mp3->id3v2Info[TPE1][info][0][Value]))
                    echo $mp3->id3v2Info[TPE1][info][0][Value] . '"';
                elseif (!empty($mp3->id3v1Info[artist]))
                    echo $mp3->id3v1Info[artist] . '"';
                else
                    echo '"';

                // Step 2: the "title" attribute:
                echo ' title="';
                if (!empty($mp3->id3v2Info[TT2][info][0][Value]))
                    echo $mp3->id3v2Info[TT2][info][0][Value] . '"';
                elseif (!empty($mp3->id3v2Info[TIT2][info][0][Value]))
                    echo $mp3->id3v2Info[TIT2][info][0][Value] . '"';
                elseif (!empty($mp3->id3v1Info[title]))
                    echo $mp3->id3v1Info[title] . '"';
                else
                    echo '"';

                // Step 3: the "album" attribute:
				
                echo ' album="';
                if (!empty($mp3->id3v2Info[TAL][info][0][Value]))
                    echo $mp3->id3v2Info[TAL][info][0][Value] . '"';
                elseif (!empty($mp3->id3v2Info[TALB][info][0][Value]))
                    echo $mp3->id3v2Info[TALB][info][0][Value] . '"';
                elseif (!empty($mp3->id3v1Info[album]))
                    echo $mp3->id3v1Info[album] . '"';
                else
                    echo '"';
					

		// Step 4 - next extract PlayTime for progress bar and scrubber
		//get it in milliseconds to match position property in ActionScript
			//echo 'playtime'.floor($mp3->mpegInfo[PlaySeconds]*1000);
          
                // That's it for this file - on to the next one!
				
			echo ' />' . "\n";				
        }
}
closedir($handle);

// Print closing tag - we're done!
echo('  </player>' . "\n");

?>