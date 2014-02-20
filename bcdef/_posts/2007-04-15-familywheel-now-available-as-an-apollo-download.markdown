---
author: bcdef
comments: true
date: 2007-04-15 08:21:33+00:00
layout: post
slug: familywheel-now-available-as-an-apollo-download
title: FamilyWheel now available as an Apollo download
wordpress_id: 26
categories:
- bcdef
- Miscellany
---

Two related dilemmas exist with the browser-based version of FamilyWheel, both not uncommon to rich internet applications:



	
  * Local files are off-limits. Importing a user's selected GEDCOM file works by uploading the file to the bcdef.org server using a PHP script. This is cumbersome to the user, and introduces unnecessary complexity from a developer standpoint. Additionally, this is unlikely to scale reliably unless all imports are written to the server with unique filenames.

	
  * Neither is exporting back to the user's machine a trivial affair. Analogous to the import process, the new GEDCOM file could be written to the bcdef.org server and then trigger a browser download. Currently, FamilyWheel sidesteps this issue by asking the user to copy and paste a TextArea into a new file. Hardly a polished user experience.


Of course, there is a new alternative in town: [Apollo](http://labs.adobe.com/technologies/apollo/). Converting a browser-based application to an installable application resolves both of these dilemmas. Security restrictions of the browser sandbox no longer apply; the user's local files can be modified without resorting to external scripts and server uploads.

And since FamilyWheel is already written in Flex, the conversion to an Apollo application is relatively straightforward. FileReference instances and URLRequest uploads swap out nicely for instances of the File class of the new Apollo API.

Simple enough!

[Here's the AIR package which will allow you to install FamilyWheel locally](http://bcdef.org/downloads/FamilyWheel.air). Note that you'll need to install the alpha of the [Apollo runtime from Adobe labs](http://labs.adobe.com/downloads/apolloruntime.html) beforehand.

EDIT: Just to clarify the new functionality, pressing "Export" will save an export.ged file to your desktop (overwriting if necessary).

EDIT: This will not work with the public beta of AIR. I will update FamilyWheel to support the next beta release.

EDIT: FamilyWheel is now an AIR Beta 2 installer.
