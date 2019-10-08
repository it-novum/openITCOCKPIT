document.addEventListener('DOMContentLoaded', function () {
	/* this sets all default colors and width sizes - however you can still override them with the HTML tagOptions */
	/* this is a non-destructive settings which can be applied to any sparkline chart to keep things constant */

	$('.sparklines').sparkline('html', {
		// enables you to use HTML tad options (eg. sparkBarWidth="100")
		enableTagOptions: true,
		// you can also use percentage (eg. "100%")
		width: 110,
		// globalized height
		height: 40,
		// globalize bar spacing
		barSpacing: "3px",
		// globalized bar width
		barWidth: "7px",		
		// the point radius of line chart
		spotRadius: 3,
		// the red line color
		highlightLineColor: rgb2hex($('#js-color-profile .color-danger-700').css('color')),
		// used for box chart
		targetColor: rgb2hex($('#js-color-profile .color-danger-500').css('color')),
		// used for box chart
		performanceColor: rgb2hex($('#js-color-profile .color-primary-700').css('color')),
		// range colors
		rangeColors: [	rgb2hex($('#js-color-profile .color-primary-100').css('color')), 
						rgb2hex($('#js-color-profile .color-primary-200').css('color')), 
						rgb2hex($('#js-color-profile .color-primary-300').css('color'))],
		// stacked bar colors
		barColor: rgb2hex($('#js-color-profile .color-primary-500').css('color')),
		stackedBarColor: [
						rgb2hex($('#js-color-profile .color-danger-300').css('color')), 
						rgb2hex($('#js-color-profile .color-info-300').css('color'))],
		//pie colors
		sliceColors: [	rgb2hex($('#js-color-profile .color-success-500').css('color')), 
						rgb2hex($('#js-color-profile .color-info-500').css('color')), 
						rgb2hex($('#js-color-profile .color-danger-500').css('color')), 
						rgb2hex($('#js-color-profile .color-primary-500').css('color')), 
						rgb2hex($('#js-color-profile .color-warning-500').css('color')), 
						rgb2hex($('#js-color-profile .color-primary-700').css('color')), 
						rgb2hex($('#js-color-profile .color-info-700').css('color')), 
						rgb2hex($('#js-color-profile .color-danger-700').css('color'))
					]
	});
});
