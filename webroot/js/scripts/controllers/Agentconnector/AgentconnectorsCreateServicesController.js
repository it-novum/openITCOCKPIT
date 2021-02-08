angular.module('openITCOCKPIT')
    .controller('AgentconnectorsCreateServicesController', function($scope, $http, $state, $stateParams, RedirectService, NotyService){

        $scope.connectorConfig = {};
        $scope.hostId = $stateParams.hostId;
        $scope.disableNext = true;
        $scope.runningCheck = true;

        $scope.servicesToCreateCheckboxValues = {};
        $scope.servicesToCreateArrayIndices = {};

        $scope.saving = false;
        $scope.successful = false;
        $scope.hideConfig = false;

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
            $scope.saving = true;
            $scope.hideConfig = true;
            var postServices = [];

            // add services from check boxes
            var sectionKey;
            for(sectionKey in $scope.servicesToCreateCheckboxValues){
                // is checked?
                if($scope.servicesToCreateCheckboxValues[sectionKey] === true){
                    if(typeof $scope.services[sectionKey] !== "undefined"){
                        postServices.push(
                            $scope.services[sectionKey]
                        );
                    }
                }
            }

            // grab services from select boxes
            for(sectionKey in $scope.servicesToCreateArrayIndices){
                for(var idx in $scope.servicesToCreateArrayIndices[sectionKey]){
                    var arrayIndex = $scope.servicesToCreateArrayIndices[sectionKey][idx];
                    if(typeof $scope.services[sectionKey][arrayIndex] !== "undefined"){
                        postServices.push(
                            $scope.services[sectionKey][arrayIndex]
                        );
                    }
                }
            }

            $http.post("/agentconnector/create_services.json?hostId=" + $scope.hostId + "&angular=true", {
                    services: postServices
                }
            ).then(function(result){
                NotyService.genericSuccess();
                $scope.successful = true;
                $scope.saving = false;
                NotyService.scrollTop();
            }, function errorCallback(result){
                NotyService.genericError();
                $scope.successful = false;
                $scope.hideConfig = false;
                $scope.saving = false;
            });
        };


        //Fire on page load
        $scope.load();
    });
