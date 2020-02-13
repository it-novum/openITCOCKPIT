angular.module('openITCOCKPIT')
    .controller('ChangelogsIndexController', function($scope, $http, SortService){

        $scope.useScroll = true;
        $scope.currentPage = 1;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            var now = new Date();

            $scope.filter = {
                Changelogs: {
                    name: ''
                },
                Models: {
                    Commands: 1,
                    Contacts: 1,
                    Contactgroups: 1,
                    Hosts: 1,
                    Hostgroups: 1,
                    Hosttemplates: 1,
                    Services: 1,
                    Servicegroups: 1,
                    Servicetemplates: 1,
                    Timeperiods: 1
                },
                Actions: {
                    add: 1,
                    edit: 1,
                    copy: 1,
                    delete: 1
                },
                from: date('d.m.Y H:i', now.getTime() / 1000 - (3600 * 24 * 30 * 4)),
                to: date('d.m.Y H:i', now.getTime() / 1000 + (3600 * 24 * 5)),
            };
        };

        $scope.showFilter = false;

        var getActionsFilter = function(){
            var selectedActions = [];
            for(var actionName in $scope.filter.Actions){
                if($scope.filter.Actions[actionName] === 1){
                    selectedActions.push(actionName);
                }
            }

            return selectedActions;
        };

        $scope.load = function(){
            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Changelogs.name]': $scope.filter.Changelogs.name,
                //'filter[Users.email]': $scope.filter.Users.email,
                //'filter[Users.phone]': $scope.filter.Users.phone,
                'filter[Changelogs.action][]': getActionsFilter(),
                //'filter[Users.company]': $scope.filter.Users.company
            };

            $http.get("/changelogs/index.json", {
                params: params
            }).then(function(result){
                $scope.changes = result.data.all_changes;
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
        $scope.load();

        //Watch on filter change
        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.load();
        }, true);
    });

