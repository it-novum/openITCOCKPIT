angular.module('openITCOCKPIT')
    .controller('HostsBrowserController', function($scope, $http, QueryStringService){

        $scope.id = QueryStringService.getCakeId();


        $scope.init = true;

        $scope.hostStatusTextClass = 'txt-primary';

        $scope.load = function(){
            $http.get("/hosts/browser/"+$scope.id+".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.mergedHost = result.data.mergedHost;
                $scope.hoststatus = result.data.hoststatus;
                $scope.hoststateForIcon = {
                    Hoststatus: $scope.hoststatus
                };

                $scope.mainContainer = result.data.mainContainer;
                $scope.sharedContainers = result.data.sharedContainers;
                $scope.hostStatusTextClass = getHoststatusTextColor();
                console.log( $scope.hostStatusTextClass);

                $scope.init = false;
            });
        };

        var getHoststatusTextColor = function(){
            console.log($scope.hoststatus.currentState);
            switch($scope.hoststatus.currentState){
                case 0:
                case '0':
                    return 'txt-color-green';

                case 1:
                case '1':
                    return 'txt-color-red';

                case 2:
                case '2':
                    return 'txt-color-blueLight';
            }
            return 'txt-primary';
        };

        $scope.load();

    });