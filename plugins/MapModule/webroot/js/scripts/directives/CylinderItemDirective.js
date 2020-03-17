angular.module('openITCOCKPIT').directive('cylinderItem', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors/cylinder.html',
        scope: {
            'item': '=',
            'refreshInterval': '='
        },
        controller: function($scope){
            $scope.init = true;
            $scope.statusUpdateInterval = null;

            $scope.width = 80;
            $scope.height = 125;

            $scope.item.size_x = parseInt($scope.item.size_x, 10);
            $scope.item.size_y = parseInt($scope.item.size_y, 10);

            if($scope.item.size_x > 0){
                $scope.width = $scope.item.size_x;
            }
            if($scope.item.size_y > 0){
                $scope.height = $scope.item.size_y;
            }


            $scope.load = function(){
                $http.get("/map_module/mapeditors/mapitem/.json", {
                    params: {
                        'angular': true,
                        'disableGlobalLoader': true,
                        'objectId': $scope.item.object_id,
                        'mapId': $scope.item.map_id,
                        'type': $scope.item.type
                    }
                }).then(function(result){
                    $scope.current_state = result.data.data.Servicestatus.currentState;

                    $scope.Host = result.data.data.Host;
                    $scope.Service = result.data.data.Service;


                    $scope.responsePerfdata = result.data.data.Perfdata;
                    processPerfdata();
                    renderCylinder($scope.perfdata);

                    initRefreshTimer();

                    $scope.init = false;
                });
            };

            $scope.stop = function(){
                if($scope.statusUpdateInterval !== null){
                    $interval.cancel($scope.statusUpdateInterval);
                }
            };

            //Disable status update interval, if the object gets removed from DOM.
            //E.g in Map rotations
            $scope.$on('$destroy', function(){
                $scope.stop();
            });

            var renderCylinder = function(perfdata){
                if(!$scope.perfdata){
                    return;
                }
                var $cylinder = $('#map-cylinder-' + $scope.item.id);
                $cylinder.svg('destroy');

                $cylinder.svg({
                    settings: {
                        width: $scope.width,
                        height: $scope.height
                    }
                });
                var svg = $cylinder.svg('get');

                var value = 0;

                if(isNaN(perfdata.max) && isNaN(perfdata.critical) === false){
                    perfdata.max = perfdata.critical;
                }

                if(!isNaN(perfdata.max)){
                    value = (parseInt(perfdata.current) / parseInt(perfdata.max)) * 100;
                    //todo fix me
                    if(value > 90){
                        value = 90;
                    }
                }

                var x = 0;
                var y = 10;
                //radii for the ellipse
                var rx = $scope.width / 2;
                var ry = 10;
                //calculate positions for the Cylinder
                var ellipseCx = x + rx;
                var ellipseBottomCy = $scope.height;
                var rectX = x;
                var rectY = y;
                var ellipseTopCy = y;
                var pxValue = $scope.height * value / 100;
                var newRectY = ($scope.height - pxValue);
                var newTopEllipseY = newRectY;

                //the id schema must be like this "cyliner_"+id
                var cylinder = svg.group('cylinder_' + $scope.item.id);
                var cylinerGroup = svg.group(cylinder, 'cylinder_' + $scope.item.id);

                if($scope.item.show_label){
                    var rotateX = parseInt(($scope.height - 10 - ($scope.width / 8)), 10); //10 is svg padding 16 is font size;
                    svg.text(cylinder, 0, $scope.height - 10, ($scope.Host.hostname + '/' + $scope.Service.servicename), {
                        fontSize: ($scope.width / 8),
                        fontFamily: 'Verdana',
                        fill: '#000',
                        transform: 'rotate(-90, 0, ' + rotateX + ')'
                    });
                }

                var defs = svg.defs();
                var stateColor = 'Blue';

                switch($scope.current_state){
                    case 0:
                        stateColor = 'Green';
                        break;
                    case 1:
                        stateColor = 'Yellow';
                        break;
                    case 2:
                        stateColor = 'Red';
                        break;
                    case 3:
                        stateColor = 'Gray';
                        break;
                    default:
                        stateColor = 'Blue';
                        break;
                }


                svg.linearGradient(defs, 'fadeGreen_' + $scope.item.id, [[0, '#00cc00'], [0.2, '#5BFF5B'], [0.7, '#006600']]);
                svg.linearGradient(defs, 'fadeDarkGreen_' + $scope.item.id, [[0, '#00AD00'], [0.6, '#006600'], [0.7, '#005600']]);

                svg.linearGradient(defs, 'fadeYellow_' + $scope.item.id, [[0, '#FFCC00'], [0.2, '#FFFF5B'], [0.7, '#E5BB00']]);
                svg.linearGradient(defs, 'fadeDarkYellow_' + $scope.item.id, [[0, '#FFAD00'], [0.6, '#E5BB00'], [0.7, '#E2B100']]);

                svg.linearGradient(defs, 'fadeRed_' + $scope.item.id, [[0, '#CE0D00'], [0.2, '#FF0000'], [0.7, '#BF1600']]);
                svg.linearGradient(defs, 'fadeDarkRed_' + $scope.item.id, [[0, '#c91400'], [0.6, '#BF1600'], [0.7, '#BF0600']]);

                svg.linearGradient(defs, 'fadeGray_' + $scope.item.id, [[0.0, '#AFAFAF'], [0.2, '#FFFFFF'], [0.7, '#AFAFAF'], [1.0, '#A0A0A0']], 0, 0, 1);
                svg.linearGradient(defs, 'fadeDarkGray_' + $scope.item.id, [[0.0, '#757575'], [0.2, '#939393'], [1.0, '#757575']]);

                svg.linearGradient(defs, 'fadeBlue_' + $scope.item.id, [[0.0, '#0006D5'], [0.2, '#1248D5'], [0.7, '#0006D5']]);
                svg.linearGradient(defs, 'fadeDarkBlue_' + $scope.item.id, [[0.0, '#000674'], [0.2, '#0006B8'], [1.0, '#000674']]);


                //outer Cylinder
                //bottom ellipse
                svg.ellipse(cylinerGroup, ellipseCx, ellipseBottomCy - 10, rx, ry, {
                    fill: 'url(#fadeDarkGray_' + $scope.item.id + ')',
                    fillOpacity: 0.1,
                    id: 'background_' + $scope.item.id,
                    strokeWidth: 2,
                    stroke: '#CECECE',
                    strokeOpacity: 0.5
                });

                //inner Cylinder (the value)
                //bottom ellipse
                svg.ellipse(cylinerGroup, ellipseCx, ellipseBottomCy - 10, rx, ry, {
                    fill: 'url(#fadeDark' + stateColor + '_' + $scope.item.id + ')',
                    fillOpacity: 0.8

                });
                //center rect
                if(value > 1){
                    svg.rect(cylinerGroup, rectX, newRectY - 10, $scope.width, pxValue + 10, rx, ry, {
                        fill: 'url(#fade' + stateColor + '_' + $scope.item.id + ')',
                        fillOpacity: 0.9
                    });
                    //top ellipse
                    svg.ellipse(cylinerGroup, ellipseCx, newTopEllipseY, rx, ry, {
                        fill: 'url(#fadeDark' + stateColor + '_' + $scope.item.id + ')',
                        fillOpacity: 0.8
                    });
                }
                //outer Cylinder
                //top ellipse
                svg.ellipse(cylinerGroup, ellipseCx, ellipseTopCy, rx, ry, {
                    fill: 'url(#fadeDarkGray_' + $scope.item.id + ')',
                    fillOpacity: 0.0,
                    strokeWidth: 2,
                    stroke: '#CECECE',
                    strokeOpacity: 0.4
                });

                //center rect
                svg.rect(cylinerGroup, rectX, rectY - 10, $scope.width, $scope.height, rx, ry, {
                        fill: 'url(#fadeGray_' + $scope.item.id + ')',
                        fillOpacity: 0.5,
                        id: 'background_' + $scope.item.id,
                        strokeWidth: 2,
                        stroke: '#CECECE',
                        strokeOpacity: 0.3
                    }
                );

            };

            var processPerfdata = function(){
                if($scope.responsePerfdata !== null){
                    if($scope.item.metric !== null && $scope.responsePerfdata.hasOwnProperty($scope.item.metric)){
                        $scope.perfdataName = $scope.item.metric;
                        $scope.perfdata = $scope.responsePerfdata[$scope.item.metric];
                    }else{
                        //Use first metric.
                        for(var metricName in $scope.responsePerfdata){
                            $scope.perfdataName = metricName;
                            $scope.perfdata = $scope.responsePerfdata[metricName];
                            break;
                        }
                    }
                }
                if($scope.perfdata){
                    $scope.perfdata.current = parseFloat($scope.perfdata.current);
                    $scope.perfdata.warning = parseFloat($scope.perfdata.warning);
                    $scope.perfdata.critical = parseFloat($scope.perfdata.critical);
                    $scope.perfdata.min = parseFloat($scope.perfdata.min);
                    $scope.perfdata.max = parseFloat($scope.perfdata.max);
                }
            };

            var initRefreshTimer = function(){
                if($scope.refreshInterval > 0 && $scope.statusUpdateInterval === null){
                    $scope.statusUpdateInterval = $interval(function(){
                        $scope.load();
                    }, $scope.refreshInterval);
                }
            };

            $scope.$watchGroup(['item.size_x', 'item.show_label', 'item.metric'], function(){
                if($scope.init){
                    return;
                }

                $scope.width = $scope.item.size_x - 10; //The view adds 10px
                $scope.height = $scope.item.size_y - 10;

                processPerfdata();
                renderCylinder($scope.perfdata);
            });

            $scope.$watch('item.object_id', function(){
                if($scope.init){
                    return;
                }

                $scope.load();
            });

            $scope.load();
        },

        link: function(scope, element, attr){

        }
    };
});
