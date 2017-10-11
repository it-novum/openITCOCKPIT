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

                    $timeout(function(){
                        $scope.menu = [
                            {
                                "url": "/",
                                "title": "Foobar",
                                "icon": "user",
                                "order": 1,
                                "children": [

                                ],
                                "url_array": {
                                    "controller": "dashboards",
                                    "action": "index",
                                    "plugin": ""
                                },
                                "id": "dashboard"
                            }
                    ];
                    }, 10000);

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

        link: function(scope, element, attr){
        }
    };
});