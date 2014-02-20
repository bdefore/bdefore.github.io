---
author: bcdef
comments: true
date: 2005-12-10 21:49:29+00:00
layout: post
slug: actionstep-alpha-1-now-available
title: ActionStep Alpha 1 Now Available
wordpress_id: 7
categories:
- bcdef
- Flash
---

Whether you shun or embrace Macromedia's V2 component architecture, you've undoubtedly hit some headaches trying to tame them. I've always felt it was remarkable how complicated it was to reskin them, for example. Other parties, both for profit and not-for-profit, have been offering alternatives for at least five years now, but there's not really any standout leader. I stand behind [Patrick Mineault's observations on the matter from two months ago.](http://www.5etdemi.com/blog/archives/2005/10/battle-of-the-component-sets/)

One of the better alternatives for its emphasis on a lightweight replacement that mimics the V2 API (at a hefty $399 price tag) is the [mCom](http://metaliq.com/mcom/) set from Grant Skinner and Metaliq. You would think you'd get decent documentation and support for such a price, but both are abysmal. And after wrestling with the smallest of details, I began to wonder if the true problem was the fact that mCom was attempting to be interchangeable with the built-in V2 components. Its potential is mainly limited by the same weaknesses that hamper what already comes OOTB from Macromedia. Ever try to export classes on another frame besides 1 (so that your preloader actually works) in the same project that you reskin components? Well mCom blows up just as gloriously as V2 does. In fact, I'd say that since mCom requires relies upon the Flash IDE and attachMovie() to instantiate from Actionscript, it's sadly just a glorified patch-up to a foundation that needs to be rebuilt.

It's really not fair to be so critical, building components is not easy territory. I've been checking in once in a while to see what the OSFlash community has put together. We've been hearing about [ActionStep](http://actionstep.org) for a while; it's been in development since April. Much of its approach is beyond my expertise, but in summary, it's an ActionScript 2.0 implementation of the OpenStep AppKit, using a Responder architecture. 

From the frontpage: 



> There are many component frameworks that have been written over the years, but the framework that I am most fond of is the NextStep/OpenStep/GNUStep/Cocoa "Application Kit". Because the AppKit has been implemented many times and is well documented (in books, online, etc) there is a strong base of code and designs to work from. 



This is exciting because it has the potential to be just the foundational component replacement the Flash community needs.
 
A few days ago, the team developing ActionStep released  an "Alpha 1" release. I took a gander, and it's still a little early to get a solid grasp of the project; documentation is pretty sparse yet. Some elements are [more polished than others](http://osflash.org/actionstep_components_list), and if you haven't yet explored [MTASC](http://www.mtasc.org/) you'll have a second learning curve to juggle, but the exploration piqued my curiosity. They've certainly completed a number of key milestones already.

You can read more about it, or snatch the alpha, right off the [front page](http://actionstep.org). A beta release is [scheduled](http://osflash.org/actionstep_roadmap) for January 31st, and the 1.0 is slated for (should we take a hint?) February 31st.

Note: [Another component set](http://posttool.com/as2components/) from David Karam is also looking pretty sharp. It's looking for help to finish off development. *nudge nudge*
