angular.module('openITCOCKPIT')
    .controller('ContainersShowDetailsController', function($scope, $http, $timeout, $stateParams){

        $scope.init = true;

        $scope.post = {
            Container: {
                id: null,
                tenant: null
            },
            backState: null
        };

        $scope.post.Container.id = $stateParams.id;
        if($stateParams.tenant){
            if(isNaN($stateParams.tenant)){
                $scope.post.backState = $stateParams.tenant;
            } else {
                $scope.post.Container.tenant = $stateParams.tenant;
            }
        }


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
