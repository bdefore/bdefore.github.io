---
author: bcdef
comments: true
date: 2006-12-20 06:34:10+00:00
layout: post
slug: genealogy-application-in-as3-familywheel
title: 'Genealogy Application in AS3: FamilyWheel'
wordpress_id: 25
categories:
- bcdef
- Miscellany
---

One of my Christmas gifts to the family this year is to _finally_ go through the drawer full of photos and grandparents' scratches of relations, and turn it into something more permanent.

There are many applications out there, particularly on Windows, that allow you to do much the same. But I've found them to be a creaky lot with roughly the visual appeal of Microsoft Access. And on other operating systems the options are scant. So I took the opportunity to familiarize myself more with Flex and AS3 and build my own parser of GEDCOM (the standard genealogical database format).

The greatest challenge may well have been conceiving of a way to present a family tree on a computer screen in a way that wasn't a rectangular vastness, the way most family trees appear. With some Scotch tape, lots of paper, and a large enough dining room table, this  information design is bearable, but the same cannot be said for fitting the family tree on the screen of a Macbook.

At one point I was playing with the AS3 drawing API when I realized that a circle could be divided into an infinite amount of parts, dividing each arc into two parts (representing father and mother) in the subsequent ring. Ultimately, I realized not only was this space conscious, but also more connected to the mental image of a family "tree".


[![Example Screen Shot](/genealogy/FamilyWheelSS.jpg)](/genealogy/FamilyWheelSS.jpg)


I'm sure someone's thought of this schematic somewhere, but I didn't see anything resembling this structure until I came up with a name for the application, Family Wheel. When searching around for where this phrase may have been used, I found a fascinating [circular hand-drawn chart of descendants](http://www.brasenhill.com/worrilow/wheel.html), about a century old.

The concept was born, and, as many Flex developers have come to expect, the visual part followed quite easily. I'm amazed at how effectively ActionScript 3.0 averts the roadblocks I would encounter in AS2. If anyone's counting, here's another convert to doing any and every ActionScript project in AS3.

And I've decided I want to offer this to everyone else too, so Happy Yule to y'all! Just keep in mind this is a work in progress so don't overwrite your GEDCOM files unless you're sure the export has functioned correctly.

You can try it out [here](/genealogy). If you'd like a sample GEDCOM, or to know more details about integration with Flickr as well as other genealogical apps, read up on it at the [project page](/?page_id=19).
