'use strict';

var ngVisApp = angular.module('ngVisApp', ['ngVis']);

ngVisApp.controller('appController', function ($scope, $location, $timeout, VisDataSet) {

    var names = ['SquareShaded', 'Bar', 'Blank', 'CircleShaded'];
    var groups = new vis.DataSet();
    groups.add({
        id: 0,
        content: names[0],
        options: {
            drawPoints: {
                style: 'square' // square, circle
            },
            shaded: {
                orientation: 'bottom' // top, bottom
            }
        }});

    groups.add({
        id: 1,
        content: names[1],
        options: {
            style:'bar',
            drawPoints: {style: 'circle',
                size: 10
            }
        }});

    groups.add({
        id: 2,
        content: names[2],
        options: {
            yAxisOrientation: 'right', // right, left
            drawPoints: false
        }
    });

    groups.add({
        id: 3,
        content: names[3],
        options: {
            yAxisOrientation: 'right', // right, left
            drawPoints: {
                style: 'circle' // square, circle
            },
            shaded: {
                orientation: 'top' // top, bottom
            }
        }});

    var items = [
        {x: '2014-06-12', y: 0 , group: 0},
        {x: '2014-06-13', y: 30, group: 0},
        {x: '2014-06-14', y: 10, group: 0},
        {x: '2014-06-15', y: 15, group: 1},
        {x: '2014-06-16', y: 30, group: 1},
        {x: '2014-06-17', y: 10, group: 1},
        {x: '2014-06-18', y: 15, group: 1},
        {x: '2014-06-19', y: 52, group: 1},
        {x: '2014-06-20', y: 10, group: 1},
        {x: '2014-06-21', y: 20, group: 2},
        {x: '2014-06-22', y: 600, group: 2},
        {x: '2014-06-23', y: 100, group: 2},
        {x: '2014-06-24', y: 250, group: 2},
        {x: '2014-06-25', y: 300, group: 2},
        {x: '2014-06-26', y: 200, group: 3},
        {x: '2014-06-27', y: 600, group: 3},
        {x: '2014-06-28', y: 1000, group: 3},
        {x: '2014-06-29', y: 250, group: 3},
        {x: '2014-06-30', y: 300, group: 3}
    ];

    $scope.data = {items: new vis.DataSet(items), groups: groups};
    $scope.options = {
        dataAxis: {showMinorLabels: false},
        legend: {left:{position:"bottom-left"}},
        start: '2014-06-09',
        end: '2014-07-03'
    };
});
