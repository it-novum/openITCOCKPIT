document.addEventListener('DOMContentLoaded', function () {
	/* 	Easy pie chart Snippet
		DOC: make sure to include this snippet in your project to be able to use the easy 
		configurations without any jquery implementations
	 */
	$('.js-easy-pie-chart').each(function() {

		var $this = $(this),
			barcolor = $this.css('color') || color.primary._700,
			trackcolor = $this.data('trackcolor') || 'rgba(0,0,0,0.04)',
			size = parseInt($this.data('piesize')) || 50,
			scalecolor =   $this.data('scalecolor') || $this.css('color'),
			scalelength = parseInt($this.data('scalelength')) || 0,
			linewidth = parseInt($this.data('linewidth')) ||  parseInt(size / 8.5),
			linecap = $this.data('linecap') || 'butt'; //butt, round and square.
			
		$this.easyPieChart({
			size : size,
			barColor : barcolor,
			trackColor : trackcolor,
			scaleColor: scalecolor,
			scaleLength: scalelength, //Length of the scale lines (reduces the radius of the chart).
			lineCap : linecap, //Defines how the ending of the bar line looks like. Possible values are: butt, round and square.
			lineWidth : linewidth,
			animate: {
				duration: 1500,
				enabled: true
			},
			onStep: function(from, to, percent) {
				$(this.el).find('.js-percent').text(Math.round(percent));
			}
		});

		$this = null;
	});
});