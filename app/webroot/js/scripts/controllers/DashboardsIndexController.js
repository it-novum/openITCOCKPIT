angular.module('openITCOCKPIT')
    .controller('DashboardsIndexController', function($scope, $http){

        $scope.init = true;
        $scope.activeTab = null;
        $scope.availableWidgets = [];
        $scope.gridstack = null;
        $scope.fullscreen = false;

        var genericError = function(){
            new Noty({
                theme: 'metroui',
                type: 'error',
                text: 'Error while saving data',
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

                $scope.availableWidgets = result.data.widgets;

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
                $scope.activeWidgets = result.data.widgets;
            });
        };

        $scope.renderGrid = function(){
            //This method gets called from the index.ctp template!
            var $gridstack = $('.grid-stack');
            $gridstack.gridstack({
                float: true,
                cellHeight: 10
            });

            $gridstack.on('change', function(event, items){
                $scope.saveGrid(items);
            });
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

        $scope.checkDashboardLock = function(){

        };

        $scope.load();
    });