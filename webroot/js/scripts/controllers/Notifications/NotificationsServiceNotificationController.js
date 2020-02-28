angular.module('openITCOCKPIT')
    .controller('NotificationsServiceNotificationController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, QueryStringService, $stateParams){

        SortService.setSort(QueryStringService.getValue('sort', 'NotificationServices.start_time'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;

        $scope.id = $stateParams.id;
        $scope.useScroll = true;

        var now = new Date();

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                NotificationServices: {
                    state: {
                        ok: false,
                        warning: false,
                        critical: false,
                        unknown: false
                    },
                    output: ''
                },
                from: date('d.m.Y H:i', now.getTime() / 1000 - (3600 * 24 * 30)),
                to: date('d.m.Y H:i', now.getTime() / 1000 + (3600 * 24 * 30 * 2))
            };
        };
        /*** Filter end ***/

        $scope.init = true;
        $scope.showFilter = false;

        $scope.serviceBrowserMenuConfig = {
            autoload: true,
            serviceId: $scope.id,
            includeServicestatus: true
        };

        $scope.load = function(){

            $http.get("/notifications/serviceNotification/" + $scope.id + ".json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[NotificationServices.output]': $scope.filter.NotificationServices.output,
                    'filter[NotificationServices.state][]': $rootScope.currentStateForApi($scope.filter.NotificationServices.state),
                    'filter[from]': $scope.filter.from,
                    'filter[to]': $scope.filter.to
                }
            }).then(function(result){
                $scope.notifications = result.data.all_notifications;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function(){
            defaultFilter();
        };


        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);

        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.load();
        }, true);
    });