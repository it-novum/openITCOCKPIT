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
            $scope.init = true;

            $scope.loadBookmarks = function(){
                var params = {
                    'angular': true,
                    'plugin': $scope.phpplugin,
                    'controller': $scope.phpcontroller,
                    'action': $scope.phpaction,
                }
                if(typeof $scope.queryFilter !== "undefined"){
                    params['queryFilter'] = $scope.queryFilter;
                }
                $http.get("/filter_bookmarks/index.json", {
                    params: params
                }).then(function(result){
                        $scope.bookmarks = result.data.bookmarks || [];

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

            $scope.saveNewBookmark = function(){
                var post = {
                    name: $scope.newBookmarkName,
                    filter: JSON.stringify($scope.filter),
                    plugin: $scope.phpplugin,
                    controller: $scope.phpcontroller,
                    action: $scope.phpaction
                };

                var params = {
                    'angular': true,
                }

                $http.post("/filter_bookmarks/add.json", data, {
                    params: params
                }).then(function(result){
                    $scope.errors = null;
                    NotyService.genericSuccess();

                }, function errorCallback(result){
                    NotyService.genericError();

                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
            }

            $scope.saveBookmark = function(useAsDefault){
                // Refactor me
                $scope.init = true; //Disable watch
                var params = {
                    'angular': true,
                }
                $scope.bookmark.filter = $scope.filter;
                $scope.bookmark.plugin = $scope.phpplugin;
                $scope.bookmark.controller = $scope.phpcontroller;
                $scope.bookmark.action = $scope.phpaction;
                $scope.bookmark.name = $scope.name;

                if(useAsDefault){
                    // This bookmark should be the new default now
                    $scope.bookmark.default = useAsDefault;
                }
                var data = $scope.bookmark;
                $http.post("/filter_bookmarks/save.json", data, {
                    params: params
                }).then(function(result){
                    var bookmarks = result.data.bookmarks;
                    for(var index in bookmarks){
                        var item = bookmarks[index];
                        item.filter = JSON.parse(item.filter)
                    }

                    $scope.bookmarks = bookmarks;
                    result.data.bookmark.filter = JSON.parse(result.data.bookmark.filter)
                    $scope.bookmark = result.data.bookmark;
                    $scope.select = result.data.bookmark.id;
                    $scope.name = result.data.bookmark.name;


                    $scope.errors = null;
                    NotyService.genericSuccess();

                    // Update UUID in URL if required
                    if($scope.queryFilter){
                        if($scope.queryFilter !== $scope.bookmark.uuid){
                            $state.go($scope.stateName, {
                                filter: $scope.bookmark.uuid
                            })
                        }
                    }

                    //Do not trigger watch on change of $scope.select
                    setTimeout(function(){
                        $scope.init = false;
                    }, 250);
                }, function errorCallback(result){

                    NotyService.genericError();

                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
            };

            $scope.deleteBookmark = function(){
                // Refactor me
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
                // Refactor me
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
            $scope.loadBookmarks();

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
