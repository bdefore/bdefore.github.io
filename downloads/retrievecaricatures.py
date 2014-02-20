#!/usr/bin/env python
# encoding: utf-8

import urllib2
import codecs
import BeautifulSoup
import re

teamPage = 		"http://en.euro2008.uefa.com/tournament/teams/index.html"
teamPageStub = 	"http://en.euro2008.uefa.com/tournament/teams/team="
caricatureStub ="http://en.euro2008.uefa.com/imgml/players/caricature/"

print "Getting team list..."
sock = urllib2.urlopen(teamPage)
htmlSource = sock.read()
sock.close()

p = re.compile('team=([0-9]+)/index.html" class="footer_link">([A-Z]+)')
teams = p.findall(htmlSource)

players = list()

for teamInfo in teams:
	teamInfo = list(teamInfo)
	teamId = teamInfo[0]
	teamAbbrev = teamInfo[1]
	print "Searching for players on teamInfo: " + teamId + "..." 
	constructedUrl = teamPageStub + teamId + "/index.html"

	sock = urllib2.urlopen(constructedUrl)
	htmlSource = sock.read()
	sock.close()

	p = re.compile('player=([0-9]+)/index.html">([\w\s]+)', re.UNICODE)
	teamPlayers = p.findall(htmlSource)
	for playerInfo in teamPlayers:
		playerInfo = list(playerInfo)
		playerInfo.append(teamAbbrev)
		print playerInfo
		name = playerInfo[1].replace(" ","")
#		iso8859string.encode(’iso-8859-1′)
#		print name.encode('ascii', 'replace')
#		print name.decode('utf-8')#unicode(name.decode('iso-8859-1'),'utf-8')
		players.append(playerInfo);

print "Getting caricatures..."
for playerInfo in players:
	playerId = playerInfo[0]
	playerName = playerInfo[1].replace(" ","")
	playerTeam = playerInfo[2]
	
	constructedUrl = caricatureStub + playerId + ".jpg"
	print "Downloading caricature from: " + constructedUrl + "..."
	sock = urllib2.urlopen(constructedUrl)
	imageSource = sock.read()
	sock.close()

	if imageSource.find("#") != -1: # a hack to make sure the image isn't empty
		print playerTeam + "_" + playerName
		cf = open(playerTeam + "_" + playerName + '.jpg', 'wb')
		cf.write(imageSource);
		cf.close()