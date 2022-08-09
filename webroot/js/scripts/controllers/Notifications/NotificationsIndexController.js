angular.module('openITCOCKPIT')
    .controller('NotificationsIndexController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, QueryStringService){

        SortService.setSort(QueryStringService.getValue('sort', 'NotificationHosts.start_time'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;

        var now = new Date();

        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                NotificationHosts: {
                    output: '',
                    state: {
                        recovery: false,
                        down: false,
                        unreachable: false
                    },
                },
                Hosts: {
                    name: ''
                },
                Contacts: {
                    name: ''
                },
                Commands: {
                    name: ''
                },
                from:  date('d.m.Y H:i' ,now.getTime() / 1000 - (3600 * 24 * 30)),
                to: date('d.m.Y H:i', now.getTime() / 1000 + (3600 * 24 * 30 * 2))
            };
            var from = new Date(now.getTime() - (3600 * 24 * 30 * 1000));
            from.setSeconds(0);
            var to = new Date(now.getTime() + (3600 * 24 * 30 * 2 * 1000));
            to.setSeconds(0);
            $scope.from_time = from;
            $scope.to_time = to;
        };
        /*** Filter end ***/

        $scope.init = true;
        $scope.showFilter = false;


        $scope.load = function(){

            $http.get("/notifications/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[NotificationHosts.output]': $scope.filter.NotificationHosts.output,
                    'filter[NotificationHosts.state][]': $rootScope.currentStateForApi($scope.filter.NotificationHosts.state),
                    'filter[Contacts.name]': $scope.filter.Contacts.name,
                    'filter[Commands.name]': $scope.filter.Commands.name,
                    'filter[Hosts.name]': $scope.filter.Hosts.name,
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

        $scope.$watch('from_time', function(dateObject){
            if(dateObject !== undefined && dateObject instanceof Date){
                var dateString = date('d.m.Y H:i', dateObject.getTime() / 1000);
                $scope.filter.from = dateString;
            }
        });
        $scope.$watch('to_time', function(dateObject){
            if(dateObject !== undefined && dateObject instanceof Date){
                var dateString = date('d.m.Y H:i', dateObject.getTime() / 1000);
                $scope.filter.to = dateString;
            }
        });

    });
