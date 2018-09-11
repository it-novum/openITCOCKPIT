angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsAddController', function($scope, $http, QueryStringService){

        /**
         * rows and panels structure with chosen data
         * this one is used for saving in the database
         */
        /*$scope.inputData.data = [
            [//row1
                [//panel1
                    {//Metric1 Panel1
                        hostId: null,
                        serviceId: null,
                        metric: null
                    }
                ]
            ]
        ];
*/
        /**
         * all incoming data like hosts and services from chosen hosts will get written in this Object
         */
        $scope.inputData = {
            data: [
                [//row1
                    [//panel1
                        {//metric1 in panel1
                            hostId: 1,
                            serviceId: 2,
                            metric: null,
                            services: null,
                            metrics: null
                        },
                        {//metric2 in panel1
                            hostId: 2,
                            serviceId: 7,
                            metric: null,
                            services: null,
                            metrics: null
                        }
                    ]
                ]
            ],
            hosts: []
        };


        $scope.addNewRow = function(){
            $scope.inputData.data.push([[]]); //new row array with empty panel array
        };

        $scope.addNewPanel = function(rowKey){
            //maximum 4 panels per row
            if($scope.inputData.data[rowKey].length < 4){
                $scope.inputData.data[rowKey].push([]);
            }else{
                console.info('maximum panels for this row reached');
            }
        };

        $scope.removePanel = function(rowKey, panelKey){
            $scope.inputData.data[rowKey].splice(panelKey);
        };

        $scope.removeRow = function(rowKey){
            $scope.inputData.data.splice(rowKey, 1);
        };

        $scope.removeMetric = function(rowKey, panelKey, metricKey){
            $scope.inputData.data[rowKey][panelKey].splice(metricKey,1);
        };

        $scope.addNewMetric = function(rowKey, panelKey){
            $scope.inputData.data[rowKey][panelKey].push({
                hostId: null,
                serviceId: null,
                metric: null,
                services: null,
                metrics: null
            });
        };


        $scope.loadHosts = function(){
            $http.get("/hosts/loadHostsByString.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.inputData.hosts = result.data.hosts;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        };

        $scope.loadServices = function(hostId, rowKey, panelKey, metricKey){
            if(hostId == null){
                return;
            }

            $http.get("/services/loadServicesByHostId.json", {
                params: {
                    'angular': true,
                    'filter[Host.id]': hostId
                }
            }).then(function(result){
                $scope.inputData.data[rowKey][panelKey][metricKey].services = result.data.services;
            });
        };

        $scope.loadMetrics = function(uuids, rowKey, panelKey, metricKey){
            if(uuids == null){
                return;
            }

            $http.get("/grafana_module/grafana_userdashboards/loadServiceruleFromService.json", {
                params: {
                    'angular': true,
                    'hostUuid': uuids.hostUuid,
                    'serviceUuid': uuids.serviceUuid
                }
            }).then(function(result){
                $scope.inputData.data[rowKey][panelKey][metricKey].metrics = result.data.perfdataStructure;
            });
        };

        $scope.getUuids = function(serviceId, rowKey, panelKey, metricKey){
            var services = $scope.inputData.data[rowKey][panelKey][metricKey].services;
            var ret = {};
            angular.forEach(services, function(v){
                var currentEntry = v.value;
                if(currentEntry.Service.id == serviceId){
                    ret = {
                        serviceUuid: currentEntry.Service.uuid,
                        hostUuid: currentEntry.Host.uuid
                    };
                    return ret;
                }
            });
            return ret;
        };


        $scope.hostSelected = function(hostId, rowKey, panelKey, metricKey){
            $scope.loadServices(hostId, rowKey, panelKey, metricKey);
        };


        $scope.serviceSelected = function(serviceId, rowKey, panelKey, metricKey){
            var uuids = $scope.getUuids(serviceId, rowKey, panelKey, metricKey);
            $scope.loadMetrics(uuids, rowKey, panelKey, metricKey);
        };


        $scope.metricSelected = function(rowKey, panelKey, metricKey){
          /*  console.log('row ' + rowKey);
            console.log('panel ' + panelKey);
            console.log('metric ' + metricKey);
*/
            $scope.saveData();
        };

        $scope.saveData = function(){
            console.log($scope.inputData.data);

            /* $http.get("/services/loadServicesByHostId.json", {
                             params: {
                                 'angular': true,
                                 'filter[Host.id]': hostId
                             }
                         }).then(function(result){
                             $scope.post.metrics[key].services = result.data.services;
                             console.log($scope.post.metrics[key].services);
                         });*/
        };

        $scope.loadHosts();

    });