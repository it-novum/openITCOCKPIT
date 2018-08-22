angular.module('openITCOCKPIT').directive('hostStatusOverviewWidget', function($http, $rootScope, $interval){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/hostStatusOverviewWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            var interval;
            $scope.init = true;

            var $widget = $('#widget-' + $scope.widget.id);
            var $widgetContent = $('#widget-content-' + $scope.widget.id);
            //$('#host-status-front-' + $scope.widget.id).css({'height': '100px!important;'});
            //console.log($('#host-status-front-' + $scope.widget.id).height());

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

            $scope.load = function(){
                $http.get("/dashboards/hostStatusOverviewWidget.json?angular=true&widgetId=" + $scope.widget.id, $scope.filter).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $scope.filter.Hoststatus = result.data.config.Hoststatus;
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
                return;
                if($scope.hostListTimeout){
                    clearTimeout($scope.hostListTimeout);
                }
                $scope.hostListTimeout = setTimeout(function(){
                    $scope.load();
                }, 500);
            };


            $scope.load();

            $scope.$watch('filter', function(){
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
                            problem_has_been_acknowledged: $scope.filter.Hoststatus.not_acknowledged,
                            scheduled_downtime_depth: $scope.filter.Hoststatus.not_in_downtime
                        },
                        Host: {
                            name: $scope.filter.Host.name
                        }
                    }
                ).then(function(result){
                    //Update status
                    $scope.load();
                });
            }, true);
        },

        link: function($scope, element, attr){

        }
    };
});
