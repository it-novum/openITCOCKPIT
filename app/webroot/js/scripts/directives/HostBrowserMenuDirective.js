angular.module('openITCOCKPIT').directive('hostBrowserMenu', function($http, $state){
    return {
        restrict: 'E',
        templateUrl: '/angular/hostBrowserMenu.html',
        scope: {
            'config': '='
        },

        controller: function($scope){

            //Example of $scope.config
            /*
            config = {
                //Pass all options manually
                showReschedulingButton: true,
                showBackButton: true,
                hostId: 1337,
                hostUuid: 'aaaa-bbbbb-ccccc-ddddd',
                docuExists: true,
                hostUrl: "https://openitcockpit.io",
                allowEdit: true,

                //Or enable autoload and the directive will load all required data by itself
                autoload: true
                hostId: 1337
            }
            */

            $scope.loadData = function(){
                $http.get("/angular/hostBrowserMenu/.json", {
                    params: {
                        'angular': true,
                        'hostId': $scope.config.hostId
                    }
                }).then(function(result){
                    $scope.config = result.data.config;

                }, function errorCallback(result){
                    if(result.status === 403){
                        $state.go('403');
                    }

                    if(result.status === 404){
                        $state.go('404');
                    }
                });
            };

            if($scope.config.hasOwnProperty('autoload') && $scope.config.hasOwnProperty('hostId')){
                if($scope.config.autoload === true){
                    $scope.loadData();
                }
            }

        }

    };
});