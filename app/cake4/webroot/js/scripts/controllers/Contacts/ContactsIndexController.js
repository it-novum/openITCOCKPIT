angular.module('openITCOCKPIT')
    .controller('ContactsIndexController', function($scope, $http, $rootScope, SortService, MassChangeService, QueryStringService){
        $rootScope.lastObjectName = null;

        SortService.setSort(QueryStringService.getValue('sort', 'Contacts.name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;


        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Contacts: {
                    name: '',
                    phone: '',
                    email: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/contacts/delete/';

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
                'filter[Contacts.name]': $scope.filter.Contacts.name,
                'filter[Contacts.phone]': $scope.filter.Contacts.phone,
                'filter[Contacts.email]': $scope.filter.Contacts.email
            };

            $http.get("/contacts/index.json", {
                params: params
            }).then(function(result){
                $scope.contacts = result.data.all_contacts;
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
            if($scope.contacts){
                for(var key in $scope.contacts){
                    if($scope.contacts[key].Contact.allow_edit === true){
                        var id = $scope.contacts[key].Contact.id;
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

        $scope.getObjectForDelete = function(contact){
            var object = {};
            object[contact.Contact.id] = contact.Contact.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.contacts){
                for(var id in selectedObjects){
                    if(id == $scope.contacts[key].Contact.id){
                        if($scope.contacts[key].Contact.allow_edit === true){
                            objects[id] = $scope.contacts[key].Contact.name;
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