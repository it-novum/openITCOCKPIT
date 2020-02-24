/**
 * Mobile orientation change events
 * DOC: recalculates app height
 **/
$( window ).on( "orientationchange", function( event ) {
	/* reset any .CSS heights and force appHeight function to recalculate */

	if (myapp_config.debugState)
		console.log("orientationchange event");
});
