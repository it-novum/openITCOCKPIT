var uPlotGraphDefaults = (function(){
    function uPlotGraphDefaults(){
        let can = document.createElement("canvas");
        this.ctx = can.getContext("2d");

        // Tooltip vars
        this.tooltip = document.createElement("div");
        this.tooltip.className = "u-tooltip";
        this.tooltipVisible = false;
        this.seriesIdx = 0;
        this.dataIdx = null;
        this.over = {};
        this.tooltipLeftOffset = 0;
        this.tooltipTopOffset = 0;
        this.fmtDate = uPlot.fmtDate("{YYYY}-{MM}-{DD} {HH}:{mm}:{ss}");
        this.unit = '';
        // Tooltip End
    }

    uPlotGraphDefaults.prototype.showTooltip = function(){
        if(!this.tooltipVisible){
            this.tooltip.style.display = "block";
            this.over.style.cursor = "pointer";
            this.tooltipVisible = true;
        }
    };

    uPlotGraphDefaults.prototype.hideTooltip = function(){
        if(this.tooltipVisible){
            this.tooltip.style.display = "none";
            this.over.style.cursor = null;
            this.tooltipVisible = false;
        }
    };

    uPlotGraphDefaults.prototype.setTooltip = function(u){
        this.showTooltip();

        let top = u.valToPos(u.data[1][this.dataIdx], 'y');
        let lft = u.valToPos(u.data[0][this.dataIdx], 'x');

        let shiftX = 10;
        let shiftY = 10;

        this.tooltip.style.top = (this.tooltipTopOffset + top + shiftX) + "px";
        this.tooltip.style.left = (this.tooltipLeftOffset + lft + shiftY) + "px";

        //tooltip.style.borderColor = isInterpolated(dataIdx) ? interpolatedColor : seriesColors[seriesIdx - 1];
        this.tooltip.style.borderColor = '#FF0000';

        //console.log(u.data);
        //console.log(this.seriesIdx);
        //console.log(this.dataIdx);
        //console.log(u.data[0][this.dataIdx]);
        this.tooltip.textContent = (
            this.fmtDate(new Date(u.data[0][this.dataIdx] * 1000)) + "\n" +
            uPlot.fmtNum(u.data[1][this.dataIdx]) + " " + this.unit
        );
    }

    uPlotGraphDefaults.prototype.scaleGradient = function(u, scaleKey, ori, scaleStops, discrete = false){
        let scale = u.scales[scaleKey];

        // we want the stop below or at the scaleMax
        // and the stop below or at the scaleMin, else the stop above scaleMin
        let minStopIdx;
        let maxStopIdx;

        for(let i = 0; i < scaleStops.length; i++){
            let stopVal = scaleStops[i][0];

            if(stopVal <= scale.min || minStopIdx == null)
                minStopIdx = i;

            maxStopIdx = i;

            if(stopVal >= scale.max)
                break;
        }

        if(minStopIdx == maxStopIdx)
            return scaleStops[minStopIdx][1];

        let minStopVal = scaleStops[minStopIdx][0];
        let maxStopVal = scaleStops[maxStopIdx][0];

        if(minStopVal == -Infinity)
            minStopVal = scale.min;

        if(maxStopVal == Infinity)
            maxStopVal = scale.max;

        let minStopPos = u.valToPos(minStopVal, scaleKey, true);
        let maxStopPos = u.valToPos(maxStopVal, scaleKey, true);

        let range = minStopPos - maxStopPos;

        let x0, y0, x1, y1;

        if(ori == 1){
            x0 = x1 = 0;
            y0 = minStopPos;
            y1 = maxStopPos;
        }else{
            y0 = y1 = 0;
            x0 = minStopPos;
            x1 = maxStopPos;
        }

        let grd = this.ctx.createLinearGradient(x0, y0, x1, y1);

        let prevColor;

        for(let i = minStopIdx; i <= maxStopIdx; i++){
            let s = scaleStops[i];

            let stopPos = i == minStopIdx ? minStopPos : i == maxStopIdx ? maxStopPos : u.valToPos(s[0], scaleKey, true);
            let pct = (minStopPos - stopPos) / range;

            if(discrete && i > minStopIdx)
                grd.addColorStop(pct, prevColor);

            grd.addColorStop(pct, prevColor = s[1]);
        }

        return grd;
    };

    /**
     * @param index
     * @returns {{fill: string, stroke: string}}
     */
    uPlotGraphDefaults.prototype.getColorByIndex = function(index){
        index = parseInt(index, 10);
        var colors = {
            stroke: [
                "rgba(50, 116, 217, 1)",
                "rgba(0,200,81, 1)",
                "rgba(163, 82, 204, 1)",
                "rgba(255, 120, 10, 1)"
            ],
            fill: [
                "rgba(50, 116, 217, 0.2)",
                "rgba(0,200,81, 0.2)",
                "rgba(163, 82, 204, 0.2)",
                "rgba(255, 120, 10, 0.2)"
            ]
        };

        if(typeof colors.stroke[index] == "undefined"){
            return {
                stroke: "rgba(255, 120, 10, 1)",
                fill: "rgba(255, 120, 10, 0.2)"
            };
        }

        return {
            stroke: colors.stroke[index],
            fill: colors.fill[index],
        };
    };

    uPlotGraphDefaults.prototype.getDefaultOptions = function(opts){
        const {linear, spline, stepped, bars} = uPlot.paths;
        const _linear = linear();
        const _spline = spline();

        opts = opts || {};
        opts.unit = opts.unit || '';
        opts.timezone = opts.timezone || Intl.DateTimeFormat().resolvedOptions().timeZone;
        opts.showLegend = opts.showLegend !== false;
        opts.lineWidth = opts.lineWidth || 3;
        opts.thresholds = opts.thresholds || {};
        opts.thresholds.show = opts.thresholds.show !== false;
        opts.thresholds.warning = opts.thresholds.warning || null;
        opts.thresholds.critical = opts.thresholds.critical || null;

        opts.start = opts.start || 0;
        opts.end = opts.end || new Date().getTime();

        opts.strokeColor = opts.strokeColor || "rgba(50, 116, 217, 1)";
        opts.fillColor = opts.fillColor || "rgba(50, 116, 217, 0.2)";

        opts.enableTooltip = opts.enableTooltip !== false;

        this.unit = opts.unit;
        uPlotOptions = {
            title: "Area Chart",
            tzDate: function(ts){
                return uPlot.tzDate(new Date(ts * 1000), opts.timezone)
            },
            cursor: {
                drag: {
                    x: true,
                    y: false,
                }
            },
            scales: {
                x: {
                    time: true,
                    auto: true,
                    min: opts.start,
                    max: opts.end,
                    //range: [opts.start, opts.end],
                },
            },
            series: [
                {},
                {
                    label: "Series Title",
                    width: opts.lineWidth,
                    paths: _spline,

                    stroke: opts.strokeColor,
                    fill: opts.fillColor,
                },
            ],
            axes: [
                {
                    // https://github.com/leeoniya/uPlot/tree/master/docs#axis--grid-opts
                    // https://github.com/leeoniya/uPlot/blob/master/src/fmtDate.js#L65-L107
                    // @formatter:off
                    values: [
                        // tick incr          default           year                             month    day                        hour     min                sec       mode
                        [3600 * 24 * 365,   "{YYYY}",         null,                            null,    null,                      null,    null,              null,        1],
                        [3600 * 24 * 28,    "{MMM}",          "\n{YYYY}",                      null,    null,                      null,    null,              null,        1],
                        [3600 * 24,         "{M}/{D}",        "\n{YYYY}",                      null,    null,                      null,    null,              null,        1],
                        [3600,              "{h}{aa}",        "\n{M}/{D}/{YY}",                null,    null,                      null,    null,              null,        1],
                        [60,                "{HH}:{mm}",      null,                            null,    null,                      null,    null,              null,        1],
                        [1,                 ":{ss}",          "\n{M}/{D}/{YY} {h}:{mm}{aa}",   null,    "\n{M}/{D} {h}:{mm}{aa}",  null,    "\n{h}:{mm}{aa}",  null,        1],
                        [0.001,             ":{ss}.{fff}",    "\n{M}/{D}/{YY} {h}:{mm}{aa}",   null,    "\n{M}/{D} {h}:{mm}{aa}",  null,    "\n{h}:{mm}{aa}",  null,        1],
                    ],
                    // @formatter:on
                },
                {
                    // https://github.com/leeoniya/uPlot/blob/master/docs/README.md#axis--grid-opts
                    size: 100, // default is 50
                    values: function(u, vals, space){
                        return vals.map(function(v){
                            return v + ' ' + opts.unit;
                        });
                    }
                },
            ],
            legend: {
                show: opts.showLegend
            }
        };

        if(opts.thresholds.show){
            if(opts.thresholds.warning !== "" &&
                opts.thresholds.critical !== "" &&
                opts.thresholds.warning !== null &&
                opts.thresholds.critical !== null){
                opts.thresholds.warning = parseFloat(opts.thresholds.warning);
                opts.thresholds.critical = parseFloat(opts.thresholds.critical);
                if(opts.thresholds.critical > opts.thresholds.warning){
                    // Normal thresholds like for a Ping
                    uPlotOptions.series[1].stroke = function(u, seriesIdx){
                        return this.scaleGradient(u, 'y', 1, [
                            [0, "rgba(86, 166, 75, 1)"],
                            [opts.thresholds.warning, "rgba(234, 184, 57, 1)"],
                            [opts.thresholds.critical, "rgba(224, 47, 68, 1)"],
                        ], true)
                    }.bind(this);

                    uPlotOptions.series[1].fill = function(u, seriesIdx){
                        return this.scaleGradient(u, 'y', 1, [
                            [0, "rgba(86, 166, 75, 0.2)"],
                            [opts.thresholds.warning, "rgba(234, 184, 57, 0.2)"],
                            [opts.thresholds.critical, "rgba(224, 47, 68, 0.2)"]
                        ], true)
                    }.bind(this);

                }else{
                    // warning is < than critical, Free disk space for example
                    uPlotOptions.series[1].stroke = function(u, seriesIdx){
                        return this.scaleGradient(u, 'y', 1, [
                            [0, "rgba(224, 47, 68, 1)"],
                            [opts.thresholds.critical, "rgba(234, 184, 57, 1)"],
                            [opts.thresholds.warning, "rgba(86, 166, 75, 1)"],
                        ], true)
                    }.bind(this);

                    uPlotOptions.series[1].fill = function(u, seriesIdx){
                        return this.scaleGradient(u, 'y', 1, [
                            [0, "rgba(224, 47, 68, 0.2)"],
                            [opts.thresholds.critical, "rgba(234, 184, 57, 0.2)"],
                            [opts.thresholds.warning, "rgba(86, 166, 75, 0.2)"]
                        ], true)
                    }.bind(this);
                }
            }
        }

        if(opts.enableTooltip){
            uPlotOptions.hooks = {
                ready: [
                    function(u){
                        this.over = u.over;
                        this.tooltipLeftOffset = parseFloat(this.over.style.left);
                        this.tooltipTopOffset = parseFloat(this.over.style.top);
                        u.root.querySelector(".u-wrap").appendChild(this.tooltip);

                        this.over.addEventListener("mouseleave", function(){
                            this.hideTooltip();
                        }.bind(this));

                    }.bind(this)
                ],
                setCursor: [
                    function(u){
                        var c = u.cursor;

                        if(this.dataIdx != c.idx){
                            this.dataIdx = c.idx;
                            this.setTooltip(u);
                        }
                    }.bind(this)
                ],
                setSeries: [
                    function(u, sidx){
                        console.log("setSeries");
                        // ¯\_(ツ)_/¯
                        /*
                        if(this.seriesIdx != sidx){
                            this.seriesIdx = sidx;

                            if(sidx == null)
                                this.hideTooltip();
                            else if(this.dataIdx != null)
                                this.setTooltip(u);
                        }
                        */
                    }.bind(this)
                ]
            }
        }

        return uPlotOptions;
    };

    return uPlotGraphDefaults;
}).call(this);

