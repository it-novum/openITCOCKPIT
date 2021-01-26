angular.module('openITCOCKPIT')
    .controller('AgentconnectorsConfigController', function($scope, $http){
        console.log('DEBUG On !!!');
        $scope.load = function(searchString, selected){
            $http.get("/agentconnector/loadHostsByString/1.json", {
                params: {
                    'angular': true,
                    'filter[Hosts.name]': searchString
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        //Fire on page load
        $scope.load();

    });
