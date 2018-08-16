angular.module('openITCOCKPIT').directive('trafficlightWidget', function($http){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/trafficLightWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            var $widget = $('#widget-' + $scope.widget.id);
            $scope.ready = false;
            $scope.trafficlightTimeout = null;

            $scope.post = {
                Service : {
                    id: null
                }
            };

            $widget.on('resize', function(event, items){
                hasResize();
            });

            $scope.load = function(options){
                options = options || {};
                options.save = options.save || false;

                $http.get("/dashboards/trafficLightWidget.json", {
                    params: {
                        'angular': true,
                        'recursive': true,
                        'widgetId': $scope.widget.id
                    }
                }).then(function(result){
                    $scope.post.Service.id = result.data.serviceId;

                    $scope.init = false;
                    setTimeout(function(){
                        $scope.ready = true;
                    }, 500);
                });
            };

            $scope.load();

            $scope.hideConfig = function() {
                $scope.$broadcast('FLIP_EVENT_IN');
            };
            $scope.showConfig = function() {
                $scope.$broadcast('FLIP_EVENT_OUT');
                $scope.loadServices('');
            };

            $scope.loadServices = function(searchString){
                $http.get("/services/loadServicesByString.json", {
                    params: {
                        'angular': true,
                        'filter[Host.name]': searchString,
                        'filter[Service.servicename]': searchString,
                        'selected[]': $scope.post.Service.id
                    }
                }).then(function (result) {
                    $scope.services = result.data.services;
                });
            };




            $scope.saveSettings = function(){
                console.log($scope.post.Service.id);
                var settings = {
                    'serviceId': $scope.post.Service.id
                };
                $http.post("/dashboards/trafficLightWidget.json?angular=true&widgetId=" + $scope.widget.id, settings).then(function(result){
                    //$scope.load();
                    console.log(result);
                    return true;
                });
            };

            $scope.$watch('post.Service.id', function(){
                if($scope.ready === true){
                    $scope.saveSettings();
                }
            });

            var hasResize = function(){
                if($scope.trafficlightTimeout){
                    clearTimeout($scope.trafficlightTimeout);
                }
                $scope.trafficlightTimeout = setTimeout(function(){
                    $scope.trafficlightTimeout = null;
                }, 500);
            };


        },

        link: function($scope, element, attr){

        }
    };
});
