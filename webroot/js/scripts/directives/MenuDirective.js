angular.module('openITCOCKPIT').directive('menu', function($http, $timeout, $httpParamSerializer, $state){
    return {
        restrict: 'A',
        templateUrl: '/angular/menu.html',
        scope: {
            phpplugin: '@',
            phpcontroller: '@',
            phpaction: '@'
        },

        controller: function($scope){
            $scope.menuLoaded = false;

            $scope.load = function(){

                $http.get("/angular/menu.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.menuLoaded = true;
                    $scope.menu = result.data.menu;
                });
            };
            $scope.scrollTop = function(){
                window.scroll(0, 0);
            };

            $scope.load();
        },

        link: function($scope, element, attrs){
            //Source: https://stackoverflow.com/a/24228604
            //Many thanks!

            // Trigger when number of children changes,
            // including by directives like ng-repeat

            $scope.menuInit = 0;
            var watch = $scope.$watch(function(){
                return element.children().children().length;
            }, function(){
                // Wait for templates to render
                $scope.$evalAsync(function(){
                    // Finally, directives are evaluated
                    // and templates are renderer here
                    if($scope.menuInit < 2){
                        $scope.menuInit++;
                        if($scope.menuLoaded){
                            $('#js-nav-menu').navigation({
                                accordion: 'true',
                                animate: 'easeOutExpo',
                                speed: 200,
                                closedSign: ' <em class="fas fa-angle-down"></em>',
                                openedSign: ' <em class="fas fa-angle-up"></em>',
                                initClass: 'js-nav-built'


                            });

                        }
                        /*

                        $(element).jarvismenu({
                            accordion: true,
                            speed: $.menu_speed,
                            closedSign: '<em class="fa fa-plus-square-o"></em>',
                            openedSign: '<em class="fa fa-minus-square-o"></em>'
                        });

                         */
                    }
                });
            });
        }
    };
});
