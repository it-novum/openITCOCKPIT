angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsAddController', function($scope, $http, QueryStringService){

        $scope.metrics = [
            {
                id:'metric1',
                hostId:1,
                serviceId:2,
                metric:null
            },
            {
                id:'metric2',
                hostId:null,
                serviceId:null,
                metric:null
            }
        ];

        $scope.addNewMetric = function(){
            var nmbr = $scope.metrics.length+1;
            $scope.metrics.push({'id':'metric'+nmbr});
        };

        $scope.loadHosts = function(){
            $http.get("/hosts/loadHostsByString.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
             //   console.log($scope.hosts);
                //$scope.config = result.data.grafanaConfiguration;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        };

        $scope.loadServices = function(){
            $http.get("/services/loadServicesByHostId.json", {
                params: {
                    'angular': true,
                    'filter[Host.id]': 1
                }
            }).then(function(result){
                $scope.services = result.data.services;
              //  console.log($scope.services);
            });
        };
//c36b8048-93ce-4385-ac19-ab5c90574b77
        //74f14950-a58f-4f18-b6c3-5cfa9dffef4e

        $scope.loadMetrics = function(){
            $http.get("/grafana_module/grafana_userdashboards/loadServiceruleFromService.json", {
                params: {
                    'angular': true,
                    'hostUuid': 'c36b8048-93ce-4385-ac19-ab5c90574b77',
                    'serviceUuid': '74fd8f59-1348-4e16-85f0-4a5c57c7dd62'
                }
            }).then(function(result){
                $scope.perfdata = result.data.perfdataStructure;
              //  console.log($scope.perfdata);
            });
        };


        $scope.$watch('metrics', function(){
           console.log($scope.metrics);
        });

        $scope.$watch('hosts', function(){
            $scope.loadServices();
        });
        $scope.$watch('services', function(){
            $scope.loadMetrics();
        });

        $scope.loadHosts();

    });