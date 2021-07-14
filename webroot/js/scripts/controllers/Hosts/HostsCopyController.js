angular.module('openITCOCKPIT')
    .controller('HostsCopyController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        $scope.checkedName = [];
        $scope.isHostnameInUse = [];

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('HostsIndex');
            return;
        }


        $scope.load = function(){
            $http.get("/hosts/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.sourceHosts = [];
                for(var key in result.data.hosts){
                    $scope.sourceHosts.push({
                        Source: {
                            id: result.data.hosts[key].Host.id,
                            name: result.data.hosts[key].Host.name,
                            address: result.data.hosts[key].Host.address
                        },
                        Host: {
                            name: result.data.hosts[key].Host.name,
                            description: result.data.hosts[key].Host.description,
                            address: result.data.hosts[key].Host.address,
                            host_url: result.data.hosts[key].Host.host_url
                        }
                    });
                }

                $scope.init = false;

            });
        };

        $scope.copy = function(){
            $http.post("/hosts/copy/.json?angular=true",
                {
                    data: $scope.sourceHosts
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('HostsIndex');
            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceHosts = result.data.result;
            });
        };

        $scope.checkForDuplicateHostname = function(hostname, index){
            $scope.checkedName[index] = hostname;
            var data = {
                hostname: $scope.checkedName[index]
            };
            $http.post("/hosts/checkForDuplicateHostname.json?angular=true",
                data
            ).then(function(result){
                $scope.isHostnameInUse[index] = result.data.isHostnameInUse;
            });
        };

        // Fire on page load

        $scope.load();
    });
