angular.module('openITCOCKPIT')
    .controller('HostsChangelogController', function($scope, $http, $stateParams) {

        $scope.id = $stateParams.id;

        $scope.showFilter = false;
        $scope.init = true;
        $scope.hostBrowserMenuConfig = {
            autoload: true,
            hostId: $scope.id,
            includeHoststatus: true
        };

        $scope.loadChangelog = function () {
            var params = {
                'angular': true,
                'filter[from]': $scope.filter.from,
                'filter[to]': $scope.filter.to,
                'filter[Changelogs.action][]': getActionsFilter(),
            };

            $http.get("/hosts/changelog/" + $scope.id + ".json", {
                params: params
            }).then(function (result) {
                $scope.changes = result.data.all_changes;
            });
        };

        var defaultFilter = function(){
            var now = new Date();

            $scope.filter = {
                Actions: {
                    add: 1,
                    edit: 1,
                    delete: 1,
                    deactivate: 1,
                    activate: 1
                },
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

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function(){
            defaultFilter();
        };

        var getActionsFilter = function(){
            var selectedActions = [];
            for(var actionName in $scope.filter.Actions){
                if($scope.filter.Actions[actionName] === 1){
                    selectedActions.push(actionName);
                }
            }

            return selectedActions;
        };

        $scope.data_unserialized_notEmpty = function(data_unserialized){
            if(data_unserialized.constructor === Array){
                if(data_unserialized.length === 0){
                    return false;
                }
            }else if(data_unserialized.constructor === Object){
                if(Object.keys(data_unserialized).length <= 0){
                    return false;
                }
            }
            return true;
        };

        defaultFilter();
        $scope.loadChangelog();

        $scope.$watch('filter', function(){
            $scope.loadChangelog();
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
