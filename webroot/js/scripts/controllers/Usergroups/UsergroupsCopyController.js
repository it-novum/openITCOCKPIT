angular.module('openITCOCKPIT')
    .controller('UsergroupsCopyController', function($scope, $http, $state, $stateParams, QueryStringService, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('UsergroupsIndex');
            return;
        }


        $scope.load = function(){
            $http.get("/usergroups/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.sourceUsergroups = [];
                for(var key in result.data.usergroups){
                    $scope.sourceUsergroups.push({
                        Source: {
                            id: result.data.usergroups[key].id,
                            name: result.data.usergroups[key].name,
                        },
                        Usergroup: {
                            name: result.data.usergroups[key].name,
                            description: result.data.usergroups[key].description
                        }
                    });
                }

                $scope.init = false;

            });
        };

        $scope.copy = function(){
            $http.post("/usergroups/copy/.json?angular=true",
                {
                    data: $scope.sourceUsergroups
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('UsergroupsIndex');
            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceUsergroups = result.data.result;
            });
        };


        $scope.load();


    });
