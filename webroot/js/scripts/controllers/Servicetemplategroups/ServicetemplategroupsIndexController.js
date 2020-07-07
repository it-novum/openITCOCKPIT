angular.module('openITCOCKPIT')
    .controller('ServicetemplategroupsIndexController', function($scope, $http, $rootScope, SortService, MassChangeService, QueryStringService, NotyService){
        $rootScope.lastObjectName = null;

        SortService.setSort(QueryStringService.getValue('sort', 'Containers.name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Containers: {
                    name: ''
                },
                Servicetemplategroups: {
                    description: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/servicetemplategroups/delete/';

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
                'filter[Containers.name]': $scope.filter.Containers.name,
                'filter[Servicetemplategroups.description]': $scope.filter.Servicetemplategroups.description
            };

            $http.get("/servicetemplategroups/index.json", {
                params: params
            }).then(function(result){
                $scope.servicetemplategroups = result.data.all_servicetemplategroups;
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
            if($scope.servicetemplategroups){
                for(var key in $scope.servicetemplategroups){
                    if($scope.servicetemplategroups[key].Servicetemplategroup.allow_edit === true){
                        var id = $scope.servicetemplategroups[key].Servicetemplategroup.id;
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

        $scope.getObjectForDelete = function(servicetemplategroup){
            var object = {};
            object[servicetemplategroup.Servicetemplategroup.id] = servicetemplategroup.Container.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.servicetemplategroups){
                for(var id in selectedObjects){
                    if(id == $scope.servicetemplategroups[key].Servicetemplategroup.id){
                        if($scope.servicetemplategroups[key].Servicetemplategroup.allow_edit === true){
                            objects[id] = $scope.servicetemplategroups[key].Container.name;
                        }
                    }
                }
            }
            return objects;
        };

        $scope.allocateToMatchingHostgroup = function(servicetemplategroupId){
            $('#loaderModal').modal('show');

            $http.post("/servicetemplategroups/allocateToMatchingHostgroup/" + servicetemplategroupId + ".json?angular=true",
                {}
            ).then(function(result){
                if(result.data.hasOwnProperty('success') && result.data.hasOwnProperty('message')){
                    if(result.data.success){
                        NotyService.genericSuccess({
                            message: result.data.message
                        });
                        $('#loaderModal').modal('hide');
                    }
                }

            }, function errorCallback(result){
                if(result.data.hasOwnProperty('success') && result.data.hasOwnProperty('message')){
                    NotyService.genericWarning({
                        message: result.data.message
                    });
                }else{
                    NotyService.genericError();
                }

                if(result.data.hasOwnProperty('errors')){
                    $scope.errors = result.data.errors;
                }
                $('#loaderModal').modal('hide');
            });

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