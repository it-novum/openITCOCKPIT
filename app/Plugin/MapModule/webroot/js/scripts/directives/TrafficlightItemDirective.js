angular.module('openITCOCKPIT').directive('trafficlightItem', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors/trafficlight.html',
        scope: {
            'item': '=',
            'refreshInterval': '='
        },
        controller: function($scope){
            $scope.init = true;
            $scope.statusUpdateInterval = null;

            $scope.width = 60;
            $scope.height = 150;

            $scope.item.size_x = parseInt($scope.item.size_x, 10);
            $scope.item.size_y = parseInt($scope.item.size_y, 10);


            if($scope.item.size_x > 0){
                $scope.width = $scope.item.size_x;
            }
            if($scope.item.size_y > 0){
                $scope.height = $scope.item.size_y;
            }

            var timer = {
                red: null,
                yellow: null,
                green: null
            };


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
                    $scope.is_flapping = result.data.data.Servicestatus.isFlapping;

                    $scope.Host = result.data.data.Host;
                    $scope.Service = result.data.data.Service;

                    $scope.showGreen = false;
                    $scope.showYellow = false;
                    $scope.showRed = false;
                    $scope.showBlue = false;
                    $scope.blink = false;

                    stopBlinking();
                    switch($scope.current_state){
                        case 0:
                            $scope.showGreen = true;
                            break;
                        case 1:
                            $scope.showYellow = true;
                            break;
                        case 2:
                            $scope.showRed = true;
                            break;
                        case 3:
                            $scope.showGreen = true;
                            $scope.showYellow = true;
                            $scope.showRed = true;
                            break;
                        default:
                            $scope.showBlue = true;
                            break;
                    }

                    if($scope.is_flapping){
                        $scope.blink = true;
                    }

                    renderTrafficlight();

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

            var blinking = function(el, color){
                //set the animation interval high to prevent high CPU usage
                //the animation isnt that smooth anymore but the browser need ~70% less CPU!

                $.fx.interval = 100;

                if(timer[color] !== null){
                    clearInterval(timer[color]);
                    timer[color] = null;
                }

                timer[color] = setInterval(function(){
                    $(el).fadeOut(2000, function(){
                        $(el).fadeIn(2000);
                    });
                }, 6000);
            };

            var stopBlinking = function(){
                for(var i in timer){
                    if(timer[i] !== null){
                        clearInterval(timer[i]);
                        timer[i] = null;
                    }
                }
            };

            var renderTrafficlight = function(){
                var $trafficlight = $('#map-trafficlight-' + $scope.item.id);
                $trafficlight.svg('destroy');

                $trafficlight.svg({
                    settings: {
                        width: $scope.width,
                        height: $scope.height
                    }
                });
                var svg = $trafficlight.svg('get');


                // 17px was the old radius of the static traffic light.
                // We calucate this value on the fly to be able to resize the traffic light
                var lightRadius = parseInt(($scope.width * (17 / 60)));
                var lightDiameter = (lightRadius * 2) + 2; //2 is the stroke width
                var lightPadding = Math.ceil(($scope.height - lightDiameter * 3) / 4);
                var x = parseInt(($scope.width / 2), 10);

                //main group
                var trafficLight = svg.group('trafficLight_' + $scope.item.id);

                //Traffic Light background group
                var tLBackground = svg.group(trafficLight, 'trafficLightBackground_' + $scope.item.id);

                //style definitions for the Traffic light
                var defs = svg.defs();
                //background gradient
                svg.linearGradient(defs, 'tlBg_' + $scope.item.id, [[0.02, '#323232'], [0.02, '#323232'], [0.03, '#333'], [0.3, '#323232']], 0, 0, 0, 1);

                svg.linearGradient(defs, 'protectorGradient_' + $scope.item.id, [[0, '#555'], [0.03, '#444'], [0.07, '#333'], [0.12, '#222']], 0, 0, 0, 1);

                //red light gradient
                svg.radialGradient(defs, 'redLight_' + $scope.item.id, [['0%', 'brown'], ['25%', 'transparent']], 1, 1, 4, 0, 0, {
                    gradientUnits: 'userSpaceOnUse'
                });
                //yellow light gradient
                svg.radialGradient(defs, 'yellowLight_' + $scope.item.id, [['0%', 'orange'], ['25%', 'transparent']], 1, 1, 4, 0, 0, {
                    gradientUnits: 'userSpaceOnUse'
                });
                //green light gradient
                svg.radialGradient(defs, 'greenLight_' + $scope.item.id, [['0%', 'lime'], ['25%', 'transparent']], 1, 1, 4, 0, 0, {
                    gradientUnits: 'userSpaceOnUse'
                });


                //the main background for the traffic light where the lights are placed
                svg.rect(tLBackground, 0, 0, $scope.width, $scope.height, 10, 10, {
                    fill: 'url(#tlBg_' + $scope.item.id + ')', stroke: '#444', strokeWidth: 2
                });

                if($scope.item.show_label){
                    var rotateX = parseInt(($scope.height - 10 - ($scope.width / 8)), 10); //10 is svg padding 16 is font size;
                    svg.text(tLBackground, 0, $scope.height - 10, ($scope.Host.hostname + '/' + $scope.Service.servicename), {
                        fontSize: ($scope.width / 8),
                        fontFamily: 'Verdana',
                        fill: '#FFF',
                        transform: 'rotate(-90, 0, ' + rotateX + ')'
                    });
                }

                //pattern which are the small green, red and yellow "Dots" within a light
                //red pattern
                var redPattern;
                redPattern = svg.pattern(defs, 'redLightPattern_' + $scope.item.id, 0, 0, 3, 3, {
                    patternUnits: 'userSpaceOnUse'
                });
                //pattern circle
                svg.circle(redPattern, 1, 1, 3, {
                    fill: 'url(#redLight_' + $scope.item.id + ')'
                });

                //yellow pattern
                redPattern = svg.pattern(defs, 'yellowLightPattern_' + $scope.item.id, 0, 0, 3, 3, {
                    patternUnits: 'userSpaceOnUse'
                });
                //pattern circle
                svg.circle(redPattern, 1, 1, 3, {
                    fill: 'url(#yellowLight_' + $scope.item.id + ')'
                });

                //green pattern
                redPattern = svg.pattern(defs, 'greenLightPattern_' + $scope.item.id, 0, 0, 3, 3, {
                    patternUnits: 'userSpaceOnUse'
                });
                //pattern circle
                svg.circle(redPattern, 1, 1, 3, {
                    fill: 'url(#greenLight_' + $scope.item.id + ')'
                });

                //main group for the lights
                var lights = svg.group(trafficLight, 'lights_' + $scope.item.id);

                var redLightGroup = svg.group(lights, 'redLightGroup_' + $scope.item.id);
                if($scope.showRed){
                    //red background
                    var redLight = svg.circle(redLightGroup, x, lightPadding + lightRadius, lightRadius, {
                        fill: '#f00'
                    });
                    if($scope.blink){
                        blinking(redLight, 'red');
                    }
                }
                //red
                svg.circle(redLightGroup, x, lightPadding + lightRadius, lightRadius, {
                    fill: 'url(#redLightPattern_' + $scope.item.id + ')', stroke: '#444', strokeWidth: 2
                });
                var yellowLightGroup = svg.group(lights, 'yellowLightGroup_' + $scope.item.id);
                if($scope.showYellow){
                    //yellow background
                    var yellowLight = svg.circle(yellowLightGroup, x, lightDiameter + lightPadding * 2 + lightRadius, lightRadius, {
                        fill: '#FFFF00'
                    });
                    if($scope.blink){
                        blinking(yellowLight, 'yellow');
                    }
                }
                //yellow
                svg.circle(yellowLightGroup, x, lightDiameter + lightPadding * 2 + lightRadius, lightRadius, {
                    fill: 'url(#yellowLightPattern_' + $scope.item.id + ')', stroke: '#444', strokeWidth: 2
                });

                var blueLightGroup = svg.group(lights, 'blueLightGroup_' + $scope.item.id);
                if($scope.showBlue){
                    //yellow background
                    var blueLight = svg.circle(blueLightGroup, x, lightDiameter + lightPadding * 2 + lightRadius, lightRadius, {
                        fill: '#6e99ff'
                    });
                }


                var greenLightGroup = svg.group(lights, 'greenLightGroup_' + $scope.item.id);
                if($scope.showGreen){
                    //green background
                    var greenLight = svg.circle(greenLightGroup, x, lightDiameter * 2 + lightPadding * 3 + lightRadius, lightRadius, {
                        fill: '#0F0'
                    });
                    if($scope.blink){
                        blinking(greenLight, 'green');
                    }
                }
                //green
                svg.circle(greenLightGroup, x, lightDiameter * 2 + lightPadding * 3 + lightRadius, lightRadius, {
                    fill: 'url(#greenLightPattern_' + $scope.item.id + ')', stroke: '#444', strokeWidth: 2
                });
            };

            var initRefreshTimer = function(){
                if($scope.refreshInterval > 0 && $scope.statusUpdateInterval === null){
                    $scope.statusUpdateInterval = $interval(function(){
                        $scope.load();
                    }, $scope.refreshInterval);
                }
            };

            $scope.$watchGroup(['item.size_x', 'item.show_label'], function(){
                if($scope.init){
                    return;
                }

                $scope.width = $scope.item.size_x - 10; //The view adds 10px
                $scope.height = $scope.item.size_y - 10;
                renderTrafficlight();
            });

            $scope.$watch('item.object_id', function(){
                if($scope.init || $scope.item.object_id === null){
                    //Avoid ajax error if user search a service in Gadget config modal
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
