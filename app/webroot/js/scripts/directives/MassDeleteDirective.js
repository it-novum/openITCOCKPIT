angular.module('openITCOCKPIT').directive('massdelete', function($http, $filter, $timeout){
    return {
        restrict: 'E',
        replace: true,
        templateUrl: '/angular/mass_delete.html',

        controller: function($scope){

            $scope.objects = {};
            $scope.percentage = 0;
            $scope.isDeleting = false;

            $scope.setObjects = function(objects){
                $scope.objects = objects;
            };

            $scope.delete = function(){
                $scope.isDeleting = true;
                var count = Object.keys($scope.objects).length;
                var i = 0;
                for(var id in $scope.objects){

                    $http.post($scope.deleteUrl + id + ".json").then(
                        function(result){
                            i++;
                            $scope.percentage = Math.round(i / count * 100);

                            if(i === count){
                                $scope.isDeleting = false;
                                $scope.load();
                                $('#angularMassDelete').modal('hide');
                            }

                        });
                }
            };

        },

        link: function($scope, element, attr){
            $scope.confirmDelete = function(objects){
                $scope.setObjects(objects);
                $('#angularMassDelete').modal('show');
            };
        }
    };
});