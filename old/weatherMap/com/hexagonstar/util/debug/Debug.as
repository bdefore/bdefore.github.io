package com.hexagonstar.util.debug
{
	import flash.net.LocalConnection;
	import flash.events.StatusEvent;
	
	/**
	 * Alcon Debug class (AS3.0 version)
	 * Sends trace actions to the Alcon output panel through a local connection.
	 * @version 1.0.7 (09.07.2006)
	 * @author Sascha Balkau <sascha@hiddenresource.corewatch.net>
	 */
	public class Debug
	{
		// Determines if data blocks > 40Kb are split into chunks:
		private static var sp:Boolean = true;
		// Default depth of recursion for object tracing:
		private static var rec:int = 4;
		
		// The sending local connection object:
		private static var dlc:LocalConnection;
		// Determines if a connection is already established:
		private static var con:Boolean = false;
		// Filter level. By default filter none (0):
		private static var fl:int = 0;
		// The chunk size used for data splitting (def. 40600, leaves some additional headroom):
		private static var cs:int = 40600;
		
		
		/**
		 * Internal Constructor
		 */
		function Debug()
		{
		}
		
		
		/**
		 * The trace method accepts three arguments, the first contains the data which
		 * is going to be traced, the second if of type Boolean is used to indicate
		 * recursive object tracing mode, if of type int desribes the filtering level
		 * for the output.
		 * @param arg0:Object the object to be traced (defaults to undefined)
		 * @param arg1:Boolean true if recursive object tracing, optional (defaults to null)
		 * @param arg2:int filter level, optional, defaults to 0
		 */
		public static function trace(arg0:* = undefined, arg1:* = null, arg2:int = -1):void
		{
			// Only connect if not already connected:
			if (!con)
			{
				dlc = new LocalConnection();
				dlc.addEventListener(StatusEvent.STATUS, onStatus);
				con = true;
			}
			
			var m:* = arg0;
			var o:String = "";
			var s:String = "";
			var t:Boolean = false;
			var l:int = 1;
			
			// Check if more than one argument was given:
			if (typeof(arg1) == "boolean") t = arg1;
			else if (typeof(arg1) == "number") l = arg1;
			if (arg2 > -1) l = arg2;
			
			// Extract signal tag if any is given:
			if (typeof(m) == "string")
			{
				if (m.substring(0, 2) == "[%")
				{
					if (m.substring(5, 7) == "%]")
					{
						s = m.substr(0, 7);
						m = m.substr(7, m.length);
						if (m == "") l = 5;
					}
				}
			}
			
			// Only show messages equal or higher than current filter level:
			if (l >= fl && l < 7)
			{
				// Check if recursive object tracing:
				if (t) o += traceObj(m);
				else o += String(m);
				
				// Check if data stream size is too large for LocalConnection:
				if (sp && o.length > cs)
				{
					splitDt(o);
				}
				else
				{
					// Send output, signal tag, mtasc string and level to Alcon console:
					dlc.send("_alcon_lc", "onMessage", o, s, "", l);
				}
			}
		}
		
		
		/**
		 * Prepares an object for recursive tracing.
		 * @param obj the traced object.
		 * @return A string that contains the object structure.
		 */
		private static function traceObj(obj:*):String
		{
			// Set the max. recursive depth:
			var rcdInit:int = rec;
			// If object is a movieclip, get the size of it:
			var otp:String = typeof(obj);
			var obt:String = (otp == "movieclip") ? ", " + obj.getBytesTotal().toString() + " bytes" : "";
			// tmp holds the string with the whole object structure:
			var tmp:String = "" + obj.toString() + " (" + otp + obt + "):\n";
			
			// Nested recursive function:
			var prObj:Function = function(o:Object, rcd:int, idt:int, br:Boolean):void
			{
				if (br)
				{
					br = false;
					tmp += ">>";
				}
				for (var p:String in o)
				{
					// Preparing indention:
					var tb:String = "";
					for (var i:int = 0; i < idt; i++) tb += "    ";
					tmp += tb + p + ": " + o[p] + "\n";
					if (rcd > 0) prObj(o[p], (rcd - 1), (idt + 1), true);
				}
			};
			prObj(obj, rcdInit, 1, true);
			return tmp;
		}
		
		
		/**
		 * Splits data blocks that are larger than 40Kb into 40Kb chunks.
		 * Note that level 6 is used internally to mark the data as
		 * a chunk for the console. User-given levels are ignored when
		 * data is processed by this method.
		 * @param dta a string which is split into chunks.
		 */
		private static function splitDt(dta:String):void
		{
			var sze:int = cs;
			var c:int = Math.ceil(dta.length / sze);
			var s:int = 0;
			var e:int = sze;
			
			for (var i:int = 0; i < c; i++)
			{
				if (i < c)
				{
					Debug.trace(dta.slice(s, e), false, 6);
					s += sze;
					e += sze;
				}
			}
			Debug.trace("", false, 1);
		}
		
		
		/**
		 * onStatus method
		 */
		private static function onStatus(e:StatusEvent):void
		{
		}
		
		
		/**
		 * Sends a clear buffer signal to the output console.
		 * Level 5 is used internally for signals, so that in any case
		 * no level keywords are placed before the signal string.
		 */
		public static function clear():void
		{
			Debug.trace("[%CLR%]", 5);
		}
		
		
		/**
		 * Sends a delimiter signal to the output console.
		 */
		public static function delimiter():void
		{
			Debug.trace("[%DLT%]", 5);
		}
		
		
		/**
		 * Sends a pause signal to the output console.
		 */
		public static function pause():void
		{
			Debug.trace("[%PSE%]", 5);
		}
		
		
		/**
		 * Toggles data splitting on/off.
		 * @param _sp A boolean if set to true turns on the option to split data
		 * streams larger than 40Kb into 40Kb chunks, if set to false turns it off.
		 */
		public static function splitData(_sp:Boolean):void
		{
			sp = _sp;
		}
		
		
		/**
		 * Sets the logging filter level.
		 * @param _fl a number for the filter level to be set.
		 */
		public static function setFilterLevel(_fl:int = 0):void
		{
			if (_fl >= 0 && _fl < 5) fl = _fl;
		}
		
		
		/**
		 * Returns the logging filter level.
		 * @return the number of the filter level.
		 */
		public static function getFilterLevel():int
		{
			return fl;
		}
		
		
		/**
		 * Sets the recursion depth for recursive object tracing.
		 * @param _rec A number with the depth for object recursive tracing.
		 */
		public static function setRecursionDepth(_rec:int):void
		{
			rec = _rec;
		}
	}
}
