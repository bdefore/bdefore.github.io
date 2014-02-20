<?php
/* ---------------------------------------------------------------------------
Title: FlickrSSP 1.0
Author: Brian Sweeting (http://www.sweeting.net/)

FlickrSSP allows you to use Flickr (http://www.flickr.com) and SlideshowPro 
(http://www.slideshowpro.net) to display all of your Flickr photosets on 
your own website instead of just your recent photos via the RSS feed.
--------------------------------------------------------------------------- */

class FlickrSSP {

   var $flickr_api_url;
   var $config = array();
   var $error = array();
   
   
   function flickrssp($config) {
            
      // Set configuration parameters
      $this->config['user_id'] = $config['user_id'];
      $this->config['api_key'] = $config['api_key'];
      $this->config['url'] = $config['url'];
      $this->config['xml'] = $config['xml'];
      $this->config['cache_period'] = $config['cache_period'];
      
      // Set the url to Flickr's REST api
      $this->flickr_api_url = 'http://www.flickr.com/services/rest/';
      
      // Get the xml and return it
      $this->getXml();
      
      // If there were errors display them
      if(sizeof($this->error)>0) {
         $this->displayErrors();
      }
      
      return;
   }
   
   function getXml() {
      
      //Check to see if xml file exists, if not attempt to create it
      if(file_exists($this->config['xml'])) {
         // Return the content of the XML file if no update is needed
         if(!((time() - filemtime($this->config['xml'])) >= $this->config['cache_period'])) {
            $this->displayXml();
         }
         else { 
            $this->updateXml();
         }
      }
      else {
         $this->updateXml();
      }
      
      return;
      
   }
   
   function updateXml() {
      
      include_once('snoopy.class.php');
      include_once('xml.inc.php');
      
      $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
      $xml .= '<gallery>'."\n";

      // Step 1 - Get the photosets
      $url = $this->flickr_api_url.'?method=flickr.photosets.getList&api_key='.$this->config['api_key'].'&user_id='.$this->config['user_id'];
      $snoopy = new Snoopy;
      $snoopy->fetch($url);
      $photosets = XML_unserialize($snoopy->results);
      if(isset($photosets[rsp][photosets][photoset])) {
          $photosets = $photosets[rsp][photosets][photoset];
       }
       else {
          $this->error[] = 'Flickr Error: '.$photosets['rsp']['err attr']['msg'].' (Error Code = '.$photosets['rsp']['err attr']['code'].')';
          $this->displayErrors();
       }

      // Step 2 - Get the info of each photoset and in the meantime
      // lets create the final XML.
      while(count($photosets) > 0) {
    
          $psa = array_shift($photosets);
          $ps = array_shift($photosets);
          // Create XML tag of the album
          $xml .= "\t".'<album title="'.$ps[title].'" description="'.$ps[description].'" lgPath="" tn="http://photos'.$psa[server].'.flickr.com/'.$psa[primary].'_'.$psa[secret].'_s.jpg">'."\n";
    
          // Get photos of the current album
          $url = $this->flickr_api_url.'?method=flickr.photosets.getPhotos&photoset_id='.$psa['id'].'&api_key='.$this->config['api_key'].'&user_id='.$this->config['user_id'];
          //echo $url;
          $snoopy->fetch($url);
          $photos = XML_unserialize($snoopy->results);
          if(isset($photos[rsp][photoset][photo])) {
             $photos = $photos[rsp][photoset][photo];
          }
          else {
             $this->error[] = 'Flickr Error: '.$photosets['rsp']['err attr']['msg'].' (Error Code = '.$photosets['rsp']['err attr']['code'].')';
             $this->displayErrors();
          }

          while(count($photos) > 0) {
              $ph = array_shift($photos);
              array_shift($photos);
              
              // Create XML tag of the image
              $xml .= "\t\t".'<img src="http://photos'.$ph[server].'.flickr.com/'.$ph[id].'_'.$ph[secret].'.jpg" link="'.$this->config['url'].$ph[id].'" tn="http://photos'.$ph[server].'.flickr.com/'.$ph[id].'_'.$ph[secret].'_t.jpg" caption="'.$ph[title].'" />'."\n";
          }
    
          $xml .= "\t".'</album>'."\n";

      }
      $xml .= '</gallery>'."\n";
      
      // Now we save the XML file
      if($w = @fopen($this->config['xml'],"wb")) {
          fwrite($w,$xml);
          fclose($w);
      }
      else {
          $this->error[] = 'Could not save the XML file';
      }
      
      //print_r($this->error);
      // Get the xml and return it
      $this->displayXml();
      
   }
   
   function displayXml() {
      
      if(!sizeof($this->error)>0) {
         header("Content-type: text/plain");
      
         if($f = fopen($this->config['xml'],'r')) {
            $xml = fread($f, filesize($this->config['xml']));
            fclose($f);
            print_r($xml);
            return;
         }
         else {
            $this->error[] = 'Could not open the cache of the XML file';
         }
      }
   }
   
   function displayErrors() {
      
      header("Content-type: text/html");
      
      // Loop through the error array and display errors
      for($i=0;$i<sizeof($this->error);$i++) {
         echo $this->error[$i]."<br />\n";
      }
      
      // Kill the execution of the script
      exit;
      
   }
}
?>