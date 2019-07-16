angular.module('openITCOCKPIT')
    .controller('UsercontainerrolesAddController', function($scope, $http, $state, NotyService){

        $scope.post = {
            'Usercontainerrole':{
                'name':''
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


        $scope.getContainerName = function(id){
            for(var c in $scope.containers){
                if($scope.containers[c].key == id){
                    return $scope.containers[c].value;
                }
            }
            return null;
        };



        $scope.submit = function(){
            $http.post("/usercontainerroles/add.json?angular=true",
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

    });

