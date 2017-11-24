angular.module('openITCOCKPIT').directive('editNode', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/containers/edit.html',

        controller: function($scope){

            var container = null;

            $scope.setContainer = function(_container){
                container = _container;
            };

            $scope.save = function(){
                console.log('save');
                console.log(container);

                //$('#angularEditNode').modal('hide');
            };

            $scope.delete = function(){
                $scope.isDeleting = true;
                console.log('delete');
                console.log(container);

                //$('#angularEditNode').modal('hide');


            };
        },

        link: function($scope, element, attr){
            $scope.edit = function(container){
                $scope.setContainer(container);
                $('#angularEditNode').modal('show');
            };

        }
    };
});
