package {
	import flash.display.Sprite;
    import mx.controls.Alert;
	import flash.events.*;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	import mx.core.UIComponent;
			
	public class Station extends UIComponent {
		public var state:String;
		public var feed:String;
		public var stationId:String;
		public var stationName:String;
		public var latitude:Number;
		public var longitude:Number;
		public var conditions:String;
	
		private var loader:URLLoader;
		public var temperature:Number;
		private var color:Number;
		private var temperatureGradients:Array = [ 	0xCC0000,
													0xCC1100,
													0xCC2200,
													0xCC3300,
													0xCC4400,
													0xCC5500,
													0xCC6600,
													0xCC7700,
													0xCC8800,
													0xCC9900,
													0xCCAA00,
													0xCCBB00,
													0xCCCC00,
													0xBBCC00,
													0xAACC00,
													0x99CC00,
													0x88CC00,
													0x77CC00,
													0x66CC00,
													0x55CC00,
													0x44CC00,
													0x33CC00,
													0x22CC00,
													0x11CC00,
													0x00CC00,
													0x00CC11,
													0x00CC22,
													0x00CC33,
													0x00CC44,
													0x00CC55,
													0x00CC66,
													0x00CC77,
													0x00CC88,
													0x00CC99,
													0x00CCAA,
													0x00CCBB,
													0x00CCCC,
													0x00BBCC,
													0x00AACC,
													0x0099CC,
													0x0088CC,
													0x0077CC,
													0x0066CC,
													0x0055CC,
													0x0044CC,
													0x0033CC,
													0x0022CC,
													0x0011CC,
													0x0000CC ]
													
										
	
		public function Station(stationData:XML) {
			this.stationId = stationData.id;
			this.stationName = stationData.station_name;
			this.state = stationData.state;
			
			var xmlLatitude:String = stationData.latitude;
			if(xmlLatitude) {
				var lat:Number	= Number(xmlLatitude);
//				if(isNaN(lat)) lat 	= Number(xmlLatitude.substr(0,xmlLatitude.lastIndexOf(".")-1)); 
				this.latitude = lat;
			}
			var xmlLongitude:String = stationData.longitude;
			if(xmlLongitude) {
				var lon:Number = Number(xmlLongitude);
//				if(isNaN(lon)) lon 	= Number(xmlLongitude.substr(0,xmlLongitude.lastIndexOf(".")-1)); 
				this.longitude = lon;
			}
			this.feed = stationData.xml_url;

			if(stationData.weather) this.conditions = stationData.weather;			
			if(stationData.temperature) {
				this.temperature = stationData.temperature;
				draw();
			}
		}
		
		public function getConditions():void {
			var request:URLRequest = new URLRequest(this.feed);
			loader = new URLLoader();
			configureListeners(loader);
			loader.load(request);
		}
	    private function configureListeners(dispatcher:IEventDispatcher):void {
	        dispatcher.addEventListener(Event.COMPLETE, conditionsLoaded);
	        dispatcher.addEventListener(SecurityErrorEvent.SECURITY_ERROR, securityErrorHandler);
	        dispatcher.addEventListener(IOErrorEvent.IO_ERROR, ioErrorHandler); 
	    }		
		private function serializeData(event:Event):void {
			var result:XML;
			try {
				result = XML(loader.data); // this seems to intermittently throw exceptions demanding the data be well formed... how do i catch this?
			} 
			catch (e:Error) {
				trace(e.toString());
			}

			if(result) {
				this.temperature = Number(result.temp_f);
				this.latitude = Number(result.latitude);
				this.longitude = Number(result.longitude);
				this.conditions = String(result.weather);			
				draw();
			}
		}
		private function generateColor():Number {
			var color:Number;
			var t:Number = this.temperature;
/*
			if		(t < 10) color = 0x0000FF;
			else if	(t < 20) color = 0x2222FF;
			else if	(t < 35) color = 0x4444CC;
			else if	(t < 45) color = 0x999999;
			else if	(t < 55) color = 0xCCCC55;
			else if	(t < 70) color = 0xDDDD33;
			else if	(t < 85) color = 0xDD8800;
			else if	(t < 90) color = 0xEE2200;
			else color = 0xFF0000;
			this.color = color;
			*/
			var highTemp:Number = 80;
			var lowTemp:Number = 0;
			var gradientIndex:Number = temperatureGradients.length - Math.round((t / highTemp)*temperatureGradients.length);
			if(gradientIndex > temperatureGradients.length - 1) gradientIndex = temperatureGradients.length - 1;
			if(gradientIndex < 0) gradientIndex = 0;
			this.color = temperatureGradients[gradientIndex];
			return color			
		}
		public function getColor():Number {
			return color;
		}
		private function draw():void {
			generateColor();
			super.graphics.lineStyle(1,0xFFFFFF,0.5);
			super.graphics.beginFill(getColor());
			super.graphics.drawCircle(0, 0, 4);
			super.graphics.endFill();
			this.toolTip = stationName + "\n" + temperature + " degrees";
			if(this.conditions) this.toolTip += "\n" + conditions;
		}
	    private function conditionsLoaded(event:Event):void {
	        serializeData(event);
	        dispatchEvent(event);        	
	    }
	    private function securityErrorHandler(event:SecurityErrorEvent):void {
	        trace("securityErrorHandler: " + event);
	    }
	    private function ioErrorHandler(event:IOErrorEvent):void {
	        trace("ioErrorHandler: " + event);
	    }		
	}
}