angular.module('openITCOCKPIT').directive('filterBookmark', function($http, $location, NotyService){
    return {
        restrict: 'E',
        templateUrl: '/filter_bookmarks/directive.html',
        scope: {
            filter: '=',
            phpplugin: '@',
            phpcontroller: '@',
            phpaction: '@',
            loadCallback: '=',
        },
        controller: function($scope){
            $scope.queryFilter = $location.search().filter,
            $scope.bookmarks = [];
            $scope.bookmarkError = '';
            $scope.select = 0;
            $scope.showFilterUrl = false;
            $scope.filterUrl = '';
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

            $scope.saveBookmark = function(){
                var params = {
                    'angular': true,
                    'plugin': $scope.phpplugin,
                    'controller': $scope.phpcontroller,
                    'action': $scope.phpaction
                }
                $scope.bookmark.filter = $scope.filter;
                $scope.bookmark.plugin = $scope.phpplugin;
                $scope.bookmark.controller = $scope.phpcontroller;
                $scope.bookmark.action = $scope.phpaction;
                var data = $scope.bookmark;
                $http.post("/filter_bookmarks/add.json", data, {
                    params: params
                }).then(function(result){
                        var bookmarks = result.data.bookmarks ?? [];
                        var existDefault = false;
                        var filter = undefined;
                        bookmarks.forEach(function(item, index){
                            item.filter = JSON.parse(item.filter);
                            if(item.default === true){
                                filter = item.filter;
                                $scope.bookmark = item;
                                $scope.select = item.id;
                                //$scope.setTagInputs();
                                existDefault = true;
                            }
                            if(!existDefault){
                                // $scope.resetFilter();
                                $scope.bookmarkReset();
                            }

                        });
                        $scope.bookmarks = bookmarks;
                        $scope.bookmarkError = '';
                        NotyService.genericSuccess({
                            message: 'Filter saved!',
                            // timeout: timeout
                        });
                        $scope.loadCallback(filter);

                    },
                    function(error){
                        $scope.bookmarkError = error.data.message;
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
                    $scope.bookmarks = result.data.bookmarks;

                    var filter = $scope.filter;
                    for(var index in $scope.bookmarks){
                        var item = $scope.bookmarks[index];
                        item.filter = JSON.parse(item.filter)
                        if(item.default === true && result.data.bookmark === null){
                            filter = item.filter;
                            $scope.bookmark = item;
                            $scope.select = item.id;
                        }
                    }
                    if(result.data.bookmark !== null) {
                        filter = JSON.parse(result.data.bookmark.filter);
                        $scope.bookmark =  result.data.bookmark;
                        $scope.bookmark.name = 'ExternalFilter'
                        $scope.bookmark.filter = filter;
                    }
                    // Trigger load method in main controller
                    $scope.loadCallback(filter);
                },
                function(error){
                    console.log(error.data.message);
                });
            };


            $scope.itemChanged = function(){
                $scope.bookmarks.forEach(function(item, index){
                    if(item.id === $scope.select){
                        $scope.bookmark = item;
                        var filter = item.filter;
                        // Trigger load method in main controller
                        $scope.loadCallback(filter);
                    }
                });
            }

            $scope.deleteBookmark = function(){
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
                        var bookmarks = result.data.bookmarks;
                        var defaultItem = false;
                        var filter = $scope.filter;
                        bookmarks.forEach(function(item, index){
                            item.filter = JSON.parse(item.filter);
                            if(item.default === true){
                                defaultItem = true;
                                filter = item.filter;
                                $scope.bookmark = item;
                                $scope.select = item.id;
                            }
                        });
                        if(!defaultItem){
                           // $scope.resetFilter();
                            $scope.bookmarkReset();
                        }
                        $scope.bookmarks = bookmarks;
                        $scope.loadCallback(filter);
                    },
                    function(error){
                        console.log(error.data.message);
                    });
            };

            $scope.loadDefaultFilterBookmark = function(){

                var params = {
                    'angular': true,
                    'type': 'host'
                }
                var data = {
                    filter: filterId
                }
                $http.post("/filter_bookmarks/default.json", data, {
                    params: params
                }).then(function(result){
                        if(result.data.bookmark && result.data.bookmark.filter){
                            $scope.filter = JSON.parse(result.data.bookmark.filter);
                        }
                        $scope.load();
                        $scope.setTagInputs();
                    },
                    function(error){
                        console.log(error.data.message);
                        $scope.load();
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
            };

            $scope.showBookmarkFilterUrl = function (){
                $scope.showFilterUrl = !$scope.showFilterUrl === true;
                $scope.computeBookmarkUrl();
            };

            $scope.computeBookmarkUrl = function() {
                    $scope.filterUrl = $location.absUrl() + '?filter=' + $scope.bookmark.uuid;
            };

            $scope.copy2Clipboard = function (){
                var copyText = document.getElementById("filterUrl");
                copyText.select();
                navigator.clipboard.writeText(copyText.value);
            };

            // Fire on page load
            $scope.getBookmarks();
        },


        link: function(scope, element, attr){

        }
    };
});
