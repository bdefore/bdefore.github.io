---
author: bcdef
comments: true
date: 2006-12-06 23:35:25+00:00
layout: post
slug: as3-flickr-upload-can-it-be-done
title: AS3 Flickr upload - can it be done?
wordpress_id: 22
categories:
- bcdef
- Miscellany
---

Has anyone out there successfully modified Darron Schall's AS3 Flickr API to upload images to Flickr entirely through ActionScript? Due to a player limitation with FileReference.upload() at the time this API was written, the upload feature was left incomplete. I believe this is no longer an obstacle, since the player includes this parameter as the second parameter of that method:

`uploadDataFieldName:String = "Filedata"`
Flickr is expecting the binary data to be sent as the value of "photo" rather than "Filedata". Passing "photo" for this parameter should match what Flickr is expecting, and it would seem that the following code should successfully replace the Upload.upload() method in the API:

`public function upload( fileReference:FileReference, title:String = "",
description:String = "", tags:String = "", is_public:Boolean = false,
is_friend:Boolean = false, is_family:Boolean = false):void {
// The upload method requires signing, so go through
// the signature process
var sig:String = _service.secret;
sig += "Filename"+fileReference.name;
sig += "UploadSubmitQuery"
sig += "api_key" + _service.api_key;
sig += "auth_token" + _service.token;
sig += "description" + description;
sig += "is_family" + ( is_family ? 1 : 0 );
sig += "is_friend" + ( is_friend ? 1 : 0 );
sig += "is_public" + ( is_public ? 1 : 0 );
sig += "tags" + tags;
sig += "title" + title; var vars:URLVariables = new URLVariables();
vars.api_key = _service.api_key;
vars.auth_token = _service.token;
vars.description = description;
vars.is_family = ( is_family ? 1 : 0 );
vars.is_friend = ( is_friend ? 1 : 0 );
vars.is_public = ( is_public ? 1 : 0 );
vars.tags = tags;
vars.title = title;
vars.api_sig = MD5.hash( sig );
var request:URLRequest = new URLRequest();
request.data = vars
request.url = UPLOAD_DEST;
request.method = "POST";
trace(vars);
fileReference.upload( request, "photo" );
}`

Try as I may, however, I get an invalid signature error from Flickr. There's a few reasons I could see this being the case:



	
  1. The FileReference class, in addition to the api_key, auth_token, and api_sig variables that must be manually added to the POST request, also sends two additional name/value pairs: "Filename" with the corresponding name of the file, and "Upload" with the value "Submit Query". I can confirm this by making a FileReference.upload to a PHP script that traces out all POST variables. These extra variables may not be expected by the Flickr upload server, and may be breaking the request. This would be problematic, since the only solution if this were true would be to find an alternative to FileReference for uploading a file.

	
  2. Should these two extra variables be a part of the generated signature?

	
  3. Flickr either expects an MD5 signature with its variables in case-sensitive sequence or case-insensitive sequence. This is important since the "Filename" and "Upload" variables are capitalized. They either are before the other variables or mixed within. There's nothing in Flickr's documentation to indicate which one is correct. This shouldn't be a roadblock, however, since it has to be one or the other.

	
  4. AS3 FileReference sends the photo data not as "image/jpeg" as Flickr specifies in their example request, but as "application/octet-stream"


ActionScript 3.0 docs list an example upload as:

`  POST /handler.cfm HTTP/1.1
Accept: text/*
Content-Type: multipart/form-data;
boundary=----------Ij5ae0ae0KM7GI3KM7ei4cH2ei4gL6
User-Agent: Shockwave Flash
Host: www.example.com
Content-Length: 421
Connection: Keep-Alive
Cache-Control: no-cache
------------Ij5GI3GI3ei4GI3ei4KM7GI3KM7KM7
Content-Disposition: form-data; name="Filename"
MyFile.jpg
------------Ij5GI3GI3ei4GI3ei4KM7GI3KM7KM7
Content-Disposition: form-data; name="photo"; filename="MyFile.jpg"
Content-Type: application/octet-stream
FileDataHere
------------Ij5GI3GI3ei4GI3ei4KM7GI3KM7KM7
Content-Disposition: form-data; name="Upload"
Submit Query
------------Ij5GI3GI3ei4GI3ei4KM7GI3KM7KM7--`

While Flickr lists a request example as: `POST /services/upload/ HTTP/1.1
Content-Type: multipart/form-data; boundary=---------------------------7d44e178b0434
Host: api.flickr.com
Content-Length: 35261
-----------------------------7d44e178b0434
Content-Disposition: form-data; name="api_key"
3632623532453245
-----------------------------7d44e178b0434
Content-Disposition: form-data; name="auth_token"
436436545
-----------------------------7d44e178b0434
Content-Disposition: form-data; name="api_sig"
43732850932746573245
-----------------------------7d44e178b0434
Content-Disposition: form-data; name="photo"; filename="C:test.jpg"
Content-Type: image/jpeg
{RAW JFIF DATA}
-----------------------------7d44e178b0434-- `

If anyone's resolved this issue, please let me know!
