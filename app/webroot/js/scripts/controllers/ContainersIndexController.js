angular.module('openITCOCKPIT')
    .controller('ContainersIndexController', function($scope, $http, $timeout, $stateParams){

        $scope.init = true;

        //Objects gets passed as reference.
        //So we use an object here, to make the $watch trigger, if the chosen directive change the value for selectedContainer.id
        $scope.selectedContainer = {
            id: null
        };
        $scope.errors = null;
        if($stateParams.id != null){
            $scope.selectedContainer.id = parseInt($stateParams.id, 10);
        }

        $scope.load = function(){
            $http.get("/containers/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
            if($scope.selectedContainer.id !== null){
                $scope.loadContainers();
            }
        };
        $scope.loadContainers = function(){
            $http.get('/containers/loadContainersByContainerId/' + $scope.selectedContainer.id + '.json', {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.subcontainers = result.data.nest;
                $('#nestable').nestable({
                    noDragClass: 'dd-nodrag'
                });
            });
        };

        $scope.load();

        $scope.$watch('selectedContainer.id', function(){
            if($scope.selectedContainer.id !== null){
                $scope.loadContainers();
            }
        });
    });
