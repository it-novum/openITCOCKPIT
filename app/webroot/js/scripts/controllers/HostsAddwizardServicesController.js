angular.module('openITCOCKPIT')
    .controller('HostsAddwizardservicesController', function($scope, $http, QueryStringService){
        $scope.id = QueryStringService.getCakeId();
        $scope.post = {
            servicetemplates: {}
        };

        $scope.init = true;
        $scope.containerId = null;


        $scope.load = function(){
            $http.get("/hosts/loadWizardServiceData/"+$scope.id+".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                console.log(result.data.data);
                $scope.hostname = result.data.data.hostname;
                $scope.containerId = result.data.data.containerId;

                $scope.servicetemplateName = result.data.data.servicetemplateName;
                $scope.servicetemplateId = result.data.data.servicetemplateId;
                $scope.init = false;
            });
        };


        $scope.loadServicetemplates = function(searchString){
            if($scope.containerId == null){
                return;
            }

            $http.get("/servicetemplates/loadServicetemplatesByContainerId/.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.containerId
                }
            }).then(function(result){
                console.log(result.data);
                $scope.servicetemplates = result.data.servicetemplates;
            });
        };

        $scope.load();
        $scope.$watch('init', function(){
            $scope.loadServicetemplates();
        });


    });