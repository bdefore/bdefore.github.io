package 
{
	import flash.display.Sprite;
	import flash.net.URLVariables;
	import flash.net.URLRequest;
	import flash.net.URLLoader;
	import flash.events.Event;
	import mx.controls.Alert;
	
	public class DataImporter extends Sprite
	{
		private var UPLOAD_SCRIPT_PATH:String = "http://bcdef.org/weatherMap/writeWeatherFeed.php";
		private var STATION_LIST_PATH:String = "http://bcdef.org/weatherMap/cachedWeatherFeed.xml";
		private var STALE_THRESHOLD:Number = 30; // minutes feed can be stale for before reloading all weather feeds.
		
		public var stations:Array;
		private var stationsList:XMLList;
		private var stationsTotal:uint = 0;
		private var stationsLoaded:uint = 0;
		private var isDataStale:Boolean = true;
		
		public var lastMessage:String;
		
		public function DataImporter() {
			stations = new Array();
		}
		public function go(e:Event):void {
			getStationList(STATION_LIST_PATH);
		}
		private function t(str:String):void {
			trace(str);
			var e:Event = new Event("trace");
			lastMessage = str;
			dispatchEvent(e);
		}			
		private function getStationList(feedPath:String):void {
			var request:URLRequest = new URLRequest(feedPath);
			var loader:URLLoader = new URLLoader();
			loader.addEventListener(Event.COMPLETE,siftFeeds);
			loader.load(request);
		}
		private function siftFeeds(event:Event):void {
	        var loader:URLLoader = URLLoader(event.target);    
	        var result:XML = XML(loader.data);
	        if(result)	{
				checkDatestamp(new Date(String(result.datestamp)));
				
				stationsList = result.station.(	latitude != "NA" &&
											state != "AK" &&	// restrict to contiguous USA 
											state != "HI" &&
											state != "PR" &&
											state != "GU" &&
											state != "AS" &&
											state != "VI");
			}
			getConditions();
		}
		private function checkDatestamp(datestamp:Date):void {
			var currentDate:Date = new Date();
			
			var msDifference:Number = currentDate.getTime() - datestamp.getTime();
			var reloadThreshold:Number = 1000 * 60 * STALE_THRESHOLD; // 1000ms * 60s * mins threshold
			
			isDataStale = msDifference > reloadThreshold;
			t(datestamp.toString() + " : " + new Date().toString());
			t(isDataStale + " : " + datestamp.getTime() + " : " + msDifference + " : " + reloadThreshold);			
		}
		private function getConditions():void {
			for each (var stationData:XML in stationsList) { 
			    var station:Station = new Station(stationData);
				// should move this logic to weatherdata class
				if(!isNaN(station.longitude) && !isNaN(station.latitude))
				{
					station.addEventListener(Event.COMPLETE,stationDataLoaded);
					stationsTotal++;
					stations.push(station);
					if(isDataStale) station.getConditions();
				}
			}
			//stationsTotal = 5; // for testing: limit to 5;

			// remove xml from data
			stationsList = null;

			if(!isDataStale) stationDataComplete(); // if temp's already there, write immediately after loop, else uncomment out getconditions above	
		}
		private function stationDataLoaded(event:Event):void {
			stationsLoaded++
//			Alert.show("Station " + stationsLoaded + " of " + stationsTotal + " loaded.","Loading Weather Data");
			t("stationDataLoaded(): " + stationsLoaded + " : " + stationsTotal);
			if(stationsLoaded == stationsTotal)	{
				stationDataComplete();
			}
		}
		private function stationDataComplete():void {
			if(isDataStale) write();
			
			var e:Event = new Event("stations");
			dispatchEvent(e);			
		}
		
		private function write():void {
			t("Sending to server");
			var resultXML:XML = new XML(<weatherFeed></weatherFeed>);
			resultXML.appendChild(new XML(new String("<datestamp>" + new Date().toString() + "</datestamp>")));
			
			var i:uint = 0;
			for(i=0; i<stationsTotal; i++) {
				var station:Station = stations[i];
				var xmlString:String = '<station></station>'
				var stationXML:XML = 	new XML(xmlString);
				if(station.stationId)	stationXML.appendChild(new XML(new String("<id>" + station.stationId + "</id>")));
				if(station.state) 		stationXML.appendChild(new XML(new String("<state>" + station.state + "</state>")));
				if(station.stationName) stationXML.appendChild(new XML(new String("<station_name>" + station.stationName + "</station_name>")));
				if(station.latitude) 	stationXML.appendChild(new XML(new String("<latitude>" + station.latitude + "</latitude>")));
				if(station.longitude) 	stationXML.appendChild(new XML(new String("<longitude>" + station.longitude + "</longitude>")));
				if(station.temperature) stationXML.appendChild(new XML(new String("<temperature>" + station.temperature + "</temperature>")));
				if(station.feed)		stationXML.appendChild(new XML(new String("<xml_url>" + station.feed + "</xml_url>")));
				if(station.conditions)	stationXML.appendChild(new XML(new String("<weather>" + station.conditions + "</weather>")));
				resultXML.appendChild(stationXML);
			}

			var vars:URLVariables = new URLVariables();
			vars.totalEntries = 1;
			vars.entry0 = resultXML.toString();

			var cacheBuster:String = "?cacheBuster=" + Math.random();
			var request:URLRequest = new URLRequest(UPLOAD_SCRIPT_PATH + cacheBuster);
			request.data = vars;
			request.method = "POST";
						
			var loader:URLLoader = new URLLoader();
			loader.addEventListener(Event.COMPLETE,loadSuccessful);
			loader.load(request);			
		}
		
		private function loadSuccessful(e:Event):void {
			t("Load successful");			
		}
	}
}