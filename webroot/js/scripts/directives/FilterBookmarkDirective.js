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
                $scope.selectedBookmarkId = bookmark.id;

                // Trigger load method in main controller
                $scope.loadCallback(filter);
            };

            $scope.loadBookmarks = function(selectedBookmark){
                $scope.init = true;

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

                        // Trigger load method in main controller
                        if(typeof selectedBookmark === "undefined"){
                            $scope.loadCallback();
                        }else if($scope.queryFilter && result.data.bookmark !== null){
                            // Set the bookmark from the URL as current selected bookmark

                        }else{
                            //Load passed bookmark (used by Save as new filter)
                            changeBookmark(selectedBookmark);
                        }

                        // This fixes two requests on "Save as new filter"
                        setTimeout(function(){
                            $scope.init = false;
                        }, 250)
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

                $scope.init = true;
                $http.post("/filter_bookmarks/add.json", post, {
                    params: params
                }).then(function(result){
                    var bookmark = result.data.bookmark;
                    bookmark.filter = JSON.parse(bookmark.filter);

                    $scope.errors = null;
                    NotyService.genericSuccess();
                    $('#createNewBookmarkModal').modal('hide');

                    // When creating a new bookmark, we HAVE TO reload all bookmarks, this is due to
                    // javascript's object binding.
                    // Example: The user has selected "Filter 1" from the select / dropdown list, and is now changing some filters. (Pick a checkbox or so)
                    // The user clicks on "Save as new filter", and openITCOCKPIT will create and save the new Filter as "New Filter".
                    // Also the system will set is as selected filter in the select box.
                    // When the user is now selecting "Filter 1" again, the interface will display the exact same filters as "New Filter" has.
                    // This is due to the user has changed $scope.filter of "Filter 1".
                    // "Filter 1" is still Ok in the database, so we simply need to reload all filters.
                    //
                    // Probably someone smarter than me can fix this easily.

                    // This does not work as expected - read above
                    //// Add new bookmark to local array of bookmarks
                    //$scope.bookmarks.push(bookmark);
                    //$scope.bookmark = bookmark;
                    //$scope.selectedBookmarkId = bookmark.id;

                    $scope.loadBookmarks(bookmark);
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


            $scope.copy2Clipboard = function(){
                navigator.clipboard.writeText($scope.filterUrl);
            };

            // Fire on page load
            $scope.loadBookmarks();

            $scope.$watch('selectedBookmarkId', function(){
                if($scope.init){
                    return;
                }

                if(typeof $scope.selectedBookmarkId !== "undefined"){
                    for(var index in $scope.bookmarks){
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
