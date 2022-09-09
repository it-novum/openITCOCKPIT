var GraphDefaults = (function(){
    function GraphDefaults(){
        this.defaultFillColor = '#3274D9';
        this.defaultBorderColor = '#3274D9';

        this.okFillColor = '#56A64B';
        this.okBorderColor = '#56A64B';

        this.criticalFillColor = '#CC0000';
        this.criticalBorderColor = '#CC0000';

        this.warningFillColor = '#EAB839';
        this.warningBorderColor = '#EAB839';

        this.defaultColors = [
            [this.defaultFillColor, this.defaultBorderColor],
            ['#ffbb33', '#E7931D'],
            ['#B054DE', '#8a2eb8'],
            ['#CC0000', '#C00000'],
            ['#00C851', '#00B44D'],
            ['#262626', '#000000'],
            ['#4285F4', '#4073E0']
        ];
    }

    /**
     * @param amount integer
     * @returns {{fill: Array, border: Array}}
     */
    GraphDefaults.prototype.getColors = function(amount){
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
    GraphDefaults.prototype.getColorByIndex = function(index){
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

    /**
     *
     * @returns {{width: string, legend: boolean, grid: {hoverable: boolean, markings: Array, borderWidth: {top: number, right: number, bottom: number, left: number}, borderColor: {top: string}}, tooltip: boolean, xaxis: {mode: string, timeformat: string, tickFormatter: (function(*, *): string)}, lines: {show: boolean, lineWidth: number, fill: boolean, fillColor: {colors: *[]}, steps: boolean}, series: {show: boolean, labelFormatter: (function(*, *): string)}, selection: {mode: string}}}
     */
    GraphDefaults.prototype.getDefaultOptions = function(){
        return {
            width: '100%',
            legend: false,
            grid: {
                hoverable: true,
                markings: [],
                borderWidth: {
                    top: 1,
                    right: 1,
                    bottom: 1,
                    left: 1
                },
                borderColor: {
                    top: '#CCCCCC'
                }
            },
            tooltip: false,
            xaxis: {
                mode: 'time',
                timeformat: '%d.%m.%y %H:%M:%S' // This is handled by a plugin, if it is used -> jquery.flot.time.js,
                //timezone: 'browser'
            },
            yaxis: {
                tickFormatter: function(val, axis){
                    //return val.toFixed(axis.tickDecimals); // flot.js default tickFormatter

                    // Format number to US format to also get decimal separators for large numbers
                    return new Intl.NumberFormat('en-US', {
                        style: 'decimal',
                        minimumFractionDigits: axis.tickDecimals
                    }).format(val.toFixed(axis.tickDecimals));
                }
            },
            lines: {
                show: true,
                lineWidth: 2,
                fill: true,
                fillColor: {
                    colors: [{opacity: 0.1}, {brightness: 1, opacity: 0.2}]
                },
                steps: false //if true its more rrdtool like
            },
            series: {
                show: true,
                labelFormatter: function(label, series){
                    // series is the series object for the label
                    return '<a href="#' + label + '">' + label + '</a>';
                },
                curvedLines: {
                    active: true
                }
            },
            selection: {
                mode: "x"
            }
        };
    };

    return GraphDefaults;
}).call(this);

