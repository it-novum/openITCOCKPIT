angular.module('openITCOCKPIT')
    .controller('UsergroupsIndexController', function($scope, $http, SortService, MassChangeService, QueryStringService){

        SortService.setSort(QueryStringService.getValue('sort', 'Usergroups.name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;

        $scope.deleteUrl = '/usergroups/delete/';

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Usergroups: {
                    name: '',
                    description: ''
                }
            };
        };
        /*** Filter end ***/

        $scope.load = function(){
            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Usergroups.name]': $scope.filter.Usergroups.name,
                'filter[Usergroups.description]': $scope.filter.Usergroups.description
            };

            $http.get("/usergroups/index.json", {
                params: params
            }).then(function(result){
                $scope.Usergroups = result.data.allUsergroups;
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
            $scope.undoSelection();
        };

        $scope.selectAll = function(){
            if($scope.Usergroups){
                for(var key in $scope.Usergroups){
                    var id = $scope.Usergroups[key].id;
                    $scope.massChange[id] = true;
                }
            }
        };

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        $scope.getObjectForDelete = function(usergroup){
            var object = {};
            object[usergroup.id] = usergroup.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.Usergroups){
                for(var id in selectedObjects){
                    if(id == $scope.Usergroups[key].id){
                        objects[id] = $scope.Usergroups[key].name;
                    }
                }
            }
            return objects;
        };


        $scope.linkForCopy = function(){
            var ids = Object.keys(MassChangeService.getSelected());
            return ids.join(',');
        };


        $scope.changepage = function(page){
            $scope.undoSelection();
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
            $scope.undoSelection();
            $scope.load();
        }, true);


        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);

    });

