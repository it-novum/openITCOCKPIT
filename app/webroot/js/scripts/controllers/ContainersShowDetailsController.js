angular.module('openITCOCKPIT')
    .controller('ContainersShowDetailsController', function($scope, $http, $timeout, QueryStringService){

        $scope.init = true;

        $scope.post = {
            Container: {
                id: null
            }
        };

        $scope.post.Container.id = QueryStringService.getCakeId();


        $scope.loadContainerDetails = function(){
            $http.get('/containers/showDetails/' + $scope.post.Container.id + '.json', {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containerDetails = result.data.containerDetails;
            });
        };

        $scope.loadContainerDetails();
    });
