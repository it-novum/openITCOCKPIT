angular.module('openITCOCKPIT')
    .controller('BrowsersIndexController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, MassChangeService, QueryStringService){
        SortService.setSort(QueryStringService.getValue('sort', 'Hoststatus.current_state'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.containerId = parseInt(QueryStringService.getValue('containerId', 1), 10); //Default ROOT_CONTAINER

        $scope.containers = [];
        $scope.containerFilter = '';
        $scope.recursiveBrowser = false;


        /*** Filter Settings ***/
        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Hoststatus: {
                    current_state: QueryStringService.hoststate(),
                    acknowledged: QueryStringService.getValue('has_been_acknowledged', false) === '1',
                    not_acknowledged: QueryStringService.getValue('has_not_been_acknowledged', false) === '1',
                    in_downtime: QueryStringService.getValue('in_downtime', false) === '1',
                    not_in_downtime: QueryStringService.getValue('not_in_downtime', false) === '1',
                    output: ''
                },
                Host: {
                    name: QueryStringService.getValue('filter[Host.name]', ''),
                    keywords: '',
                    address:  QueryStringService.getValue('filter[Host.address]', ''),
                    satellite_id: []
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/hosts/delete/';
        $scope.deactivateUrl = '/hosts/deactivate/';

        $scope.init = true;
        $scope.showFilter = false;

        $scope.load = function(){
            $http.get("/browsers/index/"+$scope.containerId+".json", {
                params: {
                    angular: true
                }
            }).then(function(result){
                $scope.init = false;

                $scope.containersFromApi = result.data.containers;
                $scope.containers = $scope.containersFromApi; //We need the original containers for filter

                $scope.recursiveBrowser = result.data.recursiveBrowser;
                $scope.breadcrumbs = result.data.breadcrumbs;

                $scope.loadHosts();
                $scope.loadStatusCounts();
            });
        };

        $scope.loadHosts = function(){
            var hasBeenAcknowledged = '';
            var inDowntime = '';
            if($scope.filter.Hoststatus.acknowledged ^ $scope.filter.Hoststatus.not_acknowledged){
                hasBeenAcknowledged = $scope.filter.Hoststatus.acknowledged === true;
            }
            if($scope.filter.Hoststatus.in_downtime ^ $scope.filter.Hoststatus.not_in_downtime){
                inDowntime = $scope.filter.Hoststatus.in_downtime === true;
            }

            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Host.name]': $scope.filter.Host.name,
                'filter[Hoststatus.output]': $scope.filter.Hoststatus.output,
                'filter[Hoststatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Hoststatus.current_state),
                'filter[Host.keywords][]': $scope.filter.Host.keywords.split(','),
                'filter[Hoststatus.problem_has_been_acknowledged]': hasBeenAcknowledged,
                'filter[Hoststatus.scheduled_downtime_depth]': inDowntime,
                'filter[Host.address]': $scope.filter.Host.address,
                'filter[Host.satellite_id][]': $scope.filter.Host.satellite_id,
                'BrowserContainerId': $scope.containerId
            };

            $http.get("/hosts/index.json", {
                params: params
            }).then(function(result){
                $scope.hosts = result.data.all_hosts;
                $scope.paging = result.data.paging;

            }, function errorCallback(result){
                console.log(result);
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
                if(result.status === 403){
                    window.location.href = '/angular/forbidden';
                }
            });
        };

        $scope.loadStatusCounts = function(){
            $http.get("/angular/statuscount.json", {
                params: {
                    angular: true,
                    'containerIds[]': $scope.containerId,
                    'recursive': $scope.recursiveBrowser
                }
            }).then(function(result){
                $scope.hoststatusCountHash = result.data.hoststatusCount;
                $scope.servicestatusCountHash = result.data.servicestatusCount;

                $scope.hoststatusSum = result.data.hoststatusSum;
                $scope.servicestatusSum = result.data.servicestatusSum;

                $scope.hoststatusCountPercentage = result.data.hoststatusCountPercentage;
                $scope.servicestatusCountPercentage = result.data.servicestatusCountPercentage;

            });
        };

        $scope.changeContainerId = function(containerId){
            $scope.containerId = containerId;
            $scope.load();
        };

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function(){
            defaultFilter();
            $scope.undoSelection();
        };

        $scope.selectAll = function(){
            if($scope.hosts){
                for(var key in $scope.hosts){
                    if($scope.hosts[key].Host.allow_edit){
                        var id = $scope.hosts[key].Host.id;
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

        $scope.getObjectForDelete = function(host){
            var object = {};
            object[host.Host.id] = host.Host.hostname;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.hosts){
                for(var id in selectedObjects){
                    if(id == $scope.hosts[key].Host.id){
                        objects[id] = $scope.hosts[key].Host.hostname;
                    }
                }
            }
            return objects;
        };

        $scope.getObjectsForExternalCommand = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.hosts){
                for(var id in selectedObjects){
                    if(id == $scope.hosts[key].Host.id){
                        objects[id] = $scope.hosts[key];
                    }

                }
            }
            return objects;
        };

        $scope.linkForCopy = function(){
            var baseUrl = '/hosts/copy/';
            return buildUrl(baseUrl);

        };

        $scope.linkForEditDetails = function(){
            var baseUrl = '/hosts/edit_details/';
            return buildUrl(baseUrl);
        };

        $scope.linkForAddToHostgroup = function(){
            var baseUrl = '/hostgroups/mass_add/';
            return buildUrl(baseUrl);
        };

        var buildUrl = function(baseUrl){
            var ids = Object.keys(MassChangeService.getSelected());
            return baseUrl + ids.join('/');
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

        $scope.$watch('containerFilter', function(){
            var searchString = $scope.containerFilter.toLowerCase();

            if(searchString === ''){
                $scope.containers = $scope.containersFromApi;
                return true;
            }

            $scope.containers = [];
            for(var key in $scope.containersFromApi){
                var containerName = $scope.containersFromApi[key].value.toLowerCase();
                if(containerName.match(searchString)){
                    $scope.containers.push($scope.containersFromApi[key]);
                }
            }

        }, true);

    });