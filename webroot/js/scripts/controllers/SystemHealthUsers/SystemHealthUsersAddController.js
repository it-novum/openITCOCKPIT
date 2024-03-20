angular.module('openITCOCKPIT')
    .controller('SystemHealthUsersAddController', function($scope, $http, SudoService, $state, $stateParams, NotyService, RedirectService){

        $scope.init = true;

        $scope.data = {
            createAnother: false
        };

        var clearForm = function(){
            $scope.post = {
                SystemHealthUser: {
                    user_ids: [],
                    notify_on_recovery: 1,
                    notify_on_warning: 1,
                    notify_on_critical: 1,
                },

            };
        }
        clearForm();

        $scope.loadUsers = function(){
            var params = {
                'angular': true
            };

            $http.get("/systemHealthUsers/loadUsers.json", {
                    params: params
                }
            ).then(function(result){
                $scope.users = result.data.users;
                $scope.init = false;
            });
        };

        $scope.submit = function(){

            $http.post("/systemHealthUsers/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('SystemHealthUsersEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('SystemHealthUsersIndex');
                }else{
                    clearForm();
                    $scope.errors = {};
                    NotyService.scrollTop();
                }

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


        $scope.loadUsers();

    });
