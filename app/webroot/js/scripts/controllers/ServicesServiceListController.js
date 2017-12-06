angular.module('openITCOCKPIT')
    .controller('ServicesServiceListController', function($scope, $http, SortService, MassChangeService, QueryStringService){

        SortService.setSort('Servicestatus.name');
        SortService.setDirection('desc');
        $scope.currentPage = 1;

        $scope.id = QueryStringService.getCakeId();

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Servicestatus: {
                    current_state: [
                        'ok',
                        'warning',
                        'critical',
                        'unknown'
                    ]
                },
                Service: {
                    name: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/hostgroups/delete/';

        $scope.init = true;
        $scope.showFilter = false;
        $scope.load = function(){

            /*
            $http.get("/services/servicestatus/.json", {
                params: {
                    'angular': true,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    //'filter[Container.name]': $scope.filter.container.name,
                    //'filter[Hostgroup.description]': $scope.filter.hostgroup.description
                }
            }).then(function(result){
                $scope.hostgroups = result.data.all_hostgroups;
                $scope.paging = result.data.paging;
                $scope.init = false;
            });

            */
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
            if($scope.hostgroups){
                for(var key in $scope.hostgroups){
                    var id = $scope.hostgroups[key].Hostgroup.id;
                    $scope.massChange[id] = true;
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
            for(var key in $scope.hostgroups){
                for(var id in selectedObjects){
                    if(id == $scope.hostgroups[key].Hostgroup.id){
                        objects[id] = $scope.hostgroups[key].Container.name;
                    }

                }
            }
            return objects;
        };

        $scope.linkForPdf = function(){
            var baseUrl = '/hostgroups/listToPdf.pdf';
            baseUrl += '?filter[Container.name]=' + encodeURI($scope.filter.container.name);
            baseUrl += '&filter[Hostgroup.description]=' + encodeURI($scope.filter.hostgroup.description);
            return baseUrl;
        };

        $scope.changepage = function(page){
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
            $scope.undoSelection();
            $scope.load();
        }, true);

        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);



    });