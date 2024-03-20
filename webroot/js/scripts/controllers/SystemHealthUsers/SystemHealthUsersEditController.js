angular.module('openITCOCKPIT')
    .controller('SystemHealthUsersEditController', function($scope, $http, SudoService, $state, $stateParams, NotyService, RedirectService){

        $scope.id = $stateParams.id;

        $scope.post = {
            SystemHealthUser: {},
            User: {}
        };

        $scope.loadUser = function(){
            var params = {
                'angular': true
            };

            $http.get("/systemHealthUsers/edit/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post.SystemHealthUser = result.data.systemHealthUser;
                $scope.post.User = result.data.user;
            });
        };

        $scope.submit = function(){
            $http.post("/systemHealthUsers/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('SystemHealthUsersEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('SystemHealthUsersIndex');
                console.log('Data saved successfully');
            }, function errorCallback(result){

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;

                    if($scope.errors.hasOwnProperty('customvariables')){
                        if($scope.errors.customvariables.hasOwnProperty('custom')){
                            $scope.errors.customvariables_unique = [
                                $scope.errors.customvariables.custom
                            ];
                        }
                    }
                }
            });

        };


        $scope.loadUser();

    });
