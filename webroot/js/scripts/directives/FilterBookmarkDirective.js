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

            var changeBookmark = function(bookmark){
                $scope.bookmark = bookmark;
                var filter = bookmark.filter;

                // Trigger load method in main controller
                $scope.loadCallback(filter);
            };

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
                        var bookmarks = result.data.bookmarks || [];

                        //Parse the JSON string into objects for all filters
                        for(var i in bookmarks){
                            bookmarks[i].filter = JSON.parse(bookmarks[i].filter);
                        }
                        $scope.bookmarks = bookmarks;

                        var filter = $scope.filter; //Should be the default filter on page load

                        // Trigger load method in main controller
                        $scope.loadCallback(filter);
                    },
                    function(error){
                        console.log(error.data.message);
                        //NotyService.genericError();
                    });
            };

            $scope.saveNewBookmark = function(){
                var post = {
                    name: $scope.bookmark.name,
                    filter: JSON.parse(JSON.stringify($scope.filter)), //Get clone not reference
                    plugin: $scope.phpplugin,
                    controller: $scope.phpcontroller,
                    action: $scope.phpaction
                };

                var params = {
                    'angular': true,
                }

                $http.post("/filter_bookmarks/add.json", post, {
                    params: params
                }).then(function(result){
                    var bookmark = result.data.bookmark;
                    bookmark.filter = JSON.parse(bookmark.filter);

                    // Add new bookmark to local array of bookmarks
                    $scope.bookmarks.push(bookmark);
                    $scope.bookmark = bookmark;
                    $scope.selectedBookmarkId = bookmark.id;

                    $scope.errors = null;
                    NotyService.genericSuccess();
                    $('#createNewBookmarkModal').modal('hide');

                }, function errorCallback(result){
                    NotyService.genericError();

                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
            }

            $scope.updateBookmark = function(){
                if(!$scope.bookmark){
                    NotyService.genericError({
                        message: 'No bookmark selected'
                    });
                    return;
                }

                var params = {
                    'angular': true,
                }

                $http.post("/filter_bookmarks/edit/" + $scope.bookmark.id + ".json", $scope.bookmark, {
                    params: params
                }).then(function(result){
                    $scope.errors = null;
                    NotyService.genericSuccess();
                    $('#editBookmarkModal').modal('hide');

                }, function errorCallback(result){
                    NotyService.genericError();

                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });

            }

            $scope.deleteBookmark = function(){
                if(!$scope.bookmark.id){
                    NotyService.genericError({
                        message: 'Nothing to delete!',
                        timeout: 1000
                    });
                    return;
                }

                var params = {
                    'angular': true,
                }

                $http.post("/filter_bookmarks/delete/" + $scope.bookmark.id + ".json", {}, {
                    params: params
                }).then(function(result){
                        $('#deleteBookmarkModal').modal('hide');

                        // Remove deleted bookmark from local array
                        for(var i in $scope.bookmarks){
                            if($scope.bookmarks[i].id === $scope.bookmark.id){
                                $scope.bookmarks.splice(i, 1);
                            }
                        }

                        $scope.bookmark = null;
                        var filter = undefined;

                        // Trigger load method in main controller
                        $scope.loadCallback(filter);
                    },
                    function(error){
                        NotyService.genericError({});
                        if(result.data.hasOwnProperty('error')){
                            $scope.errors = result.data.error;
                        }
                        console.log(error.data.message);
                    });
            };

            $scope.showNewBookmarkModel = function(){
                $scope.bookmark = {
                    name: '',
                    filter: $scope.filter
                };

                $('#createNewBookmarkModal').modal('show');
            };

            $scope.computeBookmarkUrl = function(){
                $scope.filterUrl = '';
                if($scope.bookmark.uuid !== ''){
                    $scope.filterUrl = $state.href($scope.stateName, {filter: $scope.bookmark.uuid}, {absolute: true});
                }
            };


            /**** ****/
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


            $scope.copy2Clipboard = function(){
                navigator.clipboard.writeText($scope.filterUrl);
            };

            // Fire on page load
            $scope.loadBookmarks();

            $scope.$watch('selectedBookmarkId', function(){
                if(typeof $scope.selectedBookmarkId !== "undefined"){
                    for(var index in $scope.bookmarks){
                        console.log(bookmark);
                        var bookmark = $scope.bookmarks[index];
                        if(bookmark.id === $scope.selectedBookmarkId){
                            changeBookmark(bookmark);
                            break;
                        }
                    }
                }
            });

        },

        link: function(scope, element, attr){

        }
    };
});
