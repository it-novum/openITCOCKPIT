angular.module('openITCOCKPIT').directive('trafficlightWidget', function($http){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/trafficLightWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){

            /** public vars **/
            $scope.init = true;
            $scope.trafficlightTimeout = null;
            $scope.trafficlight = {
                service_id: null,
                show_label: false
            };

            /** private vars **/
            var $widget = $('#widget-' + $scope.widget.id);
            var $widgetContent = $('#widget-content-' + $scope.widget.id);
            var timer = {
                red: null,
                yellow: null,
                green: null
            };

            //Calc dimensions
            var offset = 50;
            $scope.height = $widgetContent.height() - offset;
            $scope.width = $scope.height / 2.5;

            $scope.load = function(){
                $http.get("/dashboards/trafficLightWidget.json", {
                    params: {
                        'angular': true,
                        'widgetId': $scope.widget.id
                    }
                }).then(function(result){
                    if(Object.keys(result.data.service.Service).length > 0){
                        $scope.Service = result.data.service.Service;
                        $scope.Host = result.data.service.Host;
                        $scope.ACL = result.data.ACL;

                        $scope.trafficlight.show_label = result.data.config.show_label;
                        //User has permissions for this service / a services was selected
                        $scope.trafficlight.service_id = result.data.service.Service.id;


                        $scope.current_state = result.data.service.Servicestatus.currentState;
                        $scope.is_flapping = result.data.service.Servicestatus.isFlapping;

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

                        $scope.trafficlightHref = getHref();
                        renderTrafficlight();
                    }else{
                        //Avoid undefined errors
                        $scope.Service = {
                            hostname: 'Unknown host',
                            servicename: 'Unknown service'
                        }
                    }

                    //Do not trigger watch on page load
                    setTimeout(function(){
                        $scope.init = false;
                    }, 250);
                });
            };

            $scope.loadServices = function(searchString){
                $http.get("/services/loadServicesByString.json", {
                    params: {
                        'angular': true,
                        //'filter[Hosts.name]': searchString,
                        'filter[servicename]': searchString,
                        'selected[]': $scope.trafficlight.service_id
                    }
                }).then(function(result){
                    $scope.services = result.data.services;
                });
            };

            $scope.hideConfig = function(){
                $scope.$broadcast('FLIP_EVENT_IN');
                renderTrafficlight();
            };
            $scope.showConfig = function(){
                $scope.$broadcast('FLIP_EVENT_OUT');
                $scope.loadServices('');
            };

            $scope.saveTrafficlight = function(){
                $http.post("/dashboards/trafficLightWidget.json?angular=true",
                    {
                        Widget: {
                            id: $scope.widget.id,
                            service_id: $scope.trafficlight.service_id
                        },
                        show_label: $scope.trafficlight.show_label
                    }
                ).then(function(result){
                    //Update status
                    $scope.load();
                    $scope.hideConfig();
                });
            };

            var getHref = function(){
                var url = 'javascript:void(0);';

                if($scope.Service.isEVCService){
                    if($scope.ACL.evc.view){
                        return '/eventcorrelation_module/eventcorrelations/view/' + $scope.Host.id
                    }

                    if($scope.ACL.services.index){
                        return '/ng/#!/services/browser/' + $scope.Service.id
                    }
                }else{
                    if($scope.ACL.services.index){
                        return '/ng/#!/services/browser/' + $scope.Service.id
                    }
                }


                return url;
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
                var $trafficlight = $('#trafficlight-' + $scope.widget.id);
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
                var trafficLight = svg.group('trafficLight_' + $scope.widget.id);

                //Traffic Light background group
                var tLBackground = svg.group(trafficLight, 'trafficLightBackground');

                //style definitions for the Traffic light
                var defs = svg.defs();
                //background gradient
                svg.linearGradient(defs, 'tlBg', [[0.02, '#323232'], [0.02, '#323232'], [0.03, '#333'], [0.3, '#323232']], 0, 0, 0, 1);

                svg.linearGradient(defs, 'protectorGradient', [[0, '#555'], [0.03, '#444'], [0.07, '#333'], [0.12, '#222']], 0, 0, 0, 1);

                //red light gradient
                svg.radialGradient(defs, 'redLight', [['0%', 'brown'], ['25%', 'transparent']], 1, 1, 4, 0, 0, {
                    gradientUnits: 'userSpaceOnUse'
                });
                //yellow light gradient
                svg.radialGradient(defs, 'yellowLight', [['0%', 'orange'], ['25%', 'transparent']], 1, 1, 4, 0, 0, {
                    gradientUnits: 'userSpaceOnUse'
                });
                //green light gradient
                svg.radialGradient(defs, 'greenLight', [['0%', 'lime'], ['25%', 'transparent']], 1, 1, 4, 0, 0, {
                    gradientUnits: 'userSpaceOnUse'
                });


                //the main background for the traffic light where the lights are placed
                svg.rect(tLBackground, 0, 0, $scope.width, $scope.height, 10, 10, {
                    fill: 'url(#tlBg)', stroke: '#444', strokeWidth: 2
                });

                if($scope.trafficlight.show_label){
                    var rotateX = parseInt(($scope.height - 10 - ($scope.width / 8)), 10); //10 is svg padding 16 is font size;
                    svg.text(tLBackground, 0, $scope.height - 10, ($scope.Service.hostname + '/' + $scope.Service.servicename), {
                        fontSize: ($scope.width / 8),
                        fontFamily: 'Verdana',
                        fill: '#FFF',
                        transform: 'rotate(-90, 0, ' + rotateX + ')'
                    });
                }

                //pattern which are the small green, red and yellow "Dots" within a light
                //red pattern
                var redPattern;
                redPattern = svg.pattern(defs, 'redLightPattern', 0, 0, 3, 3, {
                    patternUnits: 'userSpaceOnUse'
                });
                //pattern circle
                svg.circle(redPattern, 1, 1, 3, {
                    fill: 'url(#redLight)'
                });

                //yellow pattern
                redPattern = svg.pattern(defs, 'yellowLightPattern', 0, 0, 3, 3, {
                    patternUnits: 'userSpaceOnUse'
                });
                //pattern circle
                svg.circle(redPattern, 1, 1, 3, {
                    fill: 'url(#yellowLight)'
                });

                //green pattern
                redPattern = svg.pattern(defs, 'greenLightPattern', 0, 0, 3, 3, {
                    patternUnits: 'userSpaceOnUse'
                });
                //pattern circle
                svg.circle(redPattern, 1, 1, 3, {
                    fill: 'url(#greenLight)'
                });

                //main group for the lights
                var lights = svg.group(trafficLight, 'lights');

                var redLightGroup = svg.group(lights, 'redLightGroup');
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
                    fill: 'url(#redLightPattern)', stroke: '#444', strokeWidth: 2
                });
                var yellowLightGroup = svg.group(lights, 'yellowLightGroup');
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
                    fill: 'url(#yellowLightPattern)', stroke: '#444', strokeWidth: 2
                });

                var blueLightGroup = svg.group(lights, 'blueLightGroup');
                if($scope.showBlue){
                    //yellow background
                    var blueLight = svg.circle(blueLightGroup, x, lightDiameter + lightPadding * 2 + lightRadius, lightRadius, {
                        fill: '#6e99ff'
                    });
                }


                var greenLightGroup = svg.group(lights, 'greenLightGroup');
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
                    fill: 'url(#greenLightPattern)', stroke: '#444', strokeWidth: 2
                });
            };


            var hasResize = function(){
                if($scope.init){
                    return;
                }

                if($scope.trafficlightTimeout){
                    clearTimeout($scope.trafficlightTimeout);
                }
                $scope.trafficlightTimeout = setTimeout(function(){
                    $scope.trafficlightTimeout = null;
                }, 500);

                $scope.height = $widgetContent.height() - offset;
                $scope.width = $scope.height / 2.5;
                renderTrafficlight();
            };


            /** Page load / widget get loaded **/

            //Add jQuery resize callback
            $widget.on('resize', function(event, items){
                hasResize();
            });

            $scope.load();

        },

        link: function($scope, element, attr){

        }
    };
});
