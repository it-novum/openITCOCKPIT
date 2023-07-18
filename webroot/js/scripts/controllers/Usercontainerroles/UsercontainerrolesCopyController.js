angular.module('openITCOCKPIT')
    .controller('UsercontainerrolesCopyController', function($scope, $http, $state, $stateParams, QueryStringService, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('UsercontainerrolesIndex');
            return;
        }


        $scope.load = function(){
            $http.get("/usercontainerroles/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.sourceUsercontainerroles = [];
                for(var key in result.data.usercontainerroles){
                    $scope.sourceUsercontainerroles.push({
                        Source: {
                            id: result.data.usercontainerroles[key].id,
                            name: result.data.usercontainerroles[key].name,
                        },
                        Usercontainerrole: {
                            name: result.data.usercontainerroles[key].name
                        }
                    });
                }

                $scope.init = false;

            });
        };

        $scope.copy = function(){
            $http.post("/usercontainerroles/copy/.json?angular=true",
                {
                    data: $scope.sourceUsercontainerroles
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('UsercontainerrolesIndex');
            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceUsercontainerroles = result.data.result;
            });
        };


        $scope.load();


    });
