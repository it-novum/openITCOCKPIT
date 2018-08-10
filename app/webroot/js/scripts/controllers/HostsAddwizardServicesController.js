angular.module('openITCOCKPIT')
    .controller('HostsAddwizardservicesController', function($scope, $http, QueryStringService){
        $scope.id = QueryStringService.getCakeId();
        $scope.post = {
            hostname:'',
            servicetemplateName:'',
            servicetemplateId:0
        };

        $scope.load = function(){
            $http.get("/hosts/loadWizardServiceData/"+$scope.id+".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                console.log(result.data.data);
                $scope.post.hostname = result.data.data.hostname;
                $scope.post.servicetemplateName = result.data.data.servicetemplateName;
                $scope.post.servicetemplateId = result.data.data.servicetemplateId;
                //$scope.init = false;
            });
        };

        $scope.load();

    });