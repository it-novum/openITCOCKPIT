angular.module('openITCOCKPIT').directive('massactivate', function($http, $filter, $timeout){
    return {
        restrict: 'E',
        replace: true,
        templateUrl: '/angular/mass_activate.html',

        controller: function($scope){

            $scope.objects = {};
            $scope.percentage = 0;
            $scope.isActivating = false;

            $scope.setObjectsForMassActivate = function(objects){
                $scope.objects = objects;
            };

            $scope.issueObjects = {};

            $scope.activate = function(){
                $scope.isActivating = true;
                var count = Object.keys($scope.objects).length;
                var i = 0;
                var issueCount = 0;

                for(var id in $scope.objects){
                    $http.post($scope.activateUrl + id + ".json").then(
                        function(result){
                            i++;
                            $scope.percentage = Math.round(i / count * 100);
                            issueCount = Object.keys($scope.issueObjects).length;

                            if(i === count && issueCount === 0){
                                $scope.isActivating = false;
                                $scope.percentage = 0;
                                $scope.load();
                                $('#angularMassAactivate').modal('hide');
                            }
                        }, function errorCallback(result){
                            i++;
                            $scope.percentage = Math.round(i / count * 100);

                            if(result.data.hasOwnProperty('success')){
                                var id = result.data.id;
                                $scope.issueObjects[id] = [];
                                $scope.issueObjects[id].push({
                                    message: result.data.message
                                });
                            }

                            issueCount = Object.keys($scope.issueObjects).length;
                            if(i === count && issueCount > 0){
                                $scope.isActivating = false;
                                $scope.percentage = 0;
                                $scope.load();
                            }
                        });
                }
            };

        },

        link: function($scope, element, attr){
            $scope.confirmActivate = function(objects){
                $scope.setObjectsForMassActivate(objects);
                $('#angularMassAactivate').modal('show');
            };
        }
    };
});