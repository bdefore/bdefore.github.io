// ActionScript file
import com.yahoo.maps.markers.CustomPOIMarker;
import com.yahoo.maps.tools.PanTool; 
import com.yahoo.maps.widgets.NavigatorWidget;

// register as a listener to get notified when the map finishes the initialization state
myMap.addEventListener(com.yahoo.maps.api.flash.YahooMap.EVENT_INITIALIZE, onInitMap);

function onInitMap(eventData)
{
	// create controls to controling the map
	myMap.addTool(new PanTool(), true);
 	myMap.addWidget(new NavigatorWidget("closed"));
 	// notify Flex that the map finished the initialization state
	connectedMap.send("_flexClient","mapInitialized");
}

var connectedMap:LocalConnection = new LocalConnection();
connectedMap.addMarker = function (connectionData:Object):Void
{
	//Add the marker to the map
	myMap.addMarkerByAddress(CustomPOIMarker,connectionData.address, connectionData.marker);
	myMap.setCenterByAddress(connectionData.address, 2500);
}
connectedMap.connect("_flashClient");