angular.module('openITCOCKPIT').directive('automapWidget', function($http){
    return {
        restrict: 'E',
        templateUrl: '/automaps/automapWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            $scope.init = true;
            $scope.automap = {
                automap_id: null
            };

            $scope.load = function(){
                $http.get("/automaps/automapWidget.json", {
                    params: {
                        'angular': true,
                        'widgetId': $scope.widget.id
                    }
                }).then(function(result){
                    $scope.automap.automap_id = result.data.config.automap_id;
                    console.log($scope.automap.automap_id = result.data.config.automap_id);

                    //Do not trigger watch on page load
                    setTimeout(function(){
                        $scope.init = false;
                    }, 250);
                });
            };





            $scope.loadAutoMaps = function(searchString){
                $http.get("/automaps/loadAutomapsByString.json", {
                    params: {
                        'angular': true,
                        'filter[Automap.name]': searchString,
                        'selected[]': $scope.automap.automap_id
                    }
                }).then(function(result){
                    console.log($scope.Automaps = result.data.automaps);
                    $scope.Automaps = result.data.automaps;
                });
            };

            $scope.showConfig = function(){
                $scope.$broadcast('FLIP_EVENT_OUT');
                $scope.loadAutoMaps('');
            };

            $scope.hideConfig = function(){
                $scope.$broadcast('FLIP_EVENT_IN');
            };

            $scope.saveAutomap = function(){
                $http.post("/automaps/automapWidget.json?angular=true",
                    {
                        Widget: {
                            id: $scope.widget.id
                        },
                        automap_id: $scope.automap.automap_id
                    }
                ).then(function(result){
                    //Update status
                    $scope.hideConfig();
                });
            };


            $scope.load();

        },

        link: function($scope, element, attr){

        }


    }

});
