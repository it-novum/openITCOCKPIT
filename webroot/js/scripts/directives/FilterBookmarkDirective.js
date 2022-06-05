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
            $scope.default = false;

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
                }
                $scope.bookmark.filter = $scope.filter;
                $scope.bookmark.plugin = $scope.phpplugin;
                $scope.bookmark.controller = $scope.phpcontroller;
                $scope.bookmark.action = $scope.phpaction;
                $scope.bookmark.name = $scope.name;
                $scope.bookmark.default = $scope.default;
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

                        NotyService.genericSuccess({
                            //message: 'Filter saved!',
                            // timeout: timeout
                        });
                        $scope.computeBookmarkUrl();
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
                    $scope.bookmarks = result.data.bookmarks ?? [];

                    var filter = $scope.filter;
                    for(var index in $scope.bookmarks){
                        var item = $scope.bookmarks[index];
                        item.filter = JSON.parse(item.filter)
                        if(item.default === true && result.data.bookmark === null){
                            filter = item.filter;
                            $scope.bookmark = item;
                            $scope.select = item.id;
                            $scope.name = item.name;
                            $scope.default = item.default;
                        }
                    }
                    if(result.data.bookmark !== null) {
                        filter = JSON.parse(result.data.bookmark.filter);
                        $scope.bookmark =  result.data.bookmark;
                        $scope.bookmark.filter = filter;
                        $scope.name = 'ExternalFilter';
                    }
                    // Trigger load method in main controller
                    $scope.loadCallback(filter);
                },
                function(error){
                    console.log(error.data.message);
                    //NotyService.genericError();
                });
            };


            $scope.itemChanged = function(){
                var filter = $scope.filter;
                for(var index in $scope.bookmarks){
                    var item = $scope.bookmarks[index];
                    if(item.id === $scope.select){
                        $scope.bookmark = item;
                        filter = item.filter;
                        $scope.name = item.name;
                        $scope.default = item.default;
                    }
                }
                // Trigger load method in main controller
                $scope.computeBookmarkUrl();
                $scope.loadCallback(filter);
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
                        var filter = undefined;
                        bookmarks.forEach(function(item, index){
                            item.filter = JSON.parse(item.filter);
                            if(item.default === true){
                                defaultItem = true;
                                filter = item.filter;
                                $scope.bookmark = item;
                                $scope.select = item.id;
                                $scope.name = item.name;
                                $scope.default = item.default;
                                $scope.computeBookmarkUrl();
                            }
                        });
                        if(!defaultItem){
                            $scope.bookmarkReset();
                        }
                        $scope.bookmarks = bookmarks;
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
                $scope.form.name = '';
                $scope.form.default = false;
                $scope.filterUrl = '';
            };

            $scope.showBookmarkFilterUrl = function (){
                $scope.showFilterUrl = !$scope.showFilterUrl === true;
                $scope.computeBookmarkUrl();
            };

            $scope.computeBookmarkUrl = function() {
                if($scope.bookmark.uuid !== ''){
                    $scope.filterUrl = $state.href($scope.stateName, {filter: $scope.bookmark.uuid}, {absolute: true});
                } else {
                    $scope.filterUrl = '';
                }
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
