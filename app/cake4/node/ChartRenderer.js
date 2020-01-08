var express = require('express');
const ChartjsNode = require('chartjs-node');
var bodyParser = require('body-parser');
const util = require('util');
var moment = require('moment');

//https://github.com/vmpowerio/chartjs-node/issues/26
if(global.CanvasGradient === undefined){
    global.CanvasGradient = function(){
    };
}
var app = express();
app.use(bodyParser.json({limit: '500mb'}));
app.use(bodyParser.urlencoded({limit: '500mb', extended: true}));

app.post('/AreaChart', function(request, response){
    //console.log(request.body);      // json data

    //If graph appears to be gray in pdf, this is a bug in wkhtmltopdf 12.5-1
    //https://github.com/wkhtmltopdf/wkhtmltopdf/issues/2208
    //https://github.com/wkhtmltopdf/wkhtmltopdf/issues/3387

    var getColor = function(index){
        var bgColors = [
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
        ];

        var borderColors = [
            'rgba(54, 162, 235, 1)',
            'rgba(255,99,132,1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
        ];

        if(index > (bgColors.length - 1)){
            return {
                background: 'rgba(0, 0, 0, 0.2)',
                border: 'rgba(0, 0, 0, 1)'
            };
        }

        return {
            background: bgColors[index],
            border: borderColors[index]
        };
    };

    var labels = Object.keys(request.body.data[0].data);

    var datasets = [];

    var useTwoYAxes = false;
    var yAxes = [{}];
    ds = request.body.data[0].datasource;
    if(ds.min !== null && ds.max !== null){
        var stepSize = null;
        var min = parseInt(ds.min, 10);
        var max = parseInt(ds.max, 10);
        if(min >= 0 && max <= 10){
            //Fix larger steps for EVC
            stepSize = 1;
        }

        yAxes = [{
            ticks: {
                beginAtZero: min === 0,
                min: min,
                max: max,
                stepSize: stepSize
            }
        }];
    }

    if(request.body.data.length === 2){
        useTwoYAxes = true;
        var ds;

        var y1 = {
            id: 'A',
            type: 'linear',
            position: 'left',
            scaleLabel: {
                display: true,
                labelString: request.body.data[0].datasource.label
            }
        };

        ds = request.body.data[0].datasource;
        if(ds.min !== null && ds.max !== null){
            y1.ticks = {
                beginAtZero: parseInt(ds.min, 10) === 0,
                min: parseInt(ds.min, 10),
                max: parseInt(ds.max, 10)
            };
        }

        var y2 = {
            id: 'B',
            type: 'linear',
            position: 'right',
            scaleLabel: {
                display: true,
                labelString: request.body.data[1].datasource.label
            },
            gridLines: {
                display: false
            }
        };

        ds = request.body.data[1].datasource;
        if(ds.min !== null && ds.max !== null){
            y2.ticks = {
                beginAtZero: parseInt(ds.min, 10) === 0,
                min: parseInt(ds.min, 10),
                max: parseInt(ds.max, 10)
            };
        }

        yAxes = [y1, y2];
    }

    for(var i in request.body.data){
        var color = getColor(i);
        var label = request.body.data[i].datasource.label;
        if(request.body.data[i].datasource.unit != '' && request.body.data[i].datasource.unit !== null){
            label = request.body.data[i].datasource.label + ' in ' + request.body.data[i].datasource.unit;
        }

        if(useTwoYAxes === true){
            datasets.push({
                label: label,
                borderColor: color.border,
                backgroundColor: color.background,
                borderWidth: 1,
                fill: true,
                data: Object.values(request.body.data[i].data),
                yAxisID: (i == 0) ? 'A' : 'B',
                pointRadius: 0
            });
        }else{
            datasets.push({
                label: label,
                borderColor: color.border,
                backgroundColor: color.background,
                borderWidth: 1,
                fill: true,
                data: Object.values(request.body.data[i].data),
                pointRadius: 0
            });
        }
    }

    var displayTitle = request.body.settings.title != '';
    var chartJsOptions = {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            scales: {
                yAxes: yAxes,

                xAxes: [{
                    type: 'time',
                    ticks: {
                        fontSize: 10,
                    },
                    time: {
                        min: moment((request.body.settings.graph_start)),
                        max: moment((request.body.settings.graph_end)),
                        displayFormats: {
                            'millisecond': 'SSSS',
                            'second': 'HH:mm',
                            'minute': 'HH:mm',
                            'hour': 'MMM D, HH:mm',
                            'day': 'MMM DD',
                            'week': 'MMM DD',
                            'month': 'MMM DD',
                            'quarter': 'MMM DD',
                            'year': 'MMM DD'
                        }
                    }
                }]
            },
            title: {
                display: displayTitle,
                text: request.body.settings.title
            }
        }
    };

    //console.log(util.inspect(chartJsOptions,  false, null, true ));
    //console.log(util.inspect(chartJsOptions, false, null, true /* enable colors */));
    //console.log("\n");

    //console.log(JSON.stringify(chartJsOptions));

    var chartNode = new ChartjsNode(request.body.settings.width, request.body.settings.height);
    return chartNode.drawChart(chartJsOptions)
        .then(function(){
            //chart is created
            //get image as png buffer
            return chartNode.getImageBuffer('image/png');
        })
        .then(function(buffer){
            response.writeHead(200, {'Content-Type': 'image/png'});
            response.end(buffer, 'binary');
        });
});

app.listen(8084, '127.0.0.1');








