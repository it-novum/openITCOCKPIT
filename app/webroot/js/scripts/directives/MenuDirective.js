angular.module('openITCOCKPIT').directive('menu', function($http, $timeout, $httpParamSerializer){
    return {
        restrict: 'A',
        templateUrl: '/angular/menu.html',
        scope: {
            phpplugin: '@',
            phpcontroller: '@',
            phpaction: '@'
        },

        controller: function($scope){
            $scope.menuFilter = '';
            $scope.currentMenu = [];
            $scope.menuMatches = [];
            $scope.menuFilterPosition = -1;

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

            $scope.navigate = function($event){
                const RETURN_KEY = 13;
                const ARROW_KEY_UP = 38;
                const ARROW_KEY_DOWN = 40;
                var keyCode = $event.keyCode;

                if(keyCode === RETURN_KEY && $scope.menuFilterPosition > -1){
                    window.location.href = $scope.menuMatches[$scope.menuFilterPosition].url;
                    return;
                }

                if(keyCode === RETURN_KEY && $scope.menuFilterPosition === -1){
                    window.location.href = '/hosts/index?filter[Host.name]=' + rawurlencode($scope.menuFilter);
                }

                if(keyCode !== ARROW_KEY_UP && keyCode !== ARROW_KEY_DOWN){
                    return;
                }

                if(keyCode === ARROW_KEY_DOWN && $scope.menuFilterPosition + 1 < $scope.menuMatches.length){
                    $scope.menuFilterPosition++;
                }

                if(keyCode === ARROW_KEY_UP && $scope.menuFilterPosition - 1 >= 0){
                    $scope.menuFilterPosition--;
                }

            };


            $scope.load();

            $scope.$watch('menuFilter', function(){
                var searchString = $scope.menuFilter;
                if(searchString.length === 0){
                    $scope.menuMatches = [];
                    $scope.menuFilterPosition = -1;
                    return;
                }

                $scope.menuMatches = [];
                $scope.menuFilterPosition = -1;
                searchString = searchString.toLowerCase();
                for(var parentKey in $scope.menu){
                    if($scope.menu[parentKey].children.length === 0){
                        //Search parent records, that have no child elements
                        var parentTitle = $scope.menu[parentKey].title.toLowerCase();
                        if(parentTitle.match(searchString)){
                            $scope.menuMatches.push($scope.menu[parentKey]);
                        }
                    }

                    //Search in child items
                    for(var childKey in $scope.menu[parentKey].children){
                        var title = $scope.menu[parentKey].children[childKey].title.toLowerCase();
                        if(title.match(searchString)){
                            $scope.menuMatches.push($scope.menu[parentKey].children[childKey]);
                        }
                    }
                }
            });

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
                        $(element).jarvismenu({
                            accordion: true,
                            speed: $.menu_speed,
                            closedSign: '<em class="fa fa-plus-square-o"></em>',
                            openedSign: '<em class="fa fa-minus-square-o"></em>'
                        });
                    }
                });
            });
        }
    };
});