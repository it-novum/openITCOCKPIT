/**
 * Bind the throttled handler to the resize event.
 * NOTE: Please do not change the order displayed (e.g. 1a, 1b, 2a, 2b...etc)
 **/
$(window).resize(

 	$.throttle( myapp_config.throttleDelay, function (e) {

		 /**
		  * (1a) ADD CLASS WHEN BELOW CERTAIN WIDTH (MOBILE MENU)
		  * Description: tracks the page min-width of #CONTENT and NAV when navigation is resized.
		  * This is to counter bugs for minimum page width on many desktop and mobile devices.
		  **/
		  initApp.mobileCheckActivation();

		 /**
		  * (1b) CHECK NAVIGATION STATUS (IF HORIZONTAL OR VERTICAL)
		  * Description: fires an event to check for navigation orientation.
		  * Based on the condition, it will initliaze or destroy the slimscroll, or horizontal nav plugins
		  **/
		  initApp.checkNavigationOrientation();


		 /** -- insert your resize codes below this line -- **/
	 
	})
); 
