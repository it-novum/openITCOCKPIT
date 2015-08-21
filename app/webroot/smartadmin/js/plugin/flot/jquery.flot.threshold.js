/*
Flot plugin for thresholding data. Controlled through the option
"threshold" in either the global series options

  series: {
    threshold: {
      below: number
      above: number
      color: colorspec
    }
  }

or in a specific series

  $.plot($("#placeholder"), [{ data: [ ... ], threshold: { ... }}])

The data points below "below" OR the points above "above" are drawn with the specified color. This
makes it easy to mark points below 0, e.g. for budget data.

Internally, the plugin works by splitting the data into two series,
above and below the threshold. The extra series above/below the threshold
will have its label cleared and the special "originSeries" attribute
set to the original series. You may need to check for this in hover
events.
*/

(function ($) {
    var options = {
        series: { threshold: null } // or { below: number, above: number, color: color spec}
    };

    function init(plot) {
        function thresholdData(plot, s, datapoints) {
            if (!s.threshold)
                return;

            var ps = datapoints.pointsize, i, x, y, p, prevp,
                thresholded = $.extend({}, s); // note: shallow copy

            thresholded.datapoints = { points: [], pointsize: ps };
            thresholded.label = null;
            thresholded.color = s.threshold.color;
            thresholded.threshold = null;
            thresholded.originSeries = s;
            thresholded.data = [];

            if ( s.threshold.below ) {
                var type = "below";
                var limit =  s.threshold.below;
            } else if ( s.threshold.above ) {
                var type = "above";
                var limit =  s.threshold.above;
            }

            var origpoints = datapoints.points,
                addCrossingPoints = s.lines.show;

            threspoints = [];
            newpoints = [];

            for (i = 0; i < origpoints.length; i += ps) {
                x = origpoints[i]
                y = origpoints[i + 1];

                prevp = p;

                if ( type === "below" ) {
                    if ( y < limit )
                        p = threspoints;
                    else
                        p = newpoints;
                } else if ( type === "above" ) {
                    if ( y > limit )
                            p = threspoints;
                        else
                            p = newpoints;
                }

                if (addCrossingPoints && prevp != p && x != null
                    && i > 0 && origpoints[i - ps] != null) {
                    if ( type === "below" )
                            var interx = (x - origpoints[i - ps]) / (y - origpoints[i - ps + 1]) * (limit - y) + x;
                    else
                            var interx = (x - origpoints[i - ps]) / (y - origpoints[i - ps + 1]) * (y - limit) + x;
                    prevp.push(interx);
                    prevp.push(limit);
                    for (m = 2; m < ps; ++m)
                        prevp.push(origpoints[i + m]);

                    p.push(null); // start new segment
                    p.push(null);
                    for (m = 2; m < ps; ++m)
                        p.push(origpoints[i + m]);
                    p.push(interx);
                    p.push(limit);
                    for (m = 2; m < ps; ++m)
                        p.push(origpoints[i + m]);
                }

                p.push(x);
                p.push(y);
            }

            datapoints.points = newpoints;
            thresholded.datapoints.points = threspoints;

            if (thresholded.datapoints.points.length > 0)
                plot.getData().push(thresholded);

            // FIXME: there are probably some edge cases left in bars
        }

        plot.hooks.processDatapoints.push(thresholdData);
    }

    $.plot.plugins.push({
        init: init,
        options: options,
        name: 'threshold',
        version: '1.0'
    });
})(jQuery);
