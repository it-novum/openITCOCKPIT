angular.module('openITCOCKPIT')
    .controller('ContainersIndexController', function($scope, $http, $timeout, $stateParams, $filter){

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
                console.log($scope.containers);
                console.info($scope.selectedContainer.id);
                if($scope.selectedContainer.id !== null){
                    var objectExist = _.isObject(_.find($scope.containers, function(obj){
                        return obj.key === $scope.selectedContainer.id;
                    }));
                    if(objectExist){ // check after delete if selected container exists
                        $scope.loadContainers();
                    }else{
                        $scope.selectedContainer.id = null;
                        $scope.subcontainers = {};
                    }

                }
            });
        };
        $scope.loadContainers = function(){
            if($scope.selectedContainer.id !== null){
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
            }
        };

        $scope.load();

        $scope.$watch('selectedContainer.id', function(){
            if($scope.init){
                return;
            }
            if($scope.selectedContainer.id !== null){
                $scope.loadContainers();
            }
        }, true);
    });
