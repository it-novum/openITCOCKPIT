angular.module('openITCOCKPIT')
    .controller('ChangelogsIndexController', function($scope, $http, SortService){

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

        $scope.load = function(){
            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                //'filter[full_name]': $scope.filter.full_name,
                //'filter[Users.email]': $scope.filter.Users.email,
                //'filter[Users.phone]': $scope.filter.Users.phone,
                //'filter[Users.usergroup_id][]': $scope.filter.Users.usergroup_id,
                //'filter[Users.company]': $scope.filter.Users.company
            };

            $http.get("/changelogs/index.json", {
                params: params
            }).then(function(result){
                console.log(result.data);
                $scope.init = false;
            });
        };

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function(){
            defaultFilter();
        };


        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);
        $scope.load();
    });

