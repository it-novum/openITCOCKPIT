angular.module('openITCOCKPIT').directive('noticeWidget', function($http){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/noticeWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            var $widget = $('#widget-' + $scope.widget.id);

            $scope.ready = false;
            $scope.noticeTimeout = null;


            $widget.on('resize', function(event, items){
                hasResize();
            });

            $scope.load = function(options){

                options = options || {};
                options.save = options.save || false;

                $http.get("/dashboards/noticeWidget.json", {
                    params: {
                        'angular': true,
                        'recursive': true,
                        'widgetId': $scope.widget.id
                    }
                }).then(function(result){
                    resizeTextarea();
                    $scope.widget.WidgetNotice = {
                        note: result.data.config.note,
                        htmlContent: result.data.htmlContent
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
                    'note': $scope.widget.WidgetNotice.note
                };
                $http.post("/dashboards/noticeWidget.json?angular=true&widgetId=" + $scope.widget.id, settings).then(function(result){
                    $scope.load();
                    return true;
                });
            };

            $scope.$watch('widget.WidgetNotice.note', function(){
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
                    resizeTextarea();
                }, 500);
            };

            var resizeTextarea = function(){
                var height = $widget.height() - 34 - 13 - 26 - 13 - 10; //Unit: px
                //                              ^ widget Header
                //                                  ^ content padding top
                //                                       ^ button height
                //                                           ^ fancy flip padding top
                //                                                  ^ fancy flip padding bottom
                $widget.find('textarea').css({height: height + 'px'});
            }
        },

        link: function($scope, element, attr){

        }
    };
});
