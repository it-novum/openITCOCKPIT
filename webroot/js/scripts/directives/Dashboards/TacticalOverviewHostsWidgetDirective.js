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

                    $('#HostsKeywordsInput' + $scope.widget.id).tagsinput('add', $scope.filter.Host.keywords);
                    $('#HostsNotKeywordsInput' + $scope.widget.id).tagsinput('add', $scope.filter.Host.not_keywords);

                    $scope.hoststatusSummary = result.data.hoststatusSummary;

                    $scope.loadHostgroups();
                });
            };

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
