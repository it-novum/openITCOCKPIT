angular.module('openITCOCKPIT')
    .controller('TenantsAddController', function($scope, $http, $state, NotyService, $location){

        $scope.data = {
            createAnother: false
        };

        var clearForm = function(){
            $scope.post = {
                id: 0,
                description: '',
                is_active: 1,
                firstname: '',
                lastname: '',
                street: '',
                zipcode: null,
                city: '',
                max_users: 0,
                container: {
                    name: ''
                }
            };
        };
        clearForm();

        $scope.submit = function(){
            $http.post("/tenants/add.json?angular=true",
                $scope.post
            ).then(function(result){

                var url = $state.href('TenantsEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                if($scope.data.createAnother === false){
                    $state.go('TenantsIndex').then(function(){
                        NotyService.scrollTop();
                    });
                }else{
                    clearForm();
                    NotyService.scrollTop();
                }

            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

    });