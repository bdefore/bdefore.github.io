package {
	import flash.display.Sprite;
    import mx.controls.Alert;
    import com.hexagonstar.util.debug.Debug;
	import flash.events.*;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
			
	public class WeatherData extends Sprite {
//  	private var RSS_STUB:String = "http://xml.weather.yahoo.com/forecastrss";
	  	public var RSS_STUB:String = "weatherfeeds.xml";
		private var zipCode:String = "94061";
		private var mapType:String = "HYBRID";
		private var loader:URLLoader;
		public var stations:XMLList;
	
		public function WeatherData() {
			super();
		}
		
		public static function init():WeatherData {
			return new WeatherData();
		}
		
		public function go(event:Event):void {
			var request:URLRequest = new URLRequest(RSS_STUB);
	//		var vars:URLVariables = new URLVariables();
	//		vars.p = zipCode;
	//		request.data = vars;
			loader = new URLLoader(request);
			configureListeners(loader);
			loader.load(request);
			
	//		mapConnection.addMarker(zipCode,mapType);
	    }
	
	    private function configureListeners(dispatcher:IEventDispatcher):void {
	        dispatcher.addEventListener(Event.COMPLETE, completeHandler);
	        dispatcher.addEventListener(Event.OPEN, openHandler);
	        dispatcher.addEventListener(ProgressEvent.PROGRESS, progressHandler);
	        dispatcher.addEventListener(SecurityErrorEvent.SECURITY_ERROR, securityErrorHandler);
	        dispatcher.addEventListener(HTTPStatusEvent.HTTP_STATUS, httpStatusHandler);
	        dispatcher.addEventListener(IOErrorEvent.IO_ERROR, ioErrorHandler);
	    }
	
		private function siftFeeds(event:Event):void {
	        var loader:URLLoader = URLLoader(event.target);
	        
	        var result:XML = XML(loader.data);
	        if(result)	{
				stations = result.station.(	latitude != "NA" &&
											state != "AK" &&	// restrict to continental 
											state != "HI" &&
											state != "PR" &&
											state != "GU" &&
											state != "AS" &&
											state != "VI");
				
				trace("stations: " + stations.length());
				
				var event:Event = new Event("stations");
				dispatchEvent(event);
			}
		}
	
	    private function completeHandler(event:Event):void {
			siftFeeds(event);
	/*
	        var loader:URLLoader = URLLoader(event.target);
	        trace("completeHandler: " + loader.data);
	        
	        var result:XML = XML(loader.data);
			var yNS:Namespace = result.namespace("yweather");
	
			var city:String = result.channel.yNS::location.@city;
			var time:String = result.channel.item.yNS::condition.@date;
			if(city && time)
			{
				var state:String = result.channel.yNS::location.@region;
				var location:String = city + ", " + state;
				var condition:String = result.channel.item.yNS::condition.@text + ", " + result.channel.item.yNS::condition.@temp + " degrees";
		
				infoField.text = location + "\n" + time + "\n" + condition;
			}
	*/
	    }
	
	    private function openHandler(event:Event):void {
	        trace("openHandler: " + event);
	    }
	
	    private function progressHandler(event:ProgressEvent):void {
	        trace("progressHandler loaded:" + event.bytesLoaded + " total: " + event.bytesTotal);
	    }
	
	    private function securityErrorHandler(event:SecurityErrorEvent):void {
//	        infoField.text = "securityErrorHandler: " + event;
	        trace("securityErrorHandler: " + event);
	    }
	
	    private function httpStatusHandler(event:HTTPStatusEvent):void {
	        trace("httpStatusHandler: " + event);
	    }
	
	    private function ioErrorHandler(event:IOErrorEvent):void {
	        trace("ioErrorHandler: " + event);
	    }
 	}
}