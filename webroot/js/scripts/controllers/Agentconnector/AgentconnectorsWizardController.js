angular.module('openITCOCKPIT')
    .controller('AgentconnectorsWizardController', function($scope, $http, $state, $stateParams){
        $scope.hostId = $stateParams.hostId;
        $scope.pushAgentId = $stateParams.pushAgentId;

        $scope.isConfigured = false;

        $scope.load = function(searchString, selected){
            $http.get("/agentconnector/loadHostsByString/1.json", {
                params: {
                    'angular': true,
                    'filter[Hosts.name]': searchString,
                    'selected[]': selected,
                    'pushAgentId': $scope.pushAgentId

                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        $scope.loadConfigForSelectedHostId = function(){
            if($scope.hostId > 0){
                $http.get("/agentconnector/wizard.json", {
                    params: {
                        'angular': true,
                        'hostId': $scope.hostId
                    }
                }).then(function(result){
                    $scope.init = false;
                    $scope.isConfigured = result.data.isConfigured;
                }, function errorCallback(result){
                    if(result.status === 403){
                        $state.go('403');
                    }

                    if(result.status === 404){
                        $state.go('404');
                    }
                });
            }
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
