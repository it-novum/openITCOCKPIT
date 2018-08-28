angular.module('openITCOCKPIT')
    .controller('HostsAddwizardservicesController', function($scope, $http, QueryStringService){
        $scope.id = QueryStringService.getCakeId();
        $scope.post = {
            Servicetemplate: {
                id:1
            }
        };

        $scope.init = true;
        $scope.containerId = null;


        $scope.load = function(){
            $http.get("/hosts/loadHostInfo/"+$scope.id+".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.hostname = result.data.host.Host.name;
                $scope.containerId = result.data.host.Host.container_id;
                $scope.init = false;
            });
        };


        $scope.loadServicetemplates = function(searchString){
            if($scope.containerId == null){
                return;
            }

            $http.get("/servicetemplates/loadServicetemplatesByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.containerId,
                    'filter[Servicetemplate.name]': searchString,
                }
            }).then(function(result){
                $scope.servicetemplates = result.data.servicetemplates;
            });
        };


        $scope.submit = function(){
            $http.post("/hosts/addwizardservices/"+$scope.id+".json?angular=true",
                $scope.post
            ).then(function(result){
                console.log('Data saved successfully');
                window.location.href = '/hosts/addwizardoverview/'+$scope.id;
            }, function errorCallback(result){
                console.info('save failed');
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                    console.log($scope.errors);
                }
            });
        };

        $scope.load();
        $scope.$watch('init', function(){
            $scope.loadServicetemplates();
        });
    });