angular.module('openITCOCKPIT').directive('filterBookmark', function($http, $location, NotyService, $state){
    return {
        restrict: 'E',
        templateUrl: '/filter_bookmarks/directive.html',
        scope: {
            filter: '=',
            phpplugin: '@',
            phpcontroller: '@',
            phpaction: '@',
            loadCallback: '=',
            stateName: '@'
        },
        controller: function($scope){
            $scope.queryFilter = $location.search().filter;
            $scope.bookmarks = [];
            $scope.select = 0;
            $scope.showFilterUrl = false;
            $scope.filterUrl = '';
            $scope.name = '';

            $scope.init = true;

            $scope.bookmark = {
                id: null,
                uuid: '',
                name: '',
                plugin: '',
                controller: '',
                action: '',
                user_id: 0,
                filter: {},
                default: false
            };

            var changeBookmark = function(bookmark){
                $scope.bookmark = bookmark;
                var filter = bookmark.filter;
                $scope.name = bookmark.name; // ?? can we remove this?

                // Trigger load method in main controller
                $scope.loadCallback(filter);
            };

            $scope.saveBookmark = function(useAsDefault){
                var params = {
                    'angular': true,
                }
                $scope.bookmark.filter = $scope.filter;
                $scope.bookmark.plugin = $scope.phpplugin;
                $scope.bookmark.controller = $scope.phpcontroller;
                $scope.bookmark.action = $scope.phpaction;
                $scope.bookmark.name = $scope.name;
                $scope.bookmark.default = useAsDefault;
                var data = $scope.bookmark;
                $http.post("/filter_bookmarks/add.json", data, {
                    params: params
                }).then(function(result){
                    var bookmarks = result.data.bookmarks ?? [];
                    bookmarks.forEach(function(item, index){
                        item.filter = JSON.parse(item.filter);
                        if(item.id === result.data.lastBookmarkId ?? 0){
                            $scope.bookmark = item;
                            $scope.select = item.id;
                        }
                    });
                    $scope.bookmarks = bookmarks;
                    $scope.errors = null;

                    NotyService.genericSuccess({
                        //message: 'Filter saved!',
                        // timeout: timeout
                    });
                }, function errorCallback(result){

                    NotyService.genericError();

                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
            };

            $scope.getBookmarks = function(){
                var params = {
                    'angular': true,
                    'plugin': $scope.phpplugin,
                    'controller': $scope.phpcontroller,
                    'action': $scope.phpaction,
                }
                if($scope.queryFilter !== "undefined"){
                    params.queryFilter = $scope.queryFilter;
                }
                $http.get("/filter_bookmarks/index.json", {
                    params: params
                }).then(function(result){
                        var bookmarks = result.data.bookmarks || [];
                        var filter = $scope.filter; // should be empty on page load

                        for(var index in bookmarks){
                            var item = bookmarks[index];
                            item.filter = JSON.parse(item.filter)

                            if(item.default){
                                // This is the default filter
                                filter = item.filter;
                                $scope.select = item.id;
                                $scope.bookmark = item;
                            }
                        }

                        $scope.bookmarks = bookmarks;

                        // Trigger load method in main controller
                        $scope.loadCallback(filter);

                        //Do not trigger watch on page load
                        setTimeout(function(){
                            $scope.init = false;
                        }, 250);

                    },
                    function(error){
                        console.log(error.data.message);
                        //NotyService.genericError();
                    });
            };

            $scope.deleteBookmark = function(){
                $scope.init = true;
                $('#deleteBookmarkModal').modal('hide');
                var params = {
                    'angular': true,
                }
                var data = $scope.bookmark;
                if(!data.id){
                    NotyService.genericError({
                        message: 'Nothing to delete!',
                        timeout: 1000
                    });
                    return;
                }
                $http.post("/filter_bookmarks/delete.json", data, {
                    params: params
                }).then(function(result){
                        $scope.bookmarkReset();
                        var bookmarks = result.data.bookmarks || [];
                        var filter = undefined

                        for(var index in bookmarks){
                            var item = bookmarks[index];
                            item.filter = JSON.parse(item.filter)

                            if(item.default){
                                // This is the default filter
                                filter = item.filter;
                                $scope.select = item.id;
                                $scope.bookmark = item;
                            }
                        }

                        $scope.bookmarks = bookmarks;

                        // Trigger load method in main controller
                        $scope.loadCallback(filter);

                        //Do not trigger watch
                        setTimeout(function(){
                            $scope.init = false;
                        }, 250);
                    },
                    function(error){
                        NotyService.genericError({});
                        if(result.data.hasOwnProperty('error')){
                            $scope.errors = result.data.error;
                        }
                        console.log(error.data.message);
                    });
            };

            $scope.bookmarkReset = function(){
                $scope.bookmark = {
                    id: null,
                    uuid: '',
                    name: '',
                    plugin: null,
                    controller: '',
                    action: '',
                    user_id: 0,
                    filter: {},
                    default: false
                }
                $scope.select = 0;
                $scope.filterUrl = '';
                $scope.name = '';
            };

            $scope.computeBookmarkUrl = function(){
                $scope.filterUrl = '';
                if($scope.bookmark.uuid !== ''){
                    $scope.filterUrl = $state.href($scope.stateName, {filter: $scope.bookmark.uuid}, {absolute: true});
                }
            };

            $scope.copy2Clipboard = function(){
                navigator.clipboard.writeText($scope.filterUrl);
            };

            // Fire on page load
            $scope.getBookmarks();

            $scope.$watch('select', function(){
                if($scope.init){
                    return;
                }

                //Bookmark has changed.
                for(var index in $scope.bookmarks){
                    var item = $scope.bookmarks[index];
                    if(item.id === $scope.select){
                        changeBookmark($scope.bookmarks[index]);
                        break;
                    }
                }
            });

        },

        link: function(scope, element, attr){

        }
    };
});
