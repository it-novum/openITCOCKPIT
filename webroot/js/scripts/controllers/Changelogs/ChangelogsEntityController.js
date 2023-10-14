angular.module('openITCOCKPIT')
    .controller('ChangelogsEntityController', function($scope, $http, $stateParams, QueryStringService) {

        $scope.id = $stateParams.id;

        $scope.useScroll = true;
        $scope.currentPage = 1;
        $scope.showServices = 0;

        objectTypes = {
            'TENANT'               : 1 << 0,
            'USER'                 : 1 << 1,
            'NODE'                 : 1 << 2,
            'LOCATION'             : 1 << 3,
            'DEVICEGROUP'          : 1 << 4,
            'CONTACT'              : 1 << 5,
            'CONTACTGROUP'         : 1 << 6,
            'TIMEPERIOD'           : 1 << 7,
            'HOST'                 : 1 << 8,
            'HOSTTEMPLATE'         : 1 << 9,
            'HOSTGROUP'            : 1 << 10,
            'SERVICE'              : 1 << 11,
            'SERVICETEMPLATE'      : 1 << 12,
            'SERVICEGROUP'         : 1 << 13,
            'COMMAND'              : 1 << 14,
            'SATELLITE'            : 1 << 15,
            'SERVICETEMPLATEGROUP' : 1 << 16,
            'HOSTESCALATION'       : 1 << 17,
            'SERVICEESCALATION'    : 1 << 18,
            'HOSTDEPENDENCY'       : 1 << 19,
            'SERVICEDEPENDENCY'    : 1 << 20,
            'EXPORT'               : 1 << 21
        };
        $scope.objecttypeId = objectTypes[(QueryStringService.getStateValue($stateParams, 'objectTypeId', 'TENANT').toUpperCase())] || 1;
        $scope.objectId     = parseInt(QueryStringService.getStateValue($stateParams, 'objectId', null));


        var defaultFilter = function (){
            var now = new Date();

            $scope.filter = {
                Actions: {
                    add: 1,
                    edit: 1,
                    copy: 1,
                    delete: 1,
                    deactivate: 1,
                    activate: 1,
                    export: 1
                },
                showServices: 0,
                from: date('d.m.Y H:i', now.getTime() / 1000 - (3600 * 24 * 30 * 4)),
                to: date('d.m.Y H:i', now.getTime() / 1000 + (3600 * 24 * 5)),
            };
            var from = new Date(now.getTime() - (3600 * 24 * 30 * 4 * 1000));
            from.setSeconds(0);
            var to = new Date(now.getTime() + (3600 * 24 * 5 * 1000));
            to.setSeconds(0);
            $scope.from_time = from;
            $scope.to_time = to;
        };

        $scope.showFilter = false;
        $scope.init = true;

        var getActionsFilter = function () {
            var selectedActions = [];
            for (var actionName in $scope.filter.Actions) {
                if ($scope.filter.Actions[actionName] === 1) {
                    selectedActions.push(actionName);
                }
            }

            return selectedActions;
        };

        var getModelsFilter = function () {
            var selectedModels = [];
            for (var modelName in $scope.filter.Models) {
                if ($scope.filter.Models[modelName] === 1) {
                    selectedModels.push(modelName);
                }
            }

            return selectedModels;
        };

        $scope.load = function () {
            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'page': $scope.currentPage,
                'filter[from]': $scope.filter.from,
                'filter[to]': $scope.filter.to,
                'filter[Changelogs.action][]': getActionsFilter(),
                'filter[Changelogs.objecttype_id]' : $scope.objecttypeId,
                'filter[Changelogs.object_id]' : $scope.objectId,
                'filter[ShowServices]' : $scope.filter.showServices
            };

            $http.get("/changelogs/index.json", {
                params: params
            }).then(function (result) {
                $scope.changes = result.data.all_changes;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.triggerFilter = function () {
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function () {
            defaultFilter();
        };

        $scope.changepage = function (page) {
            if (page !== $scope.currentPage) {
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.changeMode = function (val) {
            $scope.useScroll = val;
            $scope.load();
        };

        //Fire on page load
        defaultFilter();

        //Watch on filter change
        $scope.$watch('filter', function () {
            $scope.currentPage = 1;
            $scope.load();
        }, true);

        $scope.$watch('from_time', function (dateObject) {
            if (dateObject !== undefined && dateObject instanceof Date) {
                var dateString = date('d.m.Y H:i', dateObject.getTime() / 1000);
                $scope.filter.from = dateString;
            }
        });
        $scope.$watch('to_time', function (dateObject) {
            if (dateObject !== undefined && dateObject instanceof Date) {
                var dateString = date('d.m.Y H:i', dateObject.getTime() / 1000);
                $scope.filter.to = dateString;
            }
        });

    });




