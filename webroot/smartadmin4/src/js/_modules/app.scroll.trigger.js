/**
 * Bind the throttled handler to the scroll event
 **/
$(window).scroll(

 	$.throttle( myapp_config.throttleDelay, function (e) {

		 /**
		  * FIX APP HEIGHT
		  * Compare the height of nav and content;
		  * If one is longer/shorter than the other, measure them to be equal.
		  * This event is only fired on desktop.
		  **/
		  

		  /** -- insert your other scroll codes below this line -- **/

	})

);

/**
 * Initiate scroll events
 **/
$(window).on('scroll', initApp.windowScrollEvents);
