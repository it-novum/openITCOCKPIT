angular.module('openITCOCKPIT')
    .controller('StatuspagesAddController', function($scope, $http, $state, $stateParams, NotyService){

        var clearForm = function(){
            $scope.post = {
                Statuspage: {
                    container_id: null,
                    name: '',
                    description: '',
                    public: 0,
                    show_comments: 0,
                    selected_hostgroups: {
                        _ids: []
                    },
                    selected_hosts: {
                        _ids: []
                    },
                    selected_servicegroups: {
                        _ids: []
                    },
                    selected_services: {
                        _ids: []
                    },
                    hostgroups: {},
                    hosts: {},
                    servicegroups: {},
                    services: {},
                }
            };
        };
        clearForm();
        $scope.init = true;
        $scope.errors = {};

        $scope.loadContainers = function(){
            $http.get("/statuspages/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.loadHostgroups = function(searchString){
            if($scope.post.Statuspage.container_id === null){
                return;
            }
            $http.get("/hostgroups/loadHostgroupsByStringAndContainers.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Statuspage.container_id,
                    'filter[Containers.name]': searchString,
                    'selected[]': $scope.post.Statuspage.selected_hostgroups._ids,
                    'resolveContainerIds': true
                }
            }).then(function(result){
                let hostgroupsAliasForRefill = $scope.storeForRefill(
                    $scope.hostgroups,
                    $scope.post.Statuspage.selected_hostgroups._ids
                );
                $scope.hostgroups = result.data.hostgroups;
                $scope.hostgroups.map(function(hostgroup){
                    // New properties to be added
                    // Assign new properties and return
                    return Object.assign(hostgroup, {
                        id: parseInt(hostgroup['key'], 10),
                        _joinData: {
                            display_alias: hostgroupsAliasForRefill[hostgroup['key']] ?? ''
                        }
                    });
                });
            });
        };

        $scope.loadServicegroups = function(searchString){
            if($scope.post.Statuspage.container_id === null){
                return;
            }
            $http.get("/servicegroups/loadServicegroupsByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Statuspage.container_id,
                    'filter[Containers.name]': searchString,
                    'selected[]': $scope.post.Statuspage.selected_servicegroups._ids,
                    'resolveContainerIds': true
                }
            }).then(function(result){
                let servicegroupsAliasForRefill = $scope.storeForRefill(
                    $scope.servicegroups,
                    $scope.post.Statuspage.selected_servicegroups._ids
                );
                $scope.servicegroups = result.data.servicegroups;
                $scope.servicegroups.map(function(servicegroup){
                    // New properties to be added
                    // Assign new properties and return
                    return Object.assign(servicegroup, {
                        id: parseInt(servicegroup['key'], 10),
                        _joinData: {
                            display_alias: servicegroupsAliasForRefill[servicegroup['key']] ?? ''
                        }
                    });
                });
            });
        };

        $scope.loadHosts = function(searchString){
            if($scope.post.Statuspage.container_id === null){
                return;
            }

            $http.get("/hosts/loadHostsByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Statuspage.container_id,
                    'filter[Hosts.name]': searchString,
                    'selected[]': $scope.post.Statuspage.selected_hosts._ids,
                    'resolveContainerIds': true
                }
            }).then(function(result){
                let hostsAliasForRefill = $scope.storeForRefill(
                    $scope.hosts,
                    $scope.post.Statuspage.selected_hosts._ids
                );
                $scope.hosts = result.data.hosts;
                $scope.hosts.map(function(host){
                    // New properties to be added
                    // Assign new properties and return
                    return Object.assign(host, {
                        id: parseInt(host['key'], 10),
                        _joinData: {
                            display_alias: hostsAliasForRefill[host['key']] ?? ''
                        }
                    });
                });
            });
        };

        $scope.loadServices = function(searchString){
            if($scope.post.Statuspage.container_id === null){
                return;
            }
            $scope.params = {
                'containerId': $scope.post.Statuspage.container_id,
                'filter': {
                    'servicename': searchString,
                },
                'selected': $scope.post.Statuspage.selected_services._ids
            };
            $http.post("/services/loadServicesByContainerIdCake4.json?angular=true",
                $scope.params
            ).then(function(result){
                let servicesAliasForRefill = $scope.storeForRefill(
                    $scope.services,
                    $scope.post.Statuspage.selected_services._ids
                );
                $scope.services = result.data.services;
                $scope.services.map(function(service){
                    // New properties to be added
                    // Assign new properties and return
                    return Object.assign(service, {
                        id: parseInt(service['key'], 10),
                        _joinData: {
                            display_alias: servicesAliasForRefill[service['key']] ?? ''
                        }
                    });
                });
            });
        };


        $scope.storeForRefill = function(loadedObjects, selectedIds){
            let refillData = {};
            if(typeof loadedObjects !== "undefined" && selectedIds.length > 0){
                loadedObjects.map(function(objectInUse){
                    if(selectedIds.indexOf(objectInUse.id) !== -1){
                        Object.assign(refillData, {[objectInUse.id]: objectInUse._joinData.display_alias});
                    }
                });
            }
            return refillData;
        };

        $scope.filterBySelectedAndCleanUpForSubmit = function(){
            if($scope.post.Statuspage.selected_hostgroups._ids.length > 0){
                $scope.post.Statuspage.hostgroups = $scope.hostgroups.filter(function(hostgroup){
                    if($scope.post.Statuspage.selected_hostgroups._ids.indexOf(hostgroup.id) !== -1){
                        return hostgroup;
                    }
                });
            }
            if($scope.post.Statuspage.selected_servicegroups._ids.length > 0){
                $scope.post.Statuspage.servicegroups = $scope.servicegroups.filter(function(servicegroup){
                    if($scope.post.Statuspage.selected_servicegroups._ids.indexOf(servicegroup.id) !== -1){
                        return servicegroup;
                    }
                });
            }
            if($scope.post.Statuspage.selected_hosts._ids.length > 0){
                $scope.post.Statuspage.hosts = $scope.hosts.filter(function(host){
                    if($scope.post.Statuspage.selected_hosts._ids.indexOf(host.id) !== -1){
                        return host;
                    }
                });
            }
            if($scope.post.Statuspage.selected_services._ids.length > 0){
                $scope.post.Statuspage.services = $scope.services.filter(function(service){
                    if($scope.post.Statuspage.selected_services._ids.indexOf(service.id) !== -1){
                        return service;
                    }
                });
            }
        };

        $scope.submit = function(){
            $scope.errors = {};
            $scope.filterBySelectedAndCleanUpForSubmit();

            $http.post("/statuspages/add.json?angular=true", $scope.post
            ).then(function(result){
                var url = $state.href('StatuspagesEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });
                $state.go('StatuspagesIndex').then(function(){
                    NotyService.scrollTop();
                });
            }, function errorCallback(result){
                NotyService.genericError();
                $scope.noItemsSelected = false;
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                    if($scope.errors.hasOwnProperty('selected_hostgroups') ||
                        $scope.errors.hasOwnProperty('selected_servicegroups') ||
                        $scope.errors.hasOwnProperty('selected_hosts') ||
                        $scope.errors.hasOwnProperty('selected_services')
                    ){
                        $scope.noItemsSelected = true;
                    }
                }
            });
        };

        //Fire on page load
        $scope.loadContainers();

        $scope.$watch('post.Statuspage.container_id', function(){
            if($scope.init){
                return;
            }
            if($scope.post.Statuspage.container_id !== null){
                $scope.loadHostgroups('');
                $scope.loadHosts('');
                $scope.loadServices('');
                $scope.loadServicegroups('');
            }
        }, true);
    });
