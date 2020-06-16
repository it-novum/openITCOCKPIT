angular.module('openITCOCKPIT')
    .controller('HosttemplatesIndexController', function($scope, $http, $rootScope, $stateParams, SortService, MassChangeService, QueryStringService, $state){
        $rootScope.lastObjectName = null;

        SortService.setSort(QueryStringService.getValue('sort', 'Hosttemplates.name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Hosttemplates: {
                    id: QueryStringService.getStateValue($stateParams, 'id', []),
                    name: '',
                    hosttemplatetype_id: ['1']
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/hosttemplates/delete/';

        $scope.init = true;
        $scope.showFilter = false;


        var buildUrl = function(baseUrl){
            var ids = Object.keys(MassChangeService.getSelected());
            return baseUrl + ids.join('/');
        };


        $scope.load = function(){

            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Hosttemplates.id][]': $scope.filter.Hosttemplates.id,
                'filter[Hosttemplates.name]': $scope.filter.Hosttemplates.name,
                'filter[Hosttemplates.hosttemplatetype_id][]': $scope.filter.Hosttemplates.hosttemplatetype_id
            };

            $http.get("/hosttemplates/index.json", {
                params: params
            }).then(function(result){
                $scope.hosttemplates = result.data.all_hosttemplates;
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
            if($scope.hosttemplates){
                for(var key in $scope.hosttemplates){
                    if($scope.hosttemplates[key].Hosttemplate.allow_edit === true){
                        var id = $scope.hosttemplates[key].Hosttemplate.id;
                        $scope.massChange[id] = true;
                    }
                }
            }
        };

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        $scope.getObjectForDelete = function(hosttemplate){
            var object = {};
            object[hosttemplate.Hosttemplate.id] = hosttemplate.Hosttemplate.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.hosttemplates){
                for(var id in selectedObjects){
                    if(id == $scope.hosttemplates[key].Hosttemplate.id){
                        if($scope.hosttemplates[key].Hosttemplate.allow_edit === true){
                            objects[id] = $scope.hosttemplates[key].Hosttemplate.name;
                        }
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
