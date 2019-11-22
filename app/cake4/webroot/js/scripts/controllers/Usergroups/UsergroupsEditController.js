angular.module('openITCOCKPIT')
    .controller('UsergroupsEditController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){
        $scope.id = $stateParams.id;
        $scope.post = {
            'Usergroup': {
                'name': '',
                'description': ''
            },
            'Aco': {}
        };
        $scope.init = true;

        $scope.load = function(){
            $http.get("/usergroups/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                console.log(result.data);
                $scope.post.Usergroup = result.data.usergroup;
                $scope.acos = result.data.acos;
                $scope.aros = result.data.aros;
                $scope.alwaysAllowedAcos = result.data.alwaysAllowedAcos;
                $scope.acoDependencies = result.data.acoDependencies;
                $scope.dependentAcoIds = result.data.dependentAcoIds;
                $scope.selectChosen();
                //$scope.post.User = result.data.user;
            });
        };

        $scope.selectChosen = function(){
            $scope.aros.forEach(function(v){
                $scope.post.Aco[v] = true;
            });
        };

        $scope.coreFilter = function(coreAco){
            if($scope.hasChildren(coreAco)){
                return true;
            }
            return false;
        };

        $scope.acoFilter = function(aco){
            if(!$scope.isAllowedAco(aco) && !$scope.isDependendAco(aco)){
                return true;
            }
            return false;
        };

        $scope.isAllowedAco = function(aco){
            //console.log(aco.Aco.id);
            if($scope.alwaysAllowedAcos.hasOwnProperty(aco.Aco.id)){
                return true;
            }
            return false;
        };

        $scope.isDependendAco = function(aco){
            if($scope.dependentAcoIds.hasOwnProperty(aco.Aco.id)){
                return true;
            }
            return false;
        };

        $scope.hasChildren = function(aco){
            if(aco.children.length > 0){
                return true;
            }
            return false;
        };


        $scope.submit = function(){
            $http.post("/usergroups/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('UsergroupsIndex');
            }, function errorCallback(result){
                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.load();


        $scope.$watch('post', function(){
            console.log($scope.post)
        }, true)
    });

