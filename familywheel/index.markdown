---
author: bcdef
comments: true
date: 2006-12-06 07:40:05+00:00
layout: page
slug: familywheel
title: FamilyWheel
wordpress_id: 19
categories:
- Miscellany
---

At heart, the Family Wheel application is a simple genealogical cataloging program. You can construct a basic family tree of the names, dates, and notes of members of your family. Add a few entries and you will notice that the Family Wheel is an editor but also a succint way of viewing your family's ancestry. Its premise is to remain simple, presenting a circular ancestry chart radiating from a central circle. This circle represents the person you are currently adding information to. At a glance, you can see where gaps exist in your family records. By clicking any 'leaf' in the wheel, you can edit the information of an ancestor.


[![Example Screen Shot](/genealogy/FamilyWheelSS.jpg)](/genealogy/FamilyWheelSS.jpg)


You can also use Family Wheel to view images related to your family. You can use either a free or paid Flickr account to link Family Wheel's entries to digital copies of your family photographs and records. Assign tags to your Flickr 'photos', enter your Flickr email into Family Wheel, and it will automatically present thumbnails of images related to the person you are editing. Clicking any of these displays enlargements.

If you wish to have a local copy of your work, or to migrate your work to a more complex genealogical application, Family Wheel also supports an export of your data to a GEDCOM 5.5 file, which after being around for over ten years, is starting to show its age, but is still the most commonly used format for genealogical programs. You can also import another application's GEDCOM file to Family Wheel, though some extra information may not be presented visually.

Since Family Wheel is based on the Flash platform, you can run it on any Windows, Macintosh, or Linux machine with the Flash 9 Player installed.

That's about it. I only insist that you please do _not_ overwrite your existing GEDCOM files with those exported from Family Wheel. More detail is covered in the FAQ. Give it a spin and let me know what you think.

