angular.module('openITCOCKPIT').directive('sidebar', function($http, $timeout, $httpParamSerializer, $state){
    return {
        restrict: 'E',
        templateUrl: '/angular/sidebar.html',
        scope: {
            systemname: '@',
            userFullName: '@',
            userImage: '@'
        },

        controller: function($scope){
            $scope.menuFilterPosition = -1;

            $scope.setMenuMinify = function(boolstate = true){
                if(boolstate){
                    localStorage.setItem('menuStateMinify', '1');
                }else{
                    localStorage.setItem('menuStateMinify', '0');
                }
            };

            $scope.resetMenuFilterSelectorPosition = function(){
                $('li.js-filter-show').each(function(index){
                    $(this).removeClass('search_list_item_active');
                });
                $scope.menuFilterPosition = -1;
            };

            $scope.resetMenuFilterClasses = function(list){
                var listPrev = $(list).next().filter('.js-filter-message');
                $(list).find('[data-filter-tags]').parentsUntil(list).removeClass('js-filter-hide js-filter-show');

                /* if element exists reset print results */
                if(listPrev){
                    listPrev.text("");
                }
            };

            $scope.clearAndCloseMenu = function(){
                $('#nav_filter_input').val('');
                $('.page-sidebar').removeClass('list-filter-active');
                $scope.resetMenuFilterSelectorPosition();
                $scope.resetMenuFilterClasses($('#js-nav-menu'));
            };

            $scope.navigate = function($event){
                const RETURN_KEY = 13;
                const ARROW_KEY_UP = 38;
                const ARROW_KEY_DOWN = 40;
                const ESC = 27;
                var keyCode = $event.keyCode;
                var filteredObjects = $('li.js-filter-show.menufilterSelectable');

                if(keyCode === RETURN_KEY && $scope.menuFilterPosition > -1){
                    filteredObjects.each(function(index){
                        if(index === $scope.menuFilterPosition){
                            var selectedObj = $(":first-child", this);
                            if(selectedObj && selectedObj[0] && selectedObj[0].href !== undefined && selectedObj[0].href != null){
                                window.location.href = $(":first-child", this)[0].href;
                                $scope.resetMenuFilterSelectorPosition();
                                return true;
                            }
                        }
                    });
                    return;
                }

                if(keyCode === RETURN_KEY && $scope.menuFilterPosition === -1){
                    $state.go('HostsIndex', {
                        hostname: $('#nav_filter_input').val().toLowerCase()
                    });
                }

                if(keyCode === ESC){
                    $scope.clearAndCloseMenu();
                    return;
                }

                if(keyCode !== ARROW_KEY_UP && keyCode !== ARROW_KEY_DOWN){
                    return;
                }

                if(keyCode === ARROW_KEY_DOWN && $scope.menuFilterPosition + 1 < filteredObjects.length){
                    $scope.menuFilterPosition++;
                }

                if(keyCode === ARROW_KEY_UP && $scope.menuFilterPosition - 1 >= 0){
                    $scope.menuFilterPosition--;
                }

                filteredObjects.each(function(index){
                    if(index === $scope.menuFilterPosition){
                        $(this).addClass('search_list_item_active');
                    }else{
                        $(this).removeClass('search_list_item_active');
                    }
                });
            };

            /**
             * List filter
             * DOC: searches list items, it could be UL or DIV elements
             * usage: initApp.listFilter($('.list'), $('#intput-id'));
             *        inside the .list you will need to insert 'data-filter-tags' inside <a>
             * @param  list
             * @param  input
             * @param  anchor
             * @return
             */
            $scope.listFilter = function(list, input, anchor){
                /* add class to filter hide/show */
                if(anchor){
                    $(anchor).addClass('js-list-filter');
                }else{
                    $(list).addClass('js-list-filter');
                }

                /* on change keyboard */
                $(input).change(function(){

                    var filter = $(this).val().toLowerCase();

                    /* when user types more than 1 letter start search filter */
                    if(filter.length > 1){

                        /* this finds all data-filter-tags in a list that contain the input val,
                           hiding the ones not containing the input while showing the ones that do */

                        /* (1) hide all that does not match */
                        $(list).find($("[data-filter-tags]:not([data-filter-tags*='" + filter + "'])"))
                            .parentsUntil(list).removeClass('js-filter-show')
                            .addClass('js-filter-hide');

                        /* (2) hide all that does match */
                        $(list).find($("[data-filter-tags*='" + filter + "']"))
                            .parentsUntil(list).removeClass('js-filter-hide')
                            .addClass('js-filter-show');
                        var filterToCompare = filter.toLowerCase().replace(/ /g, '');
                        $(list).find($("[title]").filter(function(){
                            var titleToCompare = this.title.toLowerCase().replace(/ /g, '');
                            return (titleToCompare.match(filterToCompare) ? this : null);
                        })).parentsUntil(list)
                            .removeClass('js-filter-hide')
                            .addClass('js-filter-show');

                        /* if element exists then print results */
                        var listPrev = $(list).next().filter('.js-filter-message');
                        if(listPrev){
                            listPrev.text("showing " + $(list).find('li.js-filter-show').length + " from " + $(list).find('[data-filter-tags]').length + " total");
                        }

                    }else{
                        $scope.resetMenuFilterSelectorPosition();
                        /* when filter length is blank reset the classes */
                        $scope.resetMenuFilterClasses(list);
                    }

                    return false;

                }).keyup($.debounce(myapp_config.filterDelay, function(e){

                    /* fire the above change event after every letter is typed with a delay of 250ms */
                    $(this).change();

                    /*if(e.keyCode == 13) {
                        console.log( $(list).find(".filter-show:not(.filter-hide) > a") );
                    }*/

                }));
            };

            $scope.listFilter($('#js-nav-menu'), $('#nav_filter_input'), $('#js-primary-nav'));
        },

        link: function($scope, element, attrs){

        }
    };
});
