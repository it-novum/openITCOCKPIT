angular.module('openITCOCKPIT').directive('mapWidget', function($http){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors/mapWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            /** public vars **/
            $scope.init = true;
            $scope.map = {
                map_id: null
            };


            $scope.load = function(){
                $http.get("/map_module/mapeditors/mapWidget.json", {
                    params: {
                        'angular': true,
                        'widgetId': $scope.widget.id
                    }
                }).then(function(result){
                    $scope.map.map_id = result.data.config.map_id;

                    //Do not trigger watch on page load
                    setTimeout(function(){
                        $scope.init = false;
                    }, 250);
                });
            };

            $scope.loadMaps = function(searchString){
                $http.get("/map_module/mapeditors/loadMapsByString.json", {
                    params: {
                        'angular': true,
                        'filter[Maps.name]': searchString,
                        'selected[]': $scope.map.map_id
                    }
                }).then(function(result){
                    $scope.availableMaps = result.data.maps;
                });
            };

            $scope.hideConfig = function(){
                $scope.$broadcast('FLIP_EVENT_IN');
            };
            $scope.showConfig = function(){
                $scope.$broadcast('FLIP_EVENT_OUT');
                $scope.loadMaps('');
            };

            $scope.saveMap = function(){
                $http.post("/map_module/mapeditors/mapWidget.json?angular=true",
                    {
                        Widget: {
                            id: $scope.widget.id
                        },
                        map_id: $scope.map.map_id
                    }
                ).then(function(result){
                    //Update status
                    $scope.hideConfig();
                });
            };


            /** Page load / widget get loaded **/
            $scope.load();


        },

        link: function($scope, element, attr){

        }
    };
});
