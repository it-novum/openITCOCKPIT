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
            $scope.hoststatusSummary = null;

            var $widget = $('#widget-' + $scope.widget.id);

            $scope.hostListTimeout = null;


            $scope.filter = {
                Host: {
                    name: '',
                    keywords: '',
                    not_keywords: '',
                    address: ''
                },
                Hostgroup: {
                    _ids: []
                }
            };

            var loadWidgetConfig = function(){
                $http.get("/dashboards/tacticalOverviewWidget.json", {
                    params: {
                        'angular': true,
                        'widgetId': $scope.widget.id,
                        'type': 'hosts'
                    }
                }).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $scope.filter.Hostgroup._ids = result.data.config.Hostgroup._ids.split(',').map(Number);

                    $scope.hoststatusSummary = result.data.hoststatusSummary;
                    $scope.loadHostgroups();
                    //$scope.load();
                });
            };

/*
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

 */

            $scope.loadHostgroups = function(searchString){
                var selected = [];

                if($scope.filter.Hostgroup._ids){
                    selected = $scope.filter.Hostgroup._ids;
                }

                $http.get("/hostgroups/loadHostgroupsByString.json", {
                    params: {
                        'angular': true,
                        'filter[Containers.name]': searchString,
                        'selected[]': selected
                    }
                }).then(function(result){
                    $scope.hostgroups = result.data.hostgroups;
                });
            };


            $scope.saveSettings = function(){
                var settings = $scope.filter;
                $http.post("/dashboards/tacticalOverviewWidget.json?angular=true&widgetId=" + $scope.widget.id, settings).then(function(result){
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