There is an both an [installable application download](/downloads/FamilyWheel.air) (using Adobe AIR) as well as an [online version
](http://www.bcdef.org/genealogy)

If you'd like to import an example GEDCOM file, try [this one](/genealogy/Kennedy.ged) of the Kennedy family.

**FAQ:**

Q: Is there any cost?
A: Family Wheel is free. I wanted to contribute this program for free since many genealogical applications are very expensive and/or old. Further still, there are a lack of good options for a simple genealogical editor for the Macintosh and Linux platforms.

Q: Is it safe to import information from Family Tree Maker, Reunion, or another program to Family Wheel
A: It is safe to import. That said, due to the stratified nature of the GEDCOM 5.5 format, I cannot guarantee persistance from import to export. Of the dozens of genealogical applications, each exports GEDCOM information slightly differently. Though I have tried to make the possibility as unlikely as possible, information may not transition correctly. For this reason, please do not overwrite your original GEDCOM files with those exported from Family Wheel, unless you are certain that all information has migrated intact. If you can pinpoint issues not noted here, please contact me.

Q: How can I be sure information has not been lost?
A: When you click "Export" you will be presented with a visual comparison of the import version with the export version. It is likely that information may be reordered, but no information should be lost. It's probably easier to use a text editor, but you can also search for a case-sensitive search phrase within this export window.

Q: From import to export, I noticed that some of the data entries no longer have a prefix in their cross-references (i.e. @I1@ becomes @1@ or @F123@ becomes @123@. Is this a bug in your exporter?
A: Prefixes will be stripped upon import. Older versions of GEDCOM used these prefix letters but they have become redundant, since you can tell what the cross reference should be from the four letter code that follows instead. This should be OK unless you plan on using the exported GEDCOM on very old software.

Q: Will my entries maintain their ID number?
A: Yes, they most definitely should. Not only is this important for compatibility with other applications, but it is essential to the Flickr integration that ID's remain the same upon import. If they do not, please let me know!

Q: Can I add more than one note per person?
A: Unfortunately, at this time, only one note can exist per person. Take care: if multiple notes exist per person in an imported GEDCOM file, they may not be correctly exported.

Q: The "Add Child" button is ghosted. How do I add a child?
A: You'll need to select a partner before you can add a child. A current limitation is that you cannot add a child without a second individual as its parent.

Q: Why does the application start with a Jane Doe. Why can't I start with a male?
A: At this time, you'll need to begin with a female for the first entry.

Q: How do I link my Flickr photos to Family Wheel?
A: FW currently accesses photos based on individual ID's. This means that if you plan on integrating Flickr with Family Wheel, it's important that your the ID's in your GEDCOM file do not change from import to export (this should not happen from using Family Wheel, but might if you use the GEDCOM in other applications).

To associate a Flickr photo with a person in Family Wheel, add a tag to the photo for "Gen:P:xxxx" where xxxx is the ID of the person in your GEDCOM file. This ID is found in the format "0 @xxxx@ INDI" in your GEDCOM file, usually preceding the line that contains the NAME entry of the person you want to link.

Q: I have tagged my Flickr photos appropriately but I still do not see them. What's wrong?
A: Check to ensure that your Flickr email matches the one you've entered into Family Wheel. Check also to make sure that the photos you expect to see are listed as 'public' in Flickr. Unfortunately, this is a requirement at this time. A possible update may be to add Flickr authentication in the future, which would allow you to keep your photos private as well as upload and tag directly in Family Wheel.

Q: Can I add information about burials, marriages, education, etc.?
A: At this point, there is only support for names, births, deaths, and one note per individual. This is an unfortunate limitation borne out of my lack of time to dedicate to the project. I would particularly like to implement a system for linking sources to the information sometime in the future. If there's interest in the application I may add a tab for editing this extra information.

Q: Why are there slashes in the export of individual's names
A: These are to signify a last name. Some programs also capitalize the entire last name.

Q: Why are some middle or first names turned into last names when importing?
A: In the case of importing, at this time, I expect that the last name may or may not have slashes, due to differences in GEDCOM exports. As a result, some names are incorrectly presumed to be surnames. This should be refined in the future with an option for a strict import, but for now, your entries for individuals with only one known name may inaccurately be given a last name.

Q: Is there support for GEDCOM 6.0 XML, GedML, or ...
A: Unfortunately not. Although GEDCOM 5.5 is not a perfect format, since it is the most common I chose to support this format first. Many of the benefits of newer formats come from further precision or extensibility. But these benefits are also often fleeting and more burdensome to enter data. While these are useful for the genealogical professional, the intended scope of FW is to encourage newcomers to genealogy to create a rudimentary GEDCOM 5.5 file of their ancestry.

Q: Can I merge one tree with another?
A: This is a complicated feature to provide. While I wouldn't rule out the possibility of providing this functionality in a future update, you may want to consider other applications that offer GEDCOM merging in the meantime if this is important for you.

Q: Importing my GEDCOM takes a long time or hangs my browser
A: A large GEDCOM file (i.e. >300k) may take a while to load.

Q: Family Wheel looks squashed on my screen
A: FW requires at least a 1024x768 resolution on your monitor.

Q: The men in the background frighten me! Can you swap them out?
A: I hope to add the feature to allow you to add your own background image, but if you have a hi-resolution photo you think would look appropriate, feel free to e-mail it to me.

Known Issues:
- In OSX FireFox 2, cannot enter data into fields (Safari works fine)
- Always starts with Jane Doe, no way to change sex of first entry
- No ability to remove a person
- No ability to add a child without a partner
- Using "ABT" or other abbreviations within the DATE field may mess up the import
- Days can be selected for months that do not have them, i.e. February 31st
- Notes can only be assigned to a person
- Only one note per person
- Single names are presumed to be surnames (which is not always the intent of the record)
- The circular chart is off a few pixels here and there
- No way to select initial individual after an import

Known parsing issues:
- Notes must be in linked format (i.e. preceded by 0 @0001@ NOTE) rather than explicit entries (i.e. 1 NOTE Note text goes here..., 2 CONT ... and continues here). Notes not in this format should migrate, however.
- GIVN, SURN elements are not recognized, nor do they migrate
