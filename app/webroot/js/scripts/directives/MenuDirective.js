angular.module('openITCOCKPIT').directive('menu', function($http, $timeout){
    return {
        restrict: 'A',
        templateUrl: '/angular/menu.html',
        scope: {
            phpplugin: '@',
            phpcontroller: '@',
            phpaction: '@'
        },

        controller: function($scope){
            $scope.currentMenu = [];

            $scope.load = function(){
                $http.get("/angular/menu.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.menu = result.data.menu;
                });
            };

            $scope.isActiveChild = function(childNode){
                if(childNode.url_array.plugin === $scope.phpplugin){
                    if(childNode.url_array.controller === $scope.phpcontroller){
                        if(childNode.url_array.action === $scope.phpaction){
                            return true;
                        }
                    }
                }
                return false;
            };

            $scope.isActiveParent = function(parentNode){
                if(parentNode.url_array.plugin === $scope.phpplugin){
                    if(parentNode.url_array.controller === $scope.phpcontroller){
                        if(parentNode.url_array.action === $scope.phpaction){
                            return true;
                        }
                    }
                }

                if(parentNode.children.length > 0){
                    for(var index in parentNode.children){
                        if($scope.isActiveChild(parentNode.children[index])){
                            return true;
                        }
                    }
                }

                return false;
            };

            $scope.isActiveParentStyle = function(parentNode){
                if($scope.isActiveParent(parentNode)){
                    return 'display:block;';
                }
                return '';
            };

            $scope.parentHref = function(parentNode){
                if(parentNode.children.length > 0){
                    return '#';
                }
                return parentNode.url;
            };


            $scope.load();

        },

        link: function($scope, element, attrs){
            //Source: https://stackoverflow.com/a/24228604
            //Many thanks!

            // Trigger when number of children changes,
            // including by directives like ng-repeat
            var watch = $scope.$watch(function(){
                return element.children().children().length;
            }, function(){
                // Wait for templates to render
                $scope.$evalAsync(function(){
                    // Finally, directives are evaluated
                    // and templates are renderer here
                    $(element).jarvismenu({
                        accordion: true,
                        speed: $.menu_speed,
                        closedSign: '<em class="fa fa-plus-square-o"></em>',
                        openedSign: '<em class="fa fa-minus-square-o"></em>'
                    });
                });
            });
        },
    };
});