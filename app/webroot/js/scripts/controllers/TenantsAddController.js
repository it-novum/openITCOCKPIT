angular.module('openITCOCKPIT')
    .controller('TenantsAddController', function($scope, $http){
        $scope.post = {
            Tenant: {
                id: '',
                description: '',
                is_active: '',
                expires: '',
                firstname: '',
                lastname: '',
                street: '',
                zipcode: '',
                city: '',
                max_users: 0,
                max_hosts: 0,
                max_services: 0
            },
            Container: {
                name: '',
                containertype_id: '',
                parent_id: []
            }
        };

        $scope.init = true;
        $scope.loadInitalData = function(){
            $http.get("/tenants/loadinitialdata.json?angular=true", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post.Tenant.expires = result.data.initialdata.expires;
                $scope.init = false;
            });
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

        $scope.loadInitalData();
    });