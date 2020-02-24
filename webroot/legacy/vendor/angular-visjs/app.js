'use strict';


var ngVisApp = angular.module('ngVisApp', ['ngVis']);

ngVisApp.controller('appController', function ($scope, $location, $timeout, VisDataSet) {

    $scope.logs = {};

    $scope.defaults = {
        orientation: ['top', 'bottom'],
        autoResize: [true, false],
        showCurrentTime: [true, false],
        showCustomTime: [true, false],
        showMajorLabels: [true, false],
        showMinorLabels: [true, false],
        align: ['left', 'center', 'right'],
        stack: [true, false],

        moveable: [true, false],
        zoomable: [true, false],
        selectable: [true, false],
        editable: [true, false]
    };

    var options = {
        align: 'center', // left | right (String)
        autoResize: true, // false (Boolean)
        editable: true,
        selectable: true,
        // start: null,
        // end: null,
        // height: null,
        // width: '100%',
        // margin: {
        //   axis: 20,
        //   item: 10
        // },
        // min: null,
        // max: null,
        // maxHeight: null,
        orientation: 'bottom',
        // padding: 5,
        showCurrentTime: true,
        showCustomTime: true,
        showMajorLabels: true,
        showMinorLabels: true
        // type: 'box', // dot | point
        // zoomMin: 1000,
        // zoomMax: 1000 * 60 * 60 * 24 * 30 * 12 * 10,
        // groupOrder: 'content'
    };

    var now = moment().minutes(0).seconds(0).milliseconds(0);

    var sampleData = function () {
        return VisDataSet([
            {
                id: 1,
                content: '<i class="fi-flag"></i> item 1',
                start: moment().add('days', 1),
                className: 'magenta'
            },
            {
                id: 2,
                content: '<a href="http://visjs.org" target="_blank">visjs.org</a>',
                start: moment().add('days', 2)
            },
            {
                id: 3,
                content: 'item 3',
                start: moment().add('days', -2)
            },
            {
                id: 4,
                content: 'item 4',
                start: moment().add('days', 1),
                end: moment().add('days', 3),
                type: 'range'
            },
            {
                id: 7,
                content: '<i class="fi-anchor"></i> item 7',
                start: moment().add('days', -3),
                end: moment().add('days', -2),
                type: 'range',
                className: 'orange'
            },
            {
                id: 5,
                content: 'item 5',
                start: moment().add('days', -1),
                type: 'point'
            },
            {
                id: 6,
                content: 'item 6',
                start: moment().add('days', 4),
                type: 'point'
            }
        ]);
    };

    var groups = VisDataSet([
            {id: 0, content: 'First', value: 1},
            {id: 1, content: 'Third', value: 3},
            {id: 2, content: 'Second', value: 2}
        ]);
    var items = VisDataSet([
            {id: 0, group: 0, content: 'item 0', start: new Date(2014, 3, 17), end: new Date(2014, 3, 21)},
            {id: 1, group: 0, content: 'item 1', start: new Date(2014, 3, 19), end: new Date(2014, 3, 20)},
            {id: 2, group: 1, content: 'item 2', start: new Date(2014, 3, 16), end: new Date(2014, 3, 24)},
            {id: 3, group: 1, content: 'item 3', start: new Date(2014, 3, 23), end: new Date(2014, 3, 24)},
            {id: 4, group: 1, content: 'item 4', start: new Date(2014, 3, 22), end: new Date(2014, 3, 26)},
            {id: 5, group: 2, content: 'item 5', start: new Date(2014, 3, 24), end: new Date(2014, 3, 27)}
        ]);

    $scope.data = {groups: groups, items: items};
    var orderedContent = 'content';
    var orderedSorting = function (a, b) {
        // option groupOrder can be a property name or a sort function
        // the sort function must compare two groups and return a value
        //     > 0 when a > b
        //     < 0 when a < b
        //       0 when a == b
        return a.value - b.value;
    };

    $scope.options = angular.extend(options, {
        groupOrder: orderedContent,
        editable: true
    })

    $scope.onSelect = function (items) {
        // debugger;
        alert('select');
    };

    $scope.onClick = function (props) {
        //debugger;
        alert('Click');
    };

    $scope.onDoubleClick = function (props) {
        // debugger;
        alert('DoubleClick');
    };

    $scope.rightClick = function (props) {
        alert('Right click!');
        props.event.preventDefault();
    };

    $scope.events = {
        rangechange: $scope.onRangeChange,
        rangechanged: $scope.onRangeChanged,
        onload: $scope.onLoaded,
        select: $scope.onSelect,
        click: $scope.onClick,
        doubleClick: $scope.onDoubleClick,
        contextmenu: $scope.rightClick
    };

})
;
