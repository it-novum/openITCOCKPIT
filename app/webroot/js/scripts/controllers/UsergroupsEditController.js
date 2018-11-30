angular.module('openITCOCKPIT')
    .controller('UsergroupsEditController', function($scope, $http, QueryStringService){
        $scope.post = {
            Usergroup: {
                id: '',
                name: '',
                description: '',
                Aco: []
            }
        };


        $scope.id = QueryStringService.getCakeId();

        $scope.deleteUrl = "/usergroups/delete/" + $scope.id + ".json?angular=true";
        $scope.sucessUrl = '/usergroups/index';

        $scope.load = function(){
            $http.post("/usergroups/loadRoles/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.Usergroup = result.data.usergroup;
                /*$scope.post.Usergroup.name = $scope.tenant.Usergroup.name;
                $scope.post.Usergroup.description = $scope.tenant.Container.containertype_id;
                $scope.post.Usergroup.Aco = $scope.tenant.Container.parent_id; */
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        };


        $scope.submit = function(){
            $http.post("/usergroups/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                console.log('Data saved successfully');
                window.location.href = '/usergroups/index';
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.isModule = function(str){
            return RegExp('Module').test(str);
        };

        $scope.cleanControllerName = function(str){
            return str.replace(/Controller/,'')
        };

        $scope.checkAlwaysAllowedAcos = function(acoId){
            return $scope.Usergroup.alwaysAllowedAcos[acoId] != null;
        };

        $scope.checkDependentAcoIds = function(){

        };


        $scope.load();

    });