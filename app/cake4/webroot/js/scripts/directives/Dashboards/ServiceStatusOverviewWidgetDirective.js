angular.module('openITCOCKPIT').directive('serviceStatusOverviewWidget', function($http, $rootScope, $interval, $httpParamSerializer){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/serviceStatusOverviewWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            $scope.init = true;

            var $widget = $('#widget-' + $scope.widget.id);

            $scope.frontWidgetHeight = parseInt(($widget.height() - 59), 10); //-30px header
            $scope.fontSize = $scope.frontWidgetHeight / 2;

            $widget.on('resize', function(event, items){
                hasResize();
            });

            $scope.serviceStatusOverviewTimeout = null;
            $scope.filter = {
                Servicestatus: {
                    current_state: null,
                    not_acknowledged: true,
                    not_in_downtime: true
                },
                Host: {
                    name: ''
                },
                Service: {
                    name: ''
                }
            };
            $scope.statusCount = null;

            $scope.load = function(){
                $http.get("/dashboards/serviceStatusOverviewWidget.json?angular=true&widgetId=" + $scope.widget.id, $scope.filter).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $scope.filter.Service = result.data.config.Service;
                    $scope.filter.Servicestatus.current_state = result.data.config.Servicestatus.current_state;
                    $scope.filter.Servicestatus.not_acknowledged = !result.data.config.Servicestatus.problem_has_been_acknowledged;
                    $scope.filter.Servicestatus.not_in_downtime = !result.data.config.Servicestatus.scheduled_downtime_depth;
                    $scope.statusCount = result.data.statusCount;
                    $scope.init = false;
                    $scope.widgetHref = $scope.linkForServiceList();
                });
            };


            $scope.hideConfig = function(){
                $scope.$broadcast('FLIP_EVENT_IN');
            };
            $scope.showConfig = function(){
                $scope.$broadcast('FLIP_EVENT_OUT');
            };


            var hasResize = function(){
                if($scope.init){
                    return;
                }
                $scope.frontWidgetHeight = parseInt(($widget.height() - 59), 10); //-30px header
                $scope.fontSize = $scope.frontWidgetHeight / 2;

                if($scope.serviceStatusOverviewTimeout){
                    clearTimeout($scope.serviceStatusOverviewTimeout);
                }
                $scope.serviceStatusOverviewTimeout = setTimeout(function(){
                    $scope.load();
                }, 500);
            };


            $scope.load();

            $scope.saveServicestatusOverview = function(){
                if($scope.init){
                    return;
                }
                $http.post("/dashboards/serviceStatusOverviewWidget.json?angular=true",
                    {
                        Widget: {
                            id: $scope.widget.id
                        },
                        Servicestatus: {
                            current_state: $scope.filter.Servicestatus.current_state,
                            problem_has_been_acknowledged: !$scope.filter.Servicestatus.not_acknowledged,
                            scheduled_downtime_depth: !$scope.filter.Servicestatus.not_in_downtime
                        },
                        Host: {
                            name: $scope.filter.Host.name
                        },
                        Service: {
                            name: $scope.filter.Service.name
                        }
                    }
                ).then(function(result){
                    //Update status
                    $scope.load();
                    $scope.hideConfig();
                });
            };

            $scope.linkForServiceList = function(){
                if($scope.init){
                    return;
                }

                var options = {
                    'angular': true,
                    'filter[Host.name]': $scope.filter.Host.name,
                    'filter[Service.servicename]': $scope.filter.Service.name,
                    'has_not_been_acknowledged': ($scope.filter.Servicestatus.not_acknowledged) ? '1' : '0',
                    'not_in_downtime': ($scope.filter.Servicestatus.not_in_downtime) ? '1' : '0'
                };
                var currentState = 'filter[Servicestatus.current_state][' + $scope.filter.Servicestatus.current_state + ']';
                options[currentState] = 1;
                return '/ng/#!/services/index/?' + $httpParamSerializer(options);
            };
        },

        link:
            function($scope, element, attr){
            }
    };
});
