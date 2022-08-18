var uPlotGraphDefaults = (function(){
    function uPlotGraphDefaults(){
        this.defaultFillColor = '#4285F4';
        this.defaultBorderColor = '#4073E0';

        this.okFillColor = '#00C851';
        this.okBorderColor = '#00B44D';

        this.criticalFillColor = '#CC0000';
        this.criticalBorderColor = '#C00000';

        this.warningFillColor = '#ffbb33';
        this.warningBorderColor = '#E7931D';

        this.unknownFillColor = '#9B9B9B';
        this.unknownBorderColor = '#757575';

        this.defaultColors = [
            [this.defaultFillColor, this.defaultBorderColor],
            ['#ffbb33', '#E7931D'],
            ['#B054DE', '#8a2eb8'],
            ['#CC0000', '#C00000'],
            ['#00C851', '#00B44D'],
            ['#262626', '#000000'],
            ['#4285F4', '#4073E0']
        ];

        let can = document.createElement("canvas");
        this.ctx = can.getContext("2d");
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
     * @param amount integer
     * @returns {{fill: Array, border: Array}}
     */
    uPlotGraphDefaults.prototype.getColors = function(amount){
        amount = parseInt(amount, 10);

        var colors = {
            fill: [],
            border: []
        };


        if(amount > this.defaultColors.length){
            var ColorGeneratorObj = new ColorGenerator();
            var missingColors = amount - this.defaultColors.length;

            var randomColors = ColorGeneratorObj.generate(missingColors, 90, 120);
            for(var i in randomColors){
                colors.fill.push(randomColors[i]);
                colors.border.push(randomColors[i]);
            }
        }else{
            for(var i = 1; i < amount; i++){
                colors.fill.push(this.defaultColors[(i - 1)][0]);
                colors.border.push(this.defaultColors[(i - 1)][1]);
            }
        }

        return colors;
    };

    /**
     * @param index integer
     * @returns {{fill: Array, border: Array}}
     */
    uPlotGraphDefaults.prototype.getColorByIndex = function(index){
        index = parseInt(index, 10);

        var color = {
            fill: [],
            border: []
        };

        if(index > this.defaultColors.length){
            var randomColors = ColorGeneratorObj.generate(1, 90, 120);
            for(var i in randomColors){
                color.fill.push(randomColors[i]);
                color.border.push(randomColors[i]);
            }
        }else{
            color.fill.push(this.defaultColors[index][0]);
            color.border.push(this.defaultColors[index][1]);
        }

        return color;
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
                    time: true

                    /*
                    range: function(u, dataMin, dataMax){
                        if(dataMin == null){
                            console.log('HIER');
                            return [opts.start, opts.end];
                        }

                        console.log('DA!!!');
                        console.log(dataMin);
                        console.log(dataMax);
                        return [dataMin, dataMax];
                    }
                     */
                },
                /*
                y: {
                    range(u, dataMin, dataMax) {
                        if (dataMin == null)
                            return [0, 100];

                        return uPlot.rangeNum(dataMin, dataMax, 0.1, true);
                    }
                },
                 */
            },
            series: [
                {},
                {
                    label: "Series Title",
                    width: opts.lineWidth,
                    paths: _spline,

                    stroke: "red",
                    fill: "red"
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

        return uPlotOptions;
    };

    return uPlotGraphDefaults;
}).call(this);

