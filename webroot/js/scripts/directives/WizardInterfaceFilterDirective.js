angular.module('openITCOCKPIT').directive('wizardInterfaceFilter', function($state, $http, NotyService, UuidService){
    return {
        restrict: 'E',
        templateUrl: '/angular/wizardInterfaceFilter.html',

        controller: function($scope){
            $scope.selectAllInterfaces = function(){
                _.map($scope.post.interfaces, function(interface){
                    if($scope.filteredInterfaces.includes(interface)){
                        interface.createService = true;
                    }else{
                        interface.createService = false;
                    }

                    return interface;
                });
            };

            $scope.undoSelectionInterfaces = function(){
                _.map($scope.post.interfaces, function(interface){
                    if($scope.filteredInterfaces.includes(interface)){
                        interface.createService = false;
                    }
                    return interface;
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
