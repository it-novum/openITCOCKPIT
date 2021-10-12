angular.module('openITCOCKPIT').directive('tacticalOverviewServicesWidget', function($http){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/tacticalOverviewServicesWidget.html',
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
                },
                Service: {
                    servicename: '',
                    keywords: '',
                    not_keywords: ''
                },
                Servicegroup: {
                    _ids: []
                }
            };

            var loadWidgetConfig = function(){
                $http.get("/dashboards/tacticalOverviewWidget.json", {
                    params: {
                        'angular': true,
                        'widgetId': $scope.widget.id,
                        'type': 'services'
                    }
                }).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $scope.filter.Service = result.data.config.Service;
                    $scope.filter.Servicegroup._ids = result.data.config.Servicegroup._ids.split(',').map(Number);

                    $scope.servicestatusSummary = result.data.servicestatusSummary;
                    $scope.loadServicegroups();
                });
            };

            $scope.loadServicegroups = function(searchString){
                var selected = [];

                if($scope.filter.Servicegroup._ids){
                    selected = $scope.filter.Servicegroup._ids;
                }

                $http.get("/servicegroups/loadServicegroupsByString.json", {
                    params: {
                        'angular': true,
                        'filter[Containers.name]': searchString,
                        'selected[]': selected
                    }
                }).then(function(result){
                    $scope.servicegroups = result.data.servicegroups;
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
