/*
 *  Project: Countid 1.0
 *  Description: jQuery Plugin to count up and count down numbers
 *  Author: miso25
 *  License: MIT
 */

// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.

;(function ( $, window, document, undefined ) {

	
	window.cancelRequestAnimFrame = ( function() {
			return window.cancelAnimationFrame          ||
				window.webkitCancelRequestAnimationFrame    ||
				window.mozCancelRequestAnimationFrame       ||
				window.oCancelRequestAnimationFrame     ||
				window.msCancelRequestAnimationFrame        ||
				clearTimeout
		} )();

	
	window.requestAnimFrame = (function(){
		return  window.requestAnimationFrame       || 
			window.webkitRequestAnimationFrame || 
				window.mozRequestAnimationFrame    || 
				window.oRequestAnimationFrame      || 
				window.msRequestAnimationFrame     || 
				function(/* function */ callback, /* DOMElement */ element){
					return window.setTimeout(callback, 1000 / 60);
				};
		})();
	
	
    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.

    // window is passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = 'countid',
        defaults = {
            propertyName: "value"
        };

    // The actual plugin constructor
    function countidPlugin( element, options ) {
        //this.element = element;

        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        
		this.elem = element;
		this.$elem = $(element);
		this.$elem_original = this.$elem
		this.options = options;
		
		
		// This next line takes advantage of HTML5 data attributes
		// to support customization of the plugin on a per-element
		// basis. For example,
		// <div class=item' data-plugin-options='{"message":"Goodbye World!"}'></div>
		//this.metadata = this.$elem.data( 'plugin-options' );
		this.metadata = this.$elem.data( );
		
		this._init();
		
    }

	
	//Plugin.prototype = 
	countidPlugin.prototype = 
	{
	
		defaults: { 
			
			start : 0,
			end : 0,
			speed : 10,
			tick : 10,
			
			clock: false,
			switchClock: true,
			dateTime: '%m/%d/%y %h:%i:%s',
			dateTplRemaining: 'Remaining time: %D days and %H:%M:%S ', // '%Y %O %W %D %H %M %S %U',
			dateTplElapsed: 'Elapsed time: %D days and %H:%M:%S ', // '%Y %O %W %D %H %M %S %U',
			
			format: false,
			complete : false
		},
		
		
		lang: {
			textSelectAll: function () { return "Select all"; }
		},
		
		_init: function() {
			// Introduce defaults that can be extended either 
			// globally or using an object literal. 
			this.config = $.extend({}, this.defaults, this.options, 
			this.metadata);
			//alert( JSON.stringify( this.lang.textSearching() ) )


			var self = this
			
			self.id = self._getRandomInt(999,99999)
			
			self.timer = {}
			self.isPaused = false
			self.speed = self.config.speed
			self.tick = self.config.tick
			self.clock = self.config.clock
			//self.currentTime = false
			self.dateTpl = self.config.dateTplRemaining
			//alert(typeof self.config.end)
			
			if( self.config.start === 0 && self.config.end === 0 )
			self.config.end = 1 * self.$elem.text()
			//alert( self.id )
			
			self.request = 0
			self.paused = false
			//var text = 1 * self.$elem.text()
			//alert( typeof self.$elem.waypointa )
			
			
			
			
			//self.steps = 1 * ( Math.abs( self.current - self.end ) /  self.config.tick  )  
			//self.dir = self.current > self.end ? -1 * self.config.tick : 1 * self.config.tick;
			self._setSteps( self.config.start, self.config.end )
			
			if(self.clock)
			{
				// initial date validation
				if(!isNaN(self.config.dateTime))	// date is in seconds
				{
					//var now = 
					self.config.dateTime = new Date(self.config.dateTime * 1000);
					//alert( now )
					//alert(self.config.dateTime)
				}
				self._setClock()
			}
			
			if( typeof self.$elem.waypoint === 'function' )
			self.$elem.waypoint( function(){ self._loop() }, { offset: '100%', triggerOnce: true });
			else
			self._loop()
			
			//self.$elem.appear();
			
			//$(document).on('appear',  self.$elem, function(e, $affected) {
			  //self.$elem.on('appear', function(e, $affected) {
				//$affected.each(function() {
				//	if($(this).hasClass('countid-appeared')) return;
					
				//	self.$elem.addClass('countid-appeared')
					
				//	self._countIt()
					
					
				//})
			//  });
			

			

			//alert(text)
			
		},
		
		
		_setTime: function()
		{
			var self = this
			
			var now = Date.now()
			//var d = new Date("Wed Jun 20 19:20:44 +0000 2012");
			var t = new Date( self.config.dateTime ).getTime();
			//var d1 = new Date("11/28/2015 15:30");
			//var t= d.getTime(); //returns miliseconds
			//var t1= d1.getTime(); //returns miliseconds
			
			var now_floor = 1 * Math.floor( now / 1 ) 
			var end_floor = 1 * ( t / 1 )
			
			
			var start = ( Math.abs( now_floor - end_floor ) )
			var end = 0
			
			//alert(start)
			if( now_floor > end_floor ) 	// now is bigger than input date = counting elapsed time
			{
				end = 9999999999999999
				//alert(start2)
				//alert(end)
				//self.remaining = false
			}
			else	// input date is bigger than now = counting remaining time
			{
				//self.config.start = end - start
				start = end_floor - now_floor + 1000
				//start = 5555
				//end = 0
				//end = 0
			}
			//console.log( now_floor + ' - ' + end_floor );
			//
			

			self.step = Math.floor( start / 1000 ) + 1
			//var x = 1 * ( Math.abs( self.current - start ) /  self.tick  )
			
			//console.log( start + ' - ' + end + ' / '+ self.step);
			
			return [ start, end ]
		},
		
		
		_setClock: function()
		{
			
			var self = this
			//var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
			//var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
			// clock
			var maskTime={
			'Y' : 60*60*24*365*1000,
			'O' : 60*60*24*30.4368*1000,
			'W' : 60*60*24*7*1000,
			'D' : 60*60*24*1000,
			'H' : 60*60*1000,
			'M' : 60*1000,
			'S' : 1000,
			'U' : 1
			}
			
			
			
			
			//alert(self.config.dateTime)
			// date validation
			
			//var now = 1 * self.config.dateTime;
			
			var t = new Date( self.config.dateTime ).getTime();
			
			
			if( isNaN(t) )
			{	
				
				var currentTime = new Date()
				// returns the month (from 0 to 11)
				var month = currentTime.getMonth() + 1
				// returns the day of the month (from 1 to 31)
				var day = currentTime.getDate()
				// returns the year (four digits)
				var year = currentTime.getFullYear()
				// write output MM/dd/yyyy
				//alert(month + "/" + day + "/" + year)
				var hours = currentTime.getHours()
				var minutes = currentTime.getMinutes()
				var seconds = currentTime.getSeconds()
				//alert(seconds)
				//var nd = Date.now('Y')
				//var dt =  self.config.dateTime
				//var is_date = dt.search("/") !== -1 ? true : false
				//var is_time = dt.search(":") !== -1 ? true : false
				
				//Y-m-d H:i:s
				self.config.dateTime = self.config.dateTime.replace('%m', month)
				self.config.dateTime = self.config.dateTime.replace('%d', day)
				self.config.dateTime = self.config.dateTime.replace('%y', year)
				self.config.dateTime = self.config.dateTime.replace('%h', hours)
				self.config.dateTime = self.config.dateTime.replace('%i', minutes)
				self.config.dateTime = self.config.dateTime.replace('%s', seconds)
				//if(is_date)
				//self.config.dateTime = dt +'/'+ year
				//else if(is_time)
				//self.config.dateTime = month + "/" + day + "/" + year + ' ' + dt
				//else
				//self.config.dateTime = '1/1/1970'
				
				var t2 = new Date( self.config.dateTime ).getTime();
				if( isNaN(t2) )
				self.config.dateTime = '1/1/1970'
				//alert( self.config.dateTime )
			}
			//self.config.dateTime = '11/30/2015 11:09'
			//alert(start + " = " + end)
			//alert( t )
			//alert(self.config.dateTime )
			if( !self.config.dateTime )
			{
			//self.currentTime = true
			//self.config.dateTime = Date.now()
			
			}
			
			//alert( self.config.dateTime )
			
			self.speed = 1000 
			self.tick = 1 

			var st = self._setTime()
			
			self._setSteps( st[0], st[1] )

			if( st[0] < st[1] )
			self.dateTpl = self.config.dateTplElapsed
			//self.dateTpl = self.config.dateTplAlt
			
			var tpl = self.dateTpl
			
			var tplYears = tpl.search("%Y") !== -1 ? true : false
			//var tplYearsMand = tpl.search("%y") !== -1 ? true : false
			var tplMonths = tpl.search("%O") !== -1 ? true : false
			var tplWeeks = tpl.search("%W") !== -1 ? true : false
			var tplDays = tpl.search("%D") !== -1 ? true : false
			var tplHours = tpl.search("%H") !== -1 ? true : false
			var tplMinutes = tpl.search("%M") !== -1 ? true : false
			var tplSeconds = tpl.search("%S") !== -1 ? true : false
			var tplMseconds = tpl.search("%U") !== -1 ? true : false
			//alert(tplDays)
			//alert(self.end)
			//var start_clock = '11/27/15' 
			//var end_clock = '11/28/15' 
			//alert(tpl)
			self.secLeft = false;
			//self.thisTpl = tpl;
			
			function zeroPad(num, places) {
			  var zero = places - num.toString().length + 1;
			  return Array(+(zero > 0 && zero)).join("0") + num;
			}
			var maskTime={
			'Y' : 60*60*24*365*1000,
			'O' : 60*60*24*30.4368*1000,
			'W' : 60*60*24*7*1000,
			'D' : 60*60*24*1000,
			'H' : 60*60*1000,
			'M' : 60*1000,
			'S' : 1000,
			'U' : 1
			}
			var maskPad={
			'Y' : 0,
			'O' : 0,
			'W' : 0,
			'D' : 0,
			'H' : 2,
			'M' : 2,
			'S' : 2,
			'U' : 3
			}
			function setT( m, thisTpl )
			{
				var ptime = Math.floor( self.secLeft / ( maskTime[ m ] ))
				var stime = maskPad[ m ] > 0 ? zeroPad( ptime, maskPad[ m ] ) : ptime
				//self.secLeft = ptime
				self.thisTpl = self.thisTpl.replace( "%" + m, stime )
				self.secLeft = self.secLeft - ptime * maskTime[ m ] 
				//return thisTpl
			}
			
			self.config.format = function( val ){ 
			
				//console.log(self.end)
				
				
				var st = self._setTime()
				
				var msec = st[0] 
				
				if( val == 0 )		// set time to 0 because of miliseconds
				msec = 0
				
				self.secLeft = msec
				self.thisTpl = tpl
				
				//var thisTpl = tpl
				
				//var years = 0
				//var months = 0
				//var weeks = 0
				//var days = 0
				//var hours = 0
				//var minutes = 0
				//var seconds = 0
				//var mseconds = 0
				var ptime = 0
				//var msec = seconds
				
				//msec = setd( thisTpl, tplYears )
				 tplYears ? setT('Y') : ''
				 tplMonths ? setT('O') : ''
				 tplWeeks ? setT('W') : ''
				 tplDays ? setT('D') : ''
				 tplHours ? setT('H') : ''
				 tplMinutes ? setT('M') : ''
				 tplSeconds ? setT('S') : ''
				 tplMseconds ? setT('U') : ''
				
				/*
				if(tplYears)
				{
					ptime = Math.floor(msec / ( mask['Y'] ))
					thisTpl = thisTpl.replace( "%Y", ptime )
					msec = msec - ptime* mask['Y'] 
				}
				if(tplMonths)
				{
					ptime = Math.floor(msec / ( mask['O'] ))
					thisTpl = thisTpl.replace( "%O", ptime )
					msec = msec - ptime* mask['O'] 
				}
				if(tplWeeks)
				{
					ptime = Math.floor(msec / ( mask['W']))
					thisTpl = thisTpl.replace( "%W", ptime )
					msec = msec - ptime* mask['W'] 
				}
				if(tplDays)
				{
					ptime = Math.floor(msec / ( mask['D'] ))
					thisTpl = thisTpl.replace( "%D", ptime )
					msec = msec - ptime* mask['D'] 
				}
				if(tplHours)
				{
					ptime = Math.floor( msec / ( mask['H'] ) )
					thisTpl = thisTpl.replace( "%H", zeroPad(ptime,2) )
					msec = msec - ptime* mask['H'] 
				}
				if(tplMinutes)
				{
					ptime = Math.floor( msec / ( mask['M']  ) )
					//minutes.toString().length < 2 ? pad("0" + str, max) : str;
					thisTpl = thisTpl.replace( "%M", zeroPad(ptime,2) )
					msec = msec - ptime * mask['M'] 
				}
				if(tplSeconds)
				{
					ptime = Math.floor( msec / mask['S'] )
					//var n = zeroPad(seconds,2).toString()
					thisTpl = thisTpl.replace( "%S", zeroPad(ptime,2) )
					msec = msec - ptime*mask['S']
				}
				if(tplMseconds)
				{
					//mseconds = Math.floor( msec  )
					thisTpl = thisTpl.replace( "%U", zeroPad(msec,3) )
					//msec = msec - minutes*60
				}
				*/
				
				
				//thisTpl = thisTpl.replace( "%H", Math.floor(msec / (60*60)) )
				//var d
				//self.step = 1 * ( Math.abs( st[0] - st[1] ) /  self.tick  )
				//var seconds = Math.floor((dateFuture - (dateNow))/1000);
				//var minutes = Math.floor(seconds/60);
				//var hours = Math.floor(minutes/60);
				//var days = Math.floor(hours/24);
				//var diff = dateDiff(seconds)
				//console.log( self.thisTpl )
				
				return self.thisTpl
				
				//var n = 0			//  zostava este do
				
				//var now = Date.now() 
				//var now_floor = 1 * (now / 1000) 
				//if(self.config.start < self.config.end)
							
				//n = val
				//var days =  n / (3600*24)
				//var hours =  n / (3600*24)
				//var total = self.config.start
				//var diff = Math.abs( self.diff - val )
				//var theDate = new Date(val * 1000);
				//var dateString = theDate.toGMTString();
				
				//var n = val - self.config.end 
				
				//n = val
				//var d = new Date( val );
				//console.log( dateString )
				
				//return n
				//
				//var d = new Date();
				//var day = days[d.getDay()];
				//var hr = d.getHours();
				//var min = d.getMinutes();
				//var sec = d.getSeconds();
				//if (min < 10) {
				//	min = "0" + min;
				//}
				//var ampm = hr < 12 ? "am" : "pm";
				//var date = d.getDate();
				//var month = months[d.getMonth()];
				//var year = d.getFullYear();
				//var x = document.getElementById("time");
				//return day + " " + hr + ":" + min + sec + ampm + " " + date + " " + month + " " + year;

				//return val 
			} 
			
		},
		
		_setSteps: function ( start, end )
		{
			var self = this
			//if( start === undefined ) start =  self.config.start
			//if( end === undefined ) end =  self.config.end
			
				
			self.start = 1 * start
			self.end = 1 * end
			self.current = self.start
			
			if(!self.clock)
			self.$elem.html( self.start )
			
			self.step = 1 * ( Math.abs( self.current - self.end ) / self.tick  )  
			self.dir = self.current > self.end ? -1 : 1;
			self.tick = self.dir * self.tick;
			
			//alert( (self.current - self.end)  / self.tick )
			//alert( self.current - self.end )
			//alert(self.step)
		},
		
		_rep: function ()
		{
			var self = this
			
			 //console.log( Date.now() )
			
			 //console.log( self.step )
			 
			 //var start_s = start
			 
			//if( self.step >= 0 )
			if( self.step > 0 )
			{
				
				//alert( self.step )
				//console.log( self.step )
				
				var start_s = self.current
				
				
				//if( start_pom % 1 !== 0 )		// not integer - float
				if( typeof self.config.format === 'function')
				start_s = self.config.format( start_s )
				//start_pom =  start_pom.toString();
				//start_pom = addCommas(start_pom)
				//start_pom = start_pom.toFixed(1).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
				//start_pom = start_pom.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				// 
				
				//self.config.speed = self.config.speed + 100
				
				
				//if(self.clock
				self.$elem.html( start_s )
				
				self.step -= 1
				self.current += self.tick
				
				
			}
			else
			{
				// cancelAnimationFrame(globalID);
				cancelRequestAnimFrame(self.request);
				 
				var end = self.end
				
			
				//var end2 = end.toFixed(1)
				//end2 = addCommas(end2) //text
				//end2 = end2.replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
				
				
				//{
				
				//}
				//}
				
				
				
				if(self.clock)	// switch clock counter
				{
					//self._setSteps( self.end, self.start )
					//cancelRequestAnimFrame( self.request );
					//alert(53)
					if( self.config.switchClock )
					{
						self._setClock()
						self._loop()
					}
					else
						end = 0		// set to 0 - miliseconds
				}
				
				//alert(end)
				
				if( typeof self.config.format === 'function' )
				end = self.config.format( end )
				
				self.$elem.html( end )
				
				//clearInterval( self.timer )
				
				if( typeof self.config.complete === 'function')
				self.config.complete( self.$elem )
				
				
				
				
				
				//self._setSteps( self.end, self.start )
				//cancelRequestAnimFrame( self.request );
				//self._setClock()
				//self._loop()
			}
		
				
		},
		
		
		_loop: function ( start, end )
		{
			var self = this
			
			
			
			var fps,fpsInterval,startTime,now,then,delta;
			fps = self.speed	// highest number = highest speed
			
			
			fpsInterval = 1000 / fps;
			//fpsInterval=1;
			then=Date.now();
			startTime=then;
				
			self.frames = 0

			
			
			//self.request = 0
			
				// usage:
				// instead of setInterval(render, 16) ....
			
			//var globalID;

			//function repeatThis() {
			  //$("<div />").appendTo("body");
			//  globalID = requestAnimationFrame(repeatThis);
			 
				
			//	}
	
			
			//return;
			
			  
			  //console.log( self.config.speed )
			//if( self.frames % self.config.speed  !== 0 )
			// return
			
			//self.config.speed = self.config.speed + 5
			//if(self.config.speed <= 0)
			//self.config.speed = 1
			//else
			//self.config.speed = 1
			
			
			   
				
			

			
			
			//$("#start").on("click", function() {
			//globalID = requestAnimationFrame(repeatThis);
			//});
			 

			
			//self.request = 0
		

			// to store the request
			//var request;

			// start and run the animloop
			


			function animloop(){
			  //render();
			  
				self.request = requestAnimFrame( animloop );
			 
				//console.log(self.request)
			  
				self.frames ++ 
			  
			  
				now = Date.now();
				delta = now - then;

				
				// if enough time has elapsed, draw the next frame
				if (delta > fpsInterval) {
				//if (elapsed > fpsInterval) {
				
				//if(fpsInterval > 50)
				//fpsInterval = fpsInterval + steps
				
					// Get ready for next frame by setting then=now, but also adjust for your
					// specified fpsInterval not being a multiple of RAF's interval (16.7ms)
					then = now - (delta % fpsInterval);

					//if(!self.paused)
					self._rep()
				}

				
					// Put your drawing code here
					//console.log( Date.now() )
				

				
				
			}
			//})();
			animloop()
			
			//})();

			// cancelRequestAnimFrame to stop the loop in 1 second
			//setTimeout(function(){
				//cancelRequestAnimFrame(request);                
			//}, 10000)

			
				
		},		
			
			
			
			
		/*	
		_countIt: function ()
		{
			var self = this
			
			
			var start = 1 * self.config.start
			var end = 1 * self.config.end
			
			var steps = 1 * ( Math.abs( start - end ) /  self.config.tick  )  
			var dir = start > end ? -1 * self.config.tick : 1 * self.config.tick
			
			
			var start_s = start
			//var bigger = start > end ? start : end
			//var lower = start > end ? end : start
			
			//var start1 = start
			//alert(steps)
			//var start1 = 80
			//var end1 = 40

			
	

			//return;
			
			// self.timer = setInterval( function(){
			
				if( steps >= 0 )
				{
					start_s = start
					//if( start_pom % 1 !== 0 )		// not integer - float
					if( typeof self.config.format === 'function')
					start_s = self.config.format( start_s )
					//start_pom =  start_pom.toString();
					//start_pom = addCommas(start_pom)
					//start_pom = start_pom.toFixed(1).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
					//start_pom = start_pom.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					// 
					
					self.config.speed = self.config.speed + 100
					
					//console.log( 1 )
					
					self.$elem.text( start_s )
					
					start += dir
					steps -= 1
				}
				else
				{
					//console.log( self.config.tick % 1 === 0 )
					//if( self.config.tick % 1 !== 0 )		// not integer - float
					//{
					//var end2 = end
					if( typeof self.config.format === 'function')
					end = self.config.format( end )
				
					//var end2 = end.toFixed(1)
					//end2 = addCommas(end2)
					//end2 = end2.replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
					self.$elem.text( end )
					//}
					
					clearInterval( self.timer )
					
					if( typeof self.config.complete === 'function')
					self.config.complete( self.$elem )
				}
				//console.log( text )
			
			// }, self.config.speed )
			
		},
		*/
		
		
		
		/**
		 * Returns a random integer between min (inclusive) and max (inclusive)
		 * Using Math.round() will give you a non-uniform distribution!
		 */
		 _getRandomInt: function(min, max) {
			return Math.floor(Math.random() * (max - min + 1)) + min;
		},	
	
		
		_eventCallback : function( event ) {
			var self = this
			
			if(typeof self.config[ event ] === 'function')
			{
				//google.maps.event.addListener( mapObj, event, function(e) {
				//alert(1)
				//obj[event](e,mapObj) 
				//var data = self.serialize()
				//self.config[ event ] (data)
				
				//});
			}
				
		},
		
		_initEvents : function(){
		},

		
		setCurrent : function( val ){
		
			var self = this
			
			val = 1*val
			
			//if(self.start > val || self.end > val)
			if( ( self.dir == 1 && ( self.start > val || self.end < val ) )
				|| 
				( self.dir == -1 && ( self.start < val || self.end > val ) )
				)
			{
			//console.log('incorrect value');
			return;
			}
			//alert( self.dir )
			
			self.current = val
			
			self._setSteps( self.current, self.config.end )
			
			cancelRequestAnimFrame(self.request);
			//self.start = 1 * self.config.start
			//self.end = 1 * self.config.end
			//self.current = self.start
			//self._setSteps(  )
			self._loop()
		
		},
		
		
		toggleDir : function(){
		
			var self = this
			cancelRequestAnimFrame( self.request );
			
			//alert(self.start)
			self._setSteps( self.end, self.start )
			self._loop( )
		},

		togglePause : function(){
		
			var self = this
			//cancelRequestAnimFrame(self.request);
			if(self.isPaused)
			self.unpause()
			else
			self.pause()
			
		},

		
		pause : function(){
		
			var self = this
			cancelRequestAnimFrame(self.request);
			self.isPaused = true
		},

		unpause : function(){
			var self = this
			//self._loopRefresh()
			self.isPaused = false
			cancelRequestAnimFrame( self.request );
			self._loop( )
		},
		
		refresh : function(){
			var self = this
			cancelRequestAnimFrame( self.request );
				
			//self.steps = 1 * ( Math.abs( self.start - self.end ) /  self.config.tick  )  
			//self.dir = self.start > self.end ? -1 * self.config.tick : 1 * self.config.tick
			//self.actual = self.start
			self._setSteps( self.config.start, self.config.end )
			//alert(self.start - self.end)
			//cancelRequestAnimFrame( self.request );
			//self.request = 0
			self._loop()
				
		}

		
		

		
	}
	
    // You don't need to change something below:
    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations and allowing any
    // public function (ie. a function whose name doesn't start
    // with an underscore) to be called via the jQuery plugin,
    // e.g. $(element).defaultPluginName('functionName', arg1, arg2)
    $.fn[pluginName] = function ( options ) {
        var args = arguments;

        // Is the first parameter an object (options), or was omitted,
        // instantiate a new instance of the plugin.
        if (options === undefined || typeof options === 'object') {
            return this.each(function () {

                // Only allow the plugin to be instantiated once,
                // so we check that the element has no plugin instantiation yet
                if (!$.data(this, 'plugin_' + pluginName)) {
					
                    // if it has no instance, create a new one,
                    // pass options to our plugin constructor,
                    // and store the plugin instance
                    // in the elements jQuery data object.
                    $.data(this, 'plugin_' + pluginName, new countidPlugin( this, options ));
                }
            });

        // If the first parameter is a string and it doesn't start
        // with an underscore or "contains" the `init`-function,
        // treat this as a call to a public method.
        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {
			
            // Cache the method call
            // to make it possible
            // to return a value
            var returns;

            this.each(function () {
                var instance = $.data(this, 'plugin_' + pluginName);

                // Tests that there's already a plugin-instance
                // and checks that the requested public method exists
                if (instance instanceof countidPlugin && typeof instance[options] === 'function') {
					//alert( options )
                    // Call the method of our plugin instance,
                    // and pass it the supplied arguments.
                    returns = instance[options].apply( instance, Array.prototype.slice.call( args, 1 ) );
                }

                // Allow instances to be destroyed via the 'destroy' method
                if (options === 'destroy') {
                  $.data(this, 'plugin_' + pluginName, null);
                }
            });

            // If the earlier cached method
            // gives a value back return the value,
            // otherwise return this to preserve chainability.
            return returns !== undefined ? returns : this;
        }
    };

}(jQuery, window, document));


