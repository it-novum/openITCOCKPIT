angular.module('openITCOCKPIT')
    .controller('ServicetemplatesIndexController', function($scope, $http, $rootScope, $stateParams, SortService, MassChangeService, QueryStringService, $state){
        $rootScope.lastObjectName = null;

        SortService.setSort(QueryStringService.getValue('sort', 'Servicetemplates.name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Servicetemplates: {
                    id: QueryStringService.getStateValue($stateParams, 'id', []),
                    name: '',
                    template_name: '',
                    description: '',
                    servicetemplatetype_id: ['1']
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/servicetemplates/delete/';

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
                'filter[Servicetemplates.id][]': $scope.filter.Servicetemplates.id,
                'filter[Servicetemplates.name]': $scope.filter.Servicetemplates.name,
                'filter[Servicetemplates.template_name]': $scope.filter.Servicetemplates.template_name,
                'filter[Servicetemplates.description]': $scope.filter.Servicetemplates.description,
                'filter[Servicetemplates.servicetemplatetype_id][]': $scope.filter.Servicetemplates.servicetemplatetype_id
            };

            $http.get("/servicetemplates/index.json", {
                params: params
            }).then(function(result){
                $scope.servicetemplates = result.data.all_servicetemplates;
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
            if($scope.servicetemplates){
                for(var key in $scope.servicetemplates){
                    if($scope.servicetemplates[key].Servicetemplate.allow_edit === true){
                        var id = $scope.servicetemplates[key].Servicetemplate.id;
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

        $scope.getObjectForDelete = function(servicetemplate){
            var object = {};
            object[servicetemplate.Servicetemplate.id] = servicetemplate.Servicetemplate.template_name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.servicetemplates){
                for(var id in selectedObjects){
                    if(id == $scope.servicetemplates[key].Servicetemplate.id){
                        if($scope.servicetemplates[key].Servicetemplate.allow_edit === true){
                            objects[id] = $scope.servicetemplates[key].Servicetemplate.template_name;
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
