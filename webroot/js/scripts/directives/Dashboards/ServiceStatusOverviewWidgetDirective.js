angular.module('openITCOCKPIT').directive('serviceStatusOverviewWidget', function($http, $state){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/serviceStatusOverviewWidget.html',
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

            $scope.serviceStatusOverviewTimeout = null;
            $scope.filter = {
                Servicestatus: {
                    current_state: null,
                    acknowledged: false,
                    not_acknowledged: false,
                    in_downtime: false,
                    not_in_downtime: false,
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
                    $scope.filter.Servicestatus = result.data.config.Servicestatus
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
                        Servicestatus: $scope.filter.Servicestatus,
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

            $scope.goToState = function(){
                var params = {
                    servicename: $scope.filter.Service.name,
                    hostname: $scope.filter.Host.name,
                    servicestate: [$scope.filter.Servicestatus.current_state]
                };


                if($scope.filter.Servicestatus.current_state > 0){
                    if($scope.filter.Servicestatus.acknowledged){
                        params.has_been_acknowledged = 1;
                    }

                    if($scope.filter.Servicestatus.not_acknowledged){
                        params.has_not_been_acknowledged = 1;
                    }

                    if($scope.filter.Servicestatus.in_downtime){
                        params.in_downtime = 1;
                    }

                    if($scope.filter.Servicestatus.not_in_downtime){
                        params.not_in_downtime = 1;
                    }
                }
                $state.go('ServicesIndex', params);
            };
        },

        link:
            function($scope, element, attr){
            }
    };
});
