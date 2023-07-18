angular.module('openITCOCKPIT')
    .controller('ServicegroupsCopyController', function($scope, $http, $state, $stateParams, QueryStringService, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('ServicegroupsIndex');
            return;
        }


        $scope.load = function(){
            $http.get("/servicegroups/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.sourceServicegroups = [];
                for(var key in result.data.servicegroups){
                    $scope.sourceServicegroups.push({
                        Source: {
                            id: result.data.servicegroups[key].id,
                            name: result.data.servicegroups[key].container.name,
                        },
                        Servicegroup: {
                            container: {
                                name: result.data.servicegroups[key].container.name
                            },
                            description: result.data.servicegroups[key].description
                        }
                    });
                }

                $scope.init = false;

            });
        };

        $scope.copy = function(){
            $http.post("/servicegroups/copy/.json?angular=true",
                {
                    data: $scope.sourceServicegroups
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('ServicegroupsIndex');
            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceServicegroups = result.data.result;
            });
        };


        $scope.load();


    });
