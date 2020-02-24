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
            $scope.menuFilter = '';
            $scope.currentMenu = [];
            $scope.menuMatches = [];
            $scope.menuFilterPosition = -1;
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

            $scope.isActiveChild = function(childNode) {
                let urlController = $scope.phpcontroller;
                let urlAction = $scope.phpaction;
                let urlPlugin = $scope.phpplugin;
                if (window.location.href.includes('/ng/#!/')) {
                    let oldUrlParams = window.location.href.split('/ng/#!/')[1].split('/');
                    if(oldUrlParams[0].includes('_module')){
                        urlPlugin = oldUrlParams[0];
                        urlController = oldUrlParams[1];
                        urlAction = oldUrlParams[2] ? oldUrlParams[2] : "index";
                    } else {
                        urlController = oldUrlParams[0];
                        urlAction = oldUrlParams[1] ? oldUrlParams[1] : "index";
                    }
                }
                if(childNode.url_array.plugin == urlPlugin){
                    if(childNode.url_array.controller === urlController){
                        if(childNode.url_array.action === urlAction){
                            return true;
                        }
                    }
                }
                return false;
            };

            $scope.isActiveParent = function(parentNode) {
                let urlController = $scope.phpcontroller;
                let urlAction = $scope.phpaction;
                let urlPlugin = $scope.phpplugin;
                if (window.location.href.includes('/ng/#!/')) {
                    let oldUrlParams = window.location.href.split('/ng/#!/')[1].split('/');
                    if(oldUrlParams[0].includes('_module')){
                        urlPlugin = oldUrlParams[0];
                        urlController = oldUrlParams[1];
                        urlAction = oldUrlParams[2] ? oldUrlParams[2] : "index";
                    } else {
                        urlController = oldUrlParams[0];
                        urlAction = oldUrlParams[1] ? oldUrlParams[1] : "index";
                    }
                }

                if(parentNode.url_array && parentNode.url_array.plugin == urlPlugin){
                    if(parentNode.url_array.controller === urlController){
                        if(parentNode.url_array.action === urlAction){
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
                    if($scope.menuMatches[$scope.menuFilterPosition].isAngular === "1"){
                        window.location.href = "/ng/#!"+$scope.menuMatches[$scope.menuFilterPosition].url;
                        return;
                    }
                    window.location.href = $scope.menuMatches[$scope.menuFilterPosition].url;
                    return;
                }

                if(keyCode === RETURN_KEY && $scope.menuFilterPosition === -1){
                    $state.go('HostsIndex', {
                        hostname: $scope.menuFilter
                    });
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
                searchString = searchString.toLowerCase().replace(/ /g,'');
                for(var parentKey in $scope.menu){
                    if($scope.menu[parentKey].children.length === 0){
                        //Search parent records, that have no child elements
                        var parentTitle = $scope.menu[parentKey].title.toLowerCase().replace(/ /g,'');
                        if(parentTitle.match(searchString)){
                            $scope.menuMatches.push($scope.menu[parentKey]);
                        }
                    }

                    //Search in child items
                    for(var childKey in $scope.menu[parentKey].children){
                        var title = $scope.menu[parentKey].children[childKey].title.toLowerCase().replace(/ /g,'');
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
