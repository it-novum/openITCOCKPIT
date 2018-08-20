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

            var loadWidgetConfig = function(){
                $http.get("/dashboards/hostStatusOverviewWidget.json?angular=true&widgetId=" + $scope.widget.id, $scope.filter).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $scope.filter.Hoststatus = result.data.config.Hoststatus;
                    console.log($scope.filter.Hoststatus);
               //     $scope.load();
                });
            };

            $scope.load = function(options){

                options = options || {};
                options.save = options.save || false;

                var params = {
                    'angular': true,
                    'filter[Host.name]': $scope.filter.Host.name,
                    'filter[Hoststatus.current_state]': $scope.filter.Hoststatus.current_state
                };

                $http.get("/hosts/index.json", {
                    params: params
                }).then(function(result){
                    $scope.hosts = result.data.all_hosts;

                    if(options.save === true){
                        saveSettings(params);
                    }

                    $scope.init = false;
                });
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


            var saveSettings = function(){
                var settings = $scope.filter;
                settings['scroll_interval'] = $scope.scroll_interval;
                settings['useScroll'] = $scope.useScroll;
                $http.post("/dashboards/hostStatusOverviewWidget.json?angular=true&widgetId=" + $scope.widget.id, settings).then(function(result){
                    return true;
                });
            };

            loadWidgetConfig();

            $scope.$watch('filter', function(){
                $scope.currentPage = 1;
                if($scope.init === true){
                    return true;
                }

                $scope.load({
                    save: true
                });
            }, true);
        },

        link: function($scope, element, attr){

        }
    };
});
