angular.module('openITCOCKPIT')
    .controller('AgentconnectorsAutotlsController', function($scope, $http, $state, $stateParams, $interval, RedirectService, NotyService){

        $scope.hostId = $stateParams.hostId;
        $scope.isSatellite = false;
        $scope.disableNext = true;
        $scope.runningCheck = true;

        $scope.hasSatelliteError = false;
        $scope.satelliteErrorMsg = '';

        var refreshInterval = null;
        const SatelliteTaskFinishedSuccessfully = 4;
        const SatelliteTaskFinishedError = 8;

        var checkForSatelliteResponse = function(){
            $http.get("/agentconnector/satellite_response.json", {
                params: {
                    task_id: $scope.satellite_task_id,
                    'angular': true
                }
            }).then(function(result){
                if(typeof result.data.task.status === "undefined"){
                    // Unexpected answer from Server
                    $scope.cancelInterval();
                    NotyService.genericError({message: 'Unexpected answer from Server'});
                    return;
                }

                if(result.data.task.status === SatelliteTaskFinishedSuccessfully || result.data.task.status === SatelliteTaskFinishedError){
                    // We got an result from the Satellite Server
                    $scope.cancelInterval();
                    if(result.data.task.status === SatelliteTaskFinishedError){
                        try{
                            $scope.connection_test = JSON.parse(result.data.task.result);
                        }catch(e){
                            // Error is no json
                            $scope.hasSatelliteError = true;
                            $scope.satelliteErrorMsg = result.data.task.result;
                        }

                        $scope.runningCheck = false;
                        return;
                    }

                    if(result.data.task.status === SatelliteTaskFinishedSuccessfully){
                        $scope.runningCheck = false;


                        var responseJson = JSON.parse(result.data.task.result);
                        $scope.connection_test = responseJson;

                        $scope.disableNext = $scope.connection_test.status !== 'success';
                        if($scope.connection_test.status === 'success'){
                            $scope.disableNext = false;
                        }
                    }
                }

                if(result.data.task.status === SatelliteTaskFinishedError){
                    $scope.runningCheck = false;
                }

            }, function errorCallback(result){
                $scope.cancelInterval();
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    NotyService.genericError({message: 'Task not found in database!'});
                }
            });
        };

        //Disable status update interval, if the user change the page
        //E.g in Map rotations
        $scope.$on('$destroy', function(){
            $scope.cancelInterval();
        });

        // Load current agent config if any exists
        $scope.load = function(searchString, selected){
            $scope.runningCheck = true;
            $http.get("/agentconnector/autotls.json", {
                params: {
                    hostId: $scope.hostId,
                    'angular': true
                }
            }).then(function(result){

                if(result.data.satellite_task_id === null){
                    // Request was handled by the Master System
                    $scope.runningCheck = false;
                    $scope.config = result.data.config;
                    $scope.host = result.data.host;
                    $scope.connection_test = result.data.connection_test;
                    $scope.disableNext = $scope.connection_test.status !== 'success';
                }else{
                    $scope.isSatellite = true;
                    $scope.satellite_task_id = result.data.satellite_task_id;
                    // Request is running on a Satellite - Wait for response data...
                    $scope.cancelInterval();

                    refreshInterval = $interval(checkForSatelliteResponse, 5000);
                }
            });
        };

        $scope.reExchangeAutoTLS = function(){
            $scope.runningCheck = true;
            $http.get("/agentconnector/autotls.json", {
                params: {
                    hostId: $scope.hostId,
                    'angular': true,
                    'reExchangeAutoTLS': 'true'
                }
            }).then(function(result){
                if(result.data.satellite_task_id === null){
                    // Request was handled by the Master System
                    $scope.runningCheck = false;
                    $scope.config = result.data.config;
                    $scope.host = result.data.host;
                    $scope.connection_test = result.data.connection_test;
                    $scope.disableNext = $scope.connection_test.status !== 'success';
                }else{
                    $scope.isSatellite = true;
                    $scope.satellite_task_id = result.data.satellite_task_id;
                    // Request is running on a Satellite - Wait for response data...
                    $scope.cancelInterval();

                    refreshInterval = $interval(checkForSatelliteResponse, 5000);
                }
            });
        };

        $scope.submit = function(){
            $state.go('AgentconnectorsCreateServices', {
                hostId: $scope.hostId,
                testConnection: 'false'
            }).then(function(){
                NotyService.scrollTop();
            });
        };

        $scope.cancelInterval = function(){
            if(refreshInterval){
                $interval.cancel(refreshInterval);
            }
        };

        // Cancel Button in GUI
        $scope.cancelSatRequest = function(){
            $scope.cancelInterval();
            $scope.runningCheck = false;
        };


        //Fire on page load
        $scope.load();
    });
