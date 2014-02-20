---
author: bcdef
comments: true
date: 2008-05-30 22:54:12+00:00
layout: post
slug: setting-up-textmate-for-swf-compiling-and-terminal-trace-output
title: Setting up TextMate for SWF compiling and Terminal trace output
wordpress_id: 30
categories:
- bcdef
- Miscellany
---

If you're an OS X developer, chances are high that Eclipse really cramps your style. But don't feel married to Flex Builder just because you want to export SWF files. My preferred IDE for the last year has been to use TextMate in combination with the Terminal for trace outputs. Long overdue, here's my dangerously concise checklist of how to set this up for yourself:





  * Download the [debug version of the Flash Player](http://www.adobe.com/support/flashplayer/downloads.html) (you should see 'Debugger' in the context menu of any SWF you right click upon if you have the debug player). Note as of this writing there is no publicly available Flash Player 10 debug player yet - you'll need to stick with FP9 for now.


  * Download and install [TextMate](http://www.macromates.com).


  * To setup command-line compiling, install the [Flex SDK](http://opensource.adobe.com/wiki/display/flexsdk/Download+Flex+3), or if you already have Flex Builder installed just set up your .bash_profile's PATH to include '/Applications/Adobe\ Flex\ Builder\ 3/sdks/3.0.0/bin'. If all's in order, you should get a positive response typing 'mxmlc -help' from the command line. If you're not familiar with how to set up your .bash_profile, there's plenty of sites with [a little background on bash and how to do this](http://www.macdevcenter.com/pub/a/mac/2004/02/24/bash.html).


  * In order to trace debug and exceptions to your Terminal, you'll need to grant access to the player. This process is obscure and esoteric, but do it once you'll be set. If not already there, create a text file named 'mm.cfg' in /Library/Application Support/Macromedia/ and enter the following:

    
    
    ErrorReportingEnable=1
    TraceOutputFileEnable=1
    


Remember to quit all browsers and restart after making this change.


  * Now we'll equip TextMate to be AS/MXML savvy. Leopard has Subversion pre-installed, which we'll use to download the newest bundles from Macromates. Copy/paste the following into Terminal:


    
    
    cd ~/Desktop
    svn co http://macromates.com/svn/Bundles/trunk/Review/Bundles/ActionScript%203.tmbundle/
    



After this has completed, you may as well do one more.


    
    
    svn co http://macromates.com/svn/Bundles/trunk/Review/Bundles/Flex.tmbundle/
    




  * One last Terminal line. This will convert your Terminal into a trace output window for testing SWF files. You'll need to do this every time you start Terminal up.

    
    
    tail -f ~/Library/Preferences/Macromedia/Flash\ Player/Logs/flashlog.txt
    




  * OK. Go to your Desktop and double click the bundle files that have just been created.


  * Now from within TextMate, go to File / New From Template / ActionScript 3 / Project. (Fn-Cmd-F2 gives you keyboard access to the menu bar, if you fancy)


  * Specify a location for your new SWF project folder, and fill out the main class namespace (defaults to org.domain.AS3Project)


  * Cmd-Shift-B to initiate a build, hit '5' to select MXMLC. You'll see the build's success or failure in the subsequent pop-up.

	
  * Cmd-W to close the pop-up. Cmd-R to open the SWF in its HTML container in your default browser. Note that the default project structure doesn't do anything visually, but it does include a trace. So something along the lines of 'AS3ProjectTest::initialize()' should be output to your Terminal at this point.





And you're good to go! You'll notice right away that TextMate is a very bare-bones editor. But its simplicity obscures its complexity. Snippets and completion are two of its many benefits. You can familiarize yourself at the [online manual](http://manual.macromates.com/en/).



As an added bonus, check out the free utility [Visor](http://blacktree.com/) (from the creator of Quicksilver). It's a little odd to set up, but once it's there, while focused on any other application you can press any specified keyboard shortcut to slide out a terminal window from the top of the screen, giving you an easy glance at what your SWF may be tracing.



Enjoy! Feel free to add your own tips below.
