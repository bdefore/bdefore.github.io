/*
	CompressUtil 1.0
    
    Created by Maikel Sibbald
	info@flexcoders.nl
	http://labs.flexcoders.nl
	
	Basesd on an article found at:
	http://www.danielhai.com/blog/?p=23
	
	Free to use.... just give me some credit
*/
package nl.fxc.utils{
	import flash.utils.ByteArray;
	
	import formatter.Base64;
	
	public class CompressUtil{
		
		public static function compress(str:String):String {
			var bytes:ByteArray = new ByteArray();
			bytes.writeUTFBytes(str);
			bytes.compress();
			return Base64.Encode(bytes);
		}
		
		public static function uncompress(str:String):String {
			var decode:ByteArray = Base64.Decode(str);
			decode.uncompress();
			return decode.toString();
		}
	}
}