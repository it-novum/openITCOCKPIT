angular.module('openITCOCKPIT').directive('wizardFilter', function($state, $http, NotyService, UuidService){
    return {
        restrict: 'E',
        templateUrl: '/angular/wizardFilter.html',

        controller: function($scope){
            $scope.selectAll = function(){
                _.map($scope.post.services, function(service){
                    if($scope.filteredItems.includes(service)){
                        service.createService = true;
                    }else{
                        service.createService = false;
                    }

                    return service;
                });
            };

            $scope.undoSelection = function(){
                _.map($scope.post.services, function(service){
                    if($scope.filteredItems.includes(service)){
                        service.createService = false;
                    }
                    return service;
                });
            };

            //Fire on pageload
            jQuery(function(){
                $('.tagsinput').tagsinput();
            });

        },

        link: function($scope, element, attrs){

        }
    };
});
