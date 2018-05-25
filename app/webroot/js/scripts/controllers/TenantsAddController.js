angular.module('openITCOCKPIT')
    .controller('TenantsAddController', function($scope, $http){
        $scope.post = {
            Tenant: {
                id: '',
                description: '',
                is_active: '',
                firstname: '',
                lastname: '',
                street: '',
                zipcode: '',
                city: '',
                max_users: 0,
            },
            Container: {
                name: '',
                containertype_id: '',
                parent_id: []
            }
        };

        $scope.submit = function(){
            $http.post("/tenants/add.json?angular=true",
                $scope.post
            ).then(function(result){

                window.location.href = '/tenants/index';
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

    });