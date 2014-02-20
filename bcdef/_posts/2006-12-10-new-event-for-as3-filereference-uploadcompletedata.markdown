---
author: bcdef
comments: true
date: 2006-12-10 04:19:42+00:00
layout: post
slug: new-event-for-as3-filereference-uploadcompletedata
title: 'New event for AS3: FileReference uploadCompleteData'
wordpress_id: 23
categories:
- bcdef
- Miscellany
---

Just a full post from a comment to the previous: Player version 9.0.28.0 includes a little known AS3 update: the FileReference class now dispatches an event not only after an upload is complete, but also after fully downloading a server response to an upload.

I'm sure this could be used in several creative ways, but first to mind: a server script's success/error report can now be handled direclty in ActionScript and communicated to the user.

Or, one step further, you can tie into server scripts written in other languages, i.e. PHP or Ruby, that process asynchronous requests externally and return value(s) that can then be used to add functionality to your ActionScript application.

Formal documentation should arrive with the next authoring release, but to use this functionality for now, add an event listener to "uploadCompleteData". Your callback function will receive an event with a "data" property that contains the server's response. For example:

`myFileReference.addEventListener("uploadCompleteData",callbackFunction);
myFileReference.upload(new URLRequest(PATH_TO_YOUR_SCRIPT))

function callbackFunction(e:*):void {
trace(e.data);
};`

For public applications, be sure to detect the client's player for the 28 minor release.
