angular.module('openITCOCKPIT').directive('hostStatusOverviewWidget', function($http, $state){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/hostStatusOverviewWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            $scope.init = true;

            var $widget = $('#widget-' + $scope.widget.id);

            $scope.frontWidgetHeight = parseInt(($widget.height()), 10);

            $scope.fontSize = $scope.frontWidgetHeight / 2;

            $widget.on('resize', function(event, items){
                hasResize();
            });

            $scope.hostsStatusOverviewTimeout = null;
            $scope.filter = {
                Hoststatus: {
                    current_state: null,
                    acknowledged: false,
                    not_acknowledged: false,
                    in_downtime: false,
                    not_in_downtime: false,
                },
                Host: {
                    name: ''
                }
            };
            $scope.statusCount = null;

            $scope.load = function(){
                $http.get("/dashboards/hostStatusOverviewWidget.json?angular=true&widgetId=" + $scope.widget.id, $scope.filter).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $scope.filter.Hoststatus = result.data.config.Hoststatus;
                    $scope.statusCount = result.data.statusCount;
                    $scope.init = false;
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
                $scope.frontWidgetHeight = parseInt(($widget.height()), 10);
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
                        Hoststatus: $scope.filter.Hoststatus,
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

            $scope.goToState = function(){
                var params = {
                    hostname: $scope.filter.Host.name,
                    hoststate: [$scope.filter.Hoststatus.current_state]
                };

                if($scope.filter.Hoststatus.current_state > 0){
                    if($scope.filter.Hoststatus.acknowledged){
                        params.has_been_acknowledged = 1;
                    }

                    if($scope.filter.Hoststatus.not_acknowledged){
                        params.has_not_been_acknowledged = 1;
                    }

                    if($scope.filter.Hoststatus.in_downtime){
                        params.in_downtime = 1;
                    }

                    if($scope.filter.Hoststatus.not_in_downtime){
                        params.not_in_downtime = 1;
                    }
                }


                $state.go('HostsIndex', params);
            };

        },

        link: function($scope, element, attr){

        }
    };
});
