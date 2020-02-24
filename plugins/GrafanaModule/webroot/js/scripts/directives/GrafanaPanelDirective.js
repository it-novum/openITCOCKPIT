angular.module('openITCOCKPIT').directive('grafanaPanel', function($http){
    return {
        restrict: 'E',
        templateUrl: '/grafana_module/grafana_userdashboards/grafanaPanel.html',
        scope: {
            'id': '=',
            'panel': '=',
            'panelId': '=',
            'removeCallback': '=',
            'grafanaUnits': '=',
            'containerId': '='
        },
        controller: function($scope){

            $scope.currentServiceId = null;
            $scope.currentServiceMetric = null;
            $scope.rowId = parseInt($scope.panel.row, 10);
            $scope.init = true;
            $scope.humanUnit = '';

            $scope.addMetric = function(){
                $scope.currentServiceId = null;
                $scope.currentServiceMetric = null;
                loadServices(''); //Load initial services
            };

            //Called when entered search string
            $scope.loadMoreServices = function(searchString){
                loadServices(searchString);
            };

            $scope.saveMetric = function(){

                var data = {
                    GrafanaUserdashboardMetric: {
                        row: parseInt($scope.rowId, 10), //int
                        panel_id: parseInt($scope.panelId, 10), //int
                        service_id: $scope.currentServiceId, //int
                        metric: $scope.currentServiceMetric, //String
                        userdashboard_id: $scope.id //int
                    }
                };

                $http.post("/grafana_module/grafana_userdashboards/addMetricToPanel.json?angular=true", data
                ).then(function(result){
                    $scope.errors = {};

                    if(result.data.hasOwnProperty('metric')){
                        new Noty({
                            theme: 'metroui',
                            type: 'success',
                            text: 'Metric added successfully',
                            timeout: 3500
                        }).show();

                        $scope.panel.metrics.push(result.data.metric);
                        $('#addMetricToPanelModal_' + $scope.rowId + '_' + $scope.panelId).modal('hide');
                    }

                }, function errorCallback(result){
                    new Noty({
                        theme: 'metroui',
                        type: 'error',
                        text: 'Error while adding metric',
                        timeout: 3500
                    }).show();

                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });

            };

            $scope.removeMetric = function(metric){
                $http.post("/grafana_module/grafana_userdashboards/removeMetricFromPanel.json?angular=true",
                    {
                        id: parseInt(metric.id, 10)
                    }
                ).then(function(result){
                    $scope.errors = {};

                    if(result.data.success){
                        new Noty({
                            theme: 'metroui',
                            type: 'success',
                            text: 'Metric removed successfully',
                            timeout: 3500
                        }).show();
                        removeMetricFromPanel(metric.id);
                    }else{
                        new Noty({
                            theme: 'metroui',
                            type: 'error',
                            text: 'Error while removing metric',
                            timeout: 3500
                        }).show();
                    }

                }, function errorCallback(result){
                    new Noty({
                        theme: 'metroui',
                        type: 'error',
                        text: 'Error while removing metric',
                        timeout: 3500
                    }).show();

                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
            };


            var loadServices = function(searchString, selected){
                if($scope.currentServiceId){
                    selected = [$scope.currentServiceId];
                }

                if(typeof selected === "undefined"){
                    selected = [];
                }

                $http.get("/services/loadServicesByContainerId.json", {
                    params: {
                        'angular': true,
                        'filter[Hostx.name]': searchString,
                        'filter[servicename]': searchString,
                        'selected[]': selected,
                        'containerId': $scope.containerId,
                        'recursive': true
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

            $scope.removePanel = function(){
                //Call callback from parent scrope (GrafanaRowDirective)
                $scope.removeCallback($scope.panelId);
            };

            $scope.openPanelOptions = function(){
                $('#panelOptionsModal_' + $scope.rowId + '_' + $scope.panelId).modal('show');
            };

            var removeMetricFromPanel = function(metricId){
                var metrics = [];
                metricId = parseInt(metricId, 10);
                for(var i in $scope.panel.metrics){
                    if(parseInt($scope.panel.metrics[i].id, 10) !== metricId){
                        metrics.push($scope.panel.metrics[i]);
                    }
                }
                $scope.panel.metrics = metrics;
            };

            var savePanelOptions = function(){
                $http.post("/grafana_module/grafana_userdashboards/savePanelUnit.json?angular=true",
                    {
                        id: parseInt($scope.panel.id, 10),
                        unit: $scope.panel.unit,
                        title: $scope.panel.title
                    }
                ).then(function(result){
                    if(result.data.success){
                        new Noty({
                            theme: 'metroui',
                            type: 'success',
                            text: 'Panel unit saved successfully',
                            timeout: 3500
                        }).show();
                    }else{
                        new Noty({
                            theme: 'metroui',
                            type: 'error',
                            text: 'Error while saving panel unit',
                            timeout: 3500
                        }).show();
                    }

                }, function errorCallback(result){
                    new Noty({
                        theme: 'metroui',
                        type: 'error',
                        text: 'Error while saving panel unit',
                        timeout: 3500
                    }).show();
                });
            };

            $scope.$watch('currentServiceId', function(){
                loadMetrics();
            });

            $scope.$watchGroup(['panel.unit', 'panel.title'], function(){
                for(var i in $scope.grafanaUnits){
                    if($scope.grafanaUnits[i].hasOwnProperty($scope.panel.unit)){
                        $scope.humanUnit = $scope.grafanaUnits[i][$scope.panel.unit];

                        if($scope.humanUnit === 'None'){

                            //Dont show none
                            $scope.humanUnit = '';
                        }
                    }
                }

                if($scope.init){
                    $scope.init = false;
                    return;
                }
                savePanelOptions();
            });

        },

        link: function($scope, element, attr){
        }
    };
});
