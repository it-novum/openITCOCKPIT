angular.module('openITCOCKPIT')
    .controller('AgentconnectorsCreateServicesController', function($scope, $http, $state, $stateParams, RedirectService, NotyService){

        $scope.connectorConfig = {};
        $scope.hostId = $stateParams.hostId;
        $scope.disableNext = true;
        $scope.runningCheck = true;

        $scope.servicesToCreateCheckboxValues = {};
        $scope.servicesToCreateArrayIndices = {};

        // Load current agent config if any exists
        $scope.load = function(searchString, selected){
            $scope.runningCheck = true;
            $http.get("/agentconnector/create_services.json", {
                params: {
                    hostId: $scope.hostId,
                    'angular': true
                }
            }).then(function(result){
                $scope.runningCheck = false;
                $scope.config = result.data.config;
                $scope.host = result.data.host;
                $scope.services = result.data.services;

                // Find all services that could be created with an checkbox
                for(var key in $scope.services){
                    if(Array.isArray($scope.services[key]) === false){
                        //Mark all checkboxes as selected
                        $scope.servicesToCreateCheckboxValues[key] = true;
                    }else{
                        $scope.servicesToCreateArrayIndices[key] = [];
                    }
                }

            });
        };


        $scope.submit = function(){
            console.log('submit');
        };


        //Fire on page load
        $scope.load();

        $scope.$watch('servicesToCreateArrayIndices', function(){
            console.log($scope.servicesToCreateArrayIndices);
        }, true);

    });
