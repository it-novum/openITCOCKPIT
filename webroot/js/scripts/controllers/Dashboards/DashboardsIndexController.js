angular.module('openITCOCKPIT')
    .controller('DashboardsIndexController', function($scope, $http, $timeout, $interval){

        /** public vars **/
        $scope.init = true;
        $scope.activeTab = null;
        $scope.availableWidgets = [];
        $scope.fullscreen = false;
        $scope.errors = {};
        $scope.intervalText = 'disabled';
        $scope.dashboardIsLocked = false;

        $scope.data = {
            newTabName: '',
            createTabFromSharedTabId: null,
            viewTabRotateInterval: 0,
            renameTabName: '',
            renameWidgetTitle: ''
        };

        $scope.gridsterOpts = {
            minRows: 2, // the minimum height of the grid, in rows
            maxRows: 999,
            columns: 12, // the width of the grid, in columns
            colWidth: 'auto', // can be an integer or 'auto'.  'auto' uses the pixel width of the element divided by 'columns'
            //rowHeight: 'match', // can be an integer or 'match'.  Match uses the colWidth, giving you square widgets.
            rowHeight: 25,
            margins: [10, 10], // the pixel distance between each widget
            defaultSizeX: 2, // the default width of a gridster item, if not specifed
            defaultSizeY: 1, // the default height of a gridster item, if not specified
            mobileBreakPoint: 600, // if the screen is not wider that this, remove the grid layout and stack the items
            resizable: {
                enabled: true,
                start: function(event, uiWidget, $element){
                }, // optional callback fired when resize is started,
                resize: function(event, uiWidget, $element){
                }, // optional callback fired when item is resized,
                stop: function(event, uiWidget, $element){
                } // optional callback fired when item is finished resizing
            },
            draggable: {
                enabled: true, // whether dragging items is supported
                handle: '.ui-sortable-handle', // optional selector for resize handle
                start: function(event, uiWidget, $element){
                }, // optional callback fired when drag is started,
                drag: function(event, uiWidget, $element){
                }, // optional callback fired when item is moved,
                stop: function(event, uiWidget, $element){
                } // optional callback fired when item is finished dragging
            }
        };


        /** private vars **/
        var tabSortCreated = false;
        var intervalId = null;
        var disableWatch = false;
        var watchTimeout = null;

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

        $scope.enableWatch = function(){
            setTimeout(function(){
                disableWatch = false;
            }, 500);
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

                $scope.data.viewTabRotateInterval = result.data.tabRotationInterval;
                updateInterval();

                $scope.availableWidgets = result.data.widgets;
                createTabSort();

                $scope.loadTabContent($scope.activeTab);
                $scope.askForHelp = result.data.askForHelp;

                $scope.init = false;
            });
        };

        $scope.loadTabContent = function(tabId){
            disableWatch = true;
            $http.get("/dashboards/getWidgetsForTab/" + tabId + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.activeTab = tabId;

                for(var k in $scope.tabs){
                    if($scope.tabs[k].id === $scope.activeTab){
                        if($scope.tabs[k].locked === true){
                            $scope.dashboardIsLocked = true;
                            $scope.gridsterOpts.resizable.enabled = false;
                            $scope.gridsterOpts.draggable.enabled = false;
                        }else{
                            $scope.dashboardIsLocked = false;
                            $scope.gridsterOpts.resizable.enabled = true;
                            $scope.gridsterOpts.draggable.enabled = true;
                        }
                        break;
                    }
                }

                var widgets = [];
                for(var i in result.data.widgets.Widget){
                    widgets.push({
                        sizeX: parseInt(result.data.widgets.Widget[i].width, 10),
                        sizeY: parseInt(result.data.widgets.Widget[i].height, 10),
                        col: parseInt(result.data.widgets.Widget[i].col, 10),
                        row: parseInt(result.data.widgets.Widget[i].row, 10),

                        id: parseInt(result.data.widgets.Widget[i].id, 10),
                        icon: result.data.widgets.Widget[i].icon,
                        title: result.data.widgets.Widget[i].title,
                        color: result.data.widgets.Widget[i].color,
                        directive: result.data.widgets.Widget[i].directive
                    });
                }

                $scope.activeWidgets = widgets;

                //Check for updates if are available if this is a shared tab
                for(var k in $scope.tabs){
                    if($scope.tabs[k].id === $scope.activeTab && $scope.tabs[k].source_tab_id > 0){
                        if($scope.tabs[k].check_for_updates === true){
                            checkForUpdates($scope.activeTab);
                        }
                    }
                }

                //Disable watch for some time to give angular time to render the template
                //Will avoid a saveGrid method call in load (or tab switch)
                //Moved to $scope.enableWatch()
                /*
                setTimeout(function(){
                    disableWatch = false;
                }, 500);*/
            });
        };


        $scope.saveGrid = function(){
            if($scope.dashboardIsLocked){
                return;
            }

            if($scope.activeWidgets.length === 0){
                return;
            }

            var postData = [];
            for(var i in $scope.activeWidgets){
                postData.push({
                    Widget: {
                        id: $scope.activeWidgets[i].id,
                        dashboard_tab_id: $scope.activeTab,
                        row: $scope.activeWidgets[i].row,
                        col: $scope.activeWidgets[i].col,
                        width: $scope.activeWidgets[i].sizeX,
                        height: $scope.activeWidgets[i].sizeY,
                        title: $scope.activeWidgets[i].title,
                        color: $scope.activeWidgets[i].color
                    }
                });
            }

            $http.post("/dashboards/saveGrid/.json?angular=true", postData).then(
                function(result){
                    genericSuccess();
                    return true;
                }, function errorCallback(result){
                    genericError();
                });
        };

        $scope.addWidgetToTab = function(typeId){
            if($scope.dashboardIsLocked){
                return;
            }

            postData = {
                Widget: {
                    dashboard_tab_id: $scope.activeTab,
                    typeId: typeId
                }
            };
            $http.post("/dashboards/addWidgetToTab/.json?angular=true", postData).then(
                function(result){
                    $scope.activeWidgets.push({
                        sizeX: parseInt(result.data.widget.Widget.width, 10),
                        sizeY: parseInt(result.data.widget.Widget.height, 10),
                        col: parseInt(result.data.widget.Widget.col, 10),
                        row: parseInt(result.data.widget.Widget.row, 10),

                        id: parseInt(result.data.widget.Widget.id, 10),
                        icon: result.data.widget.Widget.icon,
                        title: result.data.widget.Widget.title,
                        directive: result.data.widget.Widget.directive,
                        color: result.data.widget.Widget.color
                    });
                    return true;
                }, function errorCallback(result){
                    genericError();
                });
        };

        $scope.removeWidgetFromTab = function(id){
            if($scope.dashboardIsLocked){
                return;
            }

            postData = {
                Widget: {
                    id: id,
                    dashboard_tab_id: $scope.activeTab
                }
            };


            $http.post("/dashboards/removeWidgetFromTab/.json?angular=true", postData).then(
                function(result){
                    var currentWidgets = [];
                    for(var i in $scope.activeWidgets){
                        if($scope.activeWidgets[i].id == id){
                            $scope.activeWidgets.splice(i, 1);

                            //We are done here
                            break;
                        }
                    }
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
                        name: $scope.data.newTabName
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

        $scope.addFromSharedTab = function(){
            $http.post("/dashboards/createFromSharedTab.json?angular=true",
                {
                    DashboardTab: {
                        id: $scope.data.createTabFromSharedTabId
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

        $scope.triggerRenameTabModal = function(currentTabName){
            if($scope.dashboardIsLocked){
                return;
            }

            $('#renameTabModal').modal('show');
            $scope.data.renameTabName = currentTabName;
        };

        $scope.renameTab = function(){
            $http.post("/dashboards/renameDashboardTab.json?angular=true",
                {
                    DashboardTab: {
                        id: $scope.activeTab,
                        name: $scope.data.renameTabName
                    }
                }
            ).then(function(result){
                $scope.errors = {};
                for(var i in $scope.tabs){
                    if($scope.tabs[i].id === $scope.activeTab){
                        $scope.tabs[i].name = $scope.data.renameTabName;
                    }
                }
                genericSuccess();
                $('#renameTabModal').modal('hide');
            }, function errorCallback(result){
                $scope.errors = result.data.error;
                genericError();
            });
        };

        $scope.deleteTab = function(tabId){
            $http.post("/dashboards/deleteDashboardTab.json?angular=true",
                {
                    DashboardTab: {
                        id: tabId
                    }
                }
            ).then(function(result){
                genericSuccess();

                for(var i in $scope.tabs){
                    if($scope.tabs[i].id === $scope.activeTab){
                        $scope.tabs.splice(i, 1);
                        //We are done here
                        break;
                    }
                }

                if(typeof $scope.tabs[0] !== 'undefined'){
                    $scope.loadTabContent($scope.tabs[0].id);
                }else{
                    //All tabs where removed.
                    //Reload page to get new default tab
                    window.location.href = '/';
                }
            }, function errorCallback(result){
                $scope.errors = result.data.error;
                genericError();
            });
        };

        $scope.startSharing = function(tabId){
            $http.post("/dashboards/startSharing.json?angular=true",
                {
                    DashboardTab: {
                        id: tabId
                    }
                }
            ).then(function(result){
                new Noty({
                    theme: 'metroui',
                    type: 'info',
                    layout: 'topCenter',
                    text: 'Your dashboard is now shared. Other users of the system can use your shared dashboard tab as an template.',
                    timeout: 3500
                }).show();

                for(var i in $scope.tabs){
                    if($scope.tabs[i].id === $scope.activeTab){
                        $scope.tabs[i].shared = true;
                    }
                }
            }, function errorCallback(result){
                $scope.errors = result.data.error;
                genericError();
            });
        };

        $scope.stopSharing = function(tabId){
            $http.post("/dashboards/stopSharing.json?angular=true",
                {
                    DashboardTab: {
                        id: tabId
                    }
                }
            ).then(function(result){
                genericSuccess();
                for(var i in $scope.tabs){
                    if($scope.tabs[i].id === $scope.activeTab){
                        $scope.tabs[i].shared = false;
                    }
                }
            }, function errorCallback(result){
                $scope.errors = result.data.error;
                genericError();
            });
        };

        $scope.loadSharedTabs = function(){
            $http.get("/dashboards/getSharedTabs.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.sharedTabs = result.data.tabs;
            });
        };


        $scope.saveTabRotateInterval = function(){
            $http.post("/dashboards/saveTabRotateInterval.json?angular=true",
                {
                    User: {
                        dashboard_tab_rotation: $scope.data.viewTabRotateInterval
                    }
                }
            ).then(function(result){
                $scope.errors = {};
                genericSuccess();
                updateInterval();
            }, function errorCallback(result){
                $scope.errors = result.data.error;
                genericError();
            });
        };

        $scope.neverPerformUpdates = function(){
            $http.post("/dashboards/neverPerformUpdates.json?angular=true",
                {
                    DashboardTab: {
                        id: $scope.activeTab
                    }
                }
            ).then(function(result){
                for(var k in $scope.tabs){
                    if($scope.tabs[k].id === $scope.activeTab && $scope.tabs[k].source_tab_id > 0){
                        $scope.tabs[k].check_for_updates = false;
                        break;
                    }
                }
                genericSuccess();
                $('#updateAvailableModal').modal('hide');
            }, function errorCallback(result){
                genericError();
            });
        };

        $scope.performUpdate = function(){
            $http.post("/dashboards/updateSharedTab.json?angular=true",
                {
                    DashboardTab: {
                        id: $scope.activeTab
                    }
                }
            ).then(function(result){
                //Update local json
                for(var k in $scope.tabs){
                    if($scope.tabs[k].id === $scope.activeTab){
                        $scope.tabs[k].locked = result.data.DashboardTab.DashboardTab.locked;
                        $scope.dashboardIsLocked = result.data.DashboardTab.DashboardTab.locked;
                        $scope.gridsterOpts.resizable.enabled = !$scope.dashboardIsLocked;
                        $scope.gridsterOpts.draggable.enabled = !$scope.dashboardIsLocked;
                        break;
                    }
                }

                genericSuccess();
                $('#updateAvailableModal').modal('hide');
                $scope.loadTabContent($scope.activeTab);
            }, function errorCallback(result){
                genericError();
            });
        };

        $scope.triggerRenameWidgetModal = function(widgetId){
            if($scope.dashboardIsLocked){
                return;
            }

            $scope.data.renameWidgetTitle = '';
            for(var i in $scope.activeWidgets){
                if($scope.activeWidgets[i].id === widgetId){
                    $scope.currentWidgetId = widgetId;
                    $scope.data.renameWidgetTitle = $scope.activeWidgets[i].title;
                    break;
                }
            }
            $('#renameWidgetModal').modal('show');
        };

        $scope.renameWidget = function(){
            if(typeof $scope.currentWidgetId === 'undefined' || $scope.currentWidgetId === null){
                genericError();
                return;
            }

            $http.post("/dashboards/renameWidget.json?angular=true",
                {
                    Widget: {
                        id: $scope.currentWidgetId,
                        name: $scope.data.renameWidgetTitle
                    }
                }
            ).then(function(result){
                $scope.errors = {};
                for(var i in $scope.activeWidgets){
                    if($scope.activeWidgets[i].id === $scope.currentWidgetId){
                        $scope.activeWidgets[i].title = $scope.data.renameWidgetTitle;
                    }
                }
                $scope.currentWidgetId = null;
                genericSuccess();
                $('#renameWidgetModal').modal('hide');
            }, function errorCallback(result){
                $scope.errors = result.data.error;
                genericError();
            });
        };

        $scope.restoreDefault = function(){
            $http.post("/dashboards/restoreDefault.json?angular=true",
                {
                    DashboardTab: {
                        id: $scope.activeTab
                    }
                }
            ).then(function(result){
                genericSuccess();
                $scope.loadTabContent($scope.activeTab);
            }, function errorCallback(result){
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
                        if($tab.data('tab-id')){
                            var tabId = parseInt($tab.data('tab-id'), 10);
                            tabIdsOrdered.push(tabId);
                        }
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
            if($scope.tabs.length === 0){
                return;
            }

            var nextTabId = $scope.tabs[0].id; //Just in case we rotated through all tabs, just the first tab
            var index = 0;
            for(var i in $scope.tabs){ //var index because i is a string and for no reason I don't want to parseInt it.
                if($scope.tabs[i].id === $scope.activeTab){
                    //Check if next tab exist
                    var nextIndex = index + 1;
                    if(typeof $scope.tabs[nextIndex] !== 'undefined'){
                        nextTabId = $scope.tabs[nextIndex].id;
                        break;
                    }
                }
                index++;
            }
            disableWatch = true;
            $('#updateAvailableModal').modal('hide');
            $scope.loadTabContent(nextTabId);
        };

        var updateInterval = function(){
            if(intervalId !== null){
                $interval.cancel(intervalId);
            }

            if($scope.data.viewTabRotateInterval > 0){
                intervalId = $interval(rotateTab, ($scope.data.viewTabRotateInterval * 1000));
            }
        };

        var checkForUpdates = function(tabId){
            $http.get("/dashboards/checkForUpdates.json", {
                params: {
                    'angular': true,
                    'tabId': tabId
                }
            }).then(function(result){
                if(result.data.updateAvailable === true){
                    $('#updateAvailableModal').modal('show');
                }
            });
        };

        $scope.lockOrUnlockDashboard = function(){
            $scope.dashboardIsLocked = $scope.dashboardIsLocked !== true;
            $scope.gridsterOpts.resizable.enabled = !$scope.dashboardIsLocked;
            $scope.gridsterOpts.draggable.enabled = !$scope.dashboardIsLocked;

            //Update local json data
            for(var k in $scope.tabs){
                if($scope.tabs[k].id === $scope.activeTab){
                    $scope.tabs[k].locked = $scope.dashboardIsLocked;
                    break;
                }
            }

            $http.post("/dashboards/lockOrUnlockTab.json?angular=true",
                {
                    DashboardTab: {
                        id: $scope.activeTab,
                        locked: $scope.dashboardIsLocked.toString()
                    }
                }
            ).then(function(result){
                genericSuccess();
            }, function errorCallback(result){
                genericError();
            });

        };


        /** On Load stuff **/
        $scope.$watch('data.viewTabRotateInterval', function(){
            if($scope.init){
                return;
            }

            if($scope.data.viewTabRotateInterval === 0){
                $scope.intervalText = 'disabled';
            }else{
                var min = parseInt($scope.data.viewTabRotateInterval / 60, 10);
                var sec = parseInt($scope.data.viewTabRotateInterval % 60, 10);
                if(min > 0){
                    $scope.intervalText = min + ' minutes, ' + sec + ' seconds';
                    return;
                }
                $scope.intervalText = sec + ' seconds';
            }
        });

        $scope.$watch('activeWidgets', function(){
            //console.log(disableWatch);
            if($scope.init === true || disableWatch === true){
                return;
            }

            if(watchTimeout !== null){
                $timeout.cancel(watchTimeout);
            }

            watchTimeout = $timeout(function(){
                $scope.saveGrid();
            }, 1500);
        }, true);

        $scope.load();
    });
