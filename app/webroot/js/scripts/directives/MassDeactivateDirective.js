angular.module('openITCOCKPIT').directive('massdeactivate', function($http, $filter, $timeout){
    return {
        restrict: 'E',
        replace: true,
        templateUrl: '/angular/mass_deactivate.html',

        controller: function($scope){

            $scope.objects = {};
            $scope.percentage = 0;
            $scope.isDeactivating = false;

            $scope.setObjectsForMassDeactivate = function(objects){
                $scope.objects = objects;
            };

            $scope.issueObjects = {};

            $scope.deactivate = function(){
                $scope.isDeactivating = true;
                var count = Object.keys($scope.objects).length;
                var i = 0;
                var issueCount = 0;

                for(var id in $scope.objects){
                    $http.post($scope.deactivateUrl + id + ".json").then(
                        function(result){
                            i++;
                            $scope.percentage = Math.round(i / count * 100);
                            issueCount = Object.keys($scope.issueObjects).length;

                            if(i === count && issueCount === 0){
                                $scope.isDeactivating = false;
                                $scope.percentage = 0;
                                $scope.load();
                                $('#angularMassDeactivate').modal('hide');
                            }
                        }, function errorCallback(result){
                            i++;
                            $scope.percentage = Math.round(i / count * 100);

                            if(result.data.hasOwnProperty('success') && result.data.hasOwnProperty('usedBy')){
                                var id = result.data.id;
                                $scope.issueObjects[id] = [];
                                for(var key in result.data.usedBy){
                                    $scope.issueObjects[id].push({
                                        message: result.data.usedBy[key].message,
                                        url: result.data.usedBy[key].baseUrl + id
                                    });
                                }
                            }

                            issueCount = Object.keys($scope.issueObjects).length;
                            if(i === count && issueCount > 0){
                                $scope.isDeactivating = false;
                                $scope.percentage = 0;
                                $scope.load();
                            }
                        });
                }
            };

        },

        link: function($scope, element, attr){
            $scope.confirmDeactivate = function(objects){
                $scope.setObjectsForMassDeactivate(objects);
                $('#angularMassDeactivate').modal('show');
            };
        }
    };
});