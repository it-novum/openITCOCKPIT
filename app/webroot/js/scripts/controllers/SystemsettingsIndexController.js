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
                console.log($scope.systemsettings);
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

        $scope.toInt = function(value){
            return parseInt(value);
        };

        /*   $scope.explodeStr = function(string){
               var splittedArr = string.split('.');
               var result = splittedArr.splice(0, 3);
               console.log(result);


               return result;
               //return result.push(splittedArr.join('.'));
           };



           $scope.$watch('systemsettings', function(){
               if(typeof $scope.systemsettings == 'object'){
                   if(Object.keys($scope.systemsettings).length != 0){
                       console.log($scope.systemsettings);
                       for(var k in $scope.systemsettings){
                           for(var systemsetting in $scope.systemsettings[k]){
                               var currentKey = $scope.systemsettings[k][systemsetting].key;
                               $scope.systemsettings[k][systemsetting].splittedKey = $scope.explodeStr(currentKey);
                           }
                       }
                       console.log($scope.systemsettings);
                   }
               }
           }, true);
           */
        $scope.generateOptions();
        console.log($scope.dropdownOptionSequence);
        $scope.load();
    });