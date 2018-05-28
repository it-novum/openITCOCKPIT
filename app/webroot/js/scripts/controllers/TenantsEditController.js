angular.module('openITCOCKPIT')
    .controller('TenantsEditController', function($scope, $http, QueryStringService){
        $scope.post = {
            Tenant: {
                //id: '',
                description: '',
                is_active: '',
                firstname: '',
                lastname: '',
                street: '',
                zipcode: '',
                city: '',
                max_users: 0
            },
            Container: {
                name: '',
                containertype_id: '',
                parent_id: ''
            }
        };


        $scope.id = QueryStringService.getCakeId();

        $scope.deleteUrl = "/tenants/delete/"+$scope.id+".json?angular=true";
        $scope.sucessUrl = '/tenants/index';

        $scope.load = function(){
            $http.get("/tenants/edit/"+$scope.id+".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.tenant = result.data.tenant;
                $scope.post.Container.name = $scope.tenant.Container.name;
                $scope.post.Container.containertype_id = $scope.tenant.Container.containertype_id;
                $scope.post.Container.parent_id = $scope.tenant.Container.parent_id;
                $scope.post.Tenant.description =  $scope.tenant.Tenant.description;
                $scope.post.Tenant.is_active =  parseInt($scope.tenant.Tenant.is_active, 10) === 1;
                $scope.post.Tenant.firstname =  $scope.tenant.Tenant.firstname;
                $scope.post.Tenant.lastname =  $scope.tenant.Tenant.lastname;
                $scope.post.Tenant.street =  $scope.tenant.Tenant.street;
                $scope.post.Tenant.zipcode =  $scope.tenant.Tenant.zipcode;
                $scope.post.Tenant.city =  $scope.tenant.Tenant.city;
                $scope.post.Tenant.max_users =  $scope.tenant.Tenant.max_users;
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        };


        $scope.submit = function(){
            $http.post("/tenants/edit/"+$scope.id+".json?angular=true",
                $scope.post
            ).then(function(result){
                console.log('Data saved successfully');
                window.location.href = '/tenants/index';
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };



        $scope.load();

    });