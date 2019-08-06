angular.module('openITCOCKPIT')
    .controller('UsercontainerrolesEditController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){
        $scope.id = $stateParams.id;


        $scope.post = {
            'Usercontainerrole':{
                'name':'',
                'containers': {
                    /* example data CURRENTLY NOT USED!
                    0: {
                        'id': null, //container ID
                        '_joinData':{ //saving additional data to "through" table
                            'permission_level':null //radio button value
                        }
                    }
                    */
                },
                'ContainersUsercontainerrolesMemberships': {}
            }
        };

        $scope.loadContainer = function(){
            $http.get("/containers/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
            });
        };

        $scope.load = function(){
            $http.get("/usercontainerroles/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post.Usercontainerrole = result.data.usercontainerrole;
            });
        };


        $scope.getContainerName = function(id){
            for(var c in $scope.containers){
                if($scope.containers[c].key == id){
                    return $scope.containers[c].value;
                }
            }
            return null;
        };

        /**
         * sync the membership array with the containers array so we can cleanly remove a container from a user
         */
        $scope.syncMemberships = function(){
            var memberships = $scope.post.Usercontainerrole.ContainersUsercontainerrolesMemberships;
            for(var key in memberships){
                key = parseInt(key, 10);
                if($scope.post.Usercontainerrole.containers._ids.indexOf(key) == -1){
                    delete memberships[key];
                }
            }
        };

        $scope.submit = function(){
            $http.post("/usercontainerroles/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('UsercontainerrolesIndex');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };


        $scope.loadContainer();
        $scope.load();

        $scope.$watch('post',function(){
            console.log($scope.post);
        },true);

    });

