angular.module('openITCOCKPIT')
    .controller('SystemsettingsIndexController', function($scope, $http, NotyService){
        $scope.systemsettings = {};
        $scope.dropdownOptionSequence = [];

        $scope.init = true;
        $scope.load = function(){
            $http.get("/systemsettings/index.json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.systemsettings = result.data.all_systemsettings;
                $scope.init = false;
            });
        };

        $scope.getAnonymousStatisticsValue = function(state){
            switch(state){
                case '0':
                    return 'Anonymous statistics are disabled';
                    break;
                case '1':
                    return 'Anonymous statistics are enabled';
                    break;
                case '2':
                    return 'Anonymous statistics are disabled - Waiting for your approval';
                    break;
                default:
                    return 'Anonymous statistics are disabled - Waiting for your approval';
                    break;
            }
        };


        $scope.generateOptions = function(){
            for(var i = 1; i < 107; i++){
                $scope.dropdownOptionSequence.push(i);
            }
        };

        $scope.generateOptions();
        $scope.load();
    });