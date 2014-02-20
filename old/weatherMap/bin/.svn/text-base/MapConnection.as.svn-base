package
{
	import flash.net.LocalConnection;

	public class MapConnection extends LocalConnection
	{
		private var isInitialized:Boolean;
		private var index:uint;
		
		public function MapConnection(server:String = "_flexClient")
		{
			try {
                connect(server);
            } 
            catch (error:ArgumentError) 
            {
                // server already created/connected
            }
            super();
		}
		public function mapInitialized():void
		{
			isInitialized = true;
			trace("SearchConnection init")
		}
		public function addMarker(address:String,mapType:String):void
		{
			trace(mapType);
			var marker:Object = {index:++index,  title:"title", description:"description", markerColor:0x990099, strokeColor:0xFFFF00 };
			if(isInitialized) send("_flashClient","addMarker",  {address:address, marker:marker, mapType:mapType});
		}
	}
}