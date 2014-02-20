---
author: bcdef
comments: true
date: 2007-09-10 05:26:29+00:00
layout: post
slug: a-gathering-of-commentary-on-flash-player-support-of-aach264
title: A gathering of commentary on Flash Player support of AAC/h.264
wordpress_id: 27
categories:
- bcdef
- Miscellany
---

Given the tumult of the change in my day job, it's been difficult for me to keep up with the fallout of Adobe's announcement of rudimentary support of AAC and h.264 codecs in a beta release of Flash Player 9. (For a start, you can read the [formal announcement](http://www.adobe.com/aboutadobe/pressroom/pressreleases/200708/082107FlashPlayer.html) and/or the more technical explanation of the limitations [from Tinic Uro](http://www.kaourantin.net/2007/08/what-just-happened-to-video-on-web_20.html), an engineer on the Player team.)

In the two weeks hence, there's been a lot of talk about the implications. As far as I can gather, no major player in web video has made the beta player a requirement to any public-facing player, but it wouldn't surprise me if we see this soon - YouTube may have name recognition, but undoubtedly the video-on-the-web market is a cutthroat arena with many competitors. Of course, offering h.264 support means a potential migration from the current status quo - that of On2's VP6 codec, supported in Flash Player since version 8. On2 of course isn't taking all of this lying down. They've released [an FAQ](http://www.on2.com/company/news-room/h264_faq/) that highlights how sticking with their codec is still advantageous in specific scenarios. There's even a community that prefers to sidestep the Flash Player entirely in favor of [streaming DivX](http://stage6.divx.com/). Some even [see Microsoft's impact](http://weblogs.asp.net/jezell/archive/2007/08/23/silverlight-forcing-macromedia-to-rethink.aspx) on the decision. But all said, there's little question where the momentum lies given the rabid response to h.264's inclusion into the Player.

I'm excited to see where this goes. There's already some interesting [demos](http://www.flashcomguru.com/apps/hd_full/hd.html), write-ups and [commentary](http://www.readwriteweb.com/archives/adobe_flash_player_moviestar_h264.php#comments) on the subject and there's certainly a lot of dust yet to settle, especially given Adobe's [related announcement](http://www.adobe.com/aboutadobe/pressroom/pressreleases/200709/090607FMS.html) of Flash Media Server 3.

For those interested in exploring the new technology, understand that there are some important limitations to the beta release. Most noticeable is that only a subset of h.264 movies are currently playable. A significant number of encoding tools place the index information of an h.264 .mov file at the end of the file - this needs to be moved to the front in order for the file to be playable in a SWF. Renaun Erickson has [created an AIR application](http://renaun.com/blog/2007/08/22/234/) which attempts to do this for you.

For more information, begin with [Tinic's article](http://www.kaourantin.net/2007/08/what-just-happened-to-video-on-web_20.html), as well as [an optimization note from Adobe](http://labs.adobe.com/wiki/index.php/Flash_Player:9:Update:Full-Screen_Mode_HW).

Also a note on [metadata support](http://www.brooksandrus.com/blog/2007/08/29/a-quick-look-at-h264-metadata/).
