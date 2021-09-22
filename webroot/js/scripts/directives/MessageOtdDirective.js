angular.module('openITCOCKPIT').directive('messageOtd', function($http, BBParserService, $interval){
    return {
        restrict: 'E',
        templateUrl: '/angular/message_of_the_day.html',

        controller: function($scope){
            $scope.messageOtdAvailable = false;

            $scope.load = function(){
                $http.get("/angular/message_of_the_day.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.messageOtdAvailable = result.data.messageOtdAvailable;
                    $scope.messageOtd = result.data.messageOtd;
                    $scope.messageOtd.contentHtml = BBParserService.parse($scope.messageOtd.content);
                });
            };

            $interval($scope.load, 3600 * 1000); //every hour

            $scope.load();

            $scope.openMessageOtdModal = function(){
                $(".page-inner").append($('#angularMessageOtdModal'));
                $('#angularMessageOtdModal').modal('show');
            };

        },

        link: function(scope, element, attr){
            jQuery(element).find("[rel=tooltip]").tooltip();
        }
    };
});
