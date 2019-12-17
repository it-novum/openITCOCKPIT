angular.module('openITCOCKPIT').directive('hostStatusOverviewWidget', function($http, $rootScope, $interval, $httpParamSerializer){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/hostStatusOverviewWidget.html',
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

            $scope.hostsStatusOverviewTimeout = null;
            $scope.filter = {
                Hoststatus: {
                    current_state: null,
                    not_acknowledged: true,
                    not_in_downtime: true
                },
                Host: {
                    name: ''
                }
            };
            $scope.statusCount = null;

            $scope.load = function(){
                $http.get("/dashboards/hostStatusOverviewWidget.json?angular=true&widgetId=" + $scope.widget.id, $scope.filter).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $scope.filter.Hoststatus.current_state = result.data.config.Hoststatus.current_state;
                    $scope.filter.Hoststatus.not_acknowledged = !result.data.config.Hoststatus.problem_has_been_acknowledged;
                    $scope.filter.Hoststatus.not_in_downtime = !result.data.config.Hoststatus.scheduled_downtime_depth;
                    $scope.statusCount = result.data.statusCount;
                    $scope.init = false;
                    $scope.widgetHref = $scope.linkForHostList();
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

                if($scope.hostsStatusOverviewTimeout){
                    clearTimeout($scope.hostsStatusOverviewTimeout);
                }
                $scope.hostsStatusOverviewTimeout = setTimeout(function(){
                    $scope.load();
                }, 500);
            };


            $scope.load();

            $scope.saveHoststatusOverview = function(){
                if($scope.init){
                    return;
                }
                $http.post("/dashboards/hostStatusOverviewWidget.json?angular=true",
                    {
                        Widget: {
                            id: $scope.widget.id
                        },
                        Hoststatus: {
                            current_state: $scope.filter.Hoststatus.current_state,
                            problem_has_been_acknowledged: !$scope.filter.Hoststatus.not_acknowledged,
                            scheduled_downtime_depth: !$scope.filter.Hoststatus.not_in_downtime
                        },
                        Host: {
                            name: $scope.filter.Host.name
                        }
                    }
                ).then(function(result){
                    //Update status
                    $scope.load();
                    $scope.hideConfig();
                });
            };

            $scope.linkForHostList = function(){
                if($scope.init){
                    return;
                }

                var options = {
                    'angular': true,
                    'filter[Host.name]': $scope.filter.Host.name,
                    'has_not_been_acknowledged': ($scope.filter.Hoststatus.not_acknowledged) ? '1' : '0',
                    'not_in_downtime': ($scope.filter.Hoststatus.not_in_downtime) ? '1' : '0'
                };
                var currentState = 'filter[Hoststatus.current_state][' + $scope.filter.Hoststatus.current_state + ']';
                options[currentState] = 1;
                return '/ng/#!/hosts/index/?' + $httpParamSerializer(options);
            };

        },

        link: function($scope, element, attr){

        }
    };
});
