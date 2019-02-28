angular.module('openITCOCKPIT').directive('editNode', function($http, NotyService){
    return {
        restrict: 'E',
        templateUrl: '/containers/edit.html',
        scope: {
            'container': '=',
            'callback': '='
        },

        controller: function($scope){
            $scope.post = {
                Container: {
                    id: $scope.container.Container.id,
                    name: $scope.container.Container.name
                }
            };
            $scope.openModal = function(){
                $('#angularEditNode-' + $scope.container.Container.id).modal('show');
            };
            $scope.save = function(){
                console.log($scope.post);
                $http.post("/containers/edit.json?angular=true", $scope.post).then(
                    function(result){
                        $('#angularEditNode-' + $scope.container.Container.id).modal('hide');
                        NotyService.genericSuccess();
                        $scope.callback();
                    }, function errorCallback(result){
                        if(result.data.hasOwnProperty('error')){
                            $scope.errors = result.data.error;
                        }
                        NotyService.genericError();
                    }
                );
            };
            $scope.delete = function(){
                $scope.isDeleting = true;
                $http.post('/containers/delete/' + $scope.container.Container.id + '.json?angular=true').then(
                    function(result){
                        $('#angularEditNode-' + $scope.container.Container.id).modal('hide');
                        NotyService.genericSuccess();
                        $scope.callback();
                    }, function errorCallback(result){
                        if(result.data.hasOwnProperty('error')){
                            $scope.errors = result.data.error;
                        }
                        NotyService.genericError();
                    }
                );
            };
        },
        link: function($scope, element, attr){
        }
    };
});
