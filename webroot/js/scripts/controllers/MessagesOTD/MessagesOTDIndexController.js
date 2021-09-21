angular.module('openITCOCKPIT')
    .controller('MessagesOTDIndexController', function($scope, $http, SortService, MassChangeService){
        SortService.setSort('MessagesOtd.date');
        SortService.setDirection('asc');
        $scope.currentPage = 1;
        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                messagesOtd: {
                    title: '',
                    description: '',
                    date: ''

                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/messagesOtd/delete/';

        $scope.init = true;
        $scope.showFilter = false;
        $scope.load = function(){
            $http.get("/messagesOtd/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[MessagesOtd.title]': $scope.filter.messagesOtd.title,
                    'filter[MessagesOtd.description]': $scope.filter.messagesOtd.description,
                    'filter[MessagesOtd.date]': $scope.filter.messagesOtd.date
                }
            }).then(function(result){
                $scope.messagesOtd = result.data.messagesOtd;
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
            if($scope.messagesOtd){
                for(var key in $scope.messagesOtd){
                    var id = $scope.messagesOtd[key].id;
                    $scope.massChange[id] = true;
                }
            }
        };

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        $scope.getObjectForDelete = function(messageOtd){
            var object = {};
            object[messageOtd.id] = messageOtd.title;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.messagesOtd){
                for(var id in selectedObjects){
                    if(id == $scope.messagesOtd[key].id){
                        objects[id] = $scope.messagesOtd[key].title;
                    }
                }
            }
            return objects;
        };

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        $scope.changepage = function(page){
            $scope.undoSelection();
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
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
