---
author: bcdef
comments: true
date: 2009-04-21 23:15:36+00:00
layout: post
slug: euro2008com-python-image-scraper
title: Euro2008.com Python Image Scraper
wordpress_id: 49
categories:
- bcdef
- Data Mining
- Python
---

Mind the cobwebs. So while cleaning house to better organize my code library today, I stumbled across a project I'd taken on during a day between jobs last year. I think I had some grand idea of writing up something more elaborate on it, but that never happened. Regardless, it might be of use to someone so I thought I'd post here.

The basic tenet was that the euro2008 website carried some engaging caricatures of the major players for the football tournament, and I had the urge to download them all.

[![](/blog/wp-content/ned_johnheitinga.jpg)](/blog/wp-content/ned_johnheitinga.jpg)[![](/blog/wp-content/fra_claudemakelele.jpg)](/blog/wp-content/fra_claudemakelele.jpg)

Rather than try to save them all manually, I figured it'd be instructive to learn how to build a Python script to do it for me. In short time I found a few libraries that eased the process.Â The code ended up being nice and tight. There might be some dependencies - I think you'll need [Beautiful Soup](http://www.crummy.com/software/BeautifulSoup/) at the least - but I just checked it again and it still works here on Python 2.5.1.

You could tweak it toward any number of ends, not just saving images off the web but for also data mining or other purposes.Â Or you could just run it locally and get lots of swank portraits.

As you will, then:  [retrievecaricatures.py](/downloads/retrievecaricatures.py)
