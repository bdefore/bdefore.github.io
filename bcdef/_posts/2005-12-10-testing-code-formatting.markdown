---
author: bcdef
comments: true
date: 2005-12-10 18:20:38+00:00
excerpt: 'It may be true that I''m the last Flash developer to learn of this trick,
  but just in case: here''s a shorthand fix to prevent "undefined" from showing up
  in text fields when loading in data that may or may not be null - for instance,
  while developing <a href="/flappr">Flappr</a>, I would sometimes see "undefined"
  on the user''s profile strip for the location they may or may not have entered into
  their Flickr account.'
layout: post
slug: testing-code-formatting
title: Preventing "undefined" in Text Fields
wordpress_id: 5
categories:
- bcdef
- Flash
---

It may be true that I'm the last Flash developer to learn of this trick, but just in case: here's a shorthand fix to prevent "undefined" from showing up in text fields when loading in data that may or may not be null - for instance, while developing [Flappr](/flappr), I would sometimes see "undefined" on the user's profile strip for the location they may or may not have entered into their Flickr account.

Although it can aid in debugging, it's more often a nuisance, particularly for professional work - when geekspeak like "undefined" inappropriately shows up, it's surprising how uncomfortable a client can be with the quality of your work. Also, if I had incorporated code from the days of publishing for Flash Player 6 / Actionscript 1.0, where previously no text had shown up, the more explicit Player 7 or higher would insert "undefined" into the text field.

Since I frequently work with external data sources, I had to protect against this so often that I had created a nullCheck function along the lines of:

`class org.bcdef.util.StringTools {

	private function StringTools() {}	// singleton class

	public static function nullCheck(txt:String):String {		
		if(txt==undefined) {
			return " ";
		}
		else if(txt.indexOf("undefined")!=-1) {
			return " ";
		}
		else if(txt.indexOf("NaN")!=-1) {
			return " ";
		}
		else return txt;
	}	
}
`

I would then usually wrap a nullcheck around every text assignment that came from an external data source, a la myTextField.text = StringTools.nullCheck(stringFromXMLFeed). This worked fine, but as I began to make more and more robust applications, the tedium of this structure, combined with the processing drag (in one case over 100 tf's were updating every four seconds) of sending through this 3 segment loop made me wonder if there was a better way.

Well naturally, there is: just assign your text within a conditional against a string of a one character space. If the variable is null, it will print the space instead.

`var someText:String = "Huzzah!";
var emptyText:String = "";
var nullText:String;

myTextField1.text = (someText || " "); 
myTextField2.text = (emptyText || " "); 
myTextField3.text = (nullText || " "); 
`

This cleans up my code significantly, and avoids the need for importing or pasting in an extraneous nullCheck function into each class that I use. The downside is that this code isn't as bulletproof as my nullCheck() function; if someText had passed through some previous logic, it's possible that it had already been assigned the string: "undefined", in which case it would not be null and hence would still print to the screen. This means that relying on this check requires using it whenever assigning a string, not only when it is used to set a text field.

Is one way better than the other? Does anyone else know of a trimmer / more reliable method?
