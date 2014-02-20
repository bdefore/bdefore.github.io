package com.blitzagency.xray.ui
{
	import flash.utils.*;
	
	import mx.core.Application;
		
	public class XrayTrace
	{
		public static var app:Object = mx.core.Application.application;
		
		private var recursionCheck:Number;
		/**
		 * @summary An event that is triggered when trace information is complete and sent back.
		 *
		 * @return Object with one property: sInfo
		 */
		//[Event("onTrace")]
		/**
	     * @summary sendSI is the interval id for sending data back to the interface
		 */
		private var sendSI:Number;
		/**
	     * @summary array of broken up trace output to send back to the interface via localConnection
		 */
		private var sendAry:Array;
		/**
	     * @summary when recursing objects, I indent the nested objects/properties.  This is the counter for each time
		 * an object is recursed
		 */
		private var iViewColCount:Number;
		/**
	     * @summary recursion controller number to keep it from going into a 256 loops error
		 */
		private var _recursionCount:Number;
		/**
	     * @summary the full output that is broken up and sent back to the interface
		 */
		private var sViewInfo:String;
		/**
	     * @summary clean formatted for Flash IDE/non HTML textboxes || this is actually not being used.
		 * currently, all output is passed back without formatting.  I'm keeping it here for future use incase
		 * there's a breakthrough with displaying large amounts of trace output.
		 */
		private var sViewInfoClean:String;
		// @traceInfo:  running archive
		//public var _traceInfo:String;
		// @separator
		/**
	     * @summary the separator to be used in the trace output.  Currently it's "::".  IE: (3486) my vars: john :: sally :: joe
		 */
		public var separator:String;
		/**
	     * @summary interval speed to send output back to localConnection
		 */
		public var _queInterval:Number;
	
		//=========================/[ GETTERS SETTERS ]\======================>
		/*
		public function get sViewInfo():String
		{
			return _sViewInfo;
		}
	
		public function set sViewInfo(newValue:String)void
		{
			_sViewInfo = newValue;
		}
		*/
		/*
		public function get sViewInfoClean():String
		{
			return _sViewInfoClean;
		}
	
		public function set sViewInfoClean(newValue:String)void
		{
			_sViewInfoClean = newValue;
		}
		*/
		
		public function XrayTrace()
		{
			// initialize event dispatcher
			//GDispatcher.initialize(this);
	
			separator = " :: ";
	
			_recursionCount = (.003);
			//this.recursionCheck = RecursionCheck.getInstance();
		}
		
		private function examineObj(obj:Object):Boolean
		{
			var vChr:String = "";
			var vChrClean:String = "";
	
			for(var x:Number=0;x<iViewColCount;x++)
			{
				vChr += "&nbsp;";
				vChrClean += " ";
			}
	
			//=========================/[  ]\======================>
			//if(!this.recursionCheck.isMember(obj).exists) this.recursionCheck.addMember(obj);
	
			for(var items:String in obj)
			{
				var bReturn:Boolean = false;
	
				if(items != "getRecursionChecked" && items != "__recursionCheck")
				{
					sViewInfo += vChr + "<font size=\"12\" color=\"#0000FF\">" + items + "</font>" + " = " + obj[items] + "\n";
					sViewInfoClean += vChrClean + items + " = " + obj[items] + "\n";
				}
	
				if(typeof obj[items] == "object" || obj[items] is Array)
				{
					if(Math.floor(obj[items].__recursionCheck) != Math.floor(app.recursionControl) || obj[items].__recursionCheck < app.recursionControl+_recursionCount)
					{
						// increment by .1
						if(Math.floor(obj[items].__recursionCheck) == Math.floor(app.recursionControl))
						{
							obj[items].__recursionCheck += (.001);
						}
						if(obj[items].__recursionCheck < app.recursionControl+_recursionCount || obj[items].__recursionCheck == undefined)
						{
							//bReturn = parseObjTree(obj[items], items, sPath, recursiveSearch, showHidden, obj, parent);
							iViewColCount += 4;
							if(typeof obj[items] == "object") bReturn = examineObj(obj[items]);
							if(obj[items] is Array) bReturn = examineAry(obj[items]);
							if(!bReturn) return true;
						}
					}
					//=========================\[  ]/======================>
				}
			}
	
			iViewColCount -= 4;
			sViewInfo += "\n";
			sViewInfoClean += "\n";
			return true;
		}
		
		private function examineAry(obj:Object):Boolean
		{
			var vChr:String = "";
			var vChrClean:String = "";
	
			for(var x:Number=0;x<iViewColCount;x++)
			{
				vChr += "&nbsp;";
				vChrClean += " ";
			}
	
			//=========================/[  ]\======================>
			//if(!this.recursionCheck.isMember(obj).exists) this.recursionCheck.addMember(obj);
	
			for(var i:Number=0;i<obj.length;i++)
			{
				var bReturn:Boolean = false;
				var items:Object = obj[i];
				if(items != "getRecursionChecked" && items != "__recursionCheck")
				{
					sViewInfo += vChr + "<font size=\"12\" color=\"#0000FF\">" + items + "</font>" + " = " + obj[i] + "\n";
					sViewInfoClean += vChrClean + items + " = " + obj[i] + "\n";
				}
	
				if(typeof obj[i] == "object" || obj[i] is Array)
				{
					if(Math.floor(obj[i].__recursionCheck) != Math.floor(app.recursionControl) || obj[i].__recursionCheck < app.recursionControl+_recursionCount)
					{
						// increment by .1
						if(Math.floor(obj[i].__recursionCheck) == Math.floor(app.recursionControl))
						{
							obj[i].__recursionCheck += (.001);
						}
						if(obj[i].__recursionCheck < app.recursionControl+_recursionCount || obj[i].__recursionCheck == undefined)
						{
							//bReturn = parseObjTree(obj[items], items, sPath, recursiveSearch, showHidden, obj, parent);
							iViewColCount += 4;
							if(typeof obj[i] == "object") bReturn = examineObj(obj[i]);
							if(obj[i] is Array) bReturn = examineAry(obj[i]);
							if(!bReturn) return true;
						}
					}
					//=========================\[  ]/======================>
				}
			}
	
			iViewColCount -= 4;
			sViewInfo += "\n";
			sViewInfoClean += "\n";
			return true;
		}
		/**
	     * @summary loops the arguments sent in. If it finds an object, it sends it off to be examined. If not,
		 * it adds it to the string to be returned
		 *
		 * @param mulitple arguments can be passed
		 *
		 * @return String
		 */
		public function trace(obj:Object):String
		{
			sViewInfo = "";
			sViewInfoClean = "";
			//this.recursionCheck.clear();
	
			for(var x:Number=0;x<arguments.length;x++)
			{
				// OBJECT EXAMINE
				if(typeof arguments[x] == "object")
				{
					sViewInfo += "\n";
					sViewInfoClean += "\n";
	
					// reset tab count
					iViewColCount = 2;
	
					app.recursionControl += 1;
					examineObj(arguments[x]);
				}else if(arguments[x] is Array)
				{
					sViewInfo += "\n";
					sViewInfoClean += "\n";
	
					// reset tab count
					iViewColCount = 2;
	
					app.recursionControl += 1;
					examineAry(arguments[x]);
				}else
				{
					if(x > 0)
					{
						sViewInfo += arguments[x] + " :: ";
						sViewInfoClean += arguments[x] + " :: ";
					}
				}
			}
	
			if(sViewInfo.substring(sViewInfo.length-4,sViewInfo.length) == " :: ")
			{
				sViewInfo = sViewInfo.substring(0,sViewInfo.length-4);
			}
	
			if(sViewInfoClean.substring(sViewInfoClean.length-4,sViewInfoClean.length) == " :: ")
			{
				sViewInfoClean = sViewInfoClean.substring(0,sViewInfoClean.length-4);
			}
	
			//HTML version
			//var sInfo = "(" + getTimer() + ") " + "<font size=\"12\" color=\"#0000ff\">" + arguments[0] + "</font>" + ": \n" + sViewInfo + " \n";
			//var sInfo:String = "<font size=\"12\" color=\"#0000ff\">" + arguments[0] + "</font>" + ": \n" + sViewInfo + " \n";
	
			//plain text version
			//var sInfo:String = "(" + getTimer() + ") " + arguments[0] + ": " + sViewInfoClean
			var sInfo:String = arguments[0] ;
			if(sViewInfoClean.length > 0) sInfo + ": " + sViewInfoClean;
	
			// output to the Flash IDE
			//OutputTools.tt(["(" + getTimer() + ") " + arguments[0] + ": " + sViewInfoClean], true);
	
			// dispatch event
			//dispatchEvent({type:"onTrace", sInfo:sInfo});
	
			/*
			if(Xray.lc_info && !this.queInterval && !_global.isLivePreview)
			{
				this.queInterval = 50; // the setter will kick off the interval call
				this.sendAry = new Array();
			}
			*/
	
			// return output
			return sInfo;
		}
	}
}