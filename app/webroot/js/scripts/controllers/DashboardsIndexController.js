angular.module('openITCOCKPIT')
    .controller('DashboardsIndexController', function($scope, $http){

        /** public vars **/
        $scope.init = true;
        $scope.activeTab = null;
        $scope.availableWidgets = [];
        $scope.gridstack = null;
        $scope.fullscreen = false;
        $scope.errors = {};
        $scope.viewTabRotateInterval = 0;
        $scope.intervalText = 'disabled';


        /** private vars **/
        var $gridstack = null;
        var tabSortCreated = false;
        var intervalId = null;

        var genericError = function(){
            new Noty({
                theme: 'metroui',
                type: 'error',
                text: 'Error while saving data',
                timeout: 3500
            }).show();
        };

        var genericSuccess = function(){
            new Noty({
                theme: 'metroui',
                type: 'success',
                text: 'Data saved successfully',
                timeout: 3500
            }).show();
        };

        $scope.load = function(){
            $http.get("/dashboards/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){

                $scope.tabs = result.data.tabs;
                if($scope.activeTab === null){
                    $scope.activeTab = $scope.tabs[0].id;
                }

                $scope.viewTabRotateInterval = result.data.tabRotationInterval;
                updateInterval();

                $scope.availableWidgets = result.data.widgets;
                createTabSort();

                $scope.loadTabContent($scope.activeTab);

                $scope.init = false;
            });
        };

        $scope.loadTabContent = function(tabId){
            $http.get("/dashboards/getWidgetsForTab/" + tabId + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.activeTab = tabId;
                $scope.activeWidgets = result.data.widgets;
            });
        };

        $scope.renderGrid = function(){
            // This method gets called from the index.ctp template!
            if($gridstack === null){

                //First page load
                $gridstack = $('.grid-stack');

                $gridstack.gridstack({
                    float: true,
                    cellHeight: 10,
                    draggable: {
                        handle: '.jarviswidget header[role="heading"]'
                    }
                });

                $gridstack.on('change', function(event, items){
                    if(typeof items !== 'undefined'){
                        if(Array.isArray(items)){
                            $scope.saveGrid(items);
                        }
                    }
                });
            }
        };

        $scope.saveGrid = function(items){
            $scope.checkDashboardLock();

            var postData = [];
            for(var i in items){
                postData.push({
                    Widget: {
                        id: items[i].id,
                        dashboard_tab_id: $scope.activeTab,
                        row: items[i].y,
                        col: items[i].x,
                        width: items[i].width,
                        height: items[i].height
                    }
                });
            }

            $http.post("/dashboards/saveGrid/.json?angular=true", postData).then(
                function(result){
                    return true;
                }, function errorCallback(result){
                    genericError();
                });
        };

        $scope.addWidgetToTab = function(typeId){
            postData = {
                Widget: {
                    dashboard_tab_id: $scope.activeTab,
                    typeId: typeId
                }
            };
            $http.post("/dashboards/addWidgetToTab/.json?angular=true", postData).then(
                function(result){
                    $scope.activeWidgets.Widget.push(result.data.widget.Widget);
                    //Wait a bit, that angular can render the template
                    setTimeout(function(){
                        var el = document.getElementById('widget-' + result.data.widget.Widget.id);
                        var grid = $gridstack.data('gridstack');
                        grid.addWidget(
                            $(el),
                            result.data.widget.Widget.row,
                            result.data.widget.Widget.col,
                            result.data.widget.Widget.width,
                            result.data.widget.Widget.height,
                            undefined,
                            undefined,
                            undefined,
                            undefined,
                            undefined,
                            result.data.widget.Widget.id
                        );

                    }, 250);
                    return true;
                }, function errorCallback(result){
                    genericError();
                });
        };

        $scope.removeWidgetFromTab = function(id){
            postData = {
                Widget: {
                    id: id,
                    dashboard_tab_id: $scope.activeTab
                }
            };


            $http.post("/dashboards/removeWidgetFromTab/.json?angular=true", postData).then(
                function(result){
                    var currentWidgets = [];
                    for(var i in $scope.activeWidgets.Widget){
                        if($scope.activeWidgets.Widget[i].id != id){
                            currentWidgets.push($scope.activeWidgets.Widget[i]);
                        }
                    }

                    var el = document.getElementById('widget-' + id);
                    var grid = $gridstack.data('gridstack');
                    grid.removeWidget(el);

                    $scope.activeWidgets.Widget = currentWidgets;
                }, function errorCallback(result){
                    genericError();
                });
        };

        $scope.refresh = function(){
            $scope.load();
        };

        $scope.toggleFullscreenMode = function(){
            var elem = document.getElementById('widget-container');
            if($scope.fullscreen === true){
                $scope.fullscreen = false;
                if(document.exitFullscreen){
                    document.exitFullscreen();
                }else if(document.webkitExitFullscreen){
                    document.webkitExitFullscreen();
                }else if(document.mozCancelFullScreen){
                    document.mozCancelFullScreen();
                }else if(document.msExitFullscreen){
                    document.msExitFullscreen();
                }
            }else{
                if(elem.requestFullscreen){
                    elem.requestFullscreen();
                }else if(elem.mozRequestFullScreen){
                    elem.mozRequestFullScreen();
                }else if(elem.webkitRequestFullscreen){
                    elem.webkitRequestFullscreen();
                }else if(elem.msRequestFullscreen){
                    elem.msRequestFullscreen();
                }

                $('#widget-container').css({
                    'width': $(window).width(),
                    'height': $(window).height()
                });

                $scope.fullscreen = true;
            }
        };

        $scope.addNewTab = function(){
            $http.post("/dashboards/addNewTab.json?angular=true",
                {
                    DashboardTab: {
                        name: $scope.newTabName
                    }
                }
            ).then(function(result){
                genericSuccess();

                $scope.activeTab = parseInt(result.data.DashboardTab.DashboardTab.id, 10);
                $scope.load();
                $('#addNewTabModal').modal('hide');
            }, function errorCallback(result){
                $scope.errors = result.data.error;
                genericError();
            });
        };

        $scope.saveTabRotateInterval = function(){
            $http.post("/dashboards/saveTabRotateInterval.json?angular=true",
                {
                    User: {
                        dashboard_tab_rotation: $scope.viewTabRotateInterval
                    }
                }
            ).then(function(result){
                genericSuccess();
                updateInterval();

            }, function errorCallback(result){
                $scope.errors = result.data.error;
                genericError();
            });
        };

        if(document.addEventListener){
            document.addEventListener('webkitfullscreenchange', fullscreenExitHandler, false);
            document.addEventListener('mozfullscreenchange', fullscreenExitHandler, false);
            document.addEventListener('fullscreenchange', fullscreenExitHandler, false);
            document.addEventListener('MSFullscreenChange', fullscreenExitHandler, false);
        }

        function fullscreenExitHandler(){
            if(document.webkitIsFullScreen === false || document.mozFullScreen === false || document.msFullscreenElement === false){
                $scope.fullscreen = false;
                $('#widget-container').css({
                    'width': '100%',
                    'height': '100%'
                });
            }
        }

        var createTabSort = function(){
            if(tabSortCreated === true){
                return;
            }

            tabSortCreated = true;
            $('.nav-tabs').sortable({
                update: function(){
                    var $tabbar = $(this);
                    var $tabs = $tabbar.children();
                    var tabIdsOrdered = [];
                    $tabs.each(function(key, tab){
                        var $tab = $(tab);
                        var tabId = parseInt($tab.data('tab-id'), 10);
                        tabIdsOrdered.push(tabId);
                    });
                    $http.post("/dashboards/saveTabOrder.json?angular=true",
                        {
                            order: tabIdsOrdered
                        }
                    ).then(function(result){
                        genericSuccess();
                    }, function errorCallback(result){
                        genericError();
                    });
                },
                placeholder: 'tabTargetDestination'
            });

        };

        var rotateTab = function(){
            console.log('Implement me!');
        };

        var updateInterval = function(){
            if(intervalId !== null){
                $interval.cancel(intervalId);
            }

            if($scope.viewTabRotateInterval > 0){
                intervalId = $interval(rotateTab, ($scope.viewTabRotateInterval * 1000));
            }
        };

        $scope.checkDashboardLock = function(){

        };

        /** On Load stuff **/
        $scope.$watch('viewTabRotateInterval', function(){
            if($scope.init){
                return;
            }

            if($scope.viewTabRotateInterval === 0){
                $scope.intervalText = 'disabled';
            }else{
                var min = parseInt($scope.viewTabRotateInterval / 60, 10);
                var sec = parseInt($scope.viewTabRotateInterval % 60, 10);
                if(min > 0){
                    $scope.intervalText = min + ' minutes, ' + sec + ' seconds';
                    return;
                }
                $scope.intervalText = sec + ' seconds';
            }
        });

        $scope.load();
    });