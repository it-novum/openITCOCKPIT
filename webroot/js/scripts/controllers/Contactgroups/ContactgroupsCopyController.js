angular.module('openITCOCKPIT')
    .controller('ContactgroupsCopyController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('ContactgroupsIndex');
            return;
        }


        $scope.load = function(){
            $http.get("/contactgroups/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.sourceContactgroups = [];
                for(var key in result.data.contactgroups){
                    $scope.sourceContactgroups.push({
                        Source: {
                            id: result.data.contactgroups[key].Contactgroup.id,
                            name: result.data.contactgroups[key].Container.name,
                        },
                        Contactgroup: {
                            container: {
                                name: result.data.contactgroups[key].Container.name
                            },
                            description: result.data.contactgroups[key].Contactgroup.description
                        }
                    });
                }

                $scope.init = false;

            });
        };

        $scope.copy = function(){
            $http.post("/contactgroups/copy/.json?angular=true",
                {
                    data: $scope.sourceContactgroups
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('ContactgroupsIndex');
            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceContactgroups = result.data.result;
            });
        };


        $scope.load();


    });
