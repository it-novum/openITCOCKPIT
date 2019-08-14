angular.module('openITCOCKPIT')
    .controller('UsergroupsIndexController', function($scope, $http, $rootScope, MassChangeService){
        $scope.deleteUrl = '/usergroups/delete/';
        $scope.load = function(){
            var params = {
                'angular': true,
            };

            $http.get("/usergroups/index.json", {
                params: params
            }).then(function(result){
                $scope.Usergroups = result.data.allUsergroups;
                $scope.init = false;
            });
        };

        $scope.getObjectForDelete = function(user){
            var object = {};
            object[user.User.id] = user.User.full_name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.users){
                for(var id in selectedObjects){
                    if(id == $scope.users[key].User.id){
                        if($scope.users[key].User.allow_edit === true){
                            objects[id] = $scope.users[key].User.full_name;
                        }
                    }
                }
            }
            return objects;
        };

        $scope.load();
    });

