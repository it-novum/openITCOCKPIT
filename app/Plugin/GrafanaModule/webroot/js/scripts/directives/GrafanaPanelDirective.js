angular.module('openITCOCKPIT').directive('grafanaPanel', function($http){
    return {
        restrict: 'E',
        templateUrl: '/grafana_module/grafana_userdashboards/grafanaPanel.html',
        scope: {
            'panel': '=',
            'panelId': '=',
            'rowId': '='
        },
        controller: function($scope){

            $scope.currentServiceId = null;

            $scope.addMetric = function(){
                loadServices(''); //Load initial services

                console.log($scope.rowId);
                console.log($scope.panelId);
            };

            //Called when entered search string
            $scope.loadMoreServices = function(searchString){
                loadServices(searchString);
            };

            var loadServices = function(searchString, selected){
                if(typeof selected === "undefined"){
                    selected = [];
                }

                $http.get("/services/loadServicesByString.json", {
                    params: {
                        'angular': true,
                        'filter[Host.name]': searchString,
                        'filter[Service.servicename]': searchString,
                        'selected[]': selected
                    }
                }).then(function(result){

                    var tmpServices = [];
                    for(var i in result.data.services){
                        var tmpService = result.data.services[i];

                        var serviceName = tmpService.value.Service.name;
                        if(serviceName === null || serviceName === ''){
                            serviceName = tmpService.value.Servicetemplate.name;
                        }

                        tmpServices.push({
                            key: tmpService.key,
                            value: tmpService.value.Host.name + '/' + serviceName
                        });

                    }

                    $scope.services = tmpServices;
                    $('#addMetricToPanelModal_' + $scope.rowId + '_' + $scope.panelId).modal('show');
                });
            };

            var loadMetrics = function(){
                if($scope.currentServiceId === null){
                    return;
                }

                $http.get("/grafana_module/grafana_userdashboards/getPerformanceDataMetrics/" + $scope.currentServiceId + ".json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    var metrics = {};

                    var firstMetric = null;

                    for(var metricName in result.data.perfdata){
                        if(firstMetric === null){
                            firstMetric = metricName;
                        }

                        metrics[metricName] = metricName;
                    }

                    if($scope.metric === null){
                        $scope.metric = firstMetric;
                    }

                    $scope.metrics = metrics;
                });
            };

            $scope.$watch('currentServiceId', function(){
                loadMetrics();
            });

        },

        link: function($scope, element, attr){
        }
    };
});
