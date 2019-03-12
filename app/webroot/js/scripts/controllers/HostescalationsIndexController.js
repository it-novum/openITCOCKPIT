angular.module('openITCOCKPIT')
    .controller('HostescalationsIndexController', function($scope, $http){

        $scope.currentPage = 1;
        $scope.useScroll = true;
        $scope.init = true;

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

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.hostescalations){
                for(var id in selectedObjects){
                    if(id == $scope.hostescalations[key].Hostescalation.id){
                        if($scope.hostescalations[key].Hostescalation.allow_edit === true){
                            objects[id] = $scope.hostescalations[key].Hostescalation.id;
                        }
                    }
                }
            }
            return objects;
        };

        //Fire on page load
        $scope.load();
    });
