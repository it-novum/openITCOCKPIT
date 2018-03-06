angular.module('openITCOCKPIT')
    .controller('ServicegroupsIndexController', function($scope, $http, SortService, MassChangeService){

        SortService.setSort('Container.name');
        SortService.setDirection('asc');
        $scope.currentPage = 1;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                container: {
                    name: ''
                },
                servicegroup: {
                    description: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/servicegroups/delete/';

        $scope.init = true;
        $scope.showFilter = false;
        $scope.load = function(){
            $http.get("/servicegroups/index.json", {
                params: {
                    'angular': true,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Container.name]': $scope.filter.container.name,
                    'filter[Servicegroup.description]': $scope.filter.servicegroup.description
                }
            }).then(function(result){
                $scope.servicegroups = result.data.all_servicegroups;
                $scope.paging = result.data.paging;
                $scope.init = false;
            });
        };

        $scope.triggerFilter = function(){
            if($scope.showFilter === true){
                $scope.showFilter = false;
            }else{
                $scope.showFilter = true;
            }
        };

        $scope.resetFilter = function(){
            defaultFilter();
            $scope.undoSelection();
        };

        $scope.selectAll = function(){
            if($scope.servicegroups){
                for(var key in $scope.servicegroups){
                    if($scope.servicegroups[key].Servicegroup.allowEdit){
                        var id = $scope.servicegroups[key].Servicegroup.id;
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

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.servicegroups){
                for(var id in selectedObjects){
                    if(id == $scope.servicegroups[key].Servicegroup.id){
                        objects[id] = $scope.servicegroups[key].Container.name;
                    }

                }
            }
            return objects;
        };

        $scope.linkForPdf = function(){
            var baseUrl = '/servicegroups/listToPdf.pdf';
            baseUrl += '?filter[Container.name]=' + encodeURI($scope.filter.container.name);
            baseUrl += '&filter[Servicegroup.description]=' + encodeURI($scope.filter.servicegroup.description);
            return baseUrl;
        };

        $scope.changepage = function(page){
            $scope.undoSelection();
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.deleteSelected = function(){
            console.log('Delete');
            console.log();
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
