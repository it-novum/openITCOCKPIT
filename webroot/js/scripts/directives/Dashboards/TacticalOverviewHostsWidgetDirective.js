angular.module('openITCOCKPIT').directive('tacticalOverviewHostsWidget', function($http){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/tacticalOverviewHostsWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            $scope.interval = null;
            $scope.init = true;


            var $widget = $('#widget-' + $scope.widget.id);

            $widget.on('resize', function(event, items){
                hasResize();
            });

            $scope.hostListTimeout = null;


            $scope.filter = {
                Host: {
                    name: '',
                    keywords: '',
                    not_keywords: '',
                    address: ''
                }
            };

            var loadWidgetConfig = function(){
                $http.get("/dashboards/tacticalOverviewHostsWidget.json?angular=true&widgetId=" + $scope.widget.id, $scope.filter).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $scope.load();
                });
            };


            $scope.load = function(options){

                options = options || {};
                options.save = options.save || false;


                var params = {
                    'angular': true,
                    'filter[Hosts.name]': $scope.filter.Host.name,
                    'filter[Hosts.keywords][]': $scope.filter.Host.keywords.split(','),
                    'filter[Hosts.not_keywords][]': $scope.filter.Host.not_keywords.split(','),
                    'filter[Hosts.address]': $scope.filter.Host.address
                };

                $http.get("/hosts/index.json", {
                    params: params
                }).then(function(result){
                    $scope.hosts = result.data.all_hosts;
                    $scope.scroll = result.data.scroll;

                    if(options.save === true){
                        $scope.saveSettings(params);
                    }

                    $scope.init = false;
                });
            };


            $scope.saveSettings = function(){
                var settings = $scope.filter;
                $http.post("/dashboards/tacticalOverviewHostsWidget.json?angular=true&widgetId=" + $scope.widget.id, settings).then(function(result){
                    $scope.currentPage = 1;
                    loadWidgetConfig();
                    $scope.hideConfig();
                    if($scope.init === true){
                        return true;
                    }
                    return true;
                });
            };


            $scope.hideConfig = function(){
                $scope.$broadcast('FLIP_EVENT_IN');
            };
            $scope.showConfig = function(){
                $scope.$broadcast('FLIP_EVENT_OUT');
                $scope.load();
            };


            loadWidgetConfig();

            jQuery(function(){
                $("input[data-role=tagsinput]").tagsinput();
            });
        },

        link: function($scope, element, attr){
        }
    };
});
