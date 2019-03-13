angular.module('openITCOCKPIT')
    .controller('HostescalationsIndexController', function($scope, $http){

        $scope.currentPage = 1;
        $scope.useScroll = true;
        $scope.init = true;
        $scope.deleteUrl = '/hostescalations/delete/';

        $scope.load = function(){

            $http.get("/hostescalations/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'page': $scope.currentPage
                }
            }).then(function(result){
                $scope.hostescalations = result.data.all_hostescalations;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;

                $scope.init = false;
            });

        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        $scope.getObjectForDelete = function(hostescalation){
            var object = {};
            object[hostescalation.Hostescalation.id] = $scope.objectName + hostescalation.Hostescalation.id;
            return object;
        };

        //Fire on page load
        $scope.load();
    });
