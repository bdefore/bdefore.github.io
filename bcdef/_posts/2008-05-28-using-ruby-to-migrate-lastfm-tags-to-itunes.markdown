---
author: bcdef
comments: true
date: 2008-05-28 01:40:25+00:00
layout: post
slug: using-ruby-to-migrate-lastfm-tags-to-itunes
title: Using Ruby to migrate last.fm tags to iTunes
wordpress_id: 28
categories:
- bcdef
- Ruby
tag:
- genres
- id3
- itunes
- Ruby
---

Once upon a time, digital audio files didn't carry any information about their creators. No artist name, album, track number - nothing. Naturally, this led to a lot of redundant effort keeping music organized and contextual. In 1996, a little sidecar called [ID3](http://en.wikipedia.org/wiki/Id3) came along, essentially providing a space for textual information about audio that hitched a ride within an MP3 file. While it was a victory for the preservation of metadata, it had its limitations, not least of which was a stodgy implementation of a Genre field.



The original sidecar had only 35 bytes of space to work with. Maximizing room for artist, album, and everything textual meant reducing genre to a single category, which was converted to an integer so that it only occupied a single byte in the overall sidecar. The original ID3 implementation only contained [80 pre-determined options](http://www.linuxselfhelp.com/HOWTO/MP3-HOWTO-13.html#ss13.3) for the genre field.



Which leads to the dilemma:





  * No song is of a single genre, but rather many. What good is reducing music to a single container anyway?


  * What is rawk to one listener is britrock to another, is emo to another and is shoegaze nu-wave to another.





For this reason, the metadata available in the Genre field is of little use to anyone. You can't just listen to Alternative or Electronic and expect to have a consistent listening experience. In a sense this parallels the problems with the RIAA and big label music, but I'll leave that tangent for another day...



We're not stuck in that bucket anymore. One of the advantages of having millions of people tagging music through services like [last.fm](http://last.fm) is that we now have a much more nuanced voice in describing our music. Let's face it, it's not very helpful to call [Boards of Canada](http://www.last.fm/music/Boards+of+Canada) an electronic group. In fact they are 'electronic ambient idm chillout downtempo trip-hop indie Warp experimental Scottish chill.' (according the leading tags from last.fm)



Services like last.fm, [Pandora](http://www.pandora.com) and [imeem](http://www.imeem.com) are popular because they use these tags to create radio streams that, unlike most terrestrial American radio, people enjoy listening to. 



A couple years ago, before Pandora was available, I realized that I wasn't listening to the majority of the music I owned; when I wanted to listen to music I usually thought of an artist rather than a type of music. In an effort to fix this, I took a day off attempting to create iTunes playlists that suited various musical needs. I tried to create a party mix. I tried to create a mix reminiscent of the 90's or the 60's. I tried to create an ambient electronic music group that I could listen to while working. Given the size of my library, it was tedious stuff, and never felt a step closer to being finished.



Yesterday I realized there's a better solution: last.fm's [AudioScrobbler](http://www.audioscrobbler.com) data service provides a way of retrieving tags for any given artist. The rest is creating a way of integrating that with my iTunes library. I did some searching and found that Wes Rogers' [Last.fm Tagger](http://www.633k.net/2008/03/we-made-it-to-10.html) accomplishes a great deal of what I was hoping to do. It's limitation was that it only took the foremost tag from last.fm and set that into the Genre field. 



I've modified his script so that it compiles up to 20 tags, given a popularity threshold, and places them as a string into the Grouping field. Just select whichever files in iTunes you'd like to add the tags to and run the Ruby script (If you've selected thousands it may take a while!) After that, you activate the Grouping column in iTunes and then search for whatever you're in the mood for. You can search for two or more genres to refine your search. And because users also often tag with context other than genre (where the music is from, speed, male/female vocalists) this really expands the boundaries of your search. It's a bit like bringing the benefits of online radio to your local music collection.



Here's to Wes for the MIT license, last.fm for AudioScrobbler, and the music taggers!



[Download iTunes Multi-Genre Tagger v0.5](http://www.bcdef.org/downloads/itunes_multigenre_tagger_0.5.zip).



**Important Note**: This will OVERWRITE whatever information is in the Grouping field of your music files. This field is nearly always empty, but you should check that nothing important is there before running this script. You will need a bit of terminal savvy and Ruby to be installed on your system in order to build the tags.





**UPDATE**: There's now a newer version, and I've created a separate [project page](http://bcdef.org/itunes-multi-genre-tagger/) for it. As of version 0.6, it's now a standalone OS X application (though the Ruby source is included for use on other platforms).
