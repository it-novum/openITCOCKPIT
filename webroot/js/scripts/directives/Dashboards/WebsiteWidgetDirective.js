angular.module('openITCOCKPIT').directive('websiteWidget', function($http){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/websiteWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            var $widget = $('#widget-' + $scope.widget.id);

            var getWidgetHeight = function(){
                return $widget.innerHeight() - 20 - 68; // remove button and padding
            };


            $scope.widgetHeight = getWidgetHeight();

            $scope.ready = false;
            $scope.noticeTimeout = null;


            $widget.on('resize', function(event, items){
                hasResize();
            });

            $scope.load = function(options){

                options = options || {};
                options.save = options.save || false;

                $http.get("/dashboards/websiteWidget.json", {
                    params: {
                        'angular': true,
                        'recursive': true,
                        'widgetId': $scope.widget.id
                    }
                }).then(function(result){
                    $scope.widget.WidgetWebsite = {
                        url: result.data.url
                    };

                    $scope.init = false;
                    setTimeout(function(){
                        $scope.ready = true;
                    }, 500);
                });
            };

            $scope.load();

            $scope.showConfig = function(){
                $scope.$broadcast('FLIP_EVENT_IN');
            };
            $scope.hideConfig = function(){
                $scope.$broadcast('FLIP_EVENT_OUT');
            };

            $scope.saveSettings = function(){
                var settings = {
                    'url': $scope.widget.WidgetWebsite.url
                };
                $http.post("/dashboards/websiteWidget.json?angular=true&widgetId=" + $scope.widget.id, settings).then(function(result){
                    $scope.load();
                    return true;
                });
            };

            $scope.$watch('widget.WidgetWebsite.url', function(){
                if($scope.ready === true){
                    $scope.saveSettings();
                }
            });

            var hasResize = function(){
                if($scope.noticeTimeout){
                    clearTimeout($scope.noticeTimeout);
                }
                $scope.noticeTimeout = setTimeout(function(){
                    $scope.noticeTimeout = null;
                }, 500);
                $scope.widgetHeight = getWidgetHeight();
            };
        },

        link: function($scope, element, attr){

        }
    };
});
