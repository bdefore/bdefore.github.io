---
author: bcdef
comments: true
date: 2005-12-18 02:17:18+00:00
layout: post
slug: bringing-space-invaders-into-the-as3-century
title: Bringing Space Invaders Into the AS3 Century
wordpress_id: 8
categories:
- bcdef
- Flash
---

Many people on the 'net associate Flash with gratuity and obnoxiousness. Given that it's one of the more powerful tools to use for the web experience, this is both understandable and unfortunate. Often its defense comes in the form of practical examples that genuinely improve both the experience and the intuitiveness of a web site, beyond what could be done without. While this is certainly important to the broader acceptance of Flash, what I often feel are the proudest accomplishments of the Flash community are usually those [farthest](http://sodaplay.com/constructor/index.htm) [from](http://pitaru.com/) the commercial sector. 



For years, Flash was one of the only moderately intuitive vehicles for creating mathematical / algorithmic art. The rapid spread of the Flash Player inclined creators to work with the young Flash Actionscript format since it was relatively easy to share their creations online. The work of many such math artists was compiled into a cult favorite math art manual, [Flash Math Creativity](http://www.amazon.com/gp/product/1590594290/qid=1134856717/sr=8-1/ref=pd_bbs_1/102-5966840-1007313?n=507846&s=books&v=glance). The publisher, Friend of Ed, has a [minisite](http://www.friendsofed.com/fmc/FMCv2/FMC.html) where you can play with some examples from the book.



One author, Jared Tarbell, whose broader work with Flash and [Processing](http://processing.org) can be found at [levitated.net](http://levitated.net) and [complexification.net](http://complexification.net), made an experiment called "Invaders" which populated the screen with a repeating set of pixely symmetrical monsters. You can read more about it's inspiration from [Jared's site](http://levitated.net/daily/levInvaderFractal.html). There you can also find the .FLA source if you're curious of how he's done it.



Unfortunately, that source is a few years old, in AS1 syntax, and around this time I had decided it was finally time to experiment with Macromedia's [Flex Builder 2 Alpha](http://labs.macromedia.com/technologies/flexbuilder2/). The reason I hadn't explored Builder earlier was because I really hadn't any purposeful place to start. Enter the Invaders.



Now I should hand out a disclaimer. You could say that the Invaders experiment is an absurd Flash piece to recode into AS3; it runs quite smoothly already, doesn't gain significantly from object-orientation, and certainly doesn't require any of the new libraries. And with that you'd be pretty spot on.



But regardless, I thought it was a reasonably short piece to translate and try my hands at the Flex Builder. So here you have it, running below in variations of AS1, AS2, and AS3 (you'll need the Flash Player 8.5 beta to see the third one). The only item I didn't implement into the AS3 version is the ability to click an invader and spawn smaller ones inside. But otherwise these should run identically (though they naturally may not look identical in the end).



Original SWF from levitated.net:



		


		



Updated to AS2:



		


		


Updated to AS3 (requres Flash Player 8.5 or higher to view):

		


		






You'll notice in the AS3 version that anti-aliasing isn't as smooth as in the previous two. I'm using the drawing API in the AS3 version rather than attaching a movie clip of a black box. The rougher edges may be  due to the default quality setting (I'm not sure how to change it yet in the publish options).



Overall, I found the conversion to AS3 to be relatively less tedious than I expected. The debugger could use better integration (half the time my browser couldn't locate it). But overall, it went smoothly. Most of the challenge lay in familiarizing myself with the Builder's publish settings. I have to admit that the most welcome change I have seen in coding for AS3 is the new addChild() and addChildAt() functions, which remove a lot of the troublemaking and variable passing that instance names and depths can add to a project (and certainly did to this one). In the end the code looks a lot cleaner in AS3, and another step more conducive to extending.



I have to wonder, however, at what point I should begin committing myself further to AS3. It's exciting to experiment with a future toolset this early, but since it will require another Flash Player download, we'll have to wait a few months before clients are ready to give the green light for an AS3 project. If the early release of the Builder Alpha leads you to doubt whether learning Flash 8 is worth it's investment, there's a good discsussion brewing over at [joshbuhler.com](http://www.joshbuhler.com/2005/12/08/is-learning-flash-8-worth-your-time/)



And, in case anyone's interested in exploring this further, here's [the source](/downloads/invaders.zip) for all three versions.





_Note: I had previously stated that the official launch of Flex Builder would not be for another year. I'd heard this somewhere along the vine, but couldn't retrace my information. And I've since received comment that the official projected release date from Adobe is much more optimistic than this, and that we should see an updated Alpha at the labs page in January. Good news! Maybe I'll fit in some more AS3 time after all..._
