angular.module('openITCOCKPIT')
    .controller('ServicetemplategroupsCopyController', function($scope, $http, $state, $stateParams, QueryStringService, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('ServicetemplategroupsIndex');
            return;
        }


        $scope.load = function(){
            $http.get("/servicetemplategroups/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.sourceServicetemplategroups = [];
                for(var key in result.data.servicetemplategroups){
                    $scope.sourceServicetemplategroups.push({
                        Source: {
                            id: result.data.servicetemplategroups[key].id,
                            name: result.data.servicetemplategroups[key].container.name,
                        },
                        Servicetemplategroup: {
                            container: {
                                name: result.data.servicetemplategroups[key].container.name
                            },
                            description: result.data.servicetemplategroups[key].description
                        }
                    });
                }

                $scope.init = false;

            });
        };

        $scope.copy = function(){
            $http.post("/servicetemplategroups/copy/.json?angular=true",
                {
                    data: $scope.sourceServicetemplategroups
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('ServicetemplategroupsIndex');
            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceServicetemplategroups = result.data.result;
            });
        };


        $scope.load();


    });
