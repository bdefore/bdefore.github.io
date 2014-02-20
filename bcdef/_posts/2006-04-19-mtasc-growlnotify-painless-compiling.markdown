---
author: bcdef
comments: true
date: 2006-04-19 05:46:35+00:00
layout: post
slug: mtasc-growlnotify-painless-compiling
title: MTASC + GrowlNotify = Painless Compiling
wordpress_id: 16
categories:
- bcdef
- Miscellany
---

I just stumbled into a winning combination worth sharing for anyone interested in optimzing compile time for their SWFs. Sure we're usually talking about seconds here, but for me the gain is hardly about time, but by continuity of concentration.
Long ago, many of us traded the malaise of waiting for the Flash IDE to publish SWFs for large projects (can you say "No you can't go look at the .FLA you have to kettle watch until it's done!"). If there's anyone out there that suffered from a loss of all mental momentum while watching the publishing progress bar, surely they've considered the alternative of [MTASC](http://mtasc.org).

While there are many benefits to this third-party command line compiler, the primary ones for me are that it compiles faster, it gives better error reporting, and doesn't lock in your coding but rather allows you to return to it even while the SWF is being compiled. And if you're in the late stage of debugging a large application, these payoffs are entirely worth the small investment of preparing your .FLA correctly and learning the appropriate shell command of republishing your SWF from the terminal.

For a recent project, I noticed that I was ALT-TAB'ing between BBEdit and Terminal and re-running the compile process, and that this step could be expedited, so I created an AppleScript that allowed me to hit CTRL-ENTER from BBEdit, in the fashion of Test Movie from within the Flash IDE, and it would run the mtasc shell script for the current project I was working on. In essense this saved me the step of ever having to look at the terminal, I could simply switch over to the browser, hit refresh, and I'd be good to go with the new changes.

A problem arose because there were times, of course, where I'd mistypted, and unbeknownst to me I'd be looking not at a newly compiled SWF but the same one that had been published previously. This is due to MTASC's preference (a sane one I would wager) that if there are compiler errors there's no sense in even generating a likely corrupt SWF. But I often hadn't realized that I wasn't looking at a fresh new SWF until I went back to the terminal and realized errors had been reported. Hence, my AppleScript shortcut was often adding confusion to my workflow.

A few days ago I uncovered the solution when I downloaded the newest version of [Growl](http://growl.info), a notification system for OS X that provides a means by which many popular applications, such as Safari, Adium, or GMail, may create a bubble window to quickly get your attention, tell you some small tidbit, and then piss off. No clicking of dialog boxes necessary. For real-world examples, think "toast" or those little MSN Messenger updates that would pop up in the corner of the screen. It's a great productivity enhancer.

Perhaps I'd never thought to peruse the "extras" folder of the Growl archive before, but in this version of Growl, I noticed something I hadn't noticed before: an executable called "growlnotify". After installing this little gem and reading the docs, I realized here was my ticket to Painless Compiling!

With a little refresher course in Bash, I was able to construct a shell script of the following (yes it's simple, but no it ain't pretty, laugh all you like!):

`#!/bin/sh
growlnotify -m "MTASC Compilation In Progress..." -n "MTASC"
logfile=~/Web/_internal/Flappr/mtascLog.txt
mtasc -swf ~/Web/_internal/Flappr/Flappr.swf -out ~/Web/_internal/Flappr/FlapprMtasc.swf -cp /Users/Carlos/Classes/ -mx ~/Web/_internal/Flappr/MtascStart.as -version 7 &> $logfile
report=`cat $logfile`
formattedReport=${report:="Compilation successful"}
growlnotify -m "$formattedReport" -n "MTASC"`

All I have done is to wrap a couple notification messages around my mtasc compliation script. One announces that MTASC is beginning compilation, and another to either accounce that compilation was successful or to announce the error report if it was not. This script could easily be ported to any other MTASC project, just by swapping out the paths and setting up the mtasc compile instructions accordingly.
![Using Growl's default ](/images/crop.jpg)

Then, using Growl's customizable displays, I can either skin the announcements in several potential display styles, my favorites being either blue bubbles popping up to the top right, or mimicing the "bezel" look of common OS X notifications such as the half-opaque box showing the current volume when you raise or lower volume from the keyboard. Either way, I'm content because I can now earnestly stay focused on my text editor even as an error report comes in from my attempt to compile.

![Using Growl's ](/images/bevelledLook1.jpg)

![Using Growl's ](/images/bevelledLook2.jpg)

But it could be even better. I'd love it if someone more familiar with AppleScript could suggest a way so that when a Growl notification from an MTASC error occured (or perhaps only when the message was clicked), it instantly shifted BBEdit to the line in the code where the parsing failed. Anyone up to the task?

As for Growl, I've found this type of information display highly effective, and always wondered why it wasn't used on an OS level more often for relatively unimportant dialog boxes that otherwise only slow you down.
