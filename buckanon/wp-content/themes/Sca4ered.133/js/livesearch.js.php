//  Copyright (c) 2004 Bitflux GmbH                                      

//  Licensed under the Apache License, Version 2.0 (the "License");      
//  you may not use this file except in compliance with the License.     
//  You may obtain a copy of the License at                              
//  http://www.apache.org/licenses/LICENSE-2.0                           
//  Unless required by applicable law or agreed to in writing, software  
//  distributed under the License is distributed on an "AS IS" BASIS,    
//  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or      
//  implied. See the License for the specific language governing         
//  permissions and limitations under the License.                       

//  Author: Bitflux GmbH                             



var liveSearchReq = false;
var t = null;
var liveSearchLast = "";
var isIE = false;
// on !IE we only have to initialize it once
if (window.XMLHttpRequest) {
	liveSearchReq = new XMLHttpRequest();
}

function liveSearchInit() {
	
	if (navigator.userAgent.indexOf("Safari") > 0) {
		$('livesearch').addEventListener("keydown",liveSearchKeyPress,false);
		$('livesearch').setAttribute("autocomplete","off");
	} else if (navigator.product == "Gecko") {
		$('livesearch').addEventListener("keypress",liveSearchKeyPress,false);
		$('livesearch').setAttribute("autocomplete","off");
	} else {
		$('livesearch').attachEvent('onkeydown',liveSearchKeyPress);
		isIE = true;
		$('livesearch').setAttribute("autocomplete","off");
	}
}

function liveSearchKeyPress(event) {
	if (event.keyCode == 40 )
	//KEY DOWN
	{
		highlight = $("LSHighlight");
		if (!highlight) {
			highlight = $("LSResult").firstChild.firstChild.nextSibling.firstChild;
		} else {
			highlight.removeAttribute("id");
			highlight = highlight.nextSibling;
		}
		if (highlight) {
			highlight.setAttribute("id","LSHighlight");
		} 
		if (!isIE) { event.preventDefault(); }
	} 
	//KEY UP
	else if (event.keyCode == 38 ) {
		highlight = $("LSHighlight");
		if (!highlight) {
			highlight = $("LSResult").firstChild.firstChild.nextSibling.nextSibling.lastChild;
		} 
		else {
			highlight.removeAttribute("id");
			highlight = highlight.previousSibling;
		}
		if (highlight) {
				highlight.setAttribute("id","LSHighlight");
		}
		if (!isIE) { event.preventDefault(); }
	} 
	//ESC
	else if (event.keyCode == 27) {
		highlight = $("LSHighlight");
		if (highlight) {
			highlight.removeAttribute("id");
		}
		$("LSResult").style.display = "none";
		document.forms.searchform.s.value = '';
	} 
}
function liveSearchStart() {
	if (t) {
		window.clearTimeout(t);
	}
	t = window.setTimeout("liveSearchDoSearch()",200);
}

function liveSearchDoSearch() {
	if (liveSearchLast != document.forms.searchform.s.value) {
	if (liveSearchReq && liveSearchReq.readyState < 4) {
		liveSearchReq.abort();
		// Fade the background of the results box
	}
	if ( document.forms.searchform.s.value == "") {
		$("LSResult").style.display = "none";
		highlight = $("LSHighlight");
		if (highlight) {
			highlight.removeAttribute("id");
		}
		return false;
	}
	if (window.XMLHttpRequest) {
	// branch for IE/Windows ActiveX version
	} else if (window.ActiveXObject) {
		liveSearchReq = new ActiveXObject("Microsoft.XMLHTTP");
	}
	liveSearchReq.onreadystatechange= liveSearchProcessReqChange;
	liveSearchReq.open("GET", "<?php bloginfo('template_url'); ?>/livesearch.php?s=" + document.forms.searchform.s.value);
	liveSearchLast = document.forms.searchform.s.value;
	liveSearchReq.send(null);
	}
}

function liveSearchProcessReqChange() {
	
	if (liveSearchReq.readyState == 4) {
		var  res = $("LSResult");
		res.style.display = "block";
		/* res.firstChild.innerHTML = liveSearchReq.responseText; */
		res.firstChild.innerHTML = '<div id="searchcontrols"><small>Arrow keys, enter & <a href="javascript://" title="Close results" onclick="closeResults()">escape</a></small><br /></div>'+liveSearchReq.responseText;
	}
	
	//getDDSize();
	//setListenLinks();
}

function liveSearchSubmit() {
	var highlight = $("LSHighlight");
	if (highlight && highlight.firstChild) {
		window.location = highlight.firstChild.getAttribute("href");
		return false;
	} 
	else {
		return true;
	}
}

function closeResults() {
    $("LSResult").style.display = "none";
}