angular.module('openITCOCKPIT')
    .controller('HostgroupsCopyController', function($scope, $http, $state, $stateParams, QueryStringService, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('HostgroupsIndex');
            return;
        }


        $scope.load = function(){
            $http.get("/hostgroups/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.sourceHostgroups = [];
                for(var key in result.data.hostgroups){
                    $scope.sourceHostgroups.push({
                        Source: {
                            id: result.data.hostgroups[key].id,
                            name: result.data.hostgroups[key].container.name,
                        },
                        Hostgroup: {
                            container: {
                                name: result.data.hostgroups[key].container.name
                            },
                            description: result.data.hostgroups[key].description
                        }
                    });
                }

                $scope.init = false;

            });
        };

        $scope.copy = function(){
            $http.post("/hostgroups/copy/.json?angular=true",
                {
                    data: $scope.sourceHostgroups
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('HostgroupsIndex');
            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceHostgroups = result.data.result;
            });
        };


        $scope.load();


    });
