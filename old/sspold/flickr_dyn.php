<?php

include('inc/inc.php');

global $flickr;
include('config.php');

// Collect GET Variables if they exist:

if($_GET['email']) { $flickr[email] = $_GET['email'] };
if($_GET['password']) { $flickr[email] = $_GET['password'] };
if($_GET['url']) { $flickr[email] = $_GET['url'] };

///////////////////////////////////////////////////////////
///////////////// ADVANCED CONFIGURATION  /////////////////
///////////////////////////////////////////////////////////

// Flickr API Developer Key <- Please don't copy
$flickr[api_key] = '7f0ef5320c47a765408fcb56d6e72845';

// This will be used as an alias to make the code a little
// more readable
$furl = "http://www.flickr.com/services/rest/";

///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////
/////////////////////  REGULAR CODE  //////////////////////
///////////////////////////////////////////////////////////

if(file_exists($flickr[xml]))
{
    // Return the content of the XML file if no update is needed
    if(!((time() - filemtime($flickr[xml])) >= $flickr[period]))
    {
        if($f = fopen("$flickr[xml]","r"))
        {
            $content = fread($f, filesize($flickr[xml]));
            fclose($f);
            print_r($content);
            return;
        }
        else
        {
            die('Could not open the cache of the XML file');
        }
    }
}


///////////////////////////////////////////////////////////
////////////////////////  UPDATE  /////////////////////////
///////////////////////////////////////////////////////////

// If we get to this point it means that we must update the
// XML by downloading the newest data from flickr.
$xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
$xml .= '<gallery>'."\n";

// Step 1 - Get the photosets
$url = $furl."?email=$flickr[email]&password=$flickr[password]&method=flickr.photosets.getList&api_key=$flickr[api_key]";
$snoopy = new Snoopy;
$snoopy->fetch($url);
$photosets = XML_unserialize($snoopy->results);
$photosets = $photosets[rsp][photosets][photoset];

// Step 2 - Get the info of each photoset and in the meantime
// lets create the final XML.
while(count($photosets) > 0)
{
    
    $psa = array_shift($photosets);
    $ps = array_shift($photosets);
    // Create XML tag of the album
    $xml .= "\t".'<album title="'.$ps[title].'" description="'.$ps[description].'" lgPath="" tn="http://photos'.$psa[server].'.flickr.com/'.$psa[primary].'_'.$psa[secret].'_s.jpg">'."\n";
    
    // Get photos of the current album
    $url = $furl."?email=$flickr[email]&password=$flickr[password]&method=flickr.photosets.getPhotos&photoset_id=$psa[id]&api_key=$flickr[api_key]";
    $snoopy->fetch($url);
    $photos = XML_unserialize($snoopy->results);
    $photos = $photos[rsp][photoset][photo];
        
    while(count($photos) > 0)
    {
        $ph = array_shift($photos);
        array_shift($photos);
        //print_r($ph);

        // Create XML tag of the image
        $xml .= "\t\t".'<img src="http://photos'.$ph[server].'.flickr.com/'.$ph[id].'_'.$ph[secret].'.jpg" link="'.$flickr[url].$ph[id].'" caption="'.$ph[title].'" />'."\n";
    }
    
    $xml .= "\t".'</album>'."\n";

}
$xml .= '</gallery>'."\n";

// Now we save the XML file
if($w = fopen("$flickr[xml]","wb")){
    fwrite($w,$xml);
    fclose($w);
}
else
{
    die('Could not save cache file');
}

header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] );

?>