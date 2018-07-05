angular.module('openITCOCKPIT').directive('askAnonymousStatistics', function($http){
    return {
        restrict: 'E',
        templateUrl: '/statistics/ask_anonymous_statistics.html',
        scope: {},

        controller: function($scope){

            $scope.showModal = function(){
                setTimeout(function(){
                    $('#angulWeAskForYourHelpModal').modal('show');
                }, 500);
            };

            $scope.save = function(value){
                var post = {
                    statistics: {
                        decision: value,
                        cookie: true
                    }
                };
                $http.post("/statistics/saveStatisticDecision.json", post).then(function(result){
                    $('#angulWeAskForYourHelpModal').modal('hide');

                    if(parseInt(value, 10) === 1){
                        $('#manyThanksForYourSupport').show();
                        $('#manyThanksForYourSupport').addClass('animated flipInY');

                        setTimeout(function(){
                            $('#manyThanksForYourSupport').addClass('animated flipOutY');
                        }, 2500);
                    }
                });
            };

            $scope.showModal();
        }

    };
});