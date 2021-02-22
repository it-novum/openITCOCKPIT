angular.module('openITCOCKPIT')
    .controller('AgentconnectorsWizardController', function($scope, $http, $stateParams){
        $scope.hostId = $stateParams.hostId;

        $scope.connectorConfig = {};

        $scope.load = function(searchString, selected){
            $http.get("/agentconnector/loadHostsByString/1.json", {
                params: {
                    'angular': true,
                    'filter[Hosts.name]': searchString,
                    'selected[]': selected
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        $scope.loadConfigForSelectedHostId = function(){
            var params = {
                'angular': true
            };

            $http.get("/agentconnector/loadAgentConfigByHostId/" + $scope.hostId + ".json", {
                params: params
            }).then(function(result){
                $scope.connectorConfig = result.data.connectorConfig;
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        //Fire on page load
        var selected = [];
        if($scope.hostId){
            $scope.hostId = parseInt($scope.hostId, 10);
            selected.push($scope.hostId);
        }

        $scope.load('', selected);

        $scope.$watch('hostId', function(){
            $scope.loadConfigForSelectedHostId();
        }, true);
    });
